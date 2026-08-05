/* ============================================================
   KlinKlin — Halaman Order (Pesan)
   - Form dinamis: isi field -> preview pesan langsung update
   - Validasi wajib-isi sebelum kirim (WA/IG)
   - Salin format / kirim langsung ke WhatsApp
   - Label & greeting pesan ikut bahasa UI aktif (I18N), via I18N.id / I18N.en
   - Tipe Penjemputan: jika "Jemput Saja" dipilih, Alamat Laundry jadi wajib.
     Tipe lain ("Antar Jemput (PP)" / "Antar Saja") -> flow lama, alamat
     laundry tetap opsional.
   ============================================================ */
(function () {
    'use strict';

    var WA_NUMBER = '';
    var IG_URL    = 'https://instagram.com/klinklin.service';

    // Nilai tipe penjemputan yang mewajibkan alamat laundry diisi
    var TIPE_WAJIB_LAUNDRY = 'Jemput Saja';

    var form = document.querySelector('#orderForm');
    if (!form) return;

    var preview = document.querySelector('#orderPreview');
    var notice = document.querySelector('#orderNotice');
    var emptyMark = '—';

    var tipeSelect = document.getElementById('f_tipe_antar_jemput');
    var laundryField = document.getElementById('of_field_laundry');

    function getLang() {
        var attr = (document.documentElement.lang || '').toLowerCase();
        return attr.indexOf('en') === 0 ? 'en' : 'id';
    }

    var FALLBACK = {
        id: {
            msg_greeting:      'Halo KlinKlin! 👋 Saya mau pesan laundry:',
            msg_label_nama:    'Nama lengkap',
            msg_label_telepon: 'Nomor telepon yang aktif',
            msg_label_alamat:  'Alamat customer',
            msg_label_tanggal: 'Tanggal Penjemputan',
            msg_label_jam:     'Jam Penjemputan',
            msg_label_tipe:    'Tipe Penjemputan',
            msg_label_layanan: 'Jenis Layanan',
            msg_label_jumlah:  'Estimasi Jumlah Laundry (kg/pcs)',
            msg_label_laundry: 'Alamat laundry Pilihan',
            msg_label_pilah:   'Jasa pilah',
            msg_label_bayar:   'Metode bayar',
            msg_label_catatan: 'Catatan Khusus',
            notice_incomplete: 'Mohon lengkapi field berwarna merah sebelum mengirim pesanan.',
            notice_save_failed: 'Pesanan belum tersimpan di sistem kami, tapi pesan tetap bisa dikirim manual. Mohon hubungi CS jika tidak ada balasan.'
        },
        en: {
            msg_greeting:      'Hi KlinKlin! 👋 I would like to order laundry service:',
            msg_label_nama:    'Full name',
            msg_label_telepon: 'Active phone number',
            msg_label_alamat:  'Customer address',
            msg_label_tanggal: 'Pickup Date',
            msg_label_jam:     'Pickup Time',
            msg_label_tipe:    'Pickup Type',
            msg_label_layanan: 'Service Type',
            msg_label_jumlah:  'Estimated Laundry Amount (kg/pcs)',
            msg_label_laundry: 'Preferred Laundry Address',
            msg_label_pilah:   'Sorting Service',
            msg_label_bayar:   'Payment Method',
            msg_label_catatan: 'Special Notes',
            notice_incomplete: 'Please fill in all required (*) fields before sending your order.',
            notice_save_failed: 'Your order was not saved on our system, but you can still send the message manually. Please contact us if there is no reply.'
        }
    };

    function t(key) {
        var lang = getLang();
        var dict = (typeof I18N !== 'undefined' && I18N[lang]) ? I18N[lang] : FALLBACK[lang];
        return (dict && dict[key]) || FALLBACK[lang][key] || FALLBACK.id[key] || '';
    }

    function getFields() {
        return [
            { id: 'f_nama',     label: t('msg_label_nama') },
            { id: 'f_telepon',  label: t('msg_label_telepon') },
            { id: 'f_alamat',   label: t('msg_label_alamat') },
            { id: 'f_tanggal',  label: t('msg_label_tanggal') },
            { id: 'f_jam',      label: t('msg_label_jam') },
            { id: 'f_tipe_antar_jemput', label: t('msg_label_tipe') },
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

    /* ---------- Tipe Penjemputan -> alamat laundry wajib/tidak ---------- */
    function isJemputSaja() {
        return val('f_tipe_antar_jemput') === TIPE_WAJIB_LAUNDRY;
    }

    function syncLaundryRequirement() {
        if (!laundryField) return;

        if (isJemputSaja()) {
            laundryField.dataset.required = 'true';
            laundryField.classList.add('is-dynamic-required');
        } else {
            delete laundryField.dataset.required;
            laundryField.classList.remove('is-dynamic-required');
            laundryField.classList.remove('of-error'); // bersihkan error lama kalau tipe diganti
        }
    }

    if (tipeSelect) {
        tipeSelect.addEventListener('change', syncLaundryRequirement);
    }
    syncLaundryRequirement(); // inisialisasi state awal saat halaman dimuat

    form.addEventListener('input', refresh);
    form.addEventListener('change', refresh);
    refresh();

    var langObserver = new MutationObserver(refresh);
    langObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['lang'] });

    /* ---------- Validasi wajib-isi ---------- */
    function showNotice(text) {
        if (!notice) return;
        notice.textContent = text;
        notice.classList.add('show');
    }

    function hideNotice() {
        if (!notice) return;
        notice.classList.remove('show');
        notice.textContent = '';
    }

    // Kembalikan true kalau semua field wajib terisi (termasuk field yang
    // wajib secara dinamis, mis. alamat laundry saat tipe = "Jemput Saja").
    // Field kosong ditandai merah.
    function validateRequired() {
        var ok = true;
        var firstInvalid = null;

        // sinkronkan dulu status wajib alamat laundry sebelum validasi jalan,
        // jaga-jaga kalau ada perubahan yang belum ke-trigger event change
        syncLaundryRequirement();

        form.querySelectorAll('.of-field[data-required="true"]').forEach(function (field) {
            var input = field.querySelector('input, select, textarea');
            var filled = input && input.value && input.value.trim() !== '';

            if (!filled) {
                ok = false;
                field.classList.add('of-error');
                if (!firstInvalid) firstInvalid = input;
            } else {
                field.classList.remove('of-error');
            }
        });

        if (!ok) {
            showNotice(t('notice_incomplete'));
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            hideNotice();
        }

        return ok;
    }

    // hapus tanda error begitu user mulai mengisi field itu
    form.addEventListener('input', function (e) {
        var field = e.target.closest('.of-field[data-required="true"]');
        if (field && e.target.value.trim() !== '') {
            field.classList.remove('of-error');
        }
    });
    form.addEventListener('change', function (e) {
        var field = e.target.closest('.of-field[data-required="true"]');
        if (field && e.target.value.trim() !== '') {
            field.classList.remove('of-error');
        }
    });

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
            tipe_antar_jemput: val('f_tipe_antar_jemput') || null,
        };
    }

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Sekarang mengecek response.ok secara eksplisit, bukan cuma nangkep network error.
    function saveDraft() {
        return fetch('/buat-pesanan/draft', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify(buildDraftPayload())
        }).then(function (res) {
            if (!res.ok) {
                return res.json().catch(function () { return null; }).then(function (body) {
                    console.error('Gagal menyimpan draft pesanan:', res.status, body);
                    throw new Error('draft_save_failed');
                });
            }
            return res.json();
        });
    }

    function proceedToWa() {
        var text = encodeURIComponent(buildMessage(true));
        var url = WA_NUMBER
            ? 'https://wa.me/' + WA_NUMBER + '?text=' + text
            : 'https://api.whatsapp.com/send?text=' + text;
        window.open(url, '_blank', 'noopener');
    }

    /* ---------- Kirim via WhatsApp ---------- */
    var waBtn = document.querySelector('#orderSendWa');
    if (waBtn) {
        waBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (!validateRequired()) {
                return; // blok total, jangan buka WA kalau field wajib belum lengkap
            }

            waBtn.classList.add('is-loading');
            saveDraft()
                .then(function () {
                    hideNotice();
                    proceedToWa();
                })
                .catch(function () {
                    // draft gagal tersimpan meski sudah lolos validasi (mis. server error) —
                    // beri tahu user secara jujur, jangan diam-diam lanjut seperti sebelumnya
                    showNotice(t('notice_save_failed'));
                    proceedToWa();
                })
                .finally(function () {
                    waBtn.classList.remove('is-loading');
                });
        });
    }

    /* ---------- Kirim via Instagram (salin dulu lalu buka profil) ---------- */
    var igBtn = document.querySelector('#orderSendIg');
    if (igBtn) {
        igBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (!validateRequired()) {
                return;
            }

            saveDraft()
                .then(function () {
                    hideNotice();
                })
                .catch(function () {
                    showNotice(t('notice_save_failed'));
                })
                .finally(function () {
                    var text = buildMessage(true);
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).catch(function () {});
                    } else { fallbackCopy(text); }
                    window.open(IG_URL, '_blank', 'noopener');
                });
        });
    }
})();