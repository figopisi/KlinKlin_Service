<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDriverLog;

class DriverController extends Controller
{
   public function dashboard()
{
    // Pesanan tersedia
    $tersedia = Order::with('driverLogs.driver')
        ->whereIn('status', ['Diproses', 'Dicuci'])
        ->whereNull('current_driver_id')
        ->latest()
        ->get();

    // Pesanan aktif milik driver
    $pesananAktif = Order::with('driverLogs.driver')
        ->where('current_driver_id', session('driver_id'))
        ->whereNotIn('status', ['Selesai'])
        ->latest()
        ->get();

    // Riwayat pesanan selesai
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

    public function ambilPesanan($id)
    {
        $order = Order::findOrFail($id);

        // Kalau sudah ada driver aktif
        if ($order->current_driver_id) {
            return back()->with('error', 'Pesanan sudah diambil driver lain');
        }

        $bisaDiambil = ['Diproses', 'Dicuci'];

        if (!in_array($order->status, $bisaDiambil)) {
            return back()->with('error', 'Pesanan tidak bisa diambil');
        }

        // Tentukan status baru
        $statusBaru = match ($order->status) {
            'Diproses' => 'Dijemput',
            'Dicuci'   => 'Diantar',
            default    => null,
        };

        if (!$statusBaru) {
            return back()->with('error', 'Status tidak valid');
        }

        // Simpan log
        OrderDriverLog::create([
            'order_id'  => $order->id,
            'driver_id' => session('driver_id'),
            'status'    => $statusBaru,
            'taken_at'  => now(),
        ]);

        // Update order + ownership driver
        $order->update([
            'status' => $statusBaru,
            'current_driver_id' => session('driver_id'),
        ]);

        return back()->with('success', 'Pesanan berhasil diambil!');
    }

    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);

        // Pastikan order milik driver login
        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        // Alur status
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

        // Simpan log histori
        OrderDriverLog::create([
            'order_id'  => $order->id,
            'driver_id' => session('driver_id'),
            'status'    => $statusBaru,
            'taken_at'  => now(),
        ]);

        // Kalau selesai → lepas driver
        if ($statusBaru === 'Selesai') {

            $order->update([
                'status' => $statusBaru,
            ]);

        } else {

            $order->update([
                'status' => $statusBaru,
            ]);
        }

        return back()->with('success', 'Status berhasil diupdate!');
    }

    public function lepasPesanan($id)
    {
        $order = Order::findOrFail($id);

        // Pastikan order milik driver login
        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Bukan pesanan kamu');
        }

        // Rollback status supaya bisa diambil driver lain
        if ($order->status == 'Dijemput') {
            $statusBaru = 'Diproses';
        } elseif ($order->status == 'Diantar') {
            $statusBaru = 'Dicuci';
        } else {
            $statusBaru = $order->status;
        }

        // Lepas ownership
        $order->update([
            'status' => $statusBaru,
            'current_driver_id' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil dilepas');
    }
}