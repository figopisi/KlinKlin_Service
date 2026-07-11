<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek Pesanan</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
</head>

<style>
/* ============================================================
   CEK PESANAN — tambahan CSS, dibangun di atas token landing.css
   (var(--navy), var(--blue), var(--blue-cta), var(--ease))
   ============================================================ */

/* Reset quirk h1 global milik landing.css (clamp/nowrap/indent khusus hero) */
.cek-page .order-title {
    white-space: normal;
    text-indent: 0;
    text-align: center;
    font-size: clamp(38px, 6vw, 64px);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: 1px;
    margin: 0 0 34px;
    text-shadow: 0 8px 20px rgba(31,50,78,.18);
    background: linear-gradient(90deg, var(--navy) 30%, var(--blue) 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}
@media (max-width: 640px) {
    .cek-page .order-title { font-size: clamp(32px, 9vw, 44px); margin-bottom: 26px; }
}

/* ===== Layout halaman ===== */
.cek-page {
    position: relative;
    z-index: 1;
    max-width: 880px;
    margin: 0 auto;
    padding: 50px 24px 100px;
}

/* Dekorasi bubble ambient — pengganti circle-main/circle-shadow lama */
.blob.cek-blob-1 { position: fixed; z-index: -1; top: -60px; right: -60px; width: 280px; height: 280px; }
.blob.cek-blob-2 { position: fixed; z-index: -1; bottom: -80px; left: -80px; width: 220px; height: 220px; }

/* ===== Tombol kembali ===== */
.cek-top { margin-bottom: 22px; }
.cek-back {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; border: 1.5px solid var(--navy); border-radius: 999px;
    padding: 10px 22px; font-size: 14px; font-weight: 700; color: var(--navy);
    text-decoration: none; box-shadow: 0 8px 20px rgba(31,50,78,.10);
    transition: transform .25s var(--ease), box-shadow .25s var(--ease);
}
.cek-back:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(31,50,78,.16); }
.cek-back:active { transform: translateY(0) scale(.97); }

