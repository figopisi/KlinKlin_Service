<?php

namespace App\Http\Controllers;

use App\Models\BundlePackage;
use App\Models\BundlePurchase;
use App\Models\ChatSession;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\Promotion;
use App\Services\WablasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WablasWebhookController extends Controller
{
    public function handle(Request $request)
    {
        if ($request->boolean('isGroup')) {
            return response('', 200);
        }

        if ($request->boolean('isFromMe')) {
            \Log::info('Pesan isFromMe diabaikan', ['phone' => $request->input('phone')]);
            return response('', 200);
        }

        \Log::info('WABLAS PAYLOAD MASUK:', $request->all());

        $phone = $request->input('phone');
        $text  = trim($request->input('message', ''));

        if (!$phone || $text === '') {
            return response('', 200);
        }

        $msgId = $request->input('id');

        if ($msgId) {
            $cacheKey = "wablas_msg_{$msgId}";
            $isNew = Cache::add($cacheKey, true, now()->addMinutes(10));
            if (!$isNew) {
                \Log::info("Duplicate webhook diabaikan, msgId={$msgId}");
                return response('', 200);
            }
        }

        $contentKey = 'wablas_content_' . md5($phone . '|' . $text);
        $isNewContent = Cache::add($contentKey, true, now()->addSeconds(8));
        if (!$isNewContent) {
            \Log::info("Duplicate webhook (content-based) diabaikan, phone={$phone}, message={$text}");
            return response('', 200);
        }

        $lock = Cache::lock("wablas_phone_lock_{$phone}", 15);

        try {
            $lock->block(10);
        } catch (\Throwable $e) {
            \Log::warning("Gagal mendapat lock untuk phone={$phone}, request diabaikan.");
            return response('', 200);
        }

        try {
            $this->processMessage($phone, $text);
        } finally {
            $lock->release();
        }

        return response('', 200);
    }

    protected function processMessage(string $phone, string $text): void
    {
        $session = DB::transaction(function () use ($phone) {
            return ChatSession::firstOrCreate(
                ['phone' => $phone],
                ['step' => 'menu']
            );
        });

        // ============================================================
        // GERBANG PALING AWAL: registrasi profil baru (hanya sekali).
        // Ini WAJIB paling atas, sebelum pengecekan bot_active apapun,
        // supaya alur bundle/pemesanan lain tidak bisa "menyalip" gerbang ini.
        // ============================================================
        if ($session->wasRecentlyCreated) {
            $profileExists = CustomerProfile::where('phone', $phone)->exists();
            if (!$profileExists) {
                $session->update(['step' => 'registrasi_nama']);

                app(WablasService::class)->sendText(
                    $phone,
                    "Halo, selamat datang di KlinKlin! 👋\n\n"
                    . "Sebelum mulai, kami perlu data diri Anda untuk membuat profil.\n"
                    . "*Ini hanya perlu diisi sekali saja* — setelah profil dibuat, Anda tidak perlu mengisi ulang untuk pesanan berikutnya 🙏\n\n"
                    . "Boleh tahu nama Anda?"
                );
                return;
            }
        }

        // bot_active = false dipakai untuk 2 kondisi yang sama-sama menunggu
        // verifikasi admin: (1) verifikasi KTM mahasiswa, (2) pengajuan bundle
        // menunggu approve. Selama bot_active false, pesan apapun dari customer
        // diabaikan sampai admin approve/reject via panel admin.
        if (!$session->bot_active) {
            return;
        }

        $stepPemesanan = [
            'pilih_layanan', 'tanya_pakai_bundle', 'tanya_pakai_profil', 'tanya_nama', 'tanya_alamat_customer',
            'tanya_alamat_laundry', 'tanya_promo', 'tanya_catatan', 'konfirmasi',
            'menu_bundle', 'bundle_pilih_paket', 'bundle_konfirmasi',
        ];

        if (in_array($session->step, $stepPemesanan) && in_array(strtolower(trim($text)), ['batal', 'cancel', 'batalkan'])) {
            $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);
            app(WablasService::class)->sendText($phone, "Proses dibatalkan ❌");
            $this->sendMenuUtama($phone);
            return;
        }

        $reply = match ($session->step) {
            'registrasi_nama'       => $this->handleRegistrasiNama($session, $text),
            'registrasi_alamat'     => $this->handleRegistrasiAlamat($session, $text),
            'registrasi_mahasiswa'  => $this->handleRegistrasiMahasiswa($session, $text),
            'ubah_profil_nama'      => $this->handleUbahProfilNama($session, $text),
            'ubah_profil_alamat'    => $this->handleUbahProfilAlamat($session, $text),
            'pilih_layanan'         => $this->handlePilihLayanan($session, $text),
            'tanya_pakai_bundle'    => $this->handleTanyaPakaiBundle($session, $text),
            'tanya_pakai_profil'    => $this->handleTanyaPakaiProfil($session, $text),
            'tanya_nama'            => $this->handleTanyaNama($session, $text),
            'tanya_alamat_customer' => $this->handleTanyaAlamatCustomer($session, $text),
            'tanya_alamat_laundry'  => $this->handleTanyaAlamatLaundry($session, $text),
            'tanya_promo'           => $this->handleTanyaPromo($session, $text),
            'tanya_catatan'         => $this->handleTanyaCatatan($session, $text),
            'konfirmasi'            => $this->handleKonfirmasi($session, $text),
            'cek_status'            => $this->handleCekStatus($session, $text),
            'menu_bundle'           => $this->handleMenuBundle($session, $text),
            'bundle_pilih_paket'    => $this->handleBundlePilihPaket($session, $text),
            'bundle_konfirmasi'     => $this->handleBundleKonfirmasi($session, $text),
            default                 => $this->handleMenu($session, $text),
        };

        if ($reply !== null) {
            app(WablasService::class)->sendText($phone, $reply);
        }
    }

    // ============ REGISTRASI PROFIL BARU (HANYA SEKALI) ============

    protected function handleRegistrasiNama(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Nama tidak boleh kosong ya, mohon ketik nama Anda 🙏";
        }

        $this->saveData($session, 'reg_nama', $text);
        $session->update(['step' => 'registrasi_alamat']);

        return "Baik, {$text} 👋\n\nBoleh tahu alamat tempat tinggal Anda?";
    }

    protected function handleRegistrasiAlamat(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Alamat tidak boleh kosong ya, mohon ketik alamat Anda 🙏";
        }

        $this->saveData($session, 'reg_alamat', $text);
        $session->update(['step' => 'registrasi_mahasiswa']);

        return "Apakah Anda seorang mahasiswa? (ya/tidak)\n\n"
             . "*Catatan: mahasiswa mendapat keuntungan promo khusus dari kami.*";
    }

    protected function handleRegistrasiMahasiswa(ChatSession $session, string $text): ?string
    {
        $jawaban = $this->matchYaTidak($text);

        if ($jawaban === null) {
            return "Mohon balas *ya* atau *tidak* ya 🙏";
        }

        $data = $session->data ?? [];

        if ($jawaban === 'ya') {
            CustomerProfile::updateOrCreate(
                ['phone' => $session->phone],
                ['nama' => $data['reg_nama'], 'alamat_customer' => $data['reg_alamat'], 'status' => 'unconfirmed']
            );

            $session->update(['bot_active' => false, 'data' => null]);

            return "Terima kasih! Profil Anda sudah tersimpan ✅ (data ini tidak perlu diisi ulang lagi ke depannya)\n\n"
                . "Untuk verifikasi status mahasiswa, mohon *kirim foto KTM Anda langsung di sini* 📸\n\n"
                . "Admin kami akan segera memproses verifikasi Anda melalui chat ini juga — tidak perlu menghubungi nomor lain.\n\n"
                . "Setelah diverifikasi, Anda akan otomatis bisa lanjut menggunakan layanan kami 🙏";
        }

        CustomerProfile::updateOrCreate(
            ['phone' => $session->phone],
            ['nama' => $data['reg_nama'], 'alamat_customer' => $data['reg_alamat'], 'status' => 'biasa']
        );

        $session->update(['step' => 'menu', 'data' => null]);

        app(WablasService::class)->sendText(
            $session->phone,
            "Profil Anda berhasil dibuat ✅ (data ini tidak perlu diisi ulang lagi untuk pesanan berikutnya)"
        );

        $this->sendMenuUtama($session->phone);
        return null;
    }

    protected function matchYaTidak(string $text): ?string
    {
        $t = strtolower(trim($text));
        if (in_array($t, ['ya', 'iya', 'yes', 'y'])) return 'ya';
        if (in_array($t, ['tidak', 'tdk', 'no', 'gak', 'nggak'])) return 'tidak';
        return null;
    }

    // ============ UBAH PROFIL ============

    protected function handleUbahProfilNama(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Nama tidak boleh kosong ya 🙏";
        }

        CustomerProfile::where('phone', $session->phone)->update(['nama' => $text]);
        $session->update(['step' => 'ubah_profil_alamat']);

        return "Nama berhasil diubah ✅\n\nSekarang, alamat baru Anda apa?";
    }

    protected function handleUbahProfilAlamat(ChatSession $session, string $text): ?string
    {
        if ($text === '') {
            return "Alamat tidak boleh kosong ya 🙏";
        }

        CustomerProfile::where('phone', $session->phone)->update(['alamat_customer' => $text]);
        $session->update(['step' => 'menu']);

        $this->sendMenuUtama($session->phone);
        return "Alamat berhasil diubah ✅";
    }

    // ============ MENU UTAMA ============

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
            if ($pilihan === '3') {
                $session->update(['step' => 'menu_bundle']);
                return $this->sendMenuBundle();
            }
            if ($pilihan === '4') {
                $session->update(['step' => 'ubah_profil_nama']);
                return "Baik, mari ubah profil Anda.\n\nNama baru Anda apa?";
            }
            if ($pilihan === '5') {
                $daftarAdmin = collect(config('services.wablas.cs_admins'))
                    ->map(fn($a) => "• {$a['nama']}: wa.me/{$a['phone']}")
                    ->implode("\n");
                return "Untuk pembatalan pesanan, perubahan detail pesanan, atau kendala lainnya, silakan hubungi CS kami:\n\n{$daftarAdmin}";
            }
        }

        $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);
        $this->sendMenuUtama($session->phone);
        return null;
    }

    protected function sendMenuUtama(string $phone): void
    {
        app(WablasService::class)->sendText($phone,
            "Selamat datang di KlinKlin👋\n\n"
            . "Silakan pilih menu:\n\n"
            . "1. Buat Pesanan\n"
            . "2. Cek Status Pesanan\n"
            . "3. Bundle Hemat\n"
            . "4. Ubah Profil\n"
            . "5. Hubungi CS\n\n"
            . "Balas dengan angka (1-5)"
        );
    }

    protected function matchMenuUtama(string $text): ?string
    {
        $t = strtolower(trim($text));
        if ($t === '1' || str_contains($t, 'buat pesanan')) return '1';
        if ($t === '2' || str_contains($t, 'cek status')) return '2';
        if ($t === '3' || str_contains($t, 'bundle')) return '3';
        if ($t === '4' || str_contains($t, 'ubah profil')) return '4';
        if ($t === '5' || str_contains($t, 'hubungi cs')) return '5';
        return null;
    }

    protected function sendMenuTipeLayanan(string $phone): void
    {
        app(WablasService::class)->sendText($phone,
            "Silakan pilih tipe layanan:\n\n"
            . "1. Antar Jemput\n"
            . "2. Antar Saja\n"
            . "3. Jemput Saja\n\n"
            . "Balas dengan angka (1/2/3)\n\n"
            . "📌 Penjelasan:\n"
            . "1. *Antar Jemput* — Kami jemput pakaian Anda, antarkan ke laundry pilihan anda atau mitra kami, lalu antar kembali.\n"
            . "2. *Antar Saja* — Kami antarkan pakaian anda ke laundry pilihan Anda/mitra kami, Anda ambil sendiri.\n"
            . "3. *Jemput Saja* — Kami jemput Pakaian dari laundry pilihan anda, ke rumah anda\n\n"
            . "_Ketik *batal* kapan saja untuk membatalkan proses pemesanan ini._"
        );
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

        $mapping = ['1' => 'Antar Jemput', '2' => 'Antar Saja', '3' => 'Jemput Saja'];
        $jenisLayanan = $mapping[$pilihan];

        $session->update([
            'step' => 'tanya_pakai_bundle',
            'jenis_layanan' => $jenisLayanan,
            'data' => [],
        ]);

        // Bundle hanya berlaku untuk layanan Antar Jemput
        $activeBundle = ($jenisLayanan === 'Antar Jemput')
            ? $this->getActiveBundle($session->phone)
            : null;

        if (!$activeBundle) {
            $session->update(['step' => 'tanya_pakai_profil']);
            return "Baik, *{$jenisLayanan}* dipilih ✅\n\n"
                . "Apakah nama dan alamat penjemputan sama dengan profil Anda? (ya/tidak)";
        }

        $this->saveData($session, 'available_bundle_id', $activeBundle->id);

        return "Baik, *{$jenisLayanan}* dipilih ✅\n\n"
            . "Anda memiliki bundle aktif *{$activeBundle->nama_paket_snapshot}* dengan sisa *{$activeBundle->kuota_tersisa} trip*.\n\n"
            . "Gunakan bundle untuk pesanan ini? (ya/tidak)";
    }

    protected function handleTanyaPakaiBundle(ChatSession $session, string $text): string
    {
        $jawaban = $this->matchYaTidak($text);

        if ($jawaban === null) {
            return "Mohon balas *ya* atau *tidak* ya 🙏";
        }

        if ($jawaban === 'ya') {
            $data = $session->data ?? [];
            $this->saveData($session, 'bundle_purchase_id', $data['available_bundle_id'] ?? null);
        }

        $session->update(['step' => 'tanya_pakai_profil']);
        return "Apakah nama dan alamat penjemputan sama dengan profil Anda? (ya/tidak)";
    }

    // ============ DATA PESANAN ============

    protected function handleTanyaPakaiProfil(ChatSession $session, string $text): string
    {
        $jawaban = $this->matchYaTidak($text);

        if ($jawaban === null) {
            return "Mohon balas *'ya'* atau *'tidak'* ya 🙏";
        }

        if ($jawaban === 'ya') {
            $profile = CustomerProfile::where('phone', $session->phone)->first();
            $this->saveData($session, 'nama', $profile->nama);
            $this->saveData($session, 'alamat_customer', $profile->alamat_customer);

            $session->update(['step' => 'tanya_alamat_laundry']);
            return $this->pertanyaanAlamatLaundry($session);
        }

        $session->update(['step' => 'tanya_nama']);
        return "Baik, siapa nama Anda?";
    }

    protected function handleTanyaNama(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Nama tidak boleh kosong ya, mohon ketik nama Anda 🙏";
        }

        $this->saveData($session, 'nama', $text);
        $session->update(['step' => 'tanya_alamat_customer']);

        return "Baik, {$text} 👋\n\nAlamat Rumah Anda di mana?";
    }

    protected function handleTanyaAlamatCustomer(ChatSession $session, string $text): string
    {
        if ($text === '') {
            return "Alamat tidak boleh kosong ya, mohon ketik alamat Anda 🙏";
        }

        $this->saveData($session, 'alamat_customer', $text);
        $session->update(['step' => 'tanya_alamat_laundry']);

        return $this->pertanyaanAlamatLaundry($session);
    }

    protected function pertanyaanAlamatLaundry(ChatSession $session): string
    {
        if ($session->jenis_layanan === 'Jemput Saja') {
            return "Laundry mana yang ingin dituju? Mohon kirim alamat laundrynya (wajib diisi):";
        }

        return "Ada laundry spesifik yang ingin dipilih?\n\n"
             . "Balas *'tidak'* jika tidak ada, kami akan rekomendasikan dan pilihkan mitra laundry kami yang cocok untuk Anda dan pastinya terpercaya.";
    }

    protected function handleTanyaAlamatLaundry(ChatSession $session, string $text): ?string
    {
        $kosong = $this->isKosong($text);

        if ($session->jenis_layanan === 'Jemput Saja' && $kosong) {
            return "Alamat laundry wajib diisi untuk layanan Jemput Saja ya 🙏 Mohon kirim alamatnya.";
        }

        $this->saveData($session, 'alamat_laundry', $kosong ? '' : $text);

        $data = $session->data ?? [];

        // Pakai bundle -> skip promo, harga sudah fix dari bundle
        if (!empty($data['bundle_purchase_id'])) {
            $session->update(['step' => 'tanya_catatan']);
            return $this->pertanyaanCatatan();
        }

        $session->update(['step' => 'tanya_promo']);
        return $this->tawarkanPromo($session);
    }

    // ============ PROMO (SELALU DITAWARKAN SEBELUM KONFIRMASI, KECUALI PAKAI BUNDLE) ============

    protected function tawarkanPromo(ChatSession $session): string
    {
        $promos = Promotion::where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')->orWhereDate('tanggal_mulai', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('kuota')->orWhereColumn('terpakai', '<', 'kuota');
            })
            ->get();

        if ($promos->isEmpty()) {
            $session->update(['step' => 'tanya_catatan']);
            return "Saat ini belum ada promo yang tersedia untuk Anda.\n\n"
                . $this->pertanyaanCatatan();
        }

        $daftar = $promos->map(function ($p, $i) {
            $label = ($i + 1) . ". *{$p->nama_promo}* — " . ($p->deskripsi ?? 'Tanpa deskripsi');
            if ($p->khusus_mahasiswa) {
                $label .= " 🎓 _(khusus mahasiswa)_";
            }
            return $label;
        })->implode("\n");

        $this->saveData($session, 'promo_options', $promos->pluck('id')->values()->toArray());

        return "Ada promo menarik untuk Anda! 🎉\n\n"
            . $daftar
            . "\n\nBalas dengan *nomor promo* yang ingin dipakai (contoh: 1), atau ketik *tidak* jika tidak ingin memakai promo.";
    }

    protected function handleTanyaPromo(ChatSession $session, string $text): string
    {
        if ($this->isKosong($text)) {
            $this->saveData($session, 'promo_id', null);
            $this->saveData($session, 'promo_nama', null);
            $session->update(['step' => 'tanya_catatan']);
            return $this->pertanyaanCatatan();
        }

        $data = $session->data ?? [];
        $options = $data['promo_options'] ?? [];
        $index = ((int) trim($text)) - 1;

        if (!isset($options[$index])) {
            return "Mohon balas dengan nomor promo yang tersedia (sesuai daftar di atas), atau ketik *tidak* untuk lewati.";
        }

        $promoId = $options[$index];
        $promo = Promotion::find($promoId);

        if ($promo && $promo->khusus_mahasiswa) {
            $profile = CustomerProfile::where('phone', $session->phone)->first();

            if (!$profile || $profile->status !== 'mahasiswa') {
                $daftarAdmin = collect(config('services.wablas.cs_admins'))
                    ->map(fn($a) => "• {$a['nama']}: wa.me/{$a['phone']}")
                    ->implode("\n");

                return "Maaf, promo *{$promo->nama_promo}* khusus untuk mahasiswa terverifikasi 🎓\n\n"
                    . "Status Anda saat ini belum terverifikasi sebagai mahasiswa. Silakan kirim foto KTM Anda ke salah satu admin kami berikut untuk verifikasi:\n\n"
                    . $daftarAdmin
                    . "\n\nSetelah terverifikasi, Anda bisa klaim promo ini di pesanan berikutnya 🙏\n\n"
                    . "Untuk sekarang, silakan pilih promo lain atau ketik *tidak* jika ingin lanjut tanpa promo.";
            }
        }

        $this->saveData($session, 'promo_id', $promoId);
        $this->saveData($session, 'promo_nama', $promo->nama_promo ?? null);
        $session->update(['step' => 'tanya_catatan']);

        return "Promo *" . ($promo->nama_promo ?? '') . "* berhasil dipilih ✅\n\n" . $this->pertanyaanCatatan();
    }

    // ============ CATATAN & KONFIRMASI ============

    protected function pertanyaanCatatan(): string
    {
        return "Terakhir, mohon berikan catatan untuk driver dan laundry ya 🙏\n\n"
             . "Boleh mencakup:\n"
             . "• Waktu penjemputan yang diinginkan\n"
             . "• Apakah cucian perlu kami pilah\n"
             . "• Jenis jasa laundry (cuci saja, setrika saja, cuci+setrika, dry clean, dll)\n"
             . "• Instruksi khusus lainnya\n\n"
             . "Jika tidak ada catatan tambahan, balas *tidak*.";
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

        $orderPayload = [
            'nama' => $data['nama'] ?? '',
            'phone' => $session->phone,
            'alamat_customer' => $data['alamat_customer'] ?? '',
            'alamat_laundry' => !empty($data['alamat_laundry']) ? $data['alamat_laundry'] : '-',
            'phone_laundry' => null,
            'note' => !empty($data['catatan']) ? $data['catatan'] : null,
            'jenis_layanan' => $session->jenis_layanan,
            'tipe_antar_jemput' => $tipeAntarJemput,
            'status' => 'Unconfirmed',
            'promo_id' => $data['promo_id'] ?? null,
            'is_sorted' => 0,
            'dokumentasi_pakaian' => null,
            'estimasi_jumlah_laundry' => null,
            'current_driver_id' => null,
            'zona' => null,
            'jarak_km' => null,
            'rincian_fee' => null,
            'tanggal_penjemputan' => now(),
            'token' => $this->generateUniqueToken(),
        ];

        try {
            if (!empty($data['bundle_purchase_id'])) {
                $order = DB::transaction(function () use ($orderPayload, $data) {
                    $bundlePurchase = BundlePurchase::where('id', $data['bundle_purchase_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$bundlePurchase || !$bundlePurchase->is_usable) {
                        throw new \Exception('bundle_invalid');
                    }

                    $hargaPerTrip = round(
                        (float) $bundlePurchase->harga_snapshot / $bundlePurchase->jumlah_trip_snapshot
                    );

                    $orderPayload['fee'] = $hargaPerTrip;
                    $orderPayload['fee_sebelum_diskon'] = $hargaPerTrip;
                    $orderPayload['bundle_purchase_id'] = $bundlePurchase->id;
                    $orderPayload['promo_id'] = null;

                    // Kuota otomatis berkurang begitu order ini tercatat, karena
                    // kuota_tersisa dihitung dari COUNT(orders) yang punya bundle_purchase_id ini.
                    // Tidak perlu decrement manual.
                    $order = Order::create($orderPayload);

                    if (method_exists($order, 'hitungPenghasilan')) {
                        $order->hitungPenghasilan();
                    }

                    return $order;
                });
            } else {
                $orderPayload['fee'] = 0;
                $order = Order::create($orderPayload);
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal membuat order dari WA bot', ['phone' => $session->phone, 'data' => $data, 'error' => $e->getMessage()]);

            if ($e->getMessage() === 'bundle_invalid') {
                $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);
                $this->sendMenuUtama($session->phone);
                return "Maaf, bundle Anda sudah tidak berlaku (kuota habis/kedaluwarsa). Pesanan dibatalkan, silakan cek menu Bundle atau buat pesanan tanpa bundle 🙏";
            }

            return "Maaf, terjadi kendala saat membuat pesanan Anda 🙏\n\nMohon coba balas *ya* sekali lagi, atau hubungi admin.";
        }

        $session->update(['step' => 'menu', 'jenis_layanan' => null, 'data' => null]);

        $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";
        $alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
            ? $order->alamat_laundry : 'Akan ditentukan oleh admin kami';

        $infoBundle = !empty($data['bundle_purchase_id'])
            ? "\nMenggunakan Bundle ✅ (fee otomatis terpotong dari kuota bundle Anda)"
            : '';

        return "Pesanan Anda berhasil diterima! ✅\n\n"
             . "Kode Pesanan : *{$order->token}*\n"
             . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
             . "Alamat Laundry : {$alamatLaundryText}"
             . $infoBundle . "\n\n"
             . "Pesanan Anda sedang kami konfirmasi, mohon tunggu ya 🙏\n\n"
             . "Pantau pesanan Anda di sini:\n{$linkCek}\n\n"
             . "Terima kasih telah menggunakan layanan kami! 🙏";
    }

    // ============ BUNDLE ============

    protected function sendMenuBundle(): string
    {
        return "📦 *Menu Bundle Hemat*\n\n"
             . "1. Beli Bundle\n"
             . "2. Cek Bundle Aktif Saya\n"
             . "3. Kembali ke Menu Utama\n\n"
             . "Balas dengan angka (1-3)";
    }

    protected function handleMenuBundle(ChatSession $session, string $text): ?string
    {
        $t = trim($text);
        $tLower = strtolower($t);

        if ($t === '1' || str_contains($tLower, 'beli')) {
            $packages = BundlePackage::where('is_active', true)->orderBy('urutan')->orderBy('harga')->get();

            if ($packages->isEmpty()) {
                $session->update(['step' => 'menu']);
                $this->sendMenuUtama($session->phone);
                return "Maaf, saat ini belum ada paket bundle yang tersedia 🙏";
            }

            $daftar = $packages->map(function ($p, $i) {
                $hargaPerTrip = number_format(intdiv($p->harga, $p->jumlah_trip), 0, ',', '.');
                return ($i + 1) . ". *{$p->nama_paket}* — Rp " . number_format($p->harga, 0, ',', '.')
                     . " ({$p->jumlah_trip}x trip, ± Rp {$hargaPerTrip}/trip)";
            })->implode("\n");

            $this->saveData($session, 'bundle_package_options', $packages->pluck('id')->values()->toArray());
            $session->update(['step' => 'bundle_pilih_paket']);

            return "Berikut paket bundle yang tersedia:\n\n{$daftar}\n\n"
                 . "Balas dengan *nomor paket* yang ingin dibeli.\n\n"
                 . "_Ketik *batal* kapan saja untuk membatalkan._";
        }

        if ($t === '2' || str_contains($tLower, 'cek')) {
            $session->update(['step' => 'menu']);
            $reply = $this->getBundleAktifText($session->phone);
            $this->sendMenuUtama($session->phone);
            return $reply;
        }

        if ($t === '3' || str_contains($tLower, 'kembali')) {
            $session->update(['step' => 'menu']);
            $this->sendMenuUtama($session->phone);
            return null;
        }

        return $this->sendMenuBundle();
    }

    protected function handleBundlePilihPaket(ChatSession $session, string $text): string
    {
        $data = $session->data ?? [];
        $options = $data['bundle_package_options'] ?? [];
        $index = ((int) trim($text)) - 1;

        if (!isset($options[$index])) {
            return "Mohon balas dengan nomor paket yang tersedia sesuai daftar di atas ya 🙏";
        }

        $package = BundlePackage::where('is_active', true)->find($options[$index]);

        if (!$package) {
            return "Paket tidak ditemukan, mungkin sudah tidak aktif. Mohon pilih ulang dari daftar 🙏";
        }

        $this->saveData($session, 'bundle_package_id', $package->id);
        $session->update(['step' => 'bundle_konfirmasi']);

        $hargaPerTrip = number_format(intdiv($package->harga, $package->jumlah_trip), 0, ',', '.');

        return "Anda memilih paket *{$package->nama_paket}*\n\n"
             . "Harga : Rp " . number_format($package->harga, 0, ',', '.') . "\n"
             . "Jumlah Trip : {$package->jumlah_trip}x\n"
             . "Harga per Trip : ± Rp {$hargaPerTrip}\n\n"
             . "Lanjutkan pembelian? (ya/tidak)";
    }

    protected function handleBundleKonfirmasi(ChatSession $session, string $text): ?string
    {
        $jawaban = $this->matchYaTidak($text);

        if ($jawaban === null) {
            return "Mohon balas *ya* atau *tidak* ya 🙏";
        }

        if ($jawaban === 'tidak') {
            $session->update(['step' => 'menu', 'data' => null]);
            $this->sendMenuUtama($session->phone);
            return "Pembelian bundle dibatalkan ❌";
        }

        $data = $session->data ?? [];
        $package = BundlePackage::find($data['bundle_package_id'] ?? null);

        if (!$package) {
            $session->update(['step' => 'menu', 'data' => null]);
            $this->sendMenuUtama($session->phone);
            return "Maaf, terjadi kendala. Mohon ulangi dari menu Bundle 🙏";
        }

        $customer = CustomerProfile::where('phone', $session->phone)->first();

        BundlePurchase::create([
            'customer_id'                => $customer->id,
            'bundle_package_id'          => $package->id,
            'nama_paket_snapshot'        => $package->nama_paket,
            'harga_snapshot'             => $package->harga,
            'jumlah_trip_snapshot'       => $package->jumlah_trip,
            'masa_berlaku_hari_snapshot' => $package->masa_berlaku_hari,
            'status'                     => 'pending',
        ]);

        // Nonaktifkan bot sampai admin approve/reject — pola sama seperti
        // verifikasi mahasiswa. Bukti pembayaran TIDAK diproses lewat bot;
        // admin input manual dari panel admin saat approve.
        $session->update(['step' => 'menu', 'data' => null, 'bot_active' => false]);

        return "Pengajuan bundle *{$package->nama_paket}* berhasil dibuat ✅\n\n"
             . "Silakan lakukan pembayaran dan *kirim bukti transfer (foto) langsung di sini* 📸\n\n"
             . "Admin kami akan segera memverifikasi pembayaran Anda melalui chat ini juga — tidak perlu menghubungi nomor lain.\n\n"
             . "Setelah disetujui, bundle Anda otomatis aktif dan Anda bisa langsung lanjut memesan 🙏";
    }

    protected function getActiveBundle(string $phone): ?BundlePurchase
    {
        $customer = CustomerProfile::where('phone', $phone)->first();

        if (!$customer) {
            return null;
        }

        // kuota_tersisa & is_usable itu accessor (PHP-side), jadi filter
        // status/expired dulu di query, sisanya difilter setelah diambil.
        return BundlePurchase::where('customer_id', $customer->id)
            ->where('status', 'disetujui')
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')->orWhere('tanggal_berakhir', '>=', now());
            })
            ->latest('tanggal_mulai')
            ->get()
            ->first(fn($b) => $b->is_usable);
    }

    protected function getBundleAktifText(string $phone): string
    {
        $activeBundle = $this->getActiveBundle($phone);

        if (!$activeBundle) {
            return "Anda belum memiliki bundle aktif saat ini.\n\nKetik *bundle* untuk membeli paket bundle 📦";
        }

        $berakhir = $activeBundle->tanggal_berakhir ? $activeBundle->tanggal_berakhir->format('d M Y') : '-';

        return "📦 *Bundle Aktif Anda*\n\n"
             . "Paket : {$activeBundle->nama_paket_snapshot}\n"
             . "Sisa Kuota : {$activeBundle->kuota_tersisa} trip\n"
             . "Berlaku sampai : {$berakhir}";
    }

    // ============ CEK STATUS ============

    protected function handleCekStatus(ChatSession $session, string $text): string
    {
        $order = Order::where('token', trim($text))->first();
        $session->update(['step' => 'menu']);

        if (!$order) {
            return "Kode pesanan *{$text}* tidak ditemukan. Mohon cek kembali kode Anda 🙏";
        }

        $alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
            ? $order->alamat_laundry : 'Belum/akan ditentukan oleh admin';

        $driverInfo = '';
        if ($order->current_driver_id && $order->currentDriver) {
            $driverInfo = "\nDriver : {$order->currentDriver->name}\nKontak Driver : {$order->currentDriver->phone}";
        }

        $totalFee = ($order->fee ?? 0) + ($order->fee_laundry ?? 0);
        $feeInfo = "\nFee Jasa : Rp " . number_format($order->fee ?? 0, 0, ',', '.')
                 . "\nFee Laundry : Rp " . number_format($order->fee_laundry ?? 0, 0, ',', '.')
                 . "\nTotal Fee : Rp " . number_format($totalFee, 0, ',', '.');

        $estimasi = $order->estimasi_waktu_pengerjaan ?? '-';

        $dokumentasiInfo = !empty($order->dokumentasi_pakaian)
            ? "\n📸 Dokumentasi Pakaian:\n{$order->dokumentasi_pakaian}" : '';

        $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";

        return "Status Pesanan *{$order->token}*\n\n"
            . "Nama : {$order->nama}\n"
            . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
            . "Alamat Laundry : {$alamatLaundryText}\n"
            . "Status Saat Ini : *{$order->status}*\n"
            . "Estimasi Waktu Pengerjaan : {$estimasi}"
            . $dokumentasiInfo
            . $driverInfo
            . $feeInfo
            . "\n\nPantau pesanan Anda lebih detail di sini:\n{$linkCek}";
    }

    // ============ HELPERS ============

    protected function buildRingkasan(ChatSession $session): string
    {
        $data = $session->data ?? [];
        $bundleInfo = !empty($data['bundle_purchase_id']) ? "Ya ✅ (fee otomatis dari bundle)" : "Tidak";

        return "Konfirmasi Pesanan 📋\n\n"
             . "Layanan : {$session->jenis_layanan}\n"
             . "Nama : " . ($data['nama'] ?? '-') . "\n"
             . "Alamat Penjemputan : " . ($data['alamat_customer'] ?? '-') . "\n"
             . "Alamat Laundry : " . (!empty($data['alamat_laundry']) ? $data['alamat_laundry'] : '-') . "\n"
             . "Pakai Bundle : {$bundleInfo}\n"
             . "Promo : " . ($data['promo_nama'] ?? '-') . "\n"
             . "Catatan : " . (!empty($data['catatan']) ? $data['catatan'] : '-');
    }

    protected function saveData(ChatSession $session, string $key, $value): void
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