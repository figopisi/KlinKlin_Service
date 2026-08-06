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
        if ($request->boolean('isGroup')) {
            \Log::info('Pesan grup diabaikan', ['group_id' => $request->input('group.group_id')]);
            return response('', 200);
        }

        \Log::info('WABLAS PAYLOAD MASUK:', $request->all());

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

        // null berarti balasan sudah dikirim manual di dalam handler (misal: list message)
        $reply = match ($session->step) {
            'pilih_layanan'         => $this->handlePilihLayanan($session, $text),
            'tanya_nama'            => $this->handleTanyaNama($session, $text),
            'tanya_alamat_customer' => $this->handleTanyaAlamatCustomer($session, $text),
            'tanya_alamat_laundry'  => $this->handleTanyaAlamatLaundry($session, $text),
            'tanya_catatan'         => $this->handleTanyaCatatan($session, $text),
            'konfirmasi'            => $this->handleKonfirmasi($session, $text),
            'cek_status'            => $this->handleCekStatus($session, $text),
            default                 => $this->handleMenu($session, $text),
        };

        if ($reply !== null) {
            app(WablasService::class)->sendText($phone, $reply);
        }

        return response('', 200);
    }

    protected function handleMenu(ChatSession $session, string $text): ?string
    {
        $pilihan = $this->matchMenuUtama($text);

        if ($pilihan && $session->step === 'menu') {
            if ($pilihan === '1') {
                $session->update(['step' => 'pilih_layanan']);
                $this->sendMenuTipeLayanan($session->phone);
                return null;
            }

            if ($pilihan === '2') {
                $session->update(['step' => 'cek_status']);
                return "Silakan kirim kode pesanan Anda (contoh: LND-A1B2C3)";
            }
        }

        $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);
        $this->sendMenuUtama($session->phone);
        return null;
    }

    protected function sendMenuUtama(string $phone): void
    {
        app(WablasService::class)->sendText($phone,
            "Halo, selamat datang di KlinKlin Laundry! 👋\n\n"
            . "Silakan pilih menu:\n\n"
            . "1. Buat Pesanan\n"
            . "2. Cek Status Pesanan\n\n"
            . "Balas dengan angka (1/2)"
        );
    }

    protected function sendMenuTipeLayanan(string $phone): void
    {
        $rows = [
            ['title' => 'Antar Jemput', 'description' => 'Barang dijemput & diantar kembali', 'rowId' => '1'],
            ['title' => 'Antar Saja', 'description' => 'Anda antar sendiri, kami kirim balik', 'rowId' => '2'],
            ['title' => 'Jemput Saja', 'description' => 'Kami jemput ke alamat laundry pilihan Anda', 'rowId' => '3'],
        ];

        $result = app(WablasService::class)->sendList(
            $phone,
            'Tipe Layanan',
            'Silakan pilih tipe layanan:',
            $rows
        );

        if (!($result['status'] ?? false)) {
            app(WablasService::class)->sendText($phone,
                "Silakan pilih tipe layanan:\n\n"
                . "1. Antar Jemput\n"
                . "2. Antar Saja\n"
                . "3. Jemput Saja\n\n"
                . "Balas dengan angka (1/2/3)"
            );
        }
    }

    protected function matchMenuUtama(string $text): ?string
    {
        $t = strtolower(trim($text));

        if ($t === '1' || str_contains($t, 'buat pesanan')) return '1';
        if ($t === '2' || str_contains($t, 'cek status')) return '2';

        return null;
    }

    protected function matchTipeLayanan(string $text): ?string
    {
        $t = strtolower(trim($text));

        if ($t === '1' || str_contains($t, 'antar jemput')) return '1';
        if ($t === '2' || str_contains($t, 'antar saja')) return '2';
        if ($t === '3' || str_contains($t, 'jemput saja')) return '3';

        return null;
    }

    protected function handlePilihLayanan(ChatSession $session, string $text): ?string
    {
        $pilihan = $this->matchTipeLayanan($text);

        if (!$pilihan) {
            $this->sendMenuTipeLayanan($session->phone);
            return null;
        }

        $mapping = [
            '1' => 'Antar Jemput',
            '2' => 'Antar Saja',
            '3' => 'Jemput Saja',
        ];

        $session->update([
            'step' => 'tanya_nama',
            'jenis_layanan' => $mapping[$pilihan],
            'data' => [],
        ]);

        return "Baik, *{$mapping[$pilihan]}* dipilih ✅\n\nSiapa nama Anda?";
    }

    protected function handleTanyaNama(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Nama tidak boleh kosong ya, mohon ketik nama Anda 🙏";
        }

        $this->saveData($session, 'nama', $text);
        $session->update(['step' => 'tanya_alamat_customer']);

        return "Baik, {$text} 👋\n\nAlamat penjemputan/pengantaran di mana?";
    }

    protected function handleTanyaAlamatCustomer(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Alamat tidak boleh kosong ya, mohon ketik alamat Anda 🙏";
        }

        $this->saveData($session, 'alamat_customer', $text);

        if ($session->jenis_layanan === 'Jemput Saja') {
            $session->update(['step' => 'tanya_alamat_laundry']);
            return "Laundry mana yang ingin dituju? Mohon kirim alamat laundrynya (wajib diisi):";
        }

        $session->update(['step' => 'tanya_alamat_laundry']);
        return "Apakah ada laundry spesifik yang ingin dipilih?\n\n"
             . "Ketik *tidak* akan kami pilihkan, atau tulis alamatnya jika ada.";
    }

    protected function handleTanyaAlamatLaundry(ChatSession $session, string $text): string
    {
        $kosong = $this->isKosong($text);

        if ($session->jenis_layanan === 'Jemput Saja' && $kosong) {
            return "Alamat laundry wajib diisi untuk layanan Jemput Saja ya 🙏 Mohon kirim alamatnya.";
        }

        $this->saveData($session, 'alamat_laundry', $kosong ? '' : $text);
        $session->update(['step' => 'tanya_catatan']);

        return "Ada catatan tambahan untuk pesanan Anda?\n\n"
             . "Jika tidak ada, balas *tidak*.";
    }

    protected function handleTanyaCatatan(ChatSession $session, string $text): string
    {
        $kosong = $this->isKosong($text);
        $this->saveData($session, 'catatan', $kosong ? '' : $text);

        $session->update(['step' => 'konfirmasi']);

        return $this->buildRingkasan($session)
             . "\n\nSemua data sudah benar?\n"
             . "Balas *ya* untuk buat pesanan, atau *ubah* untuk isi ulang.";
    }

    protected function handleKonfirmasi(ChatSession $session, string $text): string
    {
        $jawaban = strtolower($text);

        if (in_array($jawaban, ['ubah', 'edit', 'salah', 'ulang'])) {
            $session->update(['step' => 'tanya_nama', 'data' => []]);
            return "Baik, mari kita isi ulang.\n\nSiapa nama Anda?";
        }

        if (!in_array($jawaban, ['ya', 'iya', 'benar', 'ok', 'oke'])) {
            return $this->buildRingkasan($session)
                 . "\n\nMohon balas *ya* untuk buat pesanan, atau *ubah* untuk isi ulang.";
        }

        $data = $session->data ?? [];

        $tipeAntarJemput = match ($session->jenis_layanan) {
            'Antar Jemput' => 'Antar Jemput (PP)',
            'Antar Saja'   => 'Antar Saja',
            'Jemput Saja'  => 'Jemput Saja',
            default        => 'Antar Jemput (PP)',
        };

        $status = $tipeAntarJemput === 'Jemput Saja' ? 'Dicuci' : 'Diproses';

        try {
            $order = Order::create([
                'nama' => $data['nama'] ?? '',
                'phone' => $session->phone,
                'alamat_customer' => $data['alamat_customer'] ?? '',
                'alamat_laundry' => !empty($data['alamat_laundry']) ? $data['alamat_laundry'] : '-',
                'phone_laundry' => null,
                'note' => !empty($data['catatan']) ? $data['catatan'] : null,
                'jenis_layanan' => $session->jenis_layanan,
                'tipe_antar_jemput' => $tipeAntarJemput,
                'status' => $status,
                'fee' => 0,
                'is_sorted' => 0,
                'dokumentasi_pakaian' => null,
                'estimasi_jumlah_laundry' => null,
                'current_driver_id' => null,
                'zona' => null,
                'jarak_km' => null,
                'rincian_fee' => null,
                'tanggal_penjemputan' => now(),
                'token' => $this->generateUniqueToken(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal membuat order dari WA bot', [
                'phone' => $session->phone,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            return "Maaf, terjadi kendala saat membuat pesanan Anda 🙏\n\n"
                 . "Mohon coba balas *ya* sekali lagi, atau hubungi admin jika masalah berlanjut.";
        }

        $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);

        $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";
$alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
    ? $order->alamat_laundry
    : 'Akan ditentukan oleh driver kami';

return "Pesanan Anda berhasil dibuat! ✅\n\n"
     . "Kode Pesanan : *{$order->token}*\n"
     . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
     . "Alamat Laundry : {$alamatLaundryText}\n\n"
     . "Simpan kode ini untuk cek status pesanan Anda kapan saja.\n\n"
     . "Pantau pesanan Anda di sini:\n{$linkCek}\n\n"
     . "Terima kasih telah menggunakan layanan kami! 🙏";
    }

    protected function handleCekStatus(ChatSession $session, string $text): string
    {
        $order = Order::where('token', trim($text))->first();

        $session->update(['step' => 'menu']);

        if (!$order) {
            return "Kode pesanan *{$text}* tidak ditemukan. Mohon cek kembali kode Anda 🙏";
        }

        $foto = $order->dokumentasi_pakaian ?: '-';
        $alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
            ? $order->alamat_laundry
            : 'Belum/akan ditentukan oleh driver';

        $driverInfo = '';
        if ($order->current_driver_id && $order->currentDriver) {
            $driverInfo = "\nDriver : {$order->currentDriver->name}"
                        . "\nKontak Driver : {$order->currentDriver->phone}";
        }

        $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";

        return "Status Pesanan *{$order->token}*\n\n"
            . "Nama : {$order->nama}\n"
            . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
            . "Alamat Laundry : {$alamatLaundryText}\n"
            . "Status Saat Ini : *{$order->status}*\n"
            . "Foto Pakaian : {$foto}"
            . $driverInfo
            . "\n\nPantau pesanan Anda lebih detail di sini:\n{$linkCek}";
    }

    protected function buildRingkasan(ChatSession $session): string
    {
        $data = $session->data ?? [];

        return "Konfirmasi Pesanan 📋\n\n"
             . "Layanan : {$session->jenis_layanan}\n"
             . "Nama : " . ($data['nama'] ?? '-') . "\n"
             . "Alamat Penjemputan : " . ($data['alamat_customer'] ?? '-') . "\n"
             . "Alamat Laundry : " . (!empty($data['alamat_laundry']) ? $data['alamat_laundry'] : '-') . "\n"
             . "Catatan : " . (!empty($data['catatan']) ? $data['catatan'] : '-');
    }

    protected function saveData(ChatSession $session, string $key, string $value): void
    {
        $data = $session->data ?? [];
        $data[$key] = $value;
        $session->update(['data' => $data]);
    }

    protected function isKosong(string $text): bool
    {
        return in_array(strtolower(trim($text)), ['-', 'tidak', 'tidak ada', 'gak ada', 'nggak ada', 'kosong']);
    }

    protected function generateUniqueToken(): string
    {
        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
            $token = 'LND-' . $random;
        } while (Order::where('token', $token)->exists());

        return $token;
    }
}