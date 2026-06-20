<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek Pesanan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
</head>

<style>

    /* ================= ORDER CARD ================= */
.order-card {
    position: relative;
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 12px 32px rgba(0,0,0,.08);
    margin-bottom: 24px;
    animation: fadeUp 0.4s ease forwards;
    border: 1px solid #f1f5f9;
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,.12);
    transition: all .3s ease;
}

/* Status tag — absolute di pojok kanan atas card */
.status-tag {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-diproses       { background: #353535; color: #ffffff; }
.status-dijemput       { background: #fef9c3; color: #a16207; }
.status-mencari        { background: #ffedd5; color: #c2410c; }
.status-dicuci         { background: #ede9fe; color: #6d28d9; }
.status-diantar        { background: #d1fae5; color: #065f46; }
.status-selesai        { background: #dcfce7; color: #15803d; }

.driver-section { margin-top: 24px; }
.driver-section-title { font-size: 18px; font-weight: 800; color: #111827; margin-bottom: 16px; }
.driver-grid { display: grid; gap: 16px; }
.driver-card {
    background: white; border-radius: 20px; padding: 22px;
    border: 1px solid #e5e7eb; box-shadow: 0 10px 24px rgba(0,0,0,.05);
    position: relative; overflow: hidden;
}
.driver-card.active { border: 2px solid #22c55e; background: linear-gradient(to bottom right,#f0fdf4,#ffffff); }
.driver-card.old { opacity: .92; }
.driver-badge {
    position: absolute; top: 16px; right: 16px;
    padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 800;
}
.driver-badge.active { background: #dcfce7; color: #15803d; }
.driver-badge.old    { background: #f1f5f9; color: #475569; }
.driver-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
.driver-avatar {
    width: 54px; height: 54px; border-radius: 50%; background: #2563eb; color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; font-weight: 800; flex-shrink: 0;
}
.driver-info h4 { margin: 0; font-size: 18px; font-weight: 800; color: #111827; }
.driver-info p  { margin-top: 4px; color: #64748b; font-size: 14px; }
.driver-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 10px; }
.driver-detail { background: #f8fafc; border-radius: 14px; padding: 14px; }
.driver-detail-label { font-size: 12px; text-transform: uppercase; font-weight: 700; color: #94a3b8; margin-bottom: 6px; }
.driver-detail-value { font-size: 15px; font-weight: 700; color: #0f172a; }
.driver-status {
    display: inline-block; margin-top: 8px; padding: 6px 12px;
    border-radius: 999px; background: #dbeafe; color: #1d4ed8;
    font-size: 13px; font-weight: 800;
}

/* TOKEN BESAR */
.order-token {
    font-size: 28px;
    font-weight: 800;
    color: #05558E;
    letter-spacing: 2px;
    margin-bottom: 4px;
}
.order-customer-name {
    font-size: 15px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 12px;
}

/* FOTO COLLAPSIBLE */
.foto-section {
    margin-top: 24px;
    background: white;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 24px rgba(0,0,0,.05);
    overflow: hidden;
}

.foto-toggle {
    width: 100%;
    background: none;
    border: none;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-family: inherit;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
    text-align: left;
}

.foto-toggle-icon {
    font-size: 14px;
    color: #94a3b8;
    transition: transform .3s ease;
}

.foto-toggle.open .foto-toggle-icon {
    transform: rotate(180deg);
}

.foto-body {
    max-height: 80px;
    overflow: hidden;
    transition: max-height .4s ease;
    position: relative;
}

.foto-body.open {
    max-height: 2000px;
}

.foto-body-inner {
    padding: 0 22px 22px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

/* Gradient overlay saat tertutup */
.foto-body:not(.open)::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: linear-gradient(to bottom, transparent, white);
    pointer-events: none;
}

.foto-item {}

.foto-item-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 8px;
}

.foto-item img {
    width: 100%;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    object-fit: cover;
    aspect-ratio: 4/3;
    cursor: pointer;
    transition: transform .2s;
}

.foto-item img:hover { transform: scale(1.02); }

/* LIGHTBOX */
.lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
.lightbox.active { display: flex; }
.lightbox img {
    max-width: 90vw;
    max-height: 90vh;
    border-radius: 12px;
    object-fit: contain;
}
.lightbox-close {
    position: absolute;
    top: 20px;
    right: 24px;
    color: white;
    font-size: 32px;
    cursor: pointer;
    font-weight: 700;
    line-height: 1;
}

@media(max-width: 768px) {
    .driver-detail-grid { grid-template-columns: 1fr; }
    .foto-body-inner { grid-template-columns: 1fr; }
}
</style>

<body>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="tutupLightbox()">
    <span class="lightbox-close">✕</span>
    <img id="lightboxImg" src="" alt="">
</div>

<!-- Floating decor -->
<div class="circle-main"></div>
<div class="circle-shadow"></div>

<div class="container">

    <div class="back-btn-container">
        <a href="{{ route('dashboard') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>Cek Pesanan</h1>

    <form class="search-box" action="{{ route('pesanan.search') }}" method="GET">
        <input type="text" name="token" placeholder="Masukkan token pesanan..."
               value="{{ $token ?? '' }}" required>
        <button type="submit">Cari</button>
    </form>

    @if(!isset($orders))
    <div class="info-box">
        <p>
            Masukkan <strong>kode token</strong> yang telah Anda terima setelah melakukan pemesanan melalui
            <strong>WhatsApp</strong> atau <strong>Instagram</strong>.<br>
            Setelah token dimasukkan, detail dan status pesanan akan muncul di bawah.
        </p>
    </div>
    @endif

    @if(isset($orders))
        @if($orders->count() > 0)
            @foreach($orders as $order)

            @php
                switch($order->status) {
                    case 'Dicuci':           $statusClass = 'status-dicuci';   break;
                    case 'Dijemput':         $statusClass = 'status-dijemput'; break;
                    case 'Diproses':         $statusClass = 'status-diproses'; break;
                    case 'Mencari Laundry':  $statusClass = 'status-mencari';  break;
                    case 'Diantar':          $statusClass = 'status-diantar';  break;
                    case 'Selesai':          $statusClass = 'status-selesai';  break;
                    default: $statusClass = ''; break;
                }

                $fotoPengambilan = $order->photos->where('type', 'pengambilan')->first();
                $fotoNota        = $order->photos->where('type', 'nota')->first();
                $fotoPengiriman  = $order->photos->where('type', 'pengiriman')->first();
                $adaFoto         = $fotoPengambilan || $fotoNota || $fotoPengiriman;
            @endphp

            <div class="order-card">

                <div class="status-tag {{ $statusClass }}">{{ $order->status }}</div>

                <!-- KIRI -->
                <div class="order-left">

                    <div class="order-token">{{ $order->token }}</div>
                    <div class="order-customer-name">{{ $order->nama }}</div>

                    <div class="detail-item">
                        <strong>Alamat Customer</strong>
                        <span>{{ $order->alamat_customer }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Phone Customer</strong>
                        <span>{{ $order->phone }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Fee (harga total)</strong>
                        <span>Rp {{ number_format($order->fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Catatan</strong>
                        <span>{{ $order->note ?? '-' }}</span>
                    </div>

                    @if($order->is_sorted)
                    <div class="documentation-section">
                        <div class="detail-item">
                            <strong>Dokumentasi Pakaian</strong>
                            @if($order->dokumentasi_pakaian)
                                <span>
                                    <a href="{{ $order->dokumentasi_pakaian }}" target="_blank" class="doc-link">
                                        Lihat Dokumentasi
                                    </a>
                                </span>
                            @else
                                <span>-</span>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                <!-- KANAN -->
                <div class="order-right">
                    <div class="detail-item">
                        <strong>Alamat Laundry</strong>
                        <span>{{ $order->alamat_laundry }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Phone Laundry</strong>
                        <span>{{ $order->phone_laundry ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Jenis Layanan</strong>
                        <span>{{ $order->jenis_layanan ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Estimasi Jumlah Laundry (kg/pcs)</strong>
                        <span>{{ $order->estimasi_jumlah_laundry ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Pemilahan Pakaian</strong>
                        <span>{{ $order->is_sorted ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Tanggal Penjemputan</strong>
                        <span>
                            {{ $order->tanggal_penjemputan
                                ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->format('d M Y H:i')
                                : '-' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Tanggal Pesanan</strong>
                        <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

            </div>

            {{-- ========================= --}}
            {{-- FOTO BUKTI (COLLAPSIBLE)  --}}
            {{-- ========================= --}}
            @if($adaFoto)
            <div class="foto-section">
                <button class="foto-toggle" id="fotoToggle{{ $order->id }}"
                        onclick="toggleFoto('{{ $order->id }}')">
                    📷 Foto Bukti Pesanan
                    <span class="foto-toggle-icon">▼</span>
                </button>
                <div class="foto-body" id="fotoBody{{ $order->id }}">
                    <div class="foto-body-inner">

                        @if($fotoPengambilan)
                        <div class="foto-item">
                            <div class="foto-item-label">Bukti Pengambilan</div>
                            <img src="{{ $fotoPengambilan->url }}"
                                 alt="Bukti Pengambilan"
                                 onclick="bukaLightbox('{{ $fotoPengambilan->url }}')">
                        </div>
                        @endif

                        @if($fotoNota)
                        <div class="foto-item">
                            <div class="foto-item-label">Bukti Nota Laundry</div>
                            <img src="{{ $fotoNota->url }}"
                                 alt="Bukti Nota"
                                 onclick="bukaLightbox('{{ $fotoNota->url }}')">
                        </div>
                        @endif

                        @if($fotoPengiriman)
                        <div class="foto-item">
                            <div class="foto-item-label">Bukti Pengiriman</div>
                            <img src="{{ $fotoPengiriman->url }}"
                                 alt="Bukti Pengiriman"
                                 onclick="bukaLightbox('{{ $fotoPengiriman->url }}')">
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            {{-- ========================= --}}
            {{-- INFO DRIVER               --}}
            {{-- ========================= --}}
            @if($order->driverLogs->count())
            <div class="driver-section">
                <div class="driver-section-title">Informasi Driver</div>
                <div class="driver-grid">

                    @if($order->currentDriver)
                    <div class="driver-card active">
                        <div class="driver-badge active">DRIVER SAAT INI</div>
                        <div class="driver-header">
                            <div class="driver-avatar">🚗</div>
                            <div class="driver-info">
                                <h4>{{ $order->currentDriver->name }}</h4>
                                <p>Driver yang sedang menangani pesanan Anda</p>
                            </div>
                        </div>
                        <div class="driver-detail-grid">
                            <div class="driver-detail">
                                <div class="driver-detail-label">Nomor Telepon</div>
                                <div class="driver-detail-value">{{ $order->currentDriver->phone ?? '-' }}</div>
                            </div>
                            <div class="driver-detail">
                                <div class="driver-detail-label">Status Pesanan</div>
                                <div class="driver-detail-value">
                                    <span class="driver-status">{{ $order->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @foreach(
                        $order->driverLogs
                            ->where('driver_id', '!=', $order->current_driver_id)
                            ->sortByDesc('taken_at')
                            ->unique('driver_id')
                        as $log
                    )
                    <div class="driver-card old">
                        <div class="driver-badge old">DRIVER SEBELUMNYA</div>
                        <div class="driver-header">
                            <div class="driver-avatar">🚚</div>
                            <div class="driver-info">
                                <h4>{{ $log->driver->name ?? '-' }}</h4>
                                <p>Pernah menangani pesanan ini</p>
                            </div>
                        </div>
                        <div class="driver-detail-grid">
                            <div class="driver-detail">
                                <div class="driver-detail-label">Nomor Telepon</div>
                                <div class="driver-detail-value">{{ $log->driver->phone ?? '-' }}</div>
                            </div>
                            <div class="driver-detail">
                                <div class="driver-detail-label">Status Terakhir</div>
                                <div class="driver-detail-value">
                                    <span class="driver-status">{{ $log->status }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:16px; color:#64748b; font-size:13px;">
                            Terakhir aktif: {{ \Carbon\Carbon::parse($log->taken_at)->translatedFormat('d F Y - H:i') }}
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
            @endif

            @endforeach
        @else
            <div class="not-found">Pesanan tidak ditemukan</div>
        @endif
    @endif

</div>

@include('footer')

<script>
    function toggleFoto(orderId) {
        const body   = document.getElementById('fotoBody'   + orderId);
        const toggle = document.getElementById('fotoToggle' + orderId);
        body.classList.toggle('open');
        toggle.classList.toggle('open');
    }

    function bukaLightbox(url) {
        document.getElementById('lightboxImg').src = url;
        document.getElementById('lightbox').classList.add('active');
    }

    function tutupLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.getElementById('lightboxImg').src = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') tutupLightbox();
    });
</script>

</body>
</html>