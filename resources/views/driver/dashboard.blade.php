<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }

        .navbar .brand {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
        }

        .navbar .driver-name {
            font-size: 14px;
            color: #555;
        }

        .navbar .btn-logout {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            font-family: inherit;
        }

        /* WRAPPER */
        .wrapper {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 20px;
        }

        /* SECTION */
        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title .badge {
            background: #2563eb;
            color: white;
            font-size: 12px;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .section-title .badge.orange {
            background: #f97316;
        }

        .section-title .badge.green {
            background: #16a34a;
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid #2563eb;
        }

        .card.orange {
            border-left-color: #f97316;
        }

        .card.green {
            border-left-color: #16a34a;
        }

        .order-id {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .customer-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .info-row {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            display: flex;
            gap: 6px;
        }

        .info-row span {
            font-weight: 600;
            color: #334155;
        }

        /* STATUS */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 10px 0;
        }

        .status-Diproses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-Dijemput {
            background: #fef9c3;
            color: #a16207;
        }

        .status-Dicuci {
            background: #ede9fe;
            color: #6d28d9;
        }

        .status-Diantar {
            background: #dcfce7;
            color: #15803d;
        }

        .status-Selesai {
            background: #f1f5f9;
            color: #475569;
        }

        /* BUTTON */
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            margin-top: 8px;
        }

        .btn-ambil {
            background: #2563eb;
            color: white;
        }

        .btn-ambil:hover {
            background: #1d4ed8;
        }

        .btn-update {
            background: #f97316;
            color: white;
        }

        .btn-update:hover {
            background: #ea6c00;
        }

        .btn-lepas {
            background: #ef4444;
            color: white;
        }

        .btn-lepas:hover {
            background: #dc2626;
        }

        /* ALERT */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #15803d;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* EMPTY */
        .empty {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            padding: 30px;
            background: white;
            border-radius: 14px;
            margin-bottom: 40px;
        }

        @media(max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar">

    <div class="brand">
        🚗 Driver Panel
    </div>

    <div class="driver-name">
        Halo,
        <strong>{{ session('driver_name') }}</strong>
    </div>

    <form action="{{ route('driver.logout') }}" method="POST">
        @csrf

        <button type="submit" class="btn-logout">
            Logout
        </button>
    </form>

</nav>

<div class="wrapper">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- ========================= --}}
    {{-- PESANAN AKTIF --}}
    {{-- ========================= --}}
    <div class="section-title">
        Pesanan Aktif
        <span class="badge orange">
            {{ $pesananAktif->count() }}
        </span>
    </div>

    @if($pesananAktif->isEmpty())

        <div class="empty">
            Tidak ada pesanan aktif saat ini.
        </div>

    @else

        <div class="grid">

            @foreach($pesananAktif as $order)

            <div class="card orange">

                <div class="order-id">
                    {{ $order->token }}
                </div>

                <div class="customer-name">
                    {{ $order->nama }}
                </div>

                <div class="info-row">
                    📍 Alamat:
                    <span>{{ $order->alamat_customer }}</span>
                </div>

                <div class="info-row">
                    📞 Telp:
                    <span>{{ $order->phone }}</span>
                </div>

                <div class="info-row">
                    🧺 Layanan:
                    <span>{{ $order->jenis_layanan }}</span>
                </div>

                <div>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status }}
                    </span>
                </div>

                @php
                    $labelBtn = match($order->status) {
                        'Diproses' => '🚗 Jemput Cucian Customer',
                        'Dijemput' => '✅ Sudah di Laundry',
                        'Dicuci'   => '🚚 Antar ke Customer',
                        'Diantar'  => '✅ Selesai Diantar',
                        default    => null,
                    };
                @endphp

                @if($labelBtn)

                <form action="{{ route('driver.updateStatus', $order->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-update">
                        {{ $labelBtn }}
                    </button>
                </form>

                @endif

                @if($order->status !== 'Selesai')

                <form action="{{ route('driver.lepas', $order->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-lepas">
                        ❌ Lepaskan Pesanan
                    </button>
                </form>

                @endif

            </div>

            @endforeach

        </div>

    @endif

    {{-- ========================= --}}
    {{-- PESANAN TERSEDIA --}}
    {{-- ========================= --}}
    <div class="section-title">
        Pesanan Tersedia
        <span class="badge">
            {{ $tersedia->count() }}
        </span>
    </div>

    @if($tersedia->isEmpty())

        <div class="empty">
            Tidak ada pesanan yang tersedia saat ini.
        </div>

    @else

        <div class="grid">

            @foreach($tersedia as $order)

            <div class="card">

                <div class="order-id">
                    {{ $order->token }}
                </div>

                <div class="customer-name">
                    {{ $order->nama }}
                </div>

                <div class="info-row">
                    📍 Alamat:
                    <span>{{ $order->alamat_customer }}</span>
                </div>

                <div class="info-row">
                    📞 Telp:
                    <span>{{ $order->phone }}</span>
                </div>

                <div class="info-row">
                    🧺 Layanan:
                    <span>{{ $order->jenis_layanan }}</span>
                </div>

                <div class="info-row">
                    📦 Estimasi:
                    <span>{{ $order->estimasi_jumlah_laundry }}</span>
                </div>

                <div>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status }}
                    </span>
                </div>

                <form action="{{ route('driver.ambil', $order->id) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-ambil">
                        {{ $order->status === 'Diproses'
                            ? '🚗 Ambil Pesanan'
                            : '📦 Ambil dari Laundry'
                        }}
                    </button>
                </form>

            </div>

            @endforeach

        </div>

    @endif

     {{-- ========================= --}}
    {{-- RIWAYAT SELESAI --}}
    {{-- ========================= --}}
    <div class="section-title">
        Riwayat Pesanan Selesai
        <span class="badge green">
            {{ $pesananSelesai->count() }}
        </span>
    </div>

    @if($pesananSelesai->isEmpty())

        <div class="empty">
            Belum ada riwayat pesanan selesai.
        </div>

    @else

        <div class="grid">

            @foreach($pesananSelesai as $order)

            <div class="card green">

                <div class="order-id">
                    {{ $order->token }}
                </div>

                <div class="customer-name">
                    {{ $order->nama }}
                </div>

                <div class="info-row">
                    📍 Alamat:
                    <span>{{ $order->alamat_customer }}</span>
                </div>

                <div class="info-row">
                    📞 Telp:
                    <span>{{ $order->phone }}</span>
                </div>

                <div class="info-row">
                    🧺 Layanan:
                    <span>{{ $order->jenis_layanan }}</span>
                </div>

                <div>
                    <span class="status-badge status-Selesai">
                        Selesai
                    </span>
                </div>

            </div>

            @endforeach

        </div>

    @endif
</div>

</body>
</html>