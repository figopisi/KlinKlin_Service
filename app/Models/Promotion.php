<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'nama_promo',
        'deskripsi',
        'harga_awal',
        'harga_promo',
        'kuota',
        'terpakai',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Cek apakah promo masih berlaku (tanggal + kuota + toggle)
    public function isValid(): bool
    {
        if (!$this->is_active) return false;

        if ($this->tanggal_mulai && now()->lt($this->tanggal_mulai)) return false;
        if ($this->tanggal_selesai && now()->gt($this->tanggal_selesai)) return false;

        if ($this->kuota !== null && $this->terpakai >= $this->kuota) return false;

        return true;
    }

    // Hitung persentase diskon otomatis
    public function getPersenDiskonAttribute(): int
    {
        if ($this->harga_awal <= 0) return 0;
        return (int) round((($this->harga_awal - $this->harga_promo) / $this->harga_awal) * 100);
    }
}