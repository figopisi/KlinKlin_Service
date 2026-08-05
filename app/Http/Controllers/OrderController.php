<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // ================= PUBLIC =================
    public function adminDashboard()
    {
        $totalPesanan = Order::count();
        $totalPemasukan = Order::sum('fee');

        return view('admin.adminindex', compact('totalPesanan', 'totalPemasukan'));
    }

    public function index()
    {
        return view('pesanan');
    }

    public function search(Request $request)
    {
        $token = $request->input('token');

        $orders = Order::with([
            'driverLogs.driver',
            'currentDriver'
        ])
        ->where('token', $token)
        ->get();

        return view('pesanan', compact('orders', 'token'));
    }

    // ================= BUAT PESANAN =================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'                    => 'required|string|max:100',
            'phone'                   => 'required|string|max:20',
            'alamat_customer'         => 'required|string',
            'alamat_laundry'          => 'nullable|string',
            'phone_laundry'           => 'nullable|string|max:20',
            'fee'                     => 'required|integer|min:0',
            'is_sorted'               => 'nullable|boolean',
            'note'                    => 'nullable|string',
            'status'                  => 'nullable|in:Unconfirmed,Diproses,Dijemput,Mencari Laundry,Dicuci,Diantar,Selesai',
            'dokumentasi_pakaian'     => 'nullable|url|max:500',
            'tanggal_penjemputan'     => 'required|date_format:Y-m-d H:i',
            'jenis_layanan'           => 'required|string',
            'estimasi_jumlah_laundry' => 'nullable|string',
            'tipe_antar_jemput'       => 'required|in:Antar Saja,Jemput Saja,Antar Jemput (PP)',
        ]);

        $data['is_sorted'] = $data['is_sorted'] ?? 0;
        $data['alamat_laundry'] = $data['alamat_laundry'] ?? '-';

        if (!$data['is_sorted']) {
            $data['dokumentasi_pakaian'] = null;
        }

        // Status awal ditentukan oleh tipe_antar_jemput.
        // "Jemput Saja" artinya driver hanya mengambil pesanan yang sudah
        // ada di laundry (tidak perlu tahap Dijemput/Mencari Laundry),
        // sehingga order langsung dibuat berstatus 'Dicuci' agar muncul
        // di daftar pesanan tersedia untuk driver pada tahap tersebut.
        if ($data['tipe_antar_jemput'] === 'Jemput Saja') {
            $data['status'] = 'Dicuci';
        } else {
            $data['status'] = $data['status'] ?? 'Diproses';
        }

        $data['token'] = $this->generateUniqueToken();

        Order::create($data);

        return redirect()->back()->with('success', 'Pesanan berhasil dibuat! Token: ' . $data['token']);
    }

    public function storeDraft(Request $request)
    {
        $data = $request->validate([
            'nama'                    => 'required|string|max:100',
            'phone'                   => 'required|string|max:20',
            'alamat_customer'         => 'required|string',
            'alamat_laundry'          => 'nullable|string',
            'phone_laundry'           => 'nullable|string|max:20',
            'is_sorted'               => 'nullable|boolean',
            'note'                    => 'nullable|string',
            'tanggal_penjemputan'     => 'nullable|date_format:Y-m-d H:i',
            'jenis_layanan'           => 'nullable|string',
            'estimasi_jumlah_laundry' => 'nullable|string',
            'tipe_antar_jemput'       => 'nullable|in:Antar Saja,Jemput Saja,Antar Jemput (PP)',
        ]);

        $data['status'] = 'Unconfirmed';
        $data['fee'] = 0;
        $data['is_sorted'] = $data['is_sorted'] ?? 0;
        $data['dokumentasi_pakaian'] = null;
        $data['token'] = $this->generateUniqueToken();
        $data['alamat_laundry'] = $data['alamat_laundry'] ?? '-';
        $data['tipe_antar_jemput'] = $data['tipe_antar_jemput'] ?? 'Antar Jemput (PP)';

        // Catatan: status draft tetap 'Unconfirmed' terlepas dari tipe_antar_jemput.
        // Override status ke 'Dicuci' untuk 'Jemput Saja' baru berlaku
        // setelah draft dikonfirmasi menjadi pesanan resmi. Sebelumnya hanya
        // store() yang melakukan override ini, tapi draft yang dikonfirmasi
        // ADMIN (lewat halaman detail pesanan) memakai method update() di
        // bawah, yang sebelumnya TIDAK memiliki override serupa. Akibatnya
        // admin bisa (tanpa sadar) mengubah status draft 'Jemput Saja'
        // menjadi 'Diproses' dari dropdown, padahal tipe ini seharusnya
        // tidak pernah melalui status 'Diproses'/'Dijemput'/'Mencari
        // Laundry'. Fix untuk kasus ini ada di method update() di bawah.

        Order::create($data);

        return response()->json([
            'message' => 'Draft order saved',
            'token' => $data['token'],
        ], 201);
    }

    private function generateUniqueToken()
    {
        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
            $token = 'LND-' . $random;
        } while (Order::where('token', $token)->exists());

        return $token;
    }

    public function adminOrders(Request $request)
    {
        $query = Order::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('token', 'like', '%' . $request->search . '%');
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // SORT
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (in_array($sort, ['created_at', 'fee']) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        }

        $orders = $query->get();

        return view('admin.adminOrders', compact('orders'));
    }

    public function adminDetail($id)
    {
        $order = Order::with('driverLogs.driver')->findOrFail($id);
        return view('admin.adminDetailOrder', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'nama'                    => 'required|string|max:100',
            'phone'                   => 'required|string|max:20',
            'alamat_customer'         => 'required|string',
            'alamat_laundry'          => 'required|string',
            'phone_laundry'           => 'nullable|string|max:20',
            'status'                  => 'required|in:Unconfirmed,Diproses,Dijemput,Mencari Laundry,Dicuci,Diantar,Selesai',
            'fee'                     => 'required|numeric',
            'note'                    => 'nullable|string',
            'dokumentasi_pakaian'     => 'nullable|string',
            'is_sorted'               => 'nullable',
            'tanggal_penjemputan'     => 'nullable|date_format:Y-m-d H:i',
            'jenis_layanan'           => 'nullable|string',
            'estimasi_jumlah_laundry' => 'nullable|string',
            'tipe_antar_jemput'       => 'required|in:Antar Saja,Jemput Saja,Antar Jemput (PP)',
        ]);

        $data['is_sorted'] = (int) $request->input('is_sorted', 0);

        // ✅ FIX BUG: Order bertipe 'Jemput Saja' tidak pernah melalui
        // tahap 'Diproses' / 'Dijemput' / 'Mencari Laundry' — driver untuk
        // tipe ini baru terlibat mulai status 'Dicuci' (ambil dari laundry)
        // sampai 'Diantar' (antar ke customer). Sebelumnya, saat admin
        // mengonfirmasi draft (mengubah status dari 'Unconfirmed' ke status
        // lain lewat dropdown biasa), tidak ada validasi yang mencegah admin
        // memilih 'Diproses' untuk order 'Jemput Saja'. Akibatnya order
        // nyasar ke alur status yang salah, dan saat driver mengambilnya,
        // status ikut lompat ke 'Dijemput' — padahal seharusnya order jenis
        // ini tidak pernah menampilkan tahap "Bukti Pengambilan".
        //
        // Konsisten dengan store(): paksa status ke 'Dicuci' bila admin
        // memilih status yang tidak relevan untuk tipe 'Jemput Saja'.
        if ($data['tipe_antar_jemput'] === 'Jemput Saja'
            && in_array($data['status'], ['Diproses', 'Dijemput', 'Mencari Laundry'])) {
            $data['status'] = 'Dicuci';
        }

        $order->update($data);

        return redirect()->route('admin.orders.detail', $order->id)
            ->with('success', 'Pesanan berhasil diupdate');
    }

    public function nullifyDriver($id)
    {
        $order = Order::findOrFail($id);

        // rollback status
        if (in_array($order->status, ['Dijemput', 'Mencari Laundry'])) {
            $statusBaru = 'Diproses';
        } elseif ($order->status === 'Diantar') {
            $statusBaru = 'Dicuci';
        } else {
            $statusBaru = $order->status;
        }

        $order->update([
            'status' => $statusBaru,
            'current_driver_id' => null,
        ]);

        return back()->with('success', 'Driver berhasil dilepas dari pesanan');
    }

    // ================= DELETE ORDER ================= oleh admin
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->driverLogs()->delete();
        $order->photos()->delete();
        $order->delete();

        return redirect()->route('admin.orders')
            ->with('success', 'Pesanan berhasil dihapus');
    }

    public function exportCsv(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $query->where('token', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (in_array($sort, ['created_at', 'fee']) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        }

        $orders = $query->get();

        $filename = 'pesanan_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM biar Excel baca UTF-8 dengan benar (karakter ñ, é, dll aman)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['ID', 'Token', 'Nama', 'Phone', 'Alamat Customer', 'Alamat Laundry', 'Tipe Antar Jemput', 'Status', 'Fee', 'Tanggal Dibuat']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->token,
                    $order->nama,
                    $order->phone,
                    $order->alamat_customer,
                    $order->alamat_laundry,
                    $order->tipe_antar_jemput,
                    $order->status,
                    $order->fee,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}