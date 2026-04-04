<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cek Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

</head>
<body>

<!-- Floating decor -->
<div class="circle-main"></div>
<div class="circle-shadow"></div>

<div class="container">

    <!-- Tombol Kembali -->
    <div class="back-btn-container">
        <a href="{{ route('dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>
    </div>

    <h1>Cek Pesanan</h1>

    <!-- Form Pencarian -->
    <form class="search-box" action="{{ route('pesanan.search') }}" method="GET">
        <input type="text" name="token" placeholder="Masukkan token pesanan..." value="{{ $token ?? '' }}" required>
        <button type="submit">Cari</button>
    </form>

    <!-- Informasi awal -->
    @if(!isset($orders))
    <div class="info-box">
        <p>
            Masukkan <strong>kode token</strong> yang telah Anda terima setelah melakukan pemesanan melalui 
            <strong>WhatsApp</strong> atau <strong>Instagram</strong>.<br>
            Setelah token dimasukkan, detail dan status pesanan akan muncul di bawah.
        </p>
    </div>
    @endif

    <!-- Hasil -->
    @if(isset($orders))
        @if($orders->count() > 0)
            @foreach($orders as $order)

            @php
                switch($order->status){
                    case 'Dicuci': $statusClass = 'status-dicuci'; break;
                    case 'Dijemput': $statusClass = 'status-dijemput'; break;
                    case 'Diproses': $statusClass = 'status-diproses'; break;
                    case 'Diantar': $statusClass = 'status-diantar'; break;
                    case 'Selesai': $statusClass = 'status-selesai'; break;
                    default: $statusClass = ''; break;
                }
            @endphp

            <div class="order-card">

                <!-- Status -->
                <div class="status-tag {{ $statusClass }}">
                    {{ $order->status }}
                </div>

                <!-- Kiri -->
                <div class="order-left">
                    <div class="detail-item">
                        <strong>Pesanan</strong>
                        <span>#{{ $order->id }} - {{ $order->nama }} ({{ $order->token }})</span>
                    </div>

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
                        <span>Rp {{ number_format($order->fee,0,',','.') }}</span>
                    </div>

                    <div class="detail-item">
                        <strong>Catatan</strong>
                        <span>{{ $order->note ?? '-' }}</span>
                    </div>
                    
                        <!-- Dokumentasi (hanya jika pemilahan aktif) -->
                    @if($order->is_sorted)
                    <div class="documentation-section">
                        <div class="detail-item">
                            <strong>Dokumentasi Pakaian</strong>
                            @if($order->dokumentasi_pakaian)
                                <span>
                                    <a href="{{ $order->dokumentasi_pakaian}}" target="_blank" class="doc-link">
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

                <!-- Kanan -->
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

            @endforeach
        @else
            <div class="not-found">
                Pesanan tidak ditemukan
            </div>
        @endif
    @endif

</div>
{{-- Footer include di luar container --}}
@include('footer')
</body>
</html>