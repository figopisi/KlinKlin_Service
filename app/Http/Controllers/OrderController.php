<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MitraLaundry;
use App\Models\Promotion;
use App\Models\OrderPhoto;
 use App\Models\BundlePurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // ================= PUBLIC =================
    public function adminDashboard()
    {
        $totalPesanan = Order::count();

        // ✅ Pemasukan kotor = fee jasa + ongkos pilah + fee laundry
        // (fee laundry hanya dihitung kalau order memang bermitra, ditandai mitra_laundry_id terisi)
        $totalPemasukan = Order::where('status', 'Selesai')
            ->selectRaw('SUM(fee + ongkos_pilah + CASE WHEN mitra_laundry_id IS NOT NULL THEN COALESCE(fee_laundry, 0) ELSE 0 END) as total')
            ->value('total') ?? 0;

        $pendapatanBersih = Order::where('status', 'Selesai')->sum('penghasilan_bersih_klinklin');
        $pendapatanDariDriver = Order::where('status', 'Selesai')->sum('penghasilan_klinklin_dari_driver');
        $pendapatanDariMitra = Order::where('status', 'Selesai')->sum('penghasilan_klinklin_dari_mitra');

        return view('admin.adminindex', compact(
            'totalPesanan',
            'totalPemasukan',
            'pendapatanBersih',
            'pendapatanDariDriver',
            'pendapatanDariMitra'
        ));
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
        'ongkos_pilah'            => 'nullable|integer|min:0',
        'fee'                     => 'required_without:bundle_purchase_id|integer|min:0', // ✅ fee jadi opsional kalau pakai bundle
        'bundle_purchase_id'      => 'nullable|exists:bundle_purchases,id', // ✅ baru
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

    if ($data['tipe_antar_jemput'] === 'Jemput Saja') {
        $data['status'] = 'Dicuci';
    } else {
        $data['status'] = $data['status'] ?? 'Diproses';
    }

    $data['token'] = $this->generateUniqueToken();

    // ✅ Jika order pakai bundle, validasi + lock + override fee di sini,
    // semua dalam satu transaction supaya konsisten dengan decrement kuota
    if (!empty($data['bundle_purchase_id'])) {
        $order = DB::transaction(function () use ($data) {
            $bundlePurchase = BundlePurchase::where('id', $data['bundle_purchase_id'])
                ->lockForUpdate()
                ->first();

            if (!$bundlePurchase || $bundlePurchase->status !== 'aktif' || $bundlePurchase->kuota_tersisa <= 0) {
                throw ValidationException::withMessages([
                    'bundle_purchase_id' => 'Bundle tidak aktif atau kuota sudah habis.',
                ]);
            }

            if ($bundlePurchase->tanggal_berakhir && $bundlePurchase->tanggal_berakhir->isPast()) {
                throw ValidationException::withMessages([
                    'bundle_purchase_id' => 'Bundle sudah kedaluwarsa.',
                ]);
            }

            // fee di-override dari harga per trip bundle (bukan input manual)
            $data['fee'] = $bundlePurchase->harga_per_trip;
            $data['fee_sebelum_diskon'] = $bundlePurchase->harga_per_trip;

            $order = Order::create($data);

            $bundlePurchase->decrement('kuota_tersisa');
            if ($bundlePurchase->kuota_tersisa <= 0) {
                $bundlePurchase->update(['status' => 'habis']);
            }

            $order->hitungPenghasilan();

            return $order;
        });
    } else {
        $data['fee_sebelum_diskon'] = $data['fee'];
        $order = Order::create($data);
    }

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
        $data['fee_sebelum_diskon'] = 0;
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

        $repeatCustomers = Order::select('phone', 'nama', \DB::raw('count(*) as total_order'))
            ->groupBy('phone', 'nama')
            ->having('total_order', '>', 1)
            ->orderByDesc('total_order')
            ->get();

        // FILTER JUMLAH TAMPILAN
        $perPage = $request->get('per_page', '10');
        $allowedPerPage = ['10', '20', '50', '100', 'all'];

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = '10';
        }

        if ($perPage === 'all') {
            $orders = $query->get();
        } else {
            $orders = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.adminOrders', compact('orders', 'repeatCustomers', 'perPage'));
    }

    public function adminDetail($id)
    {
        $order = Order::with('driverLogs.driver')->findOrFail($id);

        $mitrasAktif = MitraLaundry::aktif()->orderBy('nama_laundry')->get();

        $promosAktif = Promotion::where('is_active', true)
            ->orderBy('nama_promo')
            ->get();

        return view('admin.adminDetailOrder', compact('order', 'mitrasAktif', 'promosAktif'));
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
            'mitra_laundry_id'        => 'nullable|exists:mitra_laundries,id',
            'promo_id'                => 'nullable|exists:promotions,id',
            'status'                  => 'required|in:Unconfirmed,Diproses,Dijemput,Mencari Laundry,Dicuci,Diantar,Selesai',
            'fee_sebelum_diskon'      => 'required|numeric|min:0',
            'ongkos_pilah'            => 'nullable|numeric|min:0',
            'fee_laundry'               => 'nullable|numeric|min:0',
            'estimasi_waktu_pengerjaan' => 'nullable|string|max:100',
            'note'                    => 'nullable|string',
            'dokumentasi_pakaian'     => 'nullable|string',
            'is_sorted'               => 'nullable',
            'tanggal_penjemputan'     => 'nullable|date_format:Y-m-d H:i',
            'jenis_layanan'           => 'nullable|string',
            'estimasi_jumlah_laundry' => 'nullable|string',
            'tipe_antar_jemput'       => 'required|in:Antar Saja,Jemput Saja,Antar Jemput (PP)',
        ]);

        $data['ongkos_pilah'] = $data['ongkos_pilah'] ?? 0;
        $data['is_sorted'] = (int) $request->input('is_sorted', 0);
        $data['mitra_laundry_id'] = $data['mitra_laundry_id'] ?? null;
        $data['promo_id'] = $data['promo_id'] ?? null;

        // ✅ FIX: Fee final SELALU dihitung ulang dari fee_sebelum_diskon
        // (bukan dari fee lama yang sudah tersimpan), supaya diskon promo
        // tidak terpotong berkali-kali setiap admin menyimpan perubahan
        // lain (nama, catatan, dsb) selama promo yang sama masih terpasang.
        $feeFinal = $data['fee_sebelum_diskon'];

        if ($data['promo_id']) {
            $promo = Promotion::find($data['promo_id']);
            if ($promo) {
                $diskon = $promo->harga_awal - $promo->harga_promo;
                $feeFinal = max(0, $feeFinal - $diskon);
            }
        }

        $data['fee'] = $feeFinal;

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
        $query = Order::with(['mitraLaundry', 'currentDriver']); // ✅ eager load

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

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID', 'Token', 'Nama', 'Phone',
                'Alamat Customer', 'Alamat Laundry',
                'Tipe Antar Jemput', 'Status',
                'Fee', 'Ongkos Pilah', 'Fee Laundry',
                'Mitra Laundry', 'Driver',
                'Penghasilan Driver', 'Penghasilan Laundry Mitra',
                'KlinKlin dari Driver', 'KlinKlin dari Mitra', 'Pendapatan Bersih KlinKlin',
                'Tanggal Dibuat',
            ]);

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
                    $order->ongkos_pilah,
                    $order->fee_laundry,
                    $order->mitraLaundry->nama_laundry ?? '-',
                    $order->currentDriver->name ?? '-',
                    $order->penghasilan_driver,
                    $order->penghasilan_laundry_mitra,
                    $order->penghasilan_klinklin_dari_driver,
                    $order->penghasilan_klinklin_dari_mitra,
                    $order->penghasilan_bersih_klinklin,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ================= FOTO BUKTI (ADMIN — full akses, tanpa restriksi) =================

    public function uploadFotoPengambilan(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = app(\App\Services\CloudinaryService::class)
            ->uploadBuktiPengambilan($request->file('foto'), $order->token);

        // hapus foto lama tipe yang sama supaya tidak numpuk
        $order->photos()->where('type', 'pengambilan')->delete();

        $order->photos()->create([
            'type'      => 'pengambilan',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti pengambilan berhasil diupload');
    }

    public function uploadFotoNota(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = app(\App\Services\CloudinaryService::class)
            ->uploadBuktiNota($request->file('foto'), $order->token);

        $order->photos()->where('type', 'nota')->delete();

        $order->photos()->create([
            'type'      => 'nota',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti nota berhasil diupload');
    }

    public function uploadFotoPengiriman(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = app(\App\Services\CloudinaryService::class)
            ->uploadBuktiPengiriman($request->file('foto'), $order->token);

        $order->photos()->where('type', 'pengiriman')->delete();

        $order->photos()->create([
            'type'      => 'pengiriman',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti pengiriman berhasil diupload');
    }

    public function deleteFotoAdmin($photoId)
    {
        $photo = \App\Models\OrderPhoto::findOrFail($photoId);

        app(\App\Services\CloudinaryService::class)->delete($photo->public_id);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus');
    }
}