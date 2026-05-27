<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDriverLog;

class DriverController extends Controller
{
    public function dashboard()
    {
        $tersedia = Order::with('driverLogs.driver')
            ->whereIn('status', ['Diproses', 'Dicuci'])
            ->whereNull('current_driver_id')
            ->latest()
            ->get();

        $pesananAktif = Order::with('driverLogs.driver')
            ->where('current_driver_id', session('driver_id'))
            ->whereNotIn('status', ['Selesai'])
            ->latest()
            ->get();

        $pesananSelesai = Order::with('driverLogs.driver')
            ->whereHas('driverLogs', function($q) {
                $q->where('driver_id', session('driver_id'));
            })
            ->where('status', 'Selesai')
            ->latest()
            ->get();

        return view('driver.dashboard', compact(
            'tersedia',
            'pesananAktif',
            'pesananSelesai'
        ));
    }

    // ================= DETAIL PESANAN (DRIVER) =================

   public function detail($id)
{
    $order = Order::with(['driverLogs.driver', 'currentDriver'])
        ->findOrFail($id); // ambil dulu tanpa filter driver

    $driverId = session('driver_id');

    $isPemilik         = $order->current_driver_id == $driverId;
    $adaDiLog          = $order->driverLogs->contains('driver_id', $driverId);
    // ✅ kondisi baru: pesanan tersedia (belum diambil siapapun)
    $isPesananTersedia = is_null($order->current_driver_id)
                        && in_array($order->status, ['Diproses', 'Dicuci']);

    // Tolak jika tidak punya relasi apapun ke pesanan ini
    if (!$isPemilik && !$adaDiLog && !$isPesananTersedia) {
        abort(403, 'Kamu tidak punya akses ke pesanan ini.');
    }

    // Boleh edit hanya jika pemilik aktif DAN status Dijemput
    $bisaEdit = $isPemilik && $order->status === 'Dijemput';

    return view('driver.detailPesanan', compact('order', 'bisaEdit'));
}

    // ================= UPDATE OLEH DRIVER (terbatas) =================

    public function updateByDriver(Request $request, $id)
    {
        $order = Order::where(function($q) {
                $q->where('current_driver_id', session('driver_id'))
                  ->orWhereHas('driverLogs', function($q2) {
                      $q2->where('driver_id', session('driver_id'));
                  });
            })
            ->findOrFail($id);

        // Guard 1: harus pemilik aktif
        if ($order->current_driver_id != session('driver_id')) {
            return redirect()->route('driver.pesanan.detail', $id)
                ->with('error', 'Kamu bukan driver aktif pesanan ini.');
        }

        // Guard 2: status harus Dijemput
        if ($order->status !== 'Dijemput') {
            return redirect()->route('driver.pesanan.detail', $id)
                ->with('error', 'Detail hanya bisa diubah saat status pesanan Dijemput.');
        }

        $data = $request->validate([
            'alamat_laundry'          => 'required|string',
            'phone_laundry'           => 'nullable|string|max:20',
            'estimasi_jumlah_laundry' => 'nullable|string|max:100',
        ]);

        $order->update($data);

        return redirect()->route('driver.pesanan.detail', $id)
            ->with('success', 'Data berhasil diperbarui.');
    }

    // ================= AMBIL PESANAN =================

    public function ambilPesanan($id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id) {
            return back()->with('error', 'Pesanan sudah diambil driver lain');
        }

        $bisaDiambil = ['Diproses', 'Dicuci'];

        if (!in_array($order->status, $bisaDiambil)) {
            return back()->with('error', 'Pesanan tidak bisa diambil');
        }

        $statusBaru = match ($order->status) {
            'Diproses' => 'Dijemput',
            'Dicuci'   => 'Diantar',
            default    => null,
        };

        if (!$statusBaru) {
            return back()->with('error', 'Status tidak valid');
        }

        OrderDriverLog::create([
            'order_id'  => $order->id,
            'driver_id' => session('driver_id'),
            'status'    => $statusBaru,
            'taken_at'  => now(),
        ]);

        $order->update([
            'status'            => $statusBaru,
            'current_driver_id' => session('driver_id'),
        ]);

        return back()->with('success', 'Pesanan berhasil diambil!');
    }

    // ================= UPDATE STATUS =================

    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        $transisi = [
            'Diproses' => 'Dijemput',
            'Dijemput' => 'Dicuci',
            'Dicuci'   => 'Diantar',
            'Diantar'  => 'Selesai',
        ];

        $statusBaru = $transisi[$order->status] ?? null;

        if (!$statusBaru) {
            return back()->with('error', 'Status tidak bisa diubah');
        }

        OrderDriverLog::create([
            'order_id'  => $order->id,
            'driver_id' => session('driver_id'),
            'status'    => $statusBaru,
            'taken_at'  => now(),
        ]);

        $order->update(['status' => $statusBaru]);

        return back()->with('success', 'Status berhasil diupdate!');
    }

    // ================= LEPAS PESANAN =================

    public function lepasPesanan($id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Bukan pesanan kamu');
        }

        if ($order->status == 'Dijemput') {
            $statusBaru = 'Diproses';
        } elseif ($order->status == 'Diantar') {
            $statusBaru = 'Dicuci';
        } else {
            $statusBaru = $order->status;
        }

        $order->update([
            'status'            => $statusBaru,
            'current_driver_id' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil dilepas');
    }
}