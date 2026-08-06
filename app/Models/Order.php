<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\WablasService;

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
        'tanggal_penjemputan',
        'jenis_layanan',
        'estimasi_jumlah_laundry',
        'current_driver_id',
        'zona',
        'tipe_antar_jemput',
        'jarak_km',
        'rincian_fee',
    ];

    protected $casts = [
        'is_sorted'           => 'boolean',
        'fee'                 => 'integer',
        'jarak_km'            => 'decimal:2',
        'tanggal_penjemputan' => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
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

    protected static function booted()
    {
        static::created(function (Order $order) {
            if (is_null($order->current_driver_id)) {
                $groupId = config('services.wablas.driver_group_id');
                $alamatLaundry = ($order->alamat_laundry && $order->alamat_laundry !== '-')
                    ? $order->alamat_laundry : 'Belum ditentukan customer';

                $message = "Hi Driver KlinKlin! 👋\n"
                    . "Ada pesanan baru masuk, nih.\n\n"
                    . "Kode Pesanan : *{$order->token}*\n"
                    . "Nama Customer : {$order->nama}\n"
                    . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
                    . "Alamat Jemput : {$order->alamat_customer}\n"
                    . "Alamat Laundry : {$alamatLaundry}\n\n"
                    . "Yuk langsung diambil sebelum keduluan driver lain 🏃\n"
                    . "https://klinklin.my.id/driver/login";

                app(WablasService::class)->sendGroupText($groupId, $message);
            }
        });

        static::updated(function (Order $order) {
            // Notifikasi: driver baru saja mengambil pesanan
            if ($order->isDirty('current_driver_id') && $order->current_driver_id !== null) {
                $driver = $order->currentDriver;

                if ($driver) {
                    $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";
                    $alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
                        ? $order->alamat_laundry
                        : 'Akan ditentukan oleh driver kami';

                    $message = "Halo {$order->nama} 👋\n\n"
                        . "Kabar baik! Pesanan Anda dengan kode *{$order->token}* sudah diambil oleh driver kami dan segera diproses 🚀\n\n"
                        . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
                        . "Alamat Laundry : {$alamatLaundryText}\n\n"
                        . "🛵 Driver: {$driver->name}\n"
                        . "📞 Kontak Driver: {$driver->phone}\n\n"
                        . "Pantau pesanan Anda di sini:\n{$linkCek}\n\n"
                        . "Terima kasih telah menggunakan layanan kami! 🙏";

                    app(WablasService::class)->sendText($order->phone, $message);
                }
            }

            // Notifikasi: pesanan selesai
            if ($order->isDirty('status')) {
                $statusSelesai = match ($order->tipe_antar_jemput) {
                    'Antar Saja' => 'Selesai',
                    default      => 'Diantar',
                };

                if ($order->status === $statusSelesai) {
                    $driver = $order->currentDriver;
                    $driverInfo = $driver
                        ? "🛵 Driver: {$driver->name}\n📞 Kontak Driver: {$driver->phone}\n\n"
                        : '';

                    $linkCek = "https://klinklin.my.id/pesanan/search?token={$order->token}";
                    $alamatLaundryText = ($order->alamat_laundry && $order->alamat_laundry !== '-')
                        ? $order->alamat_laundry
                        : '-';

                    $message = "Halo {$order->nama} 👋\n\n"
                        . "Pesanan laundry Anda dengan kode *{$order->token}* sudah selesai kami proses ✅\n\n"
                        . "Tipe Layanan : {$order->tipe_antar_jemput}\n"
                        . "Alamat Laundry : {$alamatLaundryText}\n\n"
                        . $driverInfo
                        . "Cek detail pesanan Anda di sini:\n{$linkCek}\n\n"
                        . "Terima kasih telah menggunakan layanan kami! 🙏";

                    app(WablasService::class)->sendText($order->phone, $message);
                }
            }
        });
    }

}