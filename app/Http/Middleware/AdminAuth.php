<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_login')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
