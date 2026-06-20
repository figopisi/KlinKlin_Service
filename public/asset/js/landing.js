/* ============================================================
   KlinKlin — Landing Page JS
   - Reveal animasi saat masuk viewport
   - Pengalih bahasa Indonesia <-> English (tombol globe)
   ============================================================ */
(function () {
    'use strict';

    /* ---------- 1. REVEAL ---------- */
    var items = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
        items.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });
        items.forEach(function (el) { io.observe(el); });
    }

    /* ---------- 1b. HEADER STICKY (frosted saat scroll) ---------- */
    var nav = document.querySelector('.navbar');
    if (nav) {
        var onScroll = function () {
            if (window.pageYOffset > 8) { nav.classList.add('scrolled'); }
            else { nav.classList.remove('scrolled'); }
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ---------- 1c. VIDEO AUTOPLAY SAAT MASUK VIEWPORT ---------- */
    var vids = document.querySelectorAll('video[data-autoplay-scroll]');
    if (vids.length && 'IntersectionObserver' in window) {
        var vio = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                var v = e.target;
                if (e.isIntersecting) { var p = v.play(); if (p && p.catch) { p.catch(function () {}); } }
                else { v.pause(); }
            });
        }, { threshold: 0.35 });
        vids.forEach(function (v) { vio.observe(v); });
    }

    /* ---------- 2. PENGALIH BAHASA ---------- */
    var I18N = {
        id: {
            nav_about: 'Tentang Kami',
            nav_home:  'Beranda',
            nav_history: 'Cek Pesanan',
            hero_title: 'Cuci Tanpa <strong>Capek</strong><br>Bersih Tanpa <strong>Repot</strong>',
            hero_desc: 'Rasakan kemudahan kebersihan bersama KlinKlin. Kami hadir dengan layanan laundry pickup & delivery yang disesuaikan dengan kebutuhanmu, memastikan pakaianmu kembali bersih, segar, dan terawat sempurna.',
            cta: 'Pesan Sekarang',
            proof: 'Pelanggan Puas',
            b1_small: 'Didanai oleh',
            b1_desc: 'Program Pembinaan Mahasiswa Wirausaha',
            b2_small: 'Beroperasi di',
            b2_desc: 'Mencakup wilayah Denpasar dan Badung',
            f1_title: 'Tepat Waktu',
            f1_desc: 'Pakaianmu dijemput & diantar tepat sesuai jadwal yang kamu tentukan.',
            f2_title: 'Terpercaya',
            f2_desc: 'Pakaianmu aman di tangan kami tercatat, terpantau, dan kembali dalam kondisi terbaik.',
            f3_title: 'Biaya Terjangkau',
            f3_desc: 'Bersih maksimal, harga minimal laundry berkualitas untuk semua kalangan.',
            about_title: 'Kami Membuat Pakaianmu Bersih & Terawat',
            about_desc: 'Bersama KlinKlin, kami hadir untuk memastikan pakaianmu selalu bersih, segar, dan terawat. Dengan semangat melayani dan teknologi yang terus berkembang, kami berusaha melampaui ekspektasimu setiap saat.',
            about_months: 'Bulan Penuh <strong>Pencapaian</strong>',
            about_stat1: '<strong>Pesanan</strong> Selesai',
            about_stat2: '<strong>Pesan</strong> Lagi',
            why_title: 'Mengapa Memilih KlinKlin?',
            why_lead: 'Bersama KlinKlin, kamu bukan sekadar menggunakan jasa laundry kamu mendapatkan mitra kebersihan yang bisa diandalkan. Ini alasan pelanggan mempercayai kami:',
            why_i1: '<strong>On Demand</strong> : Jemput & antar sesuai jadwalmu',
            why_i2: '<strong>Pemilahan Pakaian</strong> : Setiap pakaian dipilah sesuai jenis dan bahan',
            why_i3: '<strong>Pencatatan Detail</strong>: Setiap item tercatat rapi agar tidak ada yang tertukar atau hilang',
            why_care: 'Setiap pakaian kami <strong>catat</strong>, <strong>pilah</strong>, dan <strong>rawat</strong>, supaya kamu terima kembali bersih, rapi, dan tanpa khawatir.',
            why_order: 'Ayo Order Sekarang',
            why_stat: '<strong>Layanan</strong><br>anti ribet',
            why_pesan: 'PESAN SEKARANG',
            label: 'IDN'
        },
        en: {
            nav_about: 'About Us',
            nav_home:  'Home',
            nav_history: 'Order Tracking',
            hero_title: 'Wash Without <strong>Fatigue</strong><br>Clean Without <strong>Hassle</strong>',
            hero_desc: 'Experience effortless cleanliness with KlinKlin. We offer laundry pickup & delivery tailored to your needs, ensuring your clothes come back clean, fresh, and perfectly cared for.',
            cta: 'Order Now',
            proof: 'Happy Customers',
            b1_small: 'Funded by',
            b1_desc: 'Student Entrepreneurship Development Program',
            b2_small: 'Operating in',
            b2_desc: 'Covering Denpasar and Badung areas',
            f1_title: 'On Time',
            f1_desc: 'Your laundry is picked up & delivered right on the schedule you set.',
            f2_title: 'Trusted',
            f2_desc: 'Your clothes are safe with us — recorded, tracked, and returned in the best condition.',
            f3_title: 'Affordable',
            f3_desc: 'Maximum clean, minimum cost — quality laundry for everyone.',
            about_title: 'We Keep Your Clothes Clean & Cared For',
            about_desc: 'With KlinKlin, we are here to make sure your clothes are always clean, fresh, and well cared for. With a passion for service and ever-evolving technology, we strive to exceed your expectations every time.',
            about_months: 'Months of <strong>Achievement</strong>',
            about_stat1: '<strong>Orders</strong> Completed',
            about_stat2: '<strong>Order</strong> Again',
            why_title: 'Why Choose KlinKlin?',
            why_lead: 'With KlinKlin, you are not just using a laundry service — you gain a reliable cleanliness partner. Here is why customers trust us:',
            why_i1: '<strong>On Demand</strong> : Pickup & delivery on your schedule',
            why_i2: '<strong>Clothes Sorting</strong> : Every garment sorted by type and fabric',
            why_i3: '<strong>Detailed Records</strong>: Every item logged neatly so nothing gets swapped or lost',
            why_care: 'We <strong>log</strong>, <strong>sort</strong>, and <strong>care for</strong> every garment, so you get them back clean, neat, and worry-free.',
            why_order: 'Order Now',
            why_stat: '<strong>Services</strong><br>hassle-free',
            why_pesan: 'ORDER NOW',
            label: 'ENG'
        }
    };

    function applyLang(lang) {
        var dict = I18N[lang] || I18N.id;

        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            var k = el.getAttribute('data-i18n');
            if (dict[k] != null) { el.textContent = dict[k]; }
        });
        document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
            var k = el.getAttribute('data-i18n-html');
            if (dict[k] != null) { el.innerHTML = dict[k]; }
        });

        var label = document.querySelector('.lang-label');
        if (label) { label.textContent = dict.label; }

        document.documentElement.lang = lang;
        try { localStorage.setItem('klinklin-lang', lang); } catch (e) {}
    }

    var current = 'id';
    try { current = localStorage.getItem('klinklin-lang') || 'id'; } catch (e) {}
    applyLang(current);

    var btn = document.querySelector('.lang-switch');
    if (btn) {
        btn.addEventListener('click', function () {
            // Simpan bahasa baru lalu muat ulang halaman supaya animasi masuk
            // (reveal) ikut diputar ulang dari awal.
            var next = (current === 'id') ? 'en' : 'id';
            try { localStorage.setItem('klinklin-lang', next); } catch (e) {}
            window.location.reload();
        });
    }

    var hamburger = document.querySelector('.hamburger');
var navLinks  = document.querySelector('.nav-links');

if (hamburger && navLinks) {
    hamburger.addEventListener('click', function () {
        var isOpen = hamburger.classList.toggle('open');
        if (isOpen) {
            navLinks.classList.add('open');
            setTimeout(function() { navLinks.classList.add('active-overlay'); }, 10);
        } else {
            navLinks.classList.remove('active-overlay');
            setTimeout(function() { navLinks.classList.remove('open'); }, 450);
        }
    });
    navLinks.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            hamburger.classList.remove('open');
            navLinks.classList.remove('active-overlay');
            setTimeout(function() { navLinks.classList.remove('open'); }, 450);
        });
    });
}
})();
