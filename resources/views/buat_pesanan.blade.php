<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Sekarang — KlinKlin</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="asset/css/landingpage.css">
<style>
    /* tanda wajib isi */
    .of-required { color:#e53935; margin-left:2px; }
    .of-field.of-error input,
    .of-field.of-error select,
    .of-field.of-error textarea {
        border-color:#e53935 !important;
        box-shadow:0 0 0 2px rgba(229,57,53,0.15);
    }
    .of-error-msg {
        color:#e53935;
        font-size:12px;
        margin-top:4px;
        display:none;
    }
    .of-field.of-error .of-error-msg { display:block; }

    /* tanda wajib dinamis (muncul/hilang tergantung tipe penjemputan) */
    .of-required-dynamic { display:none; }
    .of-field.is-dynamic-required .of-required-dynamic { display:inline; }

    /* banner notifikasi global */
    #orderNotice {
        display:none;
        background:#fdecea;
        border:1px solid #e53935;
        color:#b71c1c;
        padding:10px 14px;
        border-radius:8px;
        font-size:14px;
        margin-bottom:14px;
    }
    #orderNotice.show { display:block; }
</style>
</head>

<body>

@include('navbar')

<!-- ===== ORDER / PESAN ===== -->
<section id="pesan" class="order order-page">
    <h2 class="order-title reveal" data-reveal="up" data-i18n-html="order_title"><strong>Ayo!</strong> laundry cucianmu <b>sekarang</b></h2>

    <div class="order-card reveal" data-reveal="up" style="--delay:.08s">
        <span class="order-glow" aria-hidden="true"></span>

        <div class="order-grid">
            <!-- KIRI: kontak + form -->
            <div class="order-main">
                <h3 class="order-card-title" data-i18n-html="order_hubungi">Hubungi <strong>KlinKlin</strong></h3>

                <div class="order-channels">
                    <a href="https://wa.me/" target="_blank" rel="noopener" class="order-channel">
                        <span class="order-channel-ic"><img src="image/wa.png" alt="WhatsApp"></span>
                        <span data-i18n="order_wa">Whatsapp</span>
                    </a>
                    <a href="https://instagram.com/klinklin.service" target="_blank" rel="noopener" class="order-channel is-ig">
                        <span class="order-channel-ic"><img src="image/ig.png" alt="Instagram"></span>
                        <span data-i18n="order_ig">Instagram</span>
                    </a>
                </div>

                <h3 class="order-card-sub" data-i18n-html="order_isi">Isi <strong>Format Pesanan</strong></h3>

                <div id="orderNotice"></div>

                <form id="orderForm" class="order-form" autocomplete="off" novalidate>

                    <div class="of-row">
                        <div class="of-field" data-required="true">
                            <label for="f_nama" data-i18n="form_nama_label">Nama lengkap<span class="of-required">*</span></label>
                            <input id="f_nama" type="text" placeholder="Nama kamu" data-i18n-placeholder="form_nama_ph">
                            <span class="of-error-msg">Nama wajib diisi.</span>
                        </div>
                        <div class="of-field" data-required="true">
                            <label for="f_telepon" data-i18n="form_telepon_label">Nomor telepon aktif<span class="of-required">*</span></label>
                            <input id="f_telepon" type="tel" placeholder="08xxxxxxxxxx" data-i18n-placeholder="form_telepon_ph">
                            <span class="of-error-msg">Nomor telepon wajib diisi.</span>
                        </div>
                    </div>

                    <div class="of-field" data-required="true">
                        <label for="f_alamat" data-i18n="form_alamat_label">Alamat customer<span class="of-required">*</span></label>
                        <textarea id="f_alamat" rows="2" placeholder="Alamat penjemputan cucian" data-i18n-placeholder="form_alamat_ph"></textarea>
                        <span class="of-error-msg">Alamat customer wajib diisi.</span>
                    </div>

                    <div class="of-row">
                        <div class="of-field" data-required="true">
                            <label for="f_tanggal" data-i18n="form_tanggal_label">Tanggal Penjemputan<span class="of-required">*</span></label>
                            <input id="f_tanggal" type="date">
                            <span class="of-error-msg">Tanggal penjemputan wajib diisi.</span>
                        </div>
                        <div class="of-field" data-required="true">
                            <label for="f_jam" data-i18n="form_jam_label">Jam Penjemputan<span class="of-required">*</span></label>
                            <input id="f_jam" type="time">
                            <span class="of-error-msg">Jam penjemputan wajib diisi.</span>
                        </div>
                    </div>

                    <div class="of-field" data-required="true">
                        <label for="f_tipe_antar_jemput" data-i18n="form_tipe_label">Tipe Penjemputan<span class="of-required">*</span></label>
                        <select id="f_tipe_antar_jemput">
                            <option value="" data-i18n="form_pilih">Pilih</option>
                            <option value="Antar Jemput (PP)" data-i18n="form_tipe_opt1">Antar Jemput (PP)</option>
                            <option value="Antar Saja" data-i18n="form_tipe_opt2">Antar Saja</option>
                            <option value="Jemput Saja" data-i18n="form_tipe_opt3">Jemput Saja</option>
                        </select>
                        <span class="of-error-msg">Tipe penjemputan wajib dipilih.</span>
                    </div>

                    <div class="of-row">
                        <div class="of-field" data-required="true">
                            <label for="f_layanan" data-i18n="form_layanan_label">Jenis Layanan<span class="of-required">*</span></label>
                            <select id="f_layanan">
                                <option value="" data-i18n="form_layanan_opt0">Pilih layanan</option>
                                <option data-i18n="form_layanan_opt1">Cuci + Setrika</option>
                                <option data-i18n="form_layanan_opt2">Cuci Kering</option>
                                <option data-i18n="form_layanan_opt3">Setrika Saja</option>
                                <option data-i18n="form_layanan_opt4">Cuci Sepatu</option>
                                <option data-i18n="form_layanan_opt5">Bed Cover / Selimut</option>
                            </select>
                            <span class="of-error-msg">Jenis layanan wajib dipilih.</span>
                        </div>
                        <div class="of-field">
                            <label for="f_jumlah" data-i18n="form_jumlah_label">
                                Estimasi Jumlah (kg/pcs)
                                <span class="of-opt" data-i18n="form_opsional">(opsional)</span>
                            </label>
                            <input id="f_jumlah" type="text" placeholder="mis. 5 kg" data-i18n-placeholder="form_jumlah_ph">
                        </div>
                    </div>

                    <div class="of-field" id="of_field_laundry">
                        <label for="f_laundry">
                            <span data-i18n="form_laundry_label">Alamat laundry Pilihan</span>
                            <span class="of-required of-required-dynamic">*</span>
                            <span class="of-opt of-opt-dynamic" data-i18n="form_opsional">(opsional)</span>
                        </label>
                        <input id="f_laundry" type="text" placeholder="Kosongkan jika diserahkan ke KlinKlin" data-i18n-placeholder="form_laundry_ph">
                        <span class="of-error-msg" data-i18n="form_laundry_required_msg">Alamat laundry wajib diisi untuk tipe "Jemput Saja".</span>
                    </div>

                    <div class="of-row">
                        <div class="of-field" data-required="true">
                            <label for="f_pilah" data-i18n="form_pilah_label">Jasa pilah<span class="of-required">*</span></label>
                            <select id="f_pilah">
                                <option value="" data-i18n="form_pilih">Pilih</option>
                                <option data-i18n="form_pilah_opt1">Iya</option>
                                <option data-i18n="form_pilah_opt2">Tidak</option>
                            </select>
                            <span class="of-error-msg">Silakan pilih jasa pilah.</span>
                        </div>
                        <div class="of-field" data-required="true">
                            <label for="f_bayar" data-i18n="form_bayar_label">Metode bayar<span class="of-required">*</span></label>
                            <select id="f_bayar">
                                <option value="" data-i18n="form_pilih">Pilih</option>
                                <option data-i18n="form_bayar_opt1">Cash di tempat</option>
                                <option data-i18n="form_bayar_opt2">Transfer</option>
                            </select>
                            <span class="of-error-msg">Silakan pilih metode bayar.</span>
                        </div>
                    </div>

                    <div class="of-field">
                        <label for="f_catatan">
                            <span data-i18n="form_catatan_label">Catatan Khusus</span>
                            <span class="of-opt" data-i18n="form_opsional">(opsional)</span>
                        </label>
                        <textarea id="f_catatan" rows="2" placeholder="Permintaan khusus, dll." data-i18n-placeholder="form_catatan_ph"></textarea>
                    </div>
                </form>
            </div>

            <!-- KANAN: preview langsung + aksi -->
            <aside class="order-side">
                <div class="order-preview-card">
                    <div class="order-preview-head">
                        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                        <span class="order-preview-title" data-i18n="order_preview">Preview Pesanan</span>
                    </div>
                    <pre id="orderPreview" class="order-preview-text"></pre>
                </div>

                <div class="order-actions">
                    <button type="button" id="orderCopy" class="order-btn order-btn-ghost" data-copied="Tersalin!">
                        <svg class="ic-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        <svg class="ic-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        <span class="label" data-i18n="order_copy">Salin Format</span>
                    </button>
                    <a href="#" id="orderSendWa" class="order-btn order-btn-wa">
                        <img src="image/wa.png" alt="">
                        <span data-i18n="order_send_wa">Kirim via WhatsApp</span>
                    </a>
                    <a href="#" id="orderSendIg" class="order-btn order-btn-ig">
                        <img src="image/ig.png" alt="">
                        <span data-i18n="order_send_ig">Salin &amp; Buka Instagram</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@include('footer')

<script src="asset/js/landing.js"></script>
<script src="asset/js/order.js"></script>

</body>
</html>