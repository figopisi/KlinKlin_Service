<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders List</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <style>
        /* Fix posisi status agar tidak absolute */
        table .status-tag {
            position: static !important;
            display: inline-block;
        }

        /* Warna status */
        .status-tag {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
        }

        .status-diproses { background-color: #374151; }
        .status-dijemput { background-color: #3B82F6; }
        .status-dicuci   { background-color: #8B5CF6; }
        .status-diantar  { background-color: #FACC15; color: #053659; }
        .status-selesai  { background-color: #16A34A; }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Daftar Pesanan</h1>
        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Dashboard</a>
    </div>

    <!-- SEARCH + SORT + FILTER -->
    <form method="GET" action="{{ route('admin.orders') }}" 
        style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">

        <!-- SEARCH -->
        <input 
            type="text" 
            name="search" 
            placeholder="Cari token..." 
            value="{{ request('search') }}"
            style="padding:10px; border-radius:10px; border:1px solid #ccc;"
        >

        <!-- SORT -->
        <select name="sort" style="padding:10px; border-radius:10px;">
            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Waktu</option>
            <option value="fee" {{ request('sort') == 'fee' ? 'selected' : '' }}>Fee</option>
        </select>

        <!-- DIRECTION -->
        <select name="direction" style="padding:10px; border-radius:10px;">
            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Terbesar / Terbaru</option>
            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Terkecil / Terlama</option>
        </select>

        <!-- STATUS FILTER -->
        <select name="status" style="padding:10px; border-radius:10px;">
            <option value="">Semua Status</option>
            <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="Dijemput" {{ request('status') == 'Dijemput' ? 'selected' : '' }}>Dijemput</option>
            <option value="Dicuci" {{ request('status') == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
            <option value="Diantar" {{ request('status') == 'Diantar' ? 'selected' : '' }}>Diantar</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="back-btn">Apply</button>
    </form>

    <!-- TABLE -->
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,.08);">

            <thead style="background:#053659; color:white;">
                <tr>
                    <th style="padding:12px;">ID</th>
                    <th style="padding:12px;">Token</th>
                    <th style="padding:12px;">Nama</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Fee</th>
                    <th style="padding:12px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)

                @php
                    $statusClass = match($order->status) {
                        'Diproses' => 'status-diproses',
                        'Dijemput' => 'status-dijemput',
                        'Dicuci'   => 'status-dicuci',
                        'Diantar'  => 'status-diantar',
                        'Selesai'  => 'status-selesai',
                        default => ''
                    };
                @endphp

                <tr style="text-align:center; border-bottom:1px solid #eee;">
                    <td style="padding:10px;">{{ $order->id }}</td>
                    <td style="padding:10px; font-weight:700;">{{ $order->token }}</td>
                    <td style="padding:10px;">{{ $order->nama }}</td>

                    <td style="padding:10px;">
                        <span class="status-tag {{ $statusClass }}">
                            {{ $order->status }}
                        </span>
                    </td>

                    <td style="padding:10px;">
                        Rp {{ number_format($order->fee) }}
                    </td>

                    <td style="padding:10px;">
                        <a href="{{ route('admin.orders.detail', $order->id) }}" class="back-btn">
                            Detail
                        </a>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" style="padding:20px; text-align:center;">
                        Tidak ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</body>
</html>