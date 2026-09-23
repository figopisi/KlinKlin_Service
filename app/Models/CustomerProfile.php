<?php

namespace App\Models;

use App\Services\WablasService;
use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = ['phone', 'nama', 'alamat_customer', 'status'];

    protected static function booted()
    {
        static::updated(function (CustomerProfile $profile) {
            // Saat admin ubah status dari 'unconfirmed' ke 'mahasiswa'/'biasa' -> aktifkan bot lagi
            if ($profile->isDirty('status') && $profile->getOriginal('status') === 'unconfirmed'
                && in_array($profile->status, ['mahasiswa', 'biasa'])) {

                $session = ChatSession::where('phone', $profile->phone)->first();
                if ($session) {
                    $session->update(['step' => 'menu', 'bot_active' => true, 'data' => null]);
                }

                app(WablasService::class)->sendText($profile->phone,
                    "Profilmu sudah terkonfirmasi ✅\n\n"
                    . "Kamu sudah bisa gunakan layanan kami. Balas pesan ini untuk membuka menu 🙏"
                );
            }
        });
    }

    // ---------- Relasi Bundle ----------

    public function bundlePurchases()
    {
        return $this->hasMany(BundlePurchase::class, 'customer_id');
    }

    // Bundle yang sedang aktif & masih bisa dipakai (status disetujui, belum expired)
    public function activeBundle()
    {
        return $this->hasOne(BundlePurchase::class, 'customer_id')
            ->where('status', 'disetujui')
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')
                  ->orWhere('tanggal_berakhir', '>=', now());
            })
            ->latest('tanggal_mulai');
    }

    // Pengajuan bundle yang masih menunggu approval admin
    public function pendingBundlePurchase()
    {
        return $this->hasOne(BundlePurchase::class, 'customer_id')
            ->where('status', 'pending')
            ->latest('created_at');
    }
}