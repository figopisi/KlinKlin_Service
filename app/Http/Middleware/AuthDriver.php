<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthDriver
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('driver_login')) {
            return redirect()->route('driver.login');
        }

        return $next($request);
    }
}