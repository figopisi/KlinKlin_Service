<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>KlinKlin Dashboard</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
</head>

<!-- ===== FOOTER ===== -->
<footer class="site-footer reveal" data-reveal="up">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('index') }}" class="f-logo">
                <img class="brand-icon" src="{{ asset('image/Logo.png') }}" alt="KlinKlin">
                <img class="brand-word" src="{{ asset('image/logo-text.png') }}" alt="klinklin">
            </a>
            <p class="f-desc" data-i18n="foot_desc">Layanan laundry pickup &amp; delivery yang bikin hidupmu lebih ringkas. Cuci tanpa capek, bersih tanpa repot — khusus area Bali.</p>
            <div class="f-social">
                <span class="f-social-title" data-i18n="foot_follow">Ikuti Kami</span>
                <div class="f-social-row">
                    <a href="https://instagram.com/klinklin.service" target="_blank" rel="noopener" class="f-social-link">
                        <span class="f-social-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1.1" fill="currentColor" stroke="none"/></svg>
                        </span>
                        <span>klinklin.service</span>
                    </a>
                    <a href="https://tiktok.com/@klinklin.service" target="_blank" rel="noopener" class="f-social-link">
                        <span class="f-social-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 3c.35 2.3 1.78 3.94 4 4.13v2.96c-1.42.04-2.74-.36-4-1.13v6.21a5.6 5.6 0 1 1-5.6-5.6c.3 0 .6.02.9.07v3.05a2.62 2.62 0 1 0 1.8 2.49V3h2.9z"/></svg>
                        </span>
                        <span>klinklin.service</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-col">
            <h4 data-i18n="foot_nav">Navigasi</h4>
            <a href="{{ route('index') }}#" data-i18n="nav_home">Beranda</a>
            <a href="{{ route('index') }}#tentang" data-i18n="nav_about">Tentang Kami</a>
            <a href="{{ route('index') }}#harga" data-i18n="foot_price">Harga</a>
            <a href="{{ route('index') }}#mengapa" data-i18n="foot_why">Mengapa Kami</a>
        </div>

        <div class="footer-col">
            <h4 data-i18n="foot_serv">Layanan</h4>
            <a href="#" data-i18n="foot_s1">Jemput &amp; Antar Cucian</a>
            <a href="#" data-i18n="foot_s2">Pemilahan Pakaian</a>
            <a href="#" data-i18n="foot_s3">Dokumentasi Pakaian</a>
        </div>

        <div class="footer-col footer-contact">
            <h4 data-i18n="foot_contact">Kontak</h4>
            <p class="f-line" data-i18n="foot_area">Denpasar &amp; Badung, Bali</p>
            <a href="https://wa.me/" target="_blank" rel="noopener" class="f-line">+62 ••• ••• •••</a>
            <a href="mailto:halo@klinklin.id" class="f-line">halo@klinklin.id</a>
            <p class="f-line f-funded" data-i18n="foot_funded">Didanai oleh Program P2MW 2026</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p data-i18n="foot_rights">© 2026 KlinKlin. Seluruh hak cipta dilindungi.</p>
        <p class="f-made" data-i18n="foot_made">Dibuat dengan ♥ di Bali</p>
    </div>
</footer>