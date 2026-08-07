<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MitraLaundry;
use Illuminate\Http\Request;

class MitraLaundryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $mitras = MitraLaundry::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_laundry', 'like', "%{$search}%");
            })
            ->orderBy('nama_laundry')
            ->get();

        return view('admin.mitraManagement', compact('mitras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_laundry'      => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'alamat'            => 'required|string',
            'persentase_bisnis' => 'required|numeric|min:0|max:100',
            'catatan'           => 'nullable|string',
        ]);

        MitraLaundry::create($validated);

        return redirect()
            ->route('admin.mitra.index')
            ->with('success', 'Mitra laundry berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mitra = MitraLaundry::findOrFail($id);

        $validated = $request->validate([
            'nama_laundry'      => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'alamat'            => 'required|string',
            'persentase_bisnis' => 'required|numeric|min:0|max:100',
            'catatan'           => 'nullable|string',
        ]);

        $mitra->update($validated);

        return redirect()
            ->route('admin.mitra.index')
            ->with('success', 'Mitra laundry berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $mitra = MitraLaundry::findOrFail($id);

        $mitra->status = $mitra->status === 'aktif' ? 'nonaktif' : 'aktif';
        $mitra->save();

        return redirect()
            ->route('admin.mitra.index')
            ->with('success', 'Status mitra laundry berhasil diubah.');
    }
}