<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Promo & Diskon — KlinKlin</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
<style>
    /* ================= PROMO PAGE — pakai token & pola landingpage.css ================= */

    .promo-hero {
        position: relative;
        z-index: 5;
        max-width: 1380px;
        margin: 0 auto;
        padding: 50px 60px 10px;
        text-align: center;
    }
    .promo-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--navy);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .3px;
        padding: 10px 22px;
        border-radius: 30px;
        margin-bottom: 26px;
    }
    .promo-eyebrow .blip {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--blue-cta);
        animation: blip 1.6s ease-in-out infinite;
    }
    @keyframes blip {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(1.5); }
    }
    .promo-hero h1 {
        font-size: clamp(32px, 4.4vw, 54px);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: .8px;
        color: var(--navy);
        white-space: normal;
        text-indent: 0;
        max-width: 780px;
        margin: 0 auto;
    }
    .promo-hero h1 .hi {
        position: relative;
        color: var(--blue);
        display: inline-block;
    }
    .promo-hero h1 .hi::after {
        content: "";
        position: absolute; left: 0; right: 0; bottom: 8px;
        height: 14px; z-index: -1; border-radius: 6px;
        background: var(--blue-cta);
        opacity: .35;
    }
    .promo-hero p {
        margin: 20px auto 0;
        max-width: 540px;
        font-size: 18px;
        line-height: 1.6;
        font-weight: 400;
        color: rgba(14,23,38,.62);
    }

    /* ---- STAT PILL STRIP ---- */
    .promo-stats {
        position: relative; z-index: 5;
        display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;
        max-width: 1380px; margin: 40px auto 0; padding: 0 60px;
    }
    .promo-stat {
        background: rgba(255,255,255,.55);
        backdrop-filter: blur(3px);
        border-radius: 24px;
        padding: 18px 32px;
        text-align: center;
        min-width: 150px;
        box-shadow: 0 10px 26px rgba(72,115,180,.14);
    }
    .promo-stat .n {
        font-size: 30px; font-weight: 800; color: var(--navy); line-height: 1;
    }
    .promo-stat .l {
        margin-top: 6px; font-size: 12.5px; font-weight: 600;
        color: rgba(14,23,38,.55); text-transform: uppercase; letter-spacing: .05em;
    }

    /* ---- PROMO GRID ---- */
    .promo-section {
        position: relative; z-index: 5;
        max-width: 1380px; margin: 0 auto;
        padding: 60px 60px 110px;
    }
    .promo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 34px;
    }

    /* ---- PROMO CARD — turunan visual dari .price-card, gradient ala .order-card ---- */
    .promo-card {
        position: relative;
        display: flex;
        flex-direction: column;
        border-radius: 35px;
        background: #fff;
        box-shadow: 0 10px 34px rgba(31,50,78,.10);
        overflow: hidden;
        transition: transform .35s var(--ease), box-shadow .35s var(--ease);
    }
    .promo-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 22px 48px rgba(31,50,78,.18);
    }

    .promo-card .pc-head {
        position: relative;
        overflow: hidden;
        padding: 30px 34px 46px;
        background: linear-gradient(90deg, #1F324E 0%, #4873B4 100%);
        color: #fff;
    }
    .promo-card .pc-head .glow {
        position: absolute; right: -80px; bottom: -100px;
        width: 260px; height: 260px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.22) 0%, rgba(255,255,255,0) 70%);
        animation: order-glow 9s ease-in-out infinite;
        pointer-events: none;
    }
    .promo-card .off-badge {
        position: relative; z-index: 1;
        display: inline-block;
        background: var(--blue-cta);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: .3px;
        padding: 7px 16px;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(102,156,242,.4);
    }
    .promo-card .pc-name {
        position: relative; z-index: 1;
        font-size: 22px;
        font-weight: 700;
        margin-top: 16px;
        line-height: 1.3;
    }

    /* price plate mengambang, konsisten pola price-tag sebelumnya tapi warna baru */
    .promo-card .price-plate {
        background: #fff;
        margin: -26px 22px 0;
        border-radius: 20px;
        padding: 18px 22px;
        box-shadow: 0 14px 28px rgba(31,50,78,.16);
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        position: relative;
        z-index: 2;
    }
    .promo-card .price-plate .old {
        font-size: 13.5px;
        font-weight: 600;
        color: #A3AAB5;
        text-decoration: line-through;
        display: block;
    }
    .promo-card .price-plate .new {
        font-size: 27px;
        font-weight: 800;
        color: var(--navy);
        display: block;
        margin-top: 2px;
        letter-spacing: .3px;
    }
    .promo-card .save-tag {
        font-size: 11.5px;
        font-weight: 700;
        color: #1F8A4C;
        background: #E4F7EB;
        padding: 6px 12px;
        border-radius: 10px;
        white-space: nowrap;
    }

    .promo-card .pc-body {
        padding: 24px 34px 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .promo-card .pc-desc {
        font-size: 14.5px;
        line-height: 1.65;
        color: rgba(14,23,38,.62);
        margin-bottom: 20px;
        flex: 1;
    }
    .promo-card .pc-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .promo-meta-chip {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--blue);
        background: #EEF3FC;
        padding: 7px 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .promo-meta-chip.urgent {
        color: #B4451C;
        background: #FFF1E8;
    }

    /* ---- EMPTY STATE ---- */
    .promo-empty {
        grid-column: 1 / -1;
        text-align: center;
        background: rgba(255,255,255,.6);
        backdrop-filter: blur(3px);
        border-radius: 30px;
        padding: 80px 24px;
        box-shadow: 0 12px 30px rgba(31,50,78,.08);
    }
    .promo-empty .ico { font-size: 44px; margin-bottom: 16px; }
    .promo-empty h3 {
        font-size: 22px; font-weight: 800; color: var(--navy); margin-bottom: 8px;
    }
    .promo-empty p {
        font-size: 15px; color: rgba(14,23,38,.6);
    }

    /* ---- Sisa blob dekoratif untuk halaman ini ---- */
    .promo-blob-1 { width: 220px; height: 220px; top: 6%; left: 2%; }
    .promo-blob-2 { width: 260px; height: 260px; bottom: 6%; right: 0%; }

    @media (max-width: 900px) {
        .promo-hero, .promo-stats, .promo-section { padding-left: 24px; padding-right: 24px; }
    }
    @media (max-width: 480px) {
        .promo-card .pc-name { font-size: 19px; }
        .promo-card .price-plate .new { font-size: 23px; }
    }
</style>
</head>
<body>

@include('navbar')

<span class="blob promo-blob-1" aria-hidden="true"></span>
<span class="blob promo-blob-2" aria-hidden="true"></span>

<section class="promo-hero">
    <span class="promo-eyebrow reveal" data-reveal="down"><span class="blip"></span>Penawaran Terbatas</span>
    <h1 class="reveal" data-reveal="up" style="--delay:.1s">
        Promo &amp; <span class="hi">Diskon</span> Spesial<br>Buat Cucianmu
    </h1>
    <p class="reveal" data-reveal="up" style="--delay:.2s">
        Hemat lebih banyak untuk setiap laundry bersama KlinKlin. Cek promo yang masih berlaku sebelum kehabisan kuota.
    </p>
</section>

@if($promotions->count() > 0)
<div class="promo-stats reveal" data-reveal="up" style="--delay:.3s">
    <div class="promo-stat">
        <div class="n">{{ $promotions->count() }}</div>
        <div class="l">Promo Aktif</div>
    </div>
    <div class="promo-stat">
        <div class="n">{{ $promotions->max('persen_diskon') ?? 0 }}%</div>
        <div class="l">Diskon Tertinggi</div>
    </div>
</div>
@endif

<section class="promo-section">
    <div class="promo-grid">
        @forelse($promotions as $i => $promo)
            @php
                $hampirHabis = $promo->kuota !== null && ($promo->kuota - $promo->terpakai) <= 5 && ($promo->kuota - $promo->terpakai) > 0;
                $segeraBerakhir = $promo->tanggal_selesai && now()->diffInDays(\Carbon\Carbon::parse($promo->tanggal_selesai), false) <= 3 && now()->lt($promo->tanggal_selesai);
            @endphp
            <div class="promo-card reveal" data-reveal="up" style="--delay:{{ $i * 0.08 }}s">
                <div class="pc-head">
                    <span class="glow" aria-hidden="true"></span>
                    <span class="off-badge">-{{ $promo->persen_diskon }}%</span>
                    <div class="pc-name">{{ $promo->nama_promo }}</div>
                </div>

                <div class="price-plate">
                    <div>
                        <span class="old">Rp{{ number_format($promo->harga_awal, 0, ',', '.') }}</span>
                        <span class="new">Rp{{ number_format($promo->harga_promo, 0, ',', '.') }}</span>
                    </div>
                    <span class="save-tag">Hemat Rp{{ number_format($promo->harga_awal - $promo->harga_promo, 0, ',', '.') }}</span>
                </div>

                <div class="pc-body">
                    @if($promo->deskripsi)
                        <p class="pc-desc">{{ $promo->deskripsi }}</p>
                    @endif
                    <div class="pc-meta">
                        @if($promo->tanggal_selesai)
                            <span class="promo-meta-chip {{ $segeraBerakhir ? 'urgent' : '' }}">
                                ⏳ s/d {{ \Carbon\Carbon::parse($promo->tanggal_selesai)->translatedFormat('d M Y') }}
                            </span>
                        @endif
                        @if($promo->kuota !== null)
                            <span class="promo-meta-chip {{ $hampirHabis ? 'urgent' : '' }}">
                                🎟️ Sisa {{ $promo->kuota - $promo->terpakai }} kuota
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="promo-empty">
                <div class="ico">🏷️</div>
                <h3>Belum Ada Promo Saat Ini</h3>
                <p>Pantau terus halaman ini, promo baru akan segera hadir.</p>
            </div>
        @endforelse
    </div>
</section>

@include('footer')

<script src="{{ asset('asset/js/landing.js') }}"></script>

</body>
</html>