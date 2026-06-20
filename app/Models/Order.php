<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // nama tabel di database
    protected $primaryKey = 'id';
    public $timestamps = true; // created_at otomatis

    protected $fillable = [
        'alamat_customer',
        'alamat_laundry',
        'fee',
        'is_sorted',
        'nama',
        'note',
        'phone',
        'phone_laundry',
        'status',
        'token',
        'dokumentasi_pakaian',
        "tanggal_penjemputan",
        'jenis_layanan',
        'estimasi_jumlah_laundry',
        'current_driver_id',
];

        // Relasi ke log driver
    public function driverLogs()
    {
        return $this->hasMany(OrderDriverLog::class);
    }

    public function currentDriver()
    {
        return $this->belongsTo(Driver::class, 'current_driver_id');
    }

        public function photos()
    {
        return $this->hasMany(OrderPhoto::class);
    }

    public function fotoPengambilan()
    {
        return $this->hasOne(OrderPhoto::class)->where('type', 'pengambilan');
    }

    public function fotoPengiriman()
    {
        return $this->hasOne(OrderPhoto::class)->where('type', 'pengiriman');
    }

    public function fotoNota()
    {
        return $this->hasOne(OrderPhoto::class)->where('type', 'nota');
    }

}