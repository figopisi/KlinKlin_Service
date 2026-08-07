<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'unconfirmed');
        $profiles = CustomerProfile::where('status', $status)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.verifikasiProfile', compact('profiles', 'status'));
    }

    public function updateStatus(Request $request, CustomerProfile $profile)
    {
        $request->validate([
            'status' => 'required|in:unconfirmed,mahasiswa,biasa',
        ]);

        $profile->update(['status' => $request->status]);

        return back()->with('success', 'Status profil berhasil diperbarui.');
    }
}