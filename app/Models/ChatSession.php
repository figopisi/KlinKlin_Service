<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = [
        'phone',
        'step',
        'jenis_layanan',
        'data',
        'bot_active',
    ];

    protected $casts = [
        'data' => 'array',
        'bot_active' => 'boolean',
    ];
}