<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPhoto extends Model
{
    protected $fillable = ['order_id', 'type', 'url', 'public_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}