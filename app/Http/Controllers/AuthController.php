<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('adminlandingpage');
    }

    public function login(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ✅ CARI USER
        $admin = Admin::where('username', $request->username)->first();

        // ✅ CEK PASSWORD (HASH)
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        // ✅ SIMPAN SESSION
        session([
            'admin_id' => $admin->id,
            'admin_login' => true
        ]);

        // ✅ REGENERATE SESSION (ANTI SESSION FIXATION)
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
}