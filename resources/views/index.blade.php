<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KlinKlin — Cuci Tanpa Capek, Bersih Tanpa Repot</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
</head>

<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar" data-reveal="down">
    <button class="hamburger" type="button" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
    <a href="#" class="brand">
        <img class="brand-icon" src="{{ asset('image/Logo.png') }}" alt="KlinKlin">
        <img class="brand-word" src="{{ asset('image/logo-text.png') }}" alt="klinklin"
             onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'brand-text',textContent:'klinklin'}))">
    </a>

    <nav class="nav-links">
        <a href="#tentang" data-i18n="nav_about">Tentang Kami</a>
        <a href="#" class="active" data-i18n="nav_home">Beranda</a>
        <a href="{{ route('pesanan') }}" data-i18n="nav_history">Cek Pesanan</a>
    </nav>

    <button class="lang-switch" type="button" aria-label="Ganti bahasa">
        <span class="globe">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#F3F3F3" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M3 12h18M12 3c2.5 2.5 2.5 15 0 18M12 3c-2.5 2.5-2.5 15 0 18"/>
            </svg>
        </span>
        <span class="lang-label">IDN</span>
    </button>
</header>

<!-- ===== HERO ===== -->
<main class="hero">

    <section class="hero-copy">
        <h1 class="reveal" data-reveal="up" style="--delay:.1s" data-i18n-html="hero_title">
            Cuci Tanpa Capek
            Bersih Tanpa 
        </h1>

        <p class="hero-desc reveal" data-reveal="up" style="--delay:.25s" data-i18n="hero_desc">
            Rasakan kemudahan kebersihan bersama KlinKlin. Kami hadir dengan layanan
            laundry pickup &amp; delivery yang disesuaikan dengan kebutuhanmu, memastikan
            pakaianmu kembali bersih, segar, dan terawat sempurna.
        </p>

        <span class="cta-deco" aria-hidden="true"></span>

        <a href="{{ route('buat-pesanan') }}" class="cta reveal" data-reveal="up" style="--delay:.4s">
            <span data-i18n="cta">Pesan Sekarang</span>
            <span class="cta-arrow">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </span>
        </a>

        <div class="social-proof reveal" data-reveal="up" style="--delay:.55s">
            <div class="avatars">
                <span class="avatar"><svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 19c0-3.6 2.9-5.6 6.5-5.6s6.5 2 6.5 5.6"/></svg></span>
                <span class="avatar"><svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 19c0-3.6 2.9-5.6 6.5-5.6s6.5 2 6.5 5.6"/></svg></span>
                <span class="avatar"><svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 19c0-3.6 2.9-5.6 6.5-5.6s6.5 2 6.5 5.6"/></svg></span>
                <span class="avatar-more">+26</span>
            </div>
            <span class="proof-label" data-i18n="proof">Pelanggan Puas</span>
        </div>

        <div class="badges">
            <div class="badge reveal" data-reveal="up" style="--delay:.7s">
                <span class="badge-ic" aria-hidden="true"></span>
                <div class="badge-text">
                    <small data-i18n="b1_small">Didanai oleh</small>
                    <strong>P2MW 2026</strong>
                    <span data-i18n="b1_desc">Program Pembinaan Mahasiswa Wirausaha</span>
                </div>
            </div>
            <div class="badge reveal" data-reveal="up" style="--delay:.8s">
                <span class="badge-ic" aria-hidden="true"></span>
                <div class="badge-text">
                    <small data-i18n="b2_small">Beroperasi di</small>
                    <strong>Bali</strong>
                    <span data-i18n="b2_desc">Mencakup wilayah Denpasar dan Badung</span>
                </div>
            </div>
        </div>
    </section>

    <section class="hero-visual" style="--delay:.3s">
        <span class="blob blob-1"></span>
        <span class="blob blob-2"></span>
        <span class="blob blob-3"></span>
        <span class="visual-shadow"></span>
        <img class="bucket reveal" data-reveal="fade" src="{{ asset('image/hero-bucket.png') }}" alt="Keranjang cucian KlinKlin">
    </section>

</main>

