<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // ================= PUBLIC =================

    public function index()
    {
        return view('pesanan');
    }

    public function search(Request $request)
    {
        $token = $request->input('token');

        $orders = Order::where('token', $token)->get();

        return view('pesanan', compact('orders', 'token'));
    }

    // ================= BUAT PESANAN =================

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'alamat_customer' => 'required|string',
            'alamat_laundry' => 'required|string',
            'phone_laundry' => 'nullable|string|max:20',
            'fee' => 'required|integer|min:0',
            'is_sorted' => 'nullable|boolean',
            'note' => 'nullable|string',
            'status' => 'nullable|in:Diproses,Dijemput,Dicuci,Diantar,Selesai',

            // 🔥 dokumentasi = link
            'dokumentasi_pakaian' => 'nullable|url|max:500',
        ]);

        // default value
        $data['status'] = $data['status'] ?? 'Diproses';
        $data['is_sorted'] = $data['is_sorted'] ?? 0;

        // 🔥 LOGIC: kalau tidak pakai pemilahan → hapus dokumentasi
        if (!$data['is_sorted']) {
            $data['dokumentasi_pakaian'] = null;
        }

        // 🔥 GENERATE TOKEN UNIQUE
        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
            $token = 'LND-' . $random;
        } while (Order::where('token', $token)->exists());

        $data['token'] = $token;

        // simpan
        Order::create($data);

        return redirect()->back()->with('success', 'Pesanan berhasil dibuat! Token: ' . $token);
    }

    // ================= ADMIN =================

    public function adminOrders(Request $request)
    {
        $query = Order::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('token', 'like', '%' . $request->search . '%');
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // SORT
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (in_array($sort, ['created_at', 'fee']) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        }

        $orders = $query->get();

        return view('adminOrders', compact('orders'));
    }

    public function adminDetail($id)
    {
        $order = Order::findOrFail($id);
        return view('adminDetailOrder', compact('order'));
    }

    public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $data = $request->validate([
        'nama' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'alamat_customer' => 'required|string',
        'alamat_laundry' => 'required|string',
        'phone_laundry' => 'nullable|string|max:20',
        'status' => 'required|in:Diproses,Dijemput,Dicuci,Diantar,Selesai',
        'fee' => 'required|numeric',
        'note' => 'nullable|string',
        'dokumentasi_pakaian' => 'nullable|string', // varchar di DB
        'is_sorted' => 'nullable', // dari select
    ]);

    // Konversi select ke integer
    $data['is_sorted'] = (int) $request->input('is_sorted', 0);

    // update ke DB
    $order->update($data);

    return redirect()->route('admin.orders')
        ->with('success', 'Pesanan berhasil diupdate');
}

    public function adminDashboard()
    {
        $totalPesanan = Order::count();

        $totalPemasukan = Order::where('status', 'Selesai')->sum('fee');

        return view('adminindex', compact('totalPesanan', 'totalPemasukan'));
    }
}