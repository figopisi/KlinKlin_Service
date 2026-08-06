<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';
    protected $primaryKey = 'id';
    public $timestamps = false; // tabel hanya punya created_at, tidak ada updated_at

    protected $fillable = [
        'username',
        'name',
        'phone',
        'email',
        'password',
        'is_active',
        'document_url',
        'document_public_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];

    // Riwayat semua log status yang pernah dikerjakan driver ini
    public function driverLogs()
    {
        return $this->hasMany(OrderDriverLog::class);
    }

    // Pesanan yang sedang aktif ditangani driver ini (current_driver_id)
    public function currentOrders()
    {
        return $this->hasMany(Order::class, 'current_driver_id');
    }

    /**
     * Total pesanan yang sudah diselesaikan driver ini.
     * Dihitung dari log status 'Selesai' yang tercatat atas nama driver,
     * bukan dari current_driver_id (karena current_driver_id akan null
     * lagi kalau driver melepas pesanan, atau bisa berubah driver lain
     * kalau ada alur reassign di masa depan).
     */
    public function totalPesananSelesai()
    {
        return $this->driverLogs()->where('status', 'Selesai')->count();
    }

    /**
     * Total fee dari pesanan yang diselesaikan driver ini.
     * Join ke tabel orders lewat order_id di log, filter log berstatus Selesai,
     * lalu ambil fee dari order terkait (bukan fee di log, karena log tidak
     * menyimpan fee).
     */
    public function totalFeeDihasilkan()
    {
        return $this->driverLogs()
            ->where('status', 'Selesai')
            ->join('orders', 'orders.id', '=', 'order_driver_logs.order_id')
            ->sum('orders.fee');
    }
}