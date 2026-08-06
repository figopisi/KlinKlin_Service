<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverManagementController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloudinary
    ) {}

    // ================= LIST + PENCAPAIAN =================
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $drivers = $query->orderBy('name')->get();

        // Pencapaian dihitung terpisah lewat query agregat supaya efisien
        // (hindari N+1 dari totalPesananSelesai()/totalFeeDihasilkan() per driver).
        $pencapaian = DB::table('order_driver_logs')
            ->join('orders', 'orders.id', '=', 'order_driver_logs.order_id')
            ->where('order_driver_logs.status', 'Selesai')
            ->select(
                'order_driver_logs.driver_id',
                DB::raw('COUNT(*) as total_selesai'),
                DB::raw('SUM(orders.fee) as total_fee')
            )
            ->groupBy('order_driver_logs.driver_id')
            ->get()
            ->keyBy('driver_id');

        $drivers->each(function ($driver) use ($pencapaian) {
            $data = $pencapaian->get($driver->id);
            $driver->total_selesai = $data->total_selesai ?? 0;
            $driver->total_fee = $data->total_fee ?? 0;
        });

        return view('admin.driverManagement', compact('drivers'));
    }

    // ================= TAMBAH DRIVER BARU =================
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:50|unique:drivers,username',
            'name'     => 'required|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'is_active'=> 'nullable|boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $data['is_active'] ?? 1;

        Driver::create($data);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Akun driver "' . $data['name'] . '" berhasil dibuat');
    }

    // ================= AKTIF / NONAKTIFKAN DRIVER =================
    public function toggleActive($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update(['is_active' => !$driver->is_active]);

        $status = $driver->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Driver "' . $driver->name . '" berhasil ' . $status);
    }

    // ================= RESET PASSWORD DRIVER =================
    public function resetPassword(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $driver->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password driver "' . $driver->name . '" berhasil direset');
    }

    // ================= DETAIL DRIVER =================
    public function show($id)
    {
        $driver = Driver::findOrFail($id);

        // Pencapaian driver ini, konsisten dengan cara hitung di index()
        $pencapaian = DB::table('order_driver_logs')
            ->join('orders', 'orders.id', '=', 'order_driver_logs.order_id')
            ->where('order_driver_logs.status', 'Selesai')
            ->where('order_driver_logs.driver_id', $driver->id)
            ->select(
                DB::raw('COUNT(*) as total_selesai'),
                DB::raw('SUM(orders.fee) as total_fee')
            )
            ->first();

        $driver->total_selesai = $pencapaian->total_selesai ?? 0;
        $driver->total_fee = $pencapaian->total_fee ?? 0;

        return view('admin.driverDetail', compact('driver'));
    }

    // ================= UPLOAD DOKUMEN (SURAT PERSETUJUAN) =================
    public function uploadDocument(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'dokumen' => 'required|image|mimes:jpg,jpeg|max:5120',
        ]);

        // Kalau sudah pernah upload sebelumnya, hapus dulu yang lama di
        // Cloudinary supaya tidak menumpuk file tak terpakai.
        if ($driver->document_public_id) {
            $this->cloudinary->delete($driver->document_public_id);
        }

        $result = $this->cloudinary->uploadDokumenDriver($request->file('dokumen'), $driver->id);

        $driver->update([
            'document_url'       => $result['url'],
            'document_public_id' => $result['public_id'],
        ]);

        return back()->with('success', 'Dokumen driver berhasil diupload');
    }

    // ================= HAPUS DOKUMEN =================
    public function deleteDocument($id)
    {
        $driver = Driver::findOrFail($id);

        if (!$driver->document_public_id) {
            return back()->with('error', 'Driver ini belum punya dokumen');
        }

        $this->cloudinary->delete($driver->document_public_id);

        $driver->update([
            'document_url'       => null,
            'document_public_id' => null,
        ]);

        return back()->with('success', 'Dokumen driver berhasil dihapus');
    }
}