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
    ];
}