/* ===== Form cari token ===== */
.cek-search { display: flex; flex-wrap: wrap; gap: 12px; margin: 0 0 30px; }
.cek-search input {
    flex: 1; min-width: 220px; font: inherit; font-size: 15px; color: var(--navy);
    background: #fff; border: 1.5px solid transparent; border-radius: 16px;
    padding: 14px 18px; box-shadow: 0 8px 20px rgba(31,50,78,.08);
    transition: border-color .25s var(--ease), box-shadow .25s var(--ease);
}
.cek-search input::placeholder { color: #9AA8BD; }
.cek-search input:focus {
    outline: none; border-color: var(--blue-cta);
    box-shadow: 0 0 0 4px rgba(102,156,242,.25);
}
.cek-search button {
    font: inherit; font-size: 15px; font-weight: 700; color: #fff; cursor: pointer;
    background: linear-gradient(263deg, #669CF2 0%, #3B5A8C 100%);
    border: none; border-radius: 16px; padding: 14px 30px;
    box-shadow: 0 10px 24px rgba(102,156,242,.35);
    transition: transform .25s var(--ease), box-shadow .25s var(--ease);
}
.cek-search button:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(102,156,242,.45); }
.cek-search button:active { transform: translateY(0) scale(.97); }

/* ===== Info box (sebelum pencarian) ===== */
.cek-info {
    background: rgba(255,255,255,.55);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    border-radius: 26px; padding: 26px 30px;
    box-shadow: 0 14px 34px rgba(31,50,78,.10);
}
.cek-info p { font-size: 15px; line-height: 1.7; color: rgba(14,23,38,.72); }
.cek-info strong { color: var(--navy); }

/* ===== Pesanan tidak ditemukan ===== */
.cek-empty {
    text-align: center; padding: 50px 20px; background: #fff; border-radius: 26px;
    box-shadow: 0 14px 34px rgba(31,50,78,.08); color: rgba(14,23,38,.55);
    font-weight: 600; font-size: 15px;
}

/* ================= KARTU PESANAN ================= */
.pesanan-card {
    position: relative;
    background: #fff;
    border-radius: 28px;
    padding: 30px;
    box-shadow: 0 14px 34px rgba(31,50,78,.10);
    margin-bottom: 24px;
    border: 1px solid rgba(72,115,180,.10);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px 36px;
    transition: transform .3s var(--ease), box-shadow .3s var(--ease);
}
.pesanan-card:hover { transform: translateY(-4px); box-shadow: 0 22px 46px rgba(31,50,78,.16); }

.order-left, .order-right { display: flex; flex-direction: column; gap: 14px; min-width: 0; }

/* Status tag — WARNA TIDAK DIUBAH, hanya posisi/font menyesuaikan kartu baru */
.status-tag {
    position: absolute;
    top: 24px;
    right: 24px;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-diproses { background: #353535; color: #ffffff; }
.status-dijemput { background: #fef9c3; color: #a16207; }
.status-mencari  { background: #ffedd5; color: #c2410c; }
.status-dicuci   { background: #ede9fe; color: #6d28d9; }
.status-diantar  { background: #d1fae5; color: #065f46; }
.status-selesai  { background: #dcfce7; color: #15803d; }

.pesanan-head { grid-column: 1 / -1; margin-bottom: 6px; }
.order-token { font-size: 26px; font-weight: 800; color: var(--navy); letter-spacing: 2px; margin-bottom: 4px; }
.order-customer-name { font-size: 18px; font-weight: 700; color: var(--navy); margin-bottom: 0; }

.detail-item { background: rgba(72,115,180,.06); border-radius: 16px; padding: 14px 16px; }
.detail-item strong {
    display: block; font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .4px; color: rgba(31,50,78,.5); margin-bottom: 6px;
}
.detail-item span {
    display: block; font-size: 14.5px; font-weight: 600; color: var(--navy);
    line-height: 1.5; word-break: break-word;
}

.documentation-section { margin-top: 4px; }
.doc-link {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--blue-cta); color: #fff; font-weight: 700; font-size: 13px;
    padding: 8px 16px; border-radius: 999px; text-decoration: none;
    transition: transform .2s var(--ease), box-shadow .2s var(--ease);
}
.doc-link:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(102,156,242,.4); }

/* ================= FOTO BUKTI (COLLAPSIBLE) ================= */
.foto-section {
    margin-top: 24px;
    background: #fff;
    border-radius: 26px;
    border: 1px solid rgba(72,115,180,.10);
    box-shadow: 0 14px 34px rgba(31,50,78,.08);
    overflow: hidden;
}
.foto-toggle {
    width: 100%; background: none; border: none; padding: 18px 22px;
    display: flex; align-items: center; justify-content: space-between; cursor: pointer;
    font-family: inherit; font-size: 17px; font-weight: 800; color: var(--navy);
    text-align: left;
}
.foto-toggle-icon { font-size: 13px; color: rgba(31,50,78,.4); transition: transform .3s ease; }
.foto-toggle.open .foto-toggle-icon { transform: rotate(180deg); }

.foto-body { max-height: 80px; overflow: hidden; transition: max-height .4s ease; position: relative; }
.foto-body.open { max-height: 2000px; }

/* FIX: gambar bukti dibatasi ukurannya (max 200px per kolom) supaya tidak
   melar mengisi seluruh lebar saat jumlah foto yang muncul cuma sedikit. */
.foto-body-inner {
    padding: 0 22px 22px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 200px));
    justify-content: start;
    gap: 16px;
}
.foto-body:not(.open)::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 60px;
    background: linear-gradient(to bottom, transparent, #fff); pointer-events: none;
}
.foto-item-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: rgba(31,50,78,.45); margin-bottom: 8px; }
.foto-item img {
    width: 100%; max-width: 100%; border-radius: 16px; border: 1px solid rgba(72,115,180,.14);
    object-fit: cover; aspect-ratio: 4/3; cursor: pointer;
    transition: transform .2s var(--ease);
}
.foto-item img:hover { transform: scale(1.02); }

/* ================= LIGHTBOX ================= */
.lightbox { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.88); z-index: 9999; justify-content: center; align-items: center; }
.lightbox.active { display: flex; }
.lightbox img { max-width: 90vw; max-height: 90vh; border-radius: 16px; object-fit: contain; }
.lightbox-close { position: absolute; top: 22px; right: 26px; color: #fff; font-size: 32px; cursor: pointer; font-weight: 700; line-height: 1; }

/* ================= INFO DRIVER ================= */
.driver-section { margin-top: 24px; }
.driver-section-title { font-size: 17px; font-weight: 800; color: var(--navy); margin-bottom: 16px; }

/* FIX: wrapper ini sekarang benar-benar dipakai di HTML, jadi gap antar
   kartu driver (saat ini + sebelumnya) tampil seperti seharusnya. */
.driver-grid { display: grid; gap: 16px; }

.driver-card {
    background: #fff; border-radius: 24px; padding: 22px;
    border: 1px solid rgba(72,115,180,.10); box-shadow: 0 12px 28px rgba(31,50,78,.08);
    position: relative; overflow: hidden;
}
.driver-card.active { border: 2px solid #22c55e; background: #ffffff; }
.driver-card.old { opacity: .92; }
.driver-badge { position: absolute; top: 16px; right: 16px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 800; }
.driver-badge.active { background: #dcfce7; color: #15803d; }
.driver-badge.old { background: rgba(31,50,78,.08); color: rgba(31,50,78,.75); }
.driver-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; padding-right: 110px; }
.driver-avatar {
    width: 52px; height: 52px; border-radius: 50%; background: var(--blue); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 800; flex-shrink: 0;
}
.driver-avatar.old { background: rgba(31,50,78,.35); }
.driver-info h4 { margin: 0; font-size: 17px; font-weight: 800; color: var(--navy); }
.driver-info p { margin-top: 4px; color: rgba(14,23,38,.7); font-size: 13.5px; }

/* FIX: grid ini sekarang dipakai untuk membungkus dua item detail (telepon
   & status) supaya rapi sejajar dan punya gap, bukan numplek satu sama lain. */
.driver-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 10px; }
.driver-detail { background: rgba(72,115,180,.06); border-radius: 14px; padding: 14px; }
.driver-detail-label,
.driver-detail strong { display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; color: rgba(31,50,78,.65); margin-bottom: 6px; }
.driver-detail-value,
.driver-detail span { display: block; font-size: 14.5px; font-weight: 700; color: var(--navy); }
.driver-status {
    display: inline-block; margin-top: 8px; padding: 6px 12px;
    border-radius: 999px; background: rgba(102,156,242,.18); color: var(--navy);
    font-size: 13px; font-weight: 800;
}

/* FIX: jarak yang jelas untuk baris "terakhir aktif" di kartu driver lama */
.driver-last-active {
    display: block;
    margin-top: 14px;
    font-size: 12.5px;
    color: rgba(31,50,78,.55);
}

/* ================= RESPONSIF ================= */
@media (max-width: 768px) {
    .cek-page { padding: 34px 18px 70px; }
    .pesanan-card { grid-template-columns: 1fr; padding: 24px; }
    .cek-search { flex-direction: column; }
    .driver-detail-grid { grid-template-columns: 1fr; }
    .driver-header { padding-right: 0; flex-wrap: wrap; }
    /* FIX: foto tetap berupa thumbnail kecil di mobile, bukan 1 kolom penuh
       yang membuat satu foto tampil raksasa saat hanya ada 1 foto. */
    .foto-body-inner { grid-template-columns: repeat(auto-fill, minmax(130px, 160px)); }
    .blob.cek-blob-1 { width: 180px; height: 180px; top: -40px; right: -50px; }
    .blob.cek-blob-2 { width: 150px; height: 150px; bottom: -50px; left: -50px; }
}
</style>

<body>
@include('navbar')

<div class="lightbox" id="lightbox" onclick="tutupLightbox()">
    <span class="lightbox-close">✕</span>
    <img id="lightboxImg" src="" alt="">
</div>

<span class="blob cek-blob-1" aria-hidden="true"></span>
<span class="blob cek-blob-2" aria-hidden="true"></span>

<main class="cek-page">

    <h1 class="order-title" data-i18n="order_check_title">
        Cek Pesanan
    </h1>

    <form class="cek-search" action="{{ route('pesanan.search') }}" method="GET">
        <input type="text"
               name="token"
               data-i18n-placeholder="search_placeholder"
               placeholder="Masukkan token pesanan..."
               value="{{ $token ?? '' }}"
               required>

        <button type="submit" data-i18n="search_button">
            Cari
        </button>
    </form>

    @if(!isset($orders))
    <div class="cek-info">
        <p data-i18n="info_text">
            Masukkan kode token yang telah Anda terima setelah melakukan pemesanan melalui WhatsApp atau Instagram.
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

            <div class="pesanan-card">

                <div class="status-tag {{ $statusClass }}"
                     data-status="{{ $order->status }}">
                    {{ $order->status }}
                </div>

                <div class="pesanan-head">
                    <div class="order-token">{{ $order->token }}</div>
                    <div class="order-customer-name">{{ $order->nama }}</div>
                </div>

                <div class="order-left">

                    <div class="detail-item">
                        <strong data-i18n="customer_address">Alamat Customer</strong>
                        <span>{{ $order->alamat_customer }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="customer_phone">Phone Customer</strong>
                        <span>{{ $order->phone }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="total_fee">Fee (harga total)</strong>
                        <span>Rp {{ number_format($order->fee, 0, ',', '.') }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="notes">Catatan</strong>
                        <span>{{ $order->note ?? '-' }}</span>
                    </div>

                    @if($order->is_sorted)
                    <div class="detail-item">
                        <strong data-i18n="clothing_documentation">Dokumentasi Pakaian</strong>

                        @if($order->dokumentasi_pakaian)
                            <a href="{{ $order->dokumentasi_pakaian }}"
                               target="_blank"
                               class="doc-link"
                               data-i18n="view_documentation">
                                Lihat Dokumentasi
                            </a>
                        @else
                            <span>-</span>
                        @endif
                    </div>
                    @endif

                </div>

                <div class="order-right">

                    <div class="detail-item">
                        <strong data-i18n="laundry_address">Alamat Laundry</strong>
                        <span>{{ $order->alamat_laundry }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="laundry_phone">Phone Laundry</strong>
                        <span>{{ $order->phone_laundry ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="service_type">Jenis Layanan</strong>
                        <span>{{ $order->jenis_layanan ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="estimated_laundry">Estimasi Jumlah Laundry</strong>
                        <span>{{ $order->estimasi_jumlah_laundry ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="clothing_sorted">Pemilahan Pakaian</strong>
                        <span data-boolean="{{ $order->is_sorted ? 'true' : 'false' }}">
                            {{ $order->is_sorted ? 'Ya' : 'Tidak' }}
                        </span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="pickup_date">Tanggal Penjemputan</strong>
                        <span>
                            {{ $order->tanggal_penjemputan
                                ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->format('d M Y H:i')
                                : '-' }}
                        </span>
                    </div>

                    <div class="detail-item">
                        <strong data-i18n="order_date">Tanggal Pesanan</strong>
                        <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>

                </div>
            </div>

            @if($adaFoto)
            <div class="foto-section">

                <button class="foto-toggle"
                        id="fotoToggle{{ $order->id }}"
                        onclick="toggleFoto('{{ $order->id }}')">

                    <span data-i18n="order_photos">Foto Bukti Pesanan</span>
                    <span class="foto-toggle-icon">▼</span>
                </button>

                <div class="foto-body" id="fotoBody{{ $order->id }}">
                    <div class="foto-body-inner">

                        @if($fotoPengambilan)
                        <div>
                            <div class="foto-item-label" data-i18n="pickup_proof">Bukti Pengambilan</div>
                            <div class="foto-item">
                                <img src="{{ $fotoPengambilan->url }}"
                                     onclick="bukaLightbox('{{ $fotoPengambilan->url }}')">
                            </div>
                        </div>
                        @endif

                        @if($fotoNota)
                        <div>
                            <div class="foto-item-label" data-i18n="laundry_receipt">Bukti Nota</div>
                            <div class="foto-item">
                                <img src="{{ $fotoNota->url }}"
                                     onclick="bukaLightbox('{{ $fotoNota->url }}')">
                            </div>
                        </div>
                        @endif

                        @if($fotoPengiriman)
                        <div>
                            <div class="foto-item-label" data-i18n="delivery_proof">Bukti Pengiriman</div>
                            <div class="foto-item">
                                <img src="{{ $fotoPengiriman->url }}"
                                     onclick="bukaLightbox('{{ $fotoPengiriman->url }}')">
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            @if($order->driverLogs->count())
            <div class="driver-section">

                <div class="driver-section-title" data-i18n="driver_information">
                    Informasi Driver
                </div>

                <div class="driver-grid">

                    @if($order->currentDriver)
                    <div class="driver-card active">

                        <div class="driver-badge active" data-i18n="current_driver">
                            DRIVER SAAT INI
                        </div>

                        <div class="driver-header">
                            <div class="driver-avatar">
                                {{ strtoupper(substr($order->currentDriver->name ?? '-', 0, 1)) }}
                            </div>
                            <div class="driver-info">
                                <h4>{{ $order->currentDriver->name }}</h4>
                                <p data-i18n="current_driver_desc">
                                    Driver yang sedang menangani pesanan Anda
                                </p>
                            </div>
                        </div>

                        <div class="driver-detail-grid">
                            <div class="driver-detail">
                                <strong data-i18n="phone_number">Nomor Telepon</strong>
                                <span>{{ $order->currentDriver->phone ?? '-' }}</span>
                            </div>

                            <div class="driver-detail">
                                <strong data-i18n="order_status">Status Pesanan</strong>
                                <span data-status="{{ $order->status }}">{{ $order->status }}</span>
                            </div>
                        </div>

                    </div>
                    @endif

                    @foreach($order->driverLogs->sortByDesc('taken_at')->unique('driver_id') as $log)
                    <div class="driver-card old">

                        <div class="driver-badge old" data-i18n="previous_driver">
                            DRIVER SEBELUMNYA
                        </div>

                        <div class="driver-header">
                            <div class="driver-avatar old">
                                {{ strtoupper(substr($log->driver->name ?? '-', 0, 1)) }}
                            </div>
                            <div class="driver-info">
                                <h4>{{ $log->driver->name ?? '-' }}</h4>
                                <p data-i18n="previous_driver_desc">
                                    Pernah menangani pesanan ini
                                </p>
                            </div>
                        </div>

                        <div class="driver-detail-grid">
                            <div class="driver-detail">
                                <strong data-i18n="phone_number">Nomor Telepon</strong>
                                <span>{{ $log->driver->phone ?? '-' }}</span>
                            </div>

                            <div class="driver-detail">
                                <strong data-i18n="last_status">Status Terakhir</strong>
                                <span data-status="{{ $log->status }}">{{ $log->status }}</span>
                            </div>
                        </div>

                        <small class="driver-last-active">
                            <span data-i18n="last_active">Terakhir aktif</span>:
                            {{ \Carbon\Carbon::parse($log->taken_at)->translatedFormat('d F Y - H:i') }}
                        </small>

                    </div>
                    @endforeach

                </div>

            </div>
            @endif

            @endforeach
        @else
            <div class="cek-empty" data-i18n="order_not_found">
                Pesanan tidak ditemukan
            </div>
        @endif
    @endif

</main>

@include('footer')

<script src="{{ asset('asset/js/landing.js') }}"></script>
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