<!-- ===== TENTANG KAMI ===== -->
<section id="tentang" class="about">

    <div class="feature-cards">
        <div class="feature-card reveal" data-reveal="up" style="--delay:.05s">
            <span class="fc-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="13" r="8"/><path d="M12 9v4l2 2"/><path d="M5 3 2 6"/><path d="m22 6-3-3"/><path d="M6.38 18.7 4 21"/><path d="M17.64 18.67 20 21"/></svg>
            </span>
            <h3 data-i18n="f1_title">Tepat Waktu</h3>
            <p data-i18n="f1_desc">Pakaianmu dijemput &amp; diantar tepat sesuai jadwal yang kamu tentukan.</p>
        </div>
        <div class="feature-card reveal" data-reveal="up" style="--delay:.15s">
            <span class="fc-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>
            </span>
            <h3 data-i18n="f2_title">Terpercaya</h3>
            <p data-i18n="f2_desc">Pakaianmu aman di tangan kami tercatat, terpantau, dan kembali dalam kondisi terbaik.</p>
        </div>
        <div class="feature-card reveal" data-reveal="up" style="--delay:.25s">
            <span class="fc-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="#4873B4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19"/><path d="M9.586 5.586A2 2 0 0 0 8.172 5H3a1 1 0 0 0-1 1v5.172a2 2 0 0 0 .586 1.414L8.29 18.29a2.426 2.426 0 0 0 3.42 0l3.58-3.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="6.5" cy="9.5" r=".5" fill="#4873B4"/></svg>
            </span>
            <h3 data-i18n="f3_title">Biaya Terjangkau</h3>
            <p data-i18n="f3_desc">Bersih maksimal, harga minimal laundry berkualitas untuk semua kalangan.</p>
        </div>
    </div>

    <div class="about-main">
        <div class="about-collage reveal" data-reveal="up">
            <span class="collage-bubble" aria-hidden="true"></span>
            <img class="collage collage-1" src="{{ asset('image/driver1.jpeg') }}" alt="Kurir KlinKlin menjemput cucian">
            <img class="collage collage-2" src="{{ asset('image/driver2.jpeg') }}" alt="Layanan KlinKlin">
            <span class="collage-3-bg" aria-hidden="true"></span>
            <img class="collage collage-3" src="{{ asset('image/driver3.jpeg') }}" alt="Serah terima cucian">
            <div class="stat-badge">
                <div class="sb-num"><span>7</span><span class="sb-plus">+</span></div>
                <div class="sb-label" data-i18n-html="about_months">Bulan Penuh <strong>Pencapaian</strong></div>
            </div>
        </div>

        <div class="about-text">
            <h2 class="reveal" data-reveal="up" data-i18n="about_title">Kami Membuat Pakaianmu Bersih &amp; Terawat</h2>
            <p class="reveal" data-reveal="up" style="--delay:.1s" data-i18n="about_desc">Bersama KlinKlin, kami hadir untuk memastikan pakaianmu selalu bersih, segar, dan terawat. Dengan semangat melayani dan teknologi yang terus berkembang, kami berusaha melampaui ekspektasimu setiap saat.</p>
            <div class="about-stats reveal" data-reveal="up" style="--delay:.2s">
                <div class="stat">
                    <div class="stat-num">37<span>+</span></div>
                    <div class="stat-label" data-i18n-html="about_stat1"><strong>Pesanan</strong> Selesai</div>
                </div>
                <span class="stat-divider" aria-hidden="true"></span>
                <div class="stat">
                    <div class="stat-num">3<span>+</span></div>
                    <div class="stat-label" data-i18n-html="about_stat2"><strong>Pesan</strong> Lagi</div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- ===== MENGAPA MEMILIH KLINKLIN ===== -->
<section id="mengapa" class="why">
    <span class="why-bubble wb1" aria-hidden="true"></span>
    <span class="why-bubble wb2" aria-hidden="true"></span>
    <span class="why-bubble wb3" aria-hidden="true"></span>
    <span class="why-bubble wb4" aria-hidden="true"></span>
    <span class="why-bubble wb5" aria-hidden="true"></span>
    <div class="why-grid">

        <!-- Kiri -->
        <div class="why-text">
            <h2 class="reveal" data-reveal="up" data-i18n="why_title">Mengapa Memilih KlinKlin?</h2>
            <p class="why-lead reveal" data-reveal="up" style="--delay:.1s" data-i18n="why_lead">Bersama KlinKlin, kamu bukan sekadar menggunakan jasa laundry kamu mendapatkan mitra kebersihan yang bisa diandalkan. Ini alasan pelanggan mempercayai kami:</p>

            <ul class="why-list reveal" data-reveal="up" style="--delay:.2s">
                <li>
                    <span class="why-chk" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                    <span data-i18n-html="why_i1"><strong>On Demand</strong> : Jemput &amp; antar sesuai jadwalmu</span>
                </li>
                <li>
                    <span class="why-chk" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                    <span data-i18n-html="why_i2"><strong>Pemilahan Pakaian</strong> : Setiap pakaian dipilah sesuai jenis dan bahan</span>
                </li>
                <li>
                    <span class="why-chk" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                    <span data-i18n-html="why_i3"><strong>Pencatatan Detail</strong>: Setiap item tercatat rapi agar tidak ada yang tertukar atau hilang</span>
                </li>
            </ul>

            <div class="why-lower reveal" data-reveal="up" style="--delay:.3s">
                <div class="why-cluster">
                    <img class="why-cluster-rect" src="{{ asset('image/klin-app-2.jpeg') }}" alt="Pelanggan KlinKlin memakai aplikasi">
                    <div class="why-video-ring">
                        <video class="why-video" data-autoplay-scroll muted loop playsinline preload="metadata" poster="{{ asset('image/driver2.jpeg') }}">
                            <source src="{{ asset('image/KLINKLIN.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="why-lower-text">
                    <p class="why-care" data-i18n-html="why_care">Setiap pakaian kami <strong>catat</strong>, <strong>pilah</strong>, dan <strong>rawat</strong>, supaya kamu terima kembali bersih, rapi, dan tanpa khawatir.</p>
                    <div class="why-order">
                        <span data-i18n="why_order">Ayo Order Sekarang</span>
                        <span class="why-order-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#1F324E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></span>
                    </div>
                    <a href="{{ route('buat-pesanan') }}" class="why-cta">
                        <span class="why-cta-circle" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#1F324E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                        <span class="txt" data-i18n="cta">Pesan Sekarang</span>
                    </a>
                </div>
            </div>

            <!-- Sosial (hanya tampil di mobile) -->
            <div class="why-social">
                <div class="why-social-title" data-i18n="why_pesan">PESAN SEKARANG</div>
                <a class="why-social-btn" href="https://wa.me/6282236405141?text=Nama%20lengkap:%0AAlamat%20customer:%0ANomor%20telepon%20yang%20aktif:%0ATanggal%20Penjemputan:%0AJam%20Penjemputan:%0AJenis%20Layanan:%0AEstimasi%20Jumlah%20Laundry:%20(kg/pcs)%0AAlamat%20laundry%20Pilihan:%20(Jika%20tidak%20ada%20alamat%20spesifik,%20kami%20yang%20akan%20menentukan%20laundry)%0AJasa%20pilah:%20Iya/Tidak%0AMetode%20bayar:%20cash%20di%20tempat/transfer%0ACatatan%20Khusus:" target="_blank" rel="noopener">
                    <span class="why-social-ic"><img src="{{ asset('image/wa.png') }}" alt="WhatsApp"></span>
                    <span>Whatsapp</span>
                </a>
                <a class="why-social-btn" href="https://www.instagram.com/klinklin.service" target="_blank" rel="noopener">
                    <span class="why-social-ic"><img src="{{ asset('image/ig.png') }}" alt="Instagram"></span>
                    <span>Instagram</span>
                </a>
            </div>
        </div>

        <!-- Kanan -->
        <div class="why-visual reveal" data-reveal="up" style="--delay:.15s">
            <img class="why-photo" src="{{ asset('image/klin-bag.jpeg') }}" alt="Kurir KlinKlin mengantar cucian">
            <span class="why-rect" aria-hidden="true"></span>
            <img class="why-photo-portrait" src="{{ asset('image/klin-app-1.jpeg') }}" alt="Pelanggan KlinKlin memakai aplikasi">
            <div class="why-stat">
                <div class="why-stat-num">2</div>
                <div class="why-stat-label" data-i18n-html="why_stat"><strong>Layanan</strong><br>anti ribet</div>
            </div>
        </div>

    </div>
    
</section>

<script src="{{ asset('asset/js/landing.js') }}"></script>

</body>

</html>
