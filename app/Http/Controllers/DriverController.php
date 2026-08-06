<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDriverLog;
use App\Models\OrderPhoto;
use App\Services\CloudinaryService;

class DriverController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloudinary
    ) {}

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
        $order = Order::with(['driverLogs.driver', 'currentDriver', 'photos'])
            ->findOrFail($id); // ambil dulu tanpa filter driver

        $driverId = session('driver_id');

        $isPemilik         = $order->current_driver_id == $driverId;
        $adaDiLog          = $order->driverLogs->contains('driver_id', $driverId);
        // kondisi: pesanan tersedia (belum diambil siapapun)
        $isPesananTersedia = is_null($order->current_driver_id)
                            && in_array($order->status, ['Diproses', 'Dicuci']);

        // Tolak jika tidak punya relasi apapun ke pesanan ini
        if (!$isPemilik && !$adaDiLog && !$isPesananTersedia) {
            abort(403, 'Kamu tidak punya akses ke pesanan ini.');
        }

        // Boleh edit field detail (alamat laundry dkk) hanya jika pemilik aktif
        // DAN status Dijemput / Mencari Laundry. Field tipe_antar_jemput
        // TIDAK pernah bisa diedit driver — murni ditentukan saat order dibuat.
        $bisaEdit = $isPemilik && in_array($order->status, ['Dijemput', 'Mencari Laundry']);

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

        // Guard 2: status harus Dijemput atau Mencari Laundry
        if (!in_array($order->status, ['Dijemput', 'Mencari Laundry'])) {
            return redirect()->route('driver.pesanan.detail', $id)
                ->with('error', 'Detail hanya bisa diubah saat status pesanan Dijemput atau Mencari Laundry.');
        }

        // Catatan: tipe_antar_jemput SENGAJA tidak divalidasi/diterima di sini
        // karena driver tidak boleh mengubah field ini (read-only).
        $data = $request->validate([
            'alamat_laundry'          => 'required|string',
            'phone_laundry'           => 'nullable|string|max:20',
            'estimasi_jumlah_laundry' => 'nullable|string|max:100',
            'dokumentasi_pakaian'     => 'nullable|string',
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

        // ✅ FIX BUG: sebelumnya cabang ini hanya menebak alur dari status
        // mentah ('Diproses' vs selain itu), tanpa mengecek tipe_antar_jemput
        // sama sekali. Kalau ada order 'Jemput Saja' yang (karena bug lain,
        // misalnya admin salah pilih status saat konfirmasi draft) nyasar
        // berstatus 'Diproses', maka driver yang mengambilnya akan diarahkan
        // ke status 'Dijemput' — padahal tipe 'Jemput Saja' seharusnya TIDAK
        // PERNAH melalui tahap Dijemput/Mencari Laundry sama sekali, karena
        // driver untuk tipe ini hanya bertugas mengambil dari laundry lalu
        // mengantar ke customer.
        //
        // Sebagai lapisan pertahanan kedua (defense-in-depth) selain fix di
        // OrderController@update, di sini kita cek tipe_antar_jemput secara
        // eksplisit, bukan cuma menebak dari status. Kalau ternyata ada
        // order 'Jemput Saja' yang statusnya 'Diproses' (data lama/nyasar),
        // order tersebut TIDAK dianggap valid untuk alur 'Diproses', dan
        // diarahkan sesuai aturan tipe 'Jemput Saja' (masuk ke Dicuci/Diantar
        // berdasarkan foto nota), bukan ke Dijemput.
        if ($order->tipe_antar_jemput === 'Jemput Saja') {
            // Berlaku untuk 'Jemput Saja': driver bergabung mulai tahap
            // Dicuci (ambil dari laundry) sampai Diantar (antar ke customer).
            $punyaNota = $order->photos()->where('type', 'nota')->exists();
            $statusBaru = $punyaNota ? 'Diantar' : 'Dicuci';
        } elseif ($order->status === 'Diproses') {
            // Berlaku untuk tipe 'Antar Jemput (PP)' dan 'Antar Saja'
            $statusBaru = 'Dijemput';
        } elseif ($order->tipe_antar_jemput === 'Antar Saja') {
            // status pasti 'Dicuci' di sini (nota sudah wajib ada sebelum sampai
            // status ini), jadi begitu diambil ulang langsung Selesai.
            $statusBaru = 'Selesai';
        } else {
            // $order->status === 'Dicuci', tipe 'Antar Jemput (PP)'
            $punyaNota = $order->photos()->where('type', 'nota')->exists();
            $statusBaru = $punyaNota ? 'Diantar' : 'Dicuci';
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

        // Cek syarat foto sebelum boleh pindah status.
        // Berlaku sama untuk semua tipe_antar_jemput karena hanya dicek
        // saat status yang relevan benar-benar tercapai (tahap yang
        // dilewati untuk tipe tertentu tidak akan pernah dicek di sini).
        if ($order->status === 'Dijemput') {
            $punya = $order->photos()->where('type', 'pengambilan')->exists();
            if (!$punya) {
                return back()->with('error', 'Upload bukti pengambilan baju terlebih dahulu');
            }
        }

        if ($order->status === 'Mencari Laundry') {
            $punya = $order->photos()->where('type', 'nota')->exists();
            if (!$punya) {
                return back()->with('error', 'Upload bukti nota laundry terlebih dahulu');
            }
        }

        if ($order->status === 'Dicuci') {
            // Hanya relevan untuk tipe 'Jemput Saja' (driver baru gabung di
            // tahap ini dan belum punya foto nota).
            $punya = $order->photos()->where('type', 'nota')->exists();
            if (!$punya) {
                return back()->with('error', 'Upload bukti nota (foto di laundry) terlebih dahulu');
            }
        }

        if ($order->status === 'Diantar') {
            $punya = $order->photos()->where('type', 'pengiriman')->exists();
            if (!$punya) {
                return back()->with('error', 'Upload bukti pengiriman terlebih dahulu');
            }
        }

        // Alur transisi status bercabang berdasarkan tipe_antar_jemput.
        $transisi = match ($order->tipe_antar_jemput) {
            'Antar Saja' => [
                'Diproses'        => 'Dijemput',
                'Dijemput'        => 'Mencari Laundry',
                'Mencari Laundry' => 'Dicuci',   // ✅ singgah dulu
                'Dicuci'          => 'Selesai',  // ✅ baru selesai
            ],
            'Jemput Saja' => [
                'Dicuci'  => 'Diantar',
                'Diantar' => 'Selesai',
            ],
            default => [ // 'Antar Jemput (PP)'
                'Diproses'        => 'Dijemput',
                'Dijemput'        => 'Mencari Laundry',
                'Mencari Laundry' => 'Dicuci',
                'Dicuci'          => 'Diantar',
                'Diantar'         => 'Selesai',
            ],
        };

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

        // Rollback status bercabang berdasarkan tipe_antar_jemput, supaya
        // pesanan kembali ke tahap yang benar dan bisa diambil driver lain.
        $rollback = match ($order->tipe_antar_jemput) {
            'Antar Saja' => [
                'Dijemput'        => 'Diproses',
                'Mencari Laundry' => 'Diproses',
                'Dicuci'          => 'Mencari Laundry', // ✅ tambah ini
            ],
            'Jemput Saja' => [
                'Diantar' => 'Dicuci',
            ],
            default => [
                'Dijemput'        => 'Diproses',
                'Mencari Laundry' => 'Diproses',
                'Diantar'         => 'Dicuci',
                // catatan: PP di status 'Dicuci' memang sengaja TIDAK di-rollback
                // (tetap 'Dicuci' tanpa driver) karena order pada tahap ini
                // memang dirancang bisa "nganggur" tanpa driver saat proses cuci,
                // lalu diambil driver (bisa siapa saja) untuk lanjut ke 'Diantar'.
            ],
        };

        $statusBaru = $rollback[$order->status] ?? $order->status;

        $order->update([
            'status'            => $statusBaru,
            'current_driver_id' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil dilepas');
    }

    // ================= UPLOAD FOTO =================

    public function uploadBuktiPengambilan(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        // Hanya relevan untuk tipe 'Antar Jemput (PP)' dan 'Antar Saja'.
        // 'Jemput Saja' tidak pernah melalui tahap Dijemput sehingga
        // guard status ini otomatis menolaknya.
        if ($order->status !== 'Dijemput') {
            return back()->with('error', 'Upload bukti pengambilan hanya bisa dilakukan saat status Dijemput');
        }

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = $this->cloudinary->uploadBuktiPengambilan($request->file('foto'), $order->token);

        OrderPhoto::create([
            'order_id'  => $order->id,
            'type'      => 'pengambilan',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti pengambilan berhasil diupload');
    }

    public function uploadBuktiNota(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        // Bukti nota bisa diupload pada dua kondisi status:
        // - 'Mencari Laundry' -> alur normal (PP / Antar Saja)
        // - 'Dicuci'          -> alur 'Jemput Saja', driver baru mengambil
        //                        pesanan yang sudah ada di laundry dan
        //                        perlu dokumentasi notanya di sini.
        if (!in_array($order->status, ['Mencari Laundry', 'Dicuci'])) {
            return back()->with('error', 'Upload bukti nota hanya bisa dilakukan saat status Mencari Laundry atau Dicuci');
        }

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = $this->cloudinary->uploadBuktiNota($request->file('foto'), $order->token);

        OrderPhoto::create([
            'order_id'  => $order->id,
            'type'      => 'nota',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti nota berhasil diupload');
    }

    public function uploadBuktiPengiriman(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        // Hanya relevan untuk tipe 'Antar Jemput (PP)' dan 'Jemput Saja'.
        // 'Antar Saja' tidak pernah mencapai status Diantar sehingga
        // guard status ini otomatis menolaknya.
        if ($order->status !== 'Diantar') {
            return back()->with('error', 'Upload bukti pengiriman hanya bisa dilakukan saat status Diantar');
        }

        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $result = $this->cloudinary->uploadBuktiPengiriman($request->file('foto'), $order->token);

        OrderPhoto::create([
            'order_id'  => $order->id,
            'type'      => 'pengiriman',
            'url'       => $result['url'],
            'public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Bukti pengiriman berhasil diupload');
    }

    public function deleteFoto($photoId)
    {
        $photo = OrderPhoto::findOrFail($photoId);
        $order = Order::findOrFail($photo->order_id);

        if ($order->current_driver_id != session('driver_id')) {
            return back()->with('error', 'Kamu tidak bertanggung jawab atas pesanan ini');
        }

        $this->cloudinary->delete($photo->public_id);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus');
    }
}