<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\Order;
use App\Services\WablasService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WablasWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('WABLAS PAYLOAD MASUK:', $request->all());

        if ($request->boolean('isGroup')) {
            return response('', 200);
        }

        $phone = $request->input('phone');
        $text  = trim($request->input('message', ''));

        if (!$phone || $text === '') {
            return response('', 200);
        }

        $session = ChatSession::firstOrCreate(
            ['phone' => $phone],
            ['step' => 'menu']
        );

        if (!$session->bot_active) {
            return response('', 200);
        }

        $reply = match ($session->step) {
            'pilih_layanan' => $this->handlePilihLayanan($session, $text),
            'isi_data' => $this->handleIsiData($session, $text),
            'cek_status' => $this->handleCekStatus($session, $text),
            default => $this->handleMenu($session, $text),
        };

        \Log::info('SEBELUM PANGGIL sendText, reply:', ['reply' => $reply]);

        app(WablasService::class)->sendText($phone, $reply);

        \Log::info('SETELAH PANGGIL sendText - selesai');

        return response('', 200);
    }

    protected function handleMenu(ChatSession $session, string $text): string
    {
        if (in_array($text, ['1', '2']) && $session->step === 'menu') {
            if ($text === '1') {
                $session->update(['step' => 'pilih_layanan']);
                return "Silakan pilih tipe layanan:\n\n"
                     . "1. Antar Jemput\n"
                     . "2. Antar Saja\n"
                     . "3. Jemput Saja\n\n"
                     . "Balas dengan angka (1/2/3)";
            }

            if ($text === '2') {
                $session->update(['step' => 'cek_status']);
                return "Silakan kirim kode pesanan Anda (contoh: LND-A1B2C3)";
            }
        }

        $session->update(['step' => 'menu']);
        return "Halo, selamat datang di KlinKlin Laundry! 👋\n\n"
             . "Silakan pilih menu:\n\n"
             . "1. Buat Pesanan\n"
             . "2. Cek Status Pesanan\n\n"
             . "Balas dengan angka (1/2)";
    }

    protected function handlePilihLayanan(ChatSession $session, string $text): string
    {
        $mapping = [
            '1' => 'Antar Jemput',
            '2' => 'Antar Saja',
            '3' => 'Jemput Saja',
        ];

        if (!isset($mapping[$text])) {
            return "Mohon balas dengan angka 1, 2, atau 3 ya 🙏\n\n"
                 . "1. Antar Jemput\n2. Antar Saja\n3. Jemput Saja";
        }

        $jenisLayanan = $mapping[$text];
        $session->update([
            'step' => 'isi_data',
            'jenis_layanan' => $jenisLayanan,
        ]);

        if ($jenisLayanan === 'Jemput Saja') {
            return "Baik, *{$jenisLayanan}* dipilih ✅\n\n"
                 . "Silakan lengkapi data pesanan Anda, lalu kirim dalam 1 pesan:\n\n"
                 . "Nama :\n"
                 . "Alamat Customer :\n"
                 . "Alamat Laundry (WAJIB diisi) :\n"
                 . "Catatan Tambahan (opsional) : -";
        }

        return "Baik, *{$jenisLayanan}* dipilih ✅\n\n"
             . "Silakan lengkapi data pesanan Anda, lalu kirim dalam 1 pesan:\n\n"
             . "Nama :\n"
             . "Alamat Customer :\n"
             . "Alamat Laundry (opsional, isi jika berbeda dari alamat customer) : -\n"
             . "Catatan Tambahan (opsional) : -";
    }

    protected function handleIsiData(ChatSession $session, string $text): string
    {
        $parsed = $this->parseTemplate($text);

        if (!$parsed) {
            return "Format belum sesuai. Mohon kirim ulang dengan format:\n\n"
                 . "Nama : ...\nAlamat Customer : ...\nAlamat Laundry : ...\nCatatan Tambahan : ...";
        }

        $jenisLayanan = $session->jenis_layanan;

        if (empty($parsed['nama']) || empty($parsed['alamat_customer'])) {
            return "Nama dan Alamat Customer wajib diisi ya 🙏 Silakan kirim ulang datanya.";
        }

        if ($jenisLayanan === 'Jemput Saja' && empty($parsed['alamat_laundry'])) {
            return "Untuk layanan *Jemput Saja*, Alamat Laundry wajib diisi. Silakan kirim ulang datanya 🙏";
        }

        $order = Order::create([
            'nama' => $parsed['nama'],
            'phone' => $session->phone,
            'alamat_customer' => $parsed['alamat_customer'],
            'alamat_laundry' => $parsed['alamat_laundry'] ?: null,
            'note' => $parsed['catatan'] ?: null,
            'jenis_layanan' => $jenisLayanan,
            'status' => 'Diproses',
        ]);

        $session->update(['step' => 'menu', 'jenis_layanan' => null]);

        return "Pesanan Anda berhasil dibuat! ✅\n\n"
             . "Kode Pesanan : *{$order->token}*\n\n"
             . "Simpan kode ini untuk cek status pesanan Anda kapan saja. Terima kasih! 🙏";
    }

    protected function handleCekStatus(ChatSession $session, string $text): string
    {
        $order = Order::where('token', trim($text))->first();

        $session->update(['step' => 'menu']);

        if (!$order) {
            return "Kode pesanan *{$text}* tidak ditemukan. Mohon cek kembali kode Anda 🙏";
        }

        return "Status Pesanan *{$order->token}*\n\n"
             . "Nama : {$order->nama}\n"
             . "Status Saat Ini : *{$order->status}*";
    }

    protected function parseTemplate(string $text): ?array
    {
        $lines = explode("\n", $text);
        $result = ['nama' => '', 'alamat_customer' => '', 'alamat_laundry' => '', 'catatan' => ''];

        foreach ($lines as $line) {
            if (!Str::contains($line, ':')) continue;

            [$label, $value] = explode(':', $line, 2);
            $label = strtolower(trim($label));
            $value = trim($value);
            $value = ($value === '-') ? '' : $value;

            if (Str::contains($label, 'nama')) $result['nama'] = $value;
            elseif (Str::contains($label, 'alamat customer')) $result['alamat_customer'] = $value;
            elseif (Str::contains($label, 'alamat laundry')) $result['alamat_laundry'] = $value;
            elseif (Str::contains($label, 'catatan')) $result['catatan'] = $value;
        }

        return ($result['nama'] || $result['alamat_customer']) ? $result : null;
    }
}