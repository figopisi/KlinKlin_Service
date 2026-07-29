/* ============================================================
   KlinKlin — Halaman Order (Pesan)
   - Form dinamis: isi field -> preview pesan langsung update
   - Salin format / kirim langsung ke WhatsApp
   - Label & greeting pesan ikut bahasa UI aktif (I18N), via I18N.id / I18N.en
   ============================================================ */
(function () {
    'use strict';

    /* Nomor WhatsApp tujuan (format internasional tanpa "+", mis. 6281234567890).
       Kosongkan -> WhatsApp terbuka tanpa kontak terpilih. GANTI dengan nomor asli KlinKlin. */
    var WA_NUMBER = '';
    var IG_URL    = 'https://instagram.com/klinklin.service';

    var form = document.querySelector('#orderForm');
    if (!form) return;

    var preview = document.querySelector('#orderPreview');
    var emptyMark = '—';

    /* ---------- Bahasa aktif ----------
       Sumber kebenaran: atribut lang pada <html>, mis. <html lang="en">.
       Default ke 'id' kalau tidak ada / tidak dikenali.
       Kalau switcher bahasa kamu TIDAK mengubah document.documentElement.lang,
       sesuaikan fungsi getLang() di bawah ini. */
    function getLang() {
        var attr = (document.documentElement.lang || '').toLowerCase();
        return attr.indexOf('en') === 0 ? 'en' : 'id';
    }

    /* Fallback default kalau objek I18N belum termuat / key belum ada,
       supaya form tetap berfungsi walau i18n belum lengkap. */
    var FALLBACK = {
        id: {
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
            msg_label_catatan: 'Catatan Khusus'
        },
        en: {
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
            msg_label_catatan: 'Special Notes'
        }
    };

    /* Ambil teks dari I18N global kalau ada, jatuh ke FALLBACK kalau tidak. */
    function t(key) {
        var lang = getLang();
        var dict = (typeof I18N !== 'undefined' && I18N[lang]) ? I18N[lang] : FALLBACK[lang];
        return (dict && dict[key]) || FALLBACK[lang][key] || FALLBACK.id[key] || '';
    }

    /* Urutan baris pesan — label diambil dinamis lewat t() sesuai bahasa aktif */
    function getFields() {
        return [
            { id: 'f_nama',     label: t('msg_label_nama') },
            { id: 'f_telepon',  label: t('msg_label_telepon') },
            { id: 'f_alamat',   label: t('msg_label_alamat') },
            { id: 'f_tanggal',  label: t('msg_label_tanggal') },
            { id: 'f_jam',      label: t('msg_label_jam') },
            { id: 'f_layanan',  label: t('msg_label_layanan') },
            { id: 'f_jumlah',   label: t('msg_label_jumlah') },
            { id: 'f_laundry',  label: t('msg_label_laundry') },
            { id: 'f_pilah',    label: t('msg_label_pilah') },
            { id: 'f_bayar',    label: t('msg_label_bayar') },
            { id: 'f_catatan',  label: t('msg_label_catatan') }
        ];
    }

    function val(id) {
        var el = document.getElementById(id);
        if (!el) return '';
        return (el.value || '').trim();
    }

    function buildMessage(forCopy) {
        var lines = [t('msg_greeting'), ''];
        getFields().forEach(function (f) {
            var v = val(f.id);
            lines.push(f.label + ': ' + (v || (forCopy ? '-' : emptyMark)));
        });
        return lines.join('\n');
    }

    function refresh() {
        if (preview) { preview.textContent = buildMessage(false); }
    }

    /* update live setiap kali user mengetik / memilih */
    form.addEventListener('input', refresh);
    form.addEventListener('change', refresh);
    refresh();

    /* update live setiap kali bahasa UI diganti (lang attribute pada <html> berubah) */
    var langObserver = new MutationObserver(refresh);
    langObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['lang'] });

    /* ---------- Salin ---------- */
    function fallbackCopy(text) {
        try {
            var ta = document.createElement('textarea');
            ta.value = text; ta.setAttribute('readonly', '');
            ta.style.position = 'fixed'; ta.style.top = '-1000px'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
        } catch (e) {}
    }

    var copyBtn = document.querySelector('#orderCopy');
    if (copyBtn) {
        var copyLabel = copyBtn.querySelector('.label');
        var copyDefault = copyLabel ? copyLabel.textContent : '';
        copyBtn.addEventListener('click', function () {
            var text = buildMessage(true);
            var done = function () {
                copyBtn.classList.add('copied');
                if (copyLabel) { copyLabel.textContent = copyBtn.getAttribute('data-copied') || 'Tersalin!'; }
                clearTimeout(copyBtn._t);
                copyBtn._t = setTimeout(function () {
                    copyBtn.classList.remove('copied');
                    if (copyLabel) { copyLabel.textContent = copyDefault; }
                }, 1900);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, function () { fallbackCopy(text); done(); });
            } else { fallbackCopy(text); done(); }
        });
    }

    function buildPickupDatetime() {
        var date = val('f_tanggal');
        var time = val('f_jam');
        if (!date || !time) { return null; }
        return date + ' ' + time;
    }

    function buildDraftPayload() {
        return {
            nama: val('f_nama'),
            phone: val('f_telepon'),
            alamat_customer: val('f_alamat'),
            alamat_laundry: val('f_laundry') || null,
            phone_laundry: null,
            is_sorted: val('f_pilah') === 'Iya',
            note: val('f_catatan') || null,
            tanggal_penjemputan: buildPickupDatetime(),
            jenis_layanan: val('f_layanan') || null,
            estimasi_jumlah_laundry: val('f_jumlah') || null,
        };
    }

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function saveDraft() {
        return fetch('/buat-pesanan/draft', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify(buildDraftPayload())
        });
    }

    /* ---------- Kirim via WhatsApp ---------- */
    var waBtn = document.querySelector('#orderSendWa');
    if (waBtn) {
        waBtn.addEventListener('click', function (e) {
            e.preventDefault();
            saveDraft().catch(function () {
                console.warn('Draft save gagal.');
            }).finally(function () {
                var text = encodeURIComponent(buildMessage(true));
                var url = WA_NUMBER
                    ? 'https://wa.me/' + WA_NUMBER + '?text=' + text
                    : 'https://api.whatsapp.com/send?text=' + text;
                window.open(url, '_blank', 'noopener');
            });
        });
    }

    /* ---------- Kirim via Instagram (salin dulu lalu buka profil) ---------- */
    var igBtn = document.querySelector('#orderSendIg');
    if (igBtn) {
        igBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var text = buildMessage(true);
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).catch(function () {});
            } else { fallbackCopy(text); }
            window.open(IG_URL, '_blank', 'noopener');
        });
    }
})();