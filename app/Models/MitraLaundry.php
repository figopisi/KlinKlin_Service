<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraLaundry extends Model
{
    use HasFactory;

    protected $table = 'mitra_laundries';

    protected $fillable = [
        'nama_laundry',
        'phone',
        'alamat',
        'persentase_bisnis',
        'status',
        'catatan',
    ];

    protected $casts = [
        'persentase_bisnis' => 'decimal:2',
    ];

    /**
     * Semua pesanan yang pernah masuk ke mitra laundry ini.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'mitra_laundry_id');
    }

    /**
     * Scope: hanya mitra yang berstatus aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Hitung bagian bisnis (fee 10%) dari sebuah nominal fee_laundry.
     */
    public function hitungBagianBisnis(float $feeLaundry): float
    {
        return round($feeLaundry * ((float) $this->persentase_bisnis / 100), 2);
    }

    /**
     * Hitung bagian mitra laundry (sisa setelah dipotong bagian bisnis).
     */
    public function hitungBagianLaundry(float $feeLaundry): float
    {
        return round($feeLaundry - $this->hitungBagianBisnis($feeLaundry), 2);
    }
}