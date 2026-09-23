<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundlePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bundle_package_id',
        'nama_paket_snapshot',
        'harga_snapshot',
        'jumlah_trip_snapshot',
        'masa_berlaku_hari_snapshot',
        'bukti_pembayaran_url',
        'status',
        'tanggal_mulai',
        'tanggal_berakhir',
    ];

    protected $casts = [
        'harga_snapshot' => 'decimal:2',
        'jumlah_trip_snapshot' => 'integer',
        'masa_berlaku_hari_snapshot' => 'integer',
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
    ];

    // ---------- Relasi ----------

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function bundlePackage()
    {
        return $this->belongsTo(BundlePackage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'bundle_purchase_id');
    }

    // ---------- Accessor kuota ----------

    public function getKuotaTerpakaiAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getKuotaTersisaAttribute(): int
    {
        return max(0, $this->jumlah_trip_snapshot - $this->kuota_terpakai);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->tanggal_berakhir !== null
            && now()->greaterThan($this->tanggal_berakhir);
    }

    // Bundle benar-benar bisa dipakai: sudah disetujui, kuota masih ada, belum expired
    public function getIsUsableAttribute(): bool
    {
        return $this->status === 'disetujui'
            && $this->kuota_tersisa > 0
            && ! $this->is_expired;
    }

    // ---------- Scopes ----------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Bundle aktif milik seorang customer (dipakai untuk cek "1 bundle aktif" & saat approve)
    public function scopeActiveForCustomer($query, $customerId)
    {
        return $query
            ->where('customer_id', $customerId)
            ->where('status', 'disetujui')
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')
                  ->orWhere('tanggal_berakhir', '>=', now());
            });
    }

    // ---------- Helper approve/tolak ----------

    public function approve(): void
    {
        $mulai = now();

        $this->update([
            'status' => 'disetujui',
            'tanggal_mulai' => $mulai,
            'tanggal_berakhir' => $this->masa_berlaku_hari_snapshot
                ? $mulai->copy()->addDays($this->masa_berlaku_hari_snapshot)
                : null,
        ]);
    }

    public function reject(): void
    {
        $this->update(['status' => 'ditolak']);
    }
}