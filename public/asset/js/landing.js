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
            harga_title: 'Harga <strong>sangat</strong><br><span class="pt-big">Terjangkau</span>',
            harga_lead: '<strong>KlinKlin</strong> engga cuma Praktis, tapi harganya juga terjangkau banget buat semua kalangan. Tunggu apalagi ayo order sekarang juga!',
            pc1_label: 'Mulai dari', pc1_sub: 'Pada kilometer <strong>Pertama</strong>',
            pc1_a: 'On Demand', pc1_b: 'Praktis', pc1_c: 'Terpercaya',
            pc2_label: 'Dilanjutkan hanya', pc2_sub: 'Pada kilometer <strong>Selanjutnya</strong>',
            pc2_a: 'Lebih jauh lebih murah', pc2_b: 'Anti kepanasan', pc2_c: 'Cocok buat kamu yang cari suasana baru',
            pc3_label: 'Tambah', pc3_sub: 'Mendapatkan <strong>jasa pilah kiloan</strong>',
            pc3_a: 'Pakaianmu terorganisir', pc3_b: 'Anti pusing', pc3_c: 'Anti baju hilang',
            foot_desc: 'Layanan laundry pickup & delivery yang bikin hidupmu lebih ringkas. Cuci tanpa capek, bersih tanpa repot — khusus area Bali.',
            foot_follow: 'Ikuti Kami',
            foot_nav: 'Navigasi',
            foot_price: 'Harga',
            foot_why: 'Mengapa Kami',
            foot_serv: 'Layanan',
            foot_s1: 'Jemput & Antar Cucian',
            foot_s2: 'Pemilahan Pakaian',
            foot_s3: 'Dokumentasi Pakaian',
            foot_contact: 'Kontak',
            foot_area: 'Denpasar & Badung, Bali',
            foot_funded: 'Didanai oleh Program P2MW 2026',
            foot_rights: '© 2026 KlinKlin. Seluruh hak cipta dilindungi.',
            foot_made: 'Dibuat dengan ♥ di Bali',
            order_title: '<strong>Ayo!</strong> laundry cucianmu <b>sekarang</b>',
            order_hubungi: 'Hubungi <strong>KlinKlin</strong>',
            order_wa: 'Whatsapp',
            order_ig: 'Instagram',
            order_isi: 'Isi <strong>Format Pesanan</strong>',
            order_preview: 'Preview Pesanan',
            order_copy: 'Salin Format',
            order_copied: 'Tersalin!',
            order_send_wa: 'Kirim via WhatsApp',
            order_send_ig: 'Salin & Buka Instagram',
            label: 'IDN',
            /* ---------- Tambahan untuk I18N.id buat pesanan ---------- */
            form_nama_label:    'Nama lengkap',
            form_nama_ph:        'Nama kamu',
            form_telepon_label:  'Nomor telepon aktif',
            form_telepon_ph:     '08xxxxxxxxxx',
            form_alamat_label:   'Alamat customer',
            form_alamat_ph:      'Alamat penjemputan cucian',
            form_tanggal_label:  'Tanggal Penjemputan',
            form_jam_label:      'Jam Penjemputan',
            form_layanan_label:  'Jenis Layanan',
            form_layanan_opt0:   'Pilih layanan',
            form_layanan_opt1:   'Cuci + Setrika',
            form_layanan_opt2:   'Cuci Kering',
            form_layanan_opt3:   'Setrika Saja',
            form_layanan_opt4:   'Cuci Sepatu',
            form_layanan_opt5:   'Bed Cover / Selimut',
            form_jumlah_label:   'Estimasi Jumlah (kg/pcs)',
            form_jumlah_ph:      'mis. 5 kg',
            form_laundry_label:  'Alamat laundry Pilihan',
            form_opsional:       '(opsional)',
            form_laundry_ph:     'Kosongkan jika diserahkan ke KlinKlin',
            form_pilah_label:    'Jasa pilah',
            form_pilih:          'Pilih',
            form_pilah_opt1:     'Iya',
            form_pilah_opt2:     'Tidak',
            form_bayar_label:    'Metode bayar',
            form_bayar_opt1:     'Cash di tempat',
            form_bayar_opt2:     'Transfer',
            form_catatan_label:  'Catatan Khusus',
            form_catatan_ph:     'Permintaan khusus, dll.',
            /* ---------- Tambahan untuk I18N.id (pesan WA/IG) ---------- */
            msg_greeting:      'Halo KlinKlin! 👋 Saya mau pesan laundry:',
            msg_label_nama:    'Nama lengkap',
            msg_label_telepon: 'Nomor telepon yang aktif',
            msg_label_alamat:  'Alamat customer',
            msg_label_tanggal: 'Tanggal Penjemputan',
            msg_label_jam:     'Jam Penjemputan',
            msg_label_layanan: 'Jenis Layanan',
            msg_label_jumlah:  'Estimasi Jumlah Laundry (kg/pcs)',
            msg_label_laundry: 'Alamat laundry Pilihan',
            msg_label_pilah:   'Jasa pilah',
            msg_label_bayar:   'Metode bayar',
            msg_label_catatan: 'Catatan Khusus',
             // Cek Pesanan
            order_check_title: 'Cek Pesanan',
            search_placeholder: 'Masukkan token pesanan...',
            search_button: 'Cari',

            // Informasi awal
            info_text: 'Masukkan kode token yang telah Anda terima setelah melakukan pemesanan melalui WhatsApp atau Instagram. Setelah token dimasukkan, detail dan status pesanan akan muncul di bawah.',

            // Pesan umum
            order_not_found: 'Pesanan tidak ditemukan',

            // Detail pesanan
            customer_address: 'Alamat Customer',
            customer_phone: 'Phone Customer',
            total_fee: 'Fee (harga total)',
            notes: 'Catatan',

            laundry_address: 'Alamat Laundry',
            laundry_phone: 'Phone Laundry',
            service_type: 'Jenis Layanan',
            estimated_laundry: 'Estimasi Jumlah Laundry (kg/pcs)',
            clothing_sorted: 'Pemilahan Pakaian',
            pickup_date: 'Tanggal Penjemputan',
            order_date: 'Tanggal Pesanan',

            yes: 'Ya',
            no: 'Tidak',

            // Dokumentasi
            clothing_documentation: 'Dokumentasi Pakaian',
            view_documentation: 'Lihat Dokumentasi',

            // Foto bukti
            order_photos: 'Foto Bukti Pesanan',
            pickup_proof: 'Bukti Pengambilan',
            laundry_receipt: 'Bukti Nota Laundry',
            delivery_proof: 'Bukti Pengiriman',

            // Driver
            driver_information: 'Informasi Driver',
            current_driver: 'DRIVER SAAT INI',
            previous_driver: 'DRIVER SEBELUMNYA',
            current_driver_desc: 'Driver yang sedang menangani pesanan Anda',
            previous_driver_desc: 'Pernah menangani pesanan ini',
            phone_number: 'Nomor Telepon',
            order_status: 'Status Pesanan',
            last_status: 'Status Terakhir',
            last_active: 'Terakhir aktif',

            // Status pesanan
            status_processing: 'Diproses',
            status_picked_up: 'Dijemput',
            status_searching: 'Mencari Laundry',
            status_washing: 'Dicuci',
            status_delivering: 'Diantar',
            status_completed: 'Selesai',
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
            harga_title: 'Super <strong>affordable</strong><br><span class="pt-big">Pricing</span>',
            harga_lead: '<strong>KlinKlin</strong> is not just practical the price is super affordable for everyone. What are you waiting for, order now!',
            pc1_label: 'Starting from', pc1_sub: 'For the <strong>first</strong> kilometer',
            pc1_a: 'On Demand', pc1_b: 'Practical', pc1_c: 'Trusted',
            pc2_label: 'Then only', pc2_sub: 'For <strong>each next</strong> kilometer',
            pc2_a: 'Farther = cheaper', pc2_b: 'No more heat', pc2_c: 'Great for a fresh vibe',
            pc3_label: 'Add-on', pc3_sub: 'Get the <strong>sorting service</strong>',
            pc3_a: 'Clothes organized', pc3_b: 'No headache', pc3_c: 'No lost clothes',
            foot_desc: 'A laundry pickup & delivery service that makes life simpler. Wash without fatigue, clean without hassle — Bali area only.',
            foot_follow: 'Follow Us',
            foot_nav: 'Navigation',
            foot_price: 'Pricing',
            foot_why: 'Why Us',
            foot_serv: 'Services',
            foot_s1: 'Pickup & Delivery',
            foot_s2: 'Clothes Sorting',
            foot_s3: 'Laundry Documentation',
            foot_contact: 'Contact',
            foot_area: 'Denpasar & Badung, Bali',
            foot_funded: 'Funded by the P2MW 2026 Program',
            foot_rights: '© 2026 KlinKlin. All rights reserved.',
            foot_made: 'Made with ♥ in Bali',
            order_title: '<strong>Let\'s!</strong> get your laundry <b>done now</b>',
            order_hubungi: 'Contact <strong>KlinKlin</strong>',
            order_wa: 'Whatsapp',
            order_ig: 'Instagram',
            order_isi: 'Fill the <strong>Order Format</strong>',
            order_preview: 'Order Preview',
            order_copy: 'Copy Format',
            order_copied: 'Copied!',
            order_send_wa: 'Send via WhatsApp',
            order_send_ig: 'Copy & Open Instagram',
            label: 'ENG',
            /* ---------- Tambahan untuk I18N.en ---------- */
            form_nama_label:    'Full name',
            form_nama_ph:        'Your name',
            form_telepon_label:  'Active phone number',
            form_telepon_ph:     '08xxxxxxxxxx',
            form_alamat_label:   'Customer address',
            form_alamat_ph:      'Pickup address for your laundry',
            form_tanggal_label:  'Pickup Date',
            form_jam_label:      'Pickup Time',
            form_layanan_label:  'Service Type',
            form_layanan_opt0:   'Select a service',
            form_layanan_opt1:   'Wash + Iron',
            form_layanan_opt2:   'Wash & Dry',
            form_layanan_opt3:   'Iron Only',
            form_layanan_opt4:   'Shoe Cleaning',
            form_layanan_opt5:   'Bed Cover / Blanket',
            form_jumlah_label:   'Estimated Amount (kg/pcs)',
            form_jumlah_ph:      'e.g. 5 kg',
            form_laundry_label:  'Preferred Laundry Address',
            form_opsional:       '(optional)',
            form_laundry_ph:     'Leave blank to let KlinKlin choose',
            form_pilah_label:    'Sorting Service',
            form_pilih:          'Select',
            form_pilah_opt1:     'Yes',
            form_pilah_opt2:     'No',
            form_bayar_label:    'Payment Method',
            form_bayar_opt1:     'Cash on site',
            form_bayar_opt2:     'Bank Transfer',
            form_catatan_label:  'Special Notes',
            form_catatan_ph:     'Special requests, etc.',
            /* ---------- Tambahan untuk I18N.en (pesan WA/IG) ---------- */
            msg_greeting:      'Hi KlinKlin! 👋 I would like to order laundry service:',
            msg_label_nama:    'Full name',
            msg_label_telepon: 'Active phone number',
            msg_label_alamat:  'Customer address',
            msg_label_tanggal: 'Pickup Date',
            msg_label_jam:     'Pickup Time',
            msg_label_layanan: 'Service Type',
            msg_label_jumlah:  'Estimated Laundry Amount (kg/pcs)',
            msg_label_laundry: 'Preferred Laundry Address',
            msg_label_pilah:   'Sorting Service',
            msg_label_bayar:   'Payment Method',
            msg_label_catatan: 'Special Notes',

             // Order Tracking
            order_check_title: 'Track Order',
            search_placeholder: 'Enter your order token...',
            search_button: 'Search',

            // Initial information
            info_text: 'Enter the token code you received after placing an order via WhatsApp or Instagram. Once the token is entered, your order details and status will appear below.',

            // General messages
            order_not_found: 'Order not found',

            // Order details
            customer_address: 'Customer Address',
            customer_phone: 'Customer Phone',
            total_fee: 'Total Fee',
            notes: 'Notes',

            laundry_address: 'Laundry Address',
            laundry_phone: 'Laundry Phone',
            service_type: 'Service Type',
            estimated_laundry: 'Estimated Laundry Amount (kg/items)',
            clothing_sorted: 'Clothes Sorted',
            pickup_date: 'Pickup Date',
            order_date: 'Order Date',

            yes: 'Yes',
            no: 'No',

            // Documentation
            clothing_documentation: 'Clothing Documentation',
            view_documentation: 'View Documentation',

            // Proof photos
            order_photos: 'Order Proof Photos',
            pickup_proof: 'Pickup Proof',
            laundry_receipt: 'Laundry Receipt',
            delivery_proof: 'Delivery Proof',

            // Driver
            driver_information: 'Driver Information',
            current_driver: 'CURRENT DRIVER',
            previous_driver: 'PREVIOUS DRIVER',
            current_driver_desc: 'The driver currently handling your order',
            previous_driver_desc: 'Previously handled this order',
            phone_number: 'Phone Number',
            order_status: 'Order Status',
            last_status: 'Last Status',
            last_active: 'Last Active',

            // Order statuses
            status_processing: 'Processing',
            status_picked_up: 'Picked Up',
            status_searching: 'Searching for Laundry',
            status_washing: 'Washing',
            status_delivering: 'Out for Delivery',
            status_completed: 'Completed',
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

        document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
            var k = el.getAttribute('data-i18n-placeholder');
            if (dict[k] != null) { el.placeholder = dict[k]; }
        });

        // SESUDAH
        document.querySelectorAll('.lang-label').forEach(function (label) {
            label.textContent = dict.label;
        });

        document.documentElement.lang = lang;
        try { localStorage.setItem('klinklin-lang', lang); } catch (e) {}
    }

    var current = 'id';
    try { current = localStorage.getItem('klinklin-lang') || 'id'; } catch (e) {}
    applyLang(current);

    document.querySelectorAll('.lang-switch').forEach(function(btn) {
    btn.addEventListener('click', function () {
        var next = (current === 'id') ? 'en' : 'id';
        try { localStorage.setItem('klinklin-lang', next); } catch (e) {}
        window.location.reload();
    });
});

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

/* ---------- 3. SALIN FORMAT PESANAN ---------- */
    function fallbackCopy(text) {
        try {
            var ta = document.createElement('textarea');
            ta.value = text; ta.setAttribute('readonly', '');
            ta.style.position = 'fixed'; ta.style.top = '-1000px'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
        } catch (e) {}
    }
    document.querySelectorAll('.order-copy').forEach(function (cbtn) {
        cbtn.addEventListener('click', function () {
            var target = document.querySelector(cbtn.getAttribute('data-copy-target'));
            if (!target) return;
            var text = target.innerText || target.textContent;
            var feedback = function () {
                var dict = I18N[current] || I18N.id;
                var label = cbtn.querySelector('.order-copy-label');
                cbtn.classList.add('copied');
                if (label) { label.textContent = dict.order_copied || 'Tersalin!'; }
                clearTimeout(cbtn._copyT);
                cbtn._copyT = setTimeout(function () {
                    cbtn.classList.remove('copied');
                    var d = I18N[current] || I18N.id;
                    if (label) { label.textContent = d.order_copy || 'Salin'; }
                }, 1900);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(feedback, function () { fallbackCopy(text); feedback(); });
            } else { fallbackCopy(text); feedback(); }
        });
    });
})();
