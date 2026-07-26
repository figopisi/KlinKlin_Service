<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // ======================
    // CUSTOMER - VIEW ONLY
    // ======================
    public function index()
    {
        $promotions = Promotion::where('is_active', true)
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($promo) => $promo->isValid());

        return view('promosi.index', compact('promotions'));
    }

    // ======================
    // ADMIN - CRUD
    // ======================

    // List semua promo (termasuk nonaktif/expired)
    public function adminIndex()
    {
        $promotions = Promotion::orderByDesc('created_at')->get();
        return view('admin.promosi.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promosi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'harga_awal'       => 'required|numeric|min:0',
            'harga_promo'      => 'required|numeric|min:0|lt:harga_awal',
            'kuota'            => 'nullable|integer|min:1',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_active'        => 'nullable|boolean',
        ], [
            'harga_promo.lt' => 'Harga promo harus lebih murah dari harga awal.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Promotion::create($validated);

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promotion $promosi)
    {
        return view('admin.promosi.edit', ['promo' => $promosi]);
    }

    public function update(Request $request, Promotion $promosi)
    {
        $validated = $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'harga_awal'       => 'required|numeric|min:0',
            'harga_promo'      => 'required|numeric|min:0|lt:harga_awal',
            'kuota'            => 'nullable|integer|min:1',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_active'        => 'nullable|boolean',
        ], [
            'harga_promo.lt' => 'Harga promo harus lebih murah dari harga awal.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $promosi->update($validated);

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promosi)
    {
        $promosi->delete();

        return redirect()->route('admin.promosi.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}