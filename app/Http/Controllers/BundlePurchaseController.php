<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BundlePackage;
use App\Models\BundlePurchase;
use App\Models\ChatSession;
use App\Models\CustomerProfile;
use App\Services\WablasService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BundlePurchaseController extends Controller
{
    // ================= PUBLIC (jalur web, di luar bot — opsional) =================

    public function index()
    {
        $packages = BundlePackage::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('harga')
            ->get();

        return view('bundle.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'phone'             => 'required|string|max:20',
            'nama'              => 'required|string|max:100',
            'bundle_package_id' => 'required|exists:bundle_packages,id',
        ]);

        $customer = CustomerProfile::firstOrCreate(
            ['phone' => $data['phone']],
            ['nama' => $data['nama'], 'status' => 'unconfirmed']
        );

        $package = BundlePackage::where('is_active', true)->findOrFail($data['bundle_package_id']);

        BundlePurchase::create([
            'customer_id'                => $customer->id,
            'bundle_package_id'          => $package->id,
            'nama_paket_snapshot'        => $package->nama_paket,
            'harga_snapshot'             => $package->harga,
            'jumlah_trip_snapshot'       => $package->jumlah_trip,
            'masa_berlaku_hari_snapshot' => $package->masa_berlaku_hari,
            'status'                     => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan bundle berhasil dibuat, menunggu konfirmasi admin.');
    }

    public function checkActive(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $customer = CustomerProfile::where('phone', $data['phone'])->first();

        if (!$customer) {
            return response()->json(['active_bundle' => null]);
        }

        $activeBundle = BundlePurchase::activeForCustomer($customer->id)
            ->latest('tanggal_mulai')
            ->get()
            ->first(fn($b) => $b->is_usable);

        return response()->json(['active_bundle' => $activeBundle]);
    }

    // ================= ADMIN =================

    // Admin: 1 halaman berisi pending approval, bundle aktif, dan cek per customer
public function adminIndex(Request $request)
{
    $pending = BundlePurchase::with(['customer', 'bundlePackage'])
        ->where('status', 'pending')
        ->latest()
        ->get();

    $active = BundlePurchase::with(['customer', 'bundlePackage'])
        ->where('status', 'disetujui')
        ->where(function ($q) {
            $q->whereNull('tanggal_berakhir')->orWhere('tanggal_berakhir', '>=', now());
        })
        ->get()
        ->filter(fn ($b) => $b->is_usable)
        ->sortByDesc('tanggal_mulai')
        ->values();

    $searchPhone = $request->query('phone');
    $searchedCustomer = null;
    $riwayat = collect();

    if ($searchPhone) {
        $searchedCustomer = CustomerProfile::where('phone', $searchPhone)->first();

        if ($searchedCustomer) {
            $riwayat = BundlePurchase::with('bundlePackage')
                ->where('customer_id', $searchedCustomer->id)
                ->latest()
                ->get();
        }
    }

    return view('admin.bundlePurchases', [
        'pending'          => $pending,
        'active'           => $active,
        'searchPhone'      => $searchPhone,
        'searchedCustomer' => $searchedCustomer,
        'riwayat'          => $riwayat,
    ]);
}

    // Admin approve pengajuan bundle -> status jadi aktif, kuota tersedia,
    // dan bot customer yang tadi nonaktif (nunggu approval) diaktifkan lagi.
   public function approve(Request $request, $id)
{
    $purchase = BundlePurchase::with('customer')->findOrFail($id);

    if ($purchase->status !== 'pending') {
        return back()->with('error', 'Bundle ini sudah diproses sebelumnya.');
    }

    $request->validate(['bukti_pembayaran_url' => 'nullable|string|max:500']);

    if ($request->filled('bukti_pembayaran_url')) {
        $purchase->update(['bukti_pembayaran_url' => $request->bukti_pembayaran_url]);
    }

    $purchase->approve(); // pakai method dari model

    $this->reaktivasiBotDanNotifikasi($purchase,
        "Bundle *{$purchase->nama_paket_snapshot}* Anda telah disetujui ✅\n\n"
        . "Kuota : {$purchase->jumlah_trip_snapshot} trip\n"
        . ($purchase->tanggal_berakhir
            ? "Berlaku sampai : {$purchase->tanggal_berakhir->format('d M Y')}\n\n"
            : "Tidak ada masa berlaku (unlimited)\n\n")
        . "Bundle Anda sudah bisa langsung dipakai untuk pemesanan berikutnya 🙏"
    );

    return back()->with('success', 'Bundle berhasil diaktifkan.');
}

public function reject(Request $request, $id)
{
    $purchase = BundlePurchase::with('customer')->findOrFail($id);

    if ($purchase->status !== 'pending') {
        return back()->with('error', 'Bundle ini sudah diproses sebelumnya.');
    }

    $purchase->reject(); // pakai method dari model

    $alasan = $request->input('alasan');
    $alasanText = $alasan ? "\n\nAlasan: {$alasan}" : '';

    $this->reaktivasiBotDanNotifikasi($purchase,
        "Mohon maaf, pengajuan bundle *{$purchase->nama_paket_snapshot}* Anda tidak dapat kami proses ❌"
        . $alasanText
        . "\n\nSilakan hubungi CS kami jika ada pertanyaan, atau ajukan kembali dari menu Bundle 🙏"
    );

    return back()->with('success', 'Bundle ditolak.');
}

    // ================= HELPER =================

    // Aktifkan kembali bot untuk customer ini (kalau session-nya sedang
    // nonaktif menunggu approval bundle), lalu kirim notifikasi + menu utama.
    protected function reaktivasiBotDanNotifikasi(BundlePurchase $purchase, string $pesan): void
    {
        $customer = $purchase->customer;

        if (!$customer || !$customer->phone) {
            \Log::warning('Tidak bisa reaktivasi bot: customer/phone tidak ditemukan', ['bundle_purchase_id' => $purchase->id]);
            return;
        }

        $session = ChatSession::where('phone', $customer->phone)->first();

        if ($session) {
            $session->update([
                'bot_active' => true,
                'step'       => 'menu',
                'data'       => null,
            ]);
        }

        $wablas = app(WablasService::class);
        $wablas->sendText($customer->phone, $pesan);

        $wablas->sendText($customer->phone,
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
}