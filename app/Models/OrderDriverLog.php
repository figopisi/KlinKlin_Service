<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDriverLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'driver_id', 'status', 'taken_at'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}