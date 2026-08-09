<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MitraLaundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // ✅ Pendapatan per mitra, order yang Selesai
        $pendapatan = DB::table('orders')
            ->where('status', 'Selesai')
            ->whereNotNull('mitra_laundry_id')
            ->select(
                'mitra_laundry_id',
                DB::raw('COUNT(*) as total_order'),
                DB::raw('SUM(penghasilan_laundry_mitra) as total_pendapatan')
            )
            ->groupBy('mitra_laundry_id')
            ->get()
            ->keyBy('mitra_laundry_id');

        $mitras->each(function ($mitra) use ($pendapatan) {
            $data = $pendapatan->get($mitra->id);
            $mitra->total_order = $data->total_order ?? 0;
            $mitra->total_pendapatan = $data->total_pendapatan ?? 0;
        });

        return view('admin.mitraManagement', compact('mitras'));
    }

    public function show($id)
    {
        $mitra = MitraLaundry::findOrFail($id);

        $pendapatan = DB::table('orders')
            ->where('status', 'Selesai')
            ->where('mitra_laundry_id', $mitra->id)
            ->select(
                DB::raw('COUNT(*) as total_order'),
                DB::raw('SUM(penghasilan_laundry_mitra) as total_pendapatan')
            )
            ->first();

        $mitra->total_order = $pendapatan->total_order ?? 0;
        $mitra->total_pendapatan = $pendapatan->total_pendapatan ?? 0;

        return view('admin.mitraDetail', compact('mitra'));
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