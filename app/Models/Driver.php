<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name', 'username', 'phone', 'email', 'password', 'is_active'
    ];

    protected $hidden = ['password'];
}