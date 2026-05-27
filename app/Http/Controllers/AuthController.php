<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Driver;

class AuthController extends Controller
{
    // ==================== ADMIN ====================

    public function showLogin()
    {
        return view('admin.adminlandingpage');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        session([
            'admin_id'    => $admin->id,
            'admin_login' => true
        ]);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ==================== DRIVER ====================

    public function showDriverLogin()
    {
        return view('driver.login'); // resources/views/driver/login.blade.php
    }

    public function driverLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $driver = Driver::where('username', $request->username)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        // Cek apakah akun driver aktif
        if (!$driver->is_active) {
            return back()->with('error', 'Akun driver tidak aktif, hubungi admin');
        }

        session([
            'driver_id'    => $driver->id,
            'driver_name'  => $driver->name,
            'driver_login' => true
        ]);

        $request->session()->regenerate();

        return redirect()->route('driver.dashboard');
    }

    public function driverLogout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('driver.login');
    }
}