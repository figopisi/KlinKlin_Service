<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundlePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'jumlah_trip',
        'masa_berlaku_hari',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'jumlah_trip' => 'integer',
        'masa_berlaku_hari' => 'integer',
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    public function purchases()
    {
        return $this->hasMany(BundlePurchase::class);
    }

    // Harga per trip, dihitung otomatis — tidak disimpan di DB
    public function getHargaPerTripAttribute(): float
    {
        return $this->jumlah_trip > 0
            ? round((float) $this->harga / $this->jumlah_trip)
            : 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}