<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [];

    protected $routeMiddleware = [
        'auth.admin' => \App\Http\Middleware\AdminAuth::class,
    ];
}