<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_penjemputan", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultDate: "{{ $order->tanggal_penjemputan ?? '' }}",
            });
        });
    </script>

    <style>
        /*
         * Sama seperti halaman lain: semua di-scope di dalam #kk-admin,
         * semua class baru pakai prefix kk- supaya tidak bentrok dengan
         * style.css yang sudah ada. IDs dibiarkan sama persis karena
         * dipakai oleh JS/flatpickr/Blade di bawah — jangan diubah.
         */
        #kk-admin, #kk-admin * { box-sizing: border-box; }

        #kk-admin {
            --kk-bg: #F5F6FA;
            --kk-surface: #FFFFFF;
            --kk-ink: #14171F;
            --kk-ink-soft: #6B7080;
            --kk-line: #E7E9F0;
            --kk-brand: #2F5DFF;
            --kk-brand-ink: #1B2A6B;
            --kk-brand-soft: #EAF0FF;
            --kk-danger: #C4342B;
            --kk-danger-soft: #FCEEEE;
            --kk-good: #17A673;
            --kk-good-soft: #E7F9F1;
            --kk-warn: #B45309;
            --kk-warn-soft: #FEF3E2;
            --kk-radius: 14px;

            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--kk-bg);
            color: var(--kk-ink);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        #kk-admin .kk-mono { font-family: 'JetBrains Mono', monospace; }

        /* ---------- SIDEBAR (identik dengan halaman lain) ---------- */
        #kk-admin .kk-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: var(--kk-surface);
            border-right: 1px solid var(--kk-line);
            padding: 24px 16px;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #kk-admin .kk-brand { display: flex; align-items: center; gap: 10px; padding: 4px 10px 24px; }
        #kk-admin .kk-brand-mark {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--kk-brand), #6E8CFF);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px;
        }
        #kk-admin .kk-brand-text { line-height: 1.2; }
        #kk-admin .kk-brand-text strong { display: block; font-size: 15px; font-weight: 800; }
        #kk-admin .kk-brand-text span { font-size: 11.5px; color: var(--kk-ink-soft); }

        #kk-admin .kk-nav-label {
            font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            color: var(--kk-ink-soft); padding: 10px 10px 6px;
        }
        #kk-admin .kk-nav { list-style: none; margin: 0 0 8px; padding: 0; display: flex; flex-direction: column; gap: 2px; }
        #kk-admin .kk-nav a {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px;
            text-decoration: none; color: var(--kk-ink); font-size: 14px; font-weight: 600;
            transition: background .15s ease, color .15s ease;
        }
        #kk-admin .kk-nav a:hover { background: var(--kk-brand-soft); color: var(--kk-brand-ink); }
        #kk-admin .kk-nav a.is-active { background: var(--kk-brand); color: #fff; }
        #kk-admin .kk-nav a .kk-ico { width: 18px; text-align: center; font-size: 15px; }

        #kk-admin .kk-sidebar-foot { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--kk-line); }
        #kk-admin .kk-logout-btn {
            width: 100%; background: var(--kk-danger-soft); color: var(--kk-danger); border: none;
            padding: 10px 12px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 13.5px;
            font-family: inherit; transition: background-color .15s ease;
        }
        #kk-admin .kk-logout-btn:hover { background: #F8DCDA; }

        /* ---------- MAIN ---------- */
        #kk-admin .kk-main { flex: 1; min-width: 0; padding: 28px 32px 56px; }

        #kk-admin .kk-topbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; gap: 16px; flex-wrap: wrap; }
        #kk-admin .kk-topbar h1 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
        #kk-admin .kk-topbar p { margin: 0; color: var(--kk-ink-soft); font-size: 13.5px; }

        #kk-admin .kk-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 700;
            text-decoration: none; border: 1px solid var(--kk-line); background: var(--kk-surface);
            color: var(--kk-ink); cursor: pointer; font-family: inherit; transition: all .15s ease;
        }
        #kk-admin .kk-btn:hover { border-color: var(--kk-brand); color: var(--kk-brand-ink); }
        #kk-admin .kk-btn-primary { background: var(--kk-brand); border-color: var(--kk-brand); color: #fff; }
        #kk-admin .kk-btn-primary:hover { background: #234BDB; color: #fff; }

        /* ---------- ALERTS / BANNERS ---------- */
        #kk-admin .kk-alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; font-weight: 600; }
        #kk-admin .kk-alert-success { background: var(--kk-good-soft); border: 1px solid #B9EBD7; color: #0E6B4E; }
        #kk-admin .kk-alert-error { background: #FDECEA; border: 1px solid #F4C2BE; color: #B71C1C; }
        #kk-admin .kk-alert-error ul { margin: 6px 0 0; padding-left: 18px; font-weight: 500; }

        #kk-admin .kk-banner-warn {
            background: var(--kk-warn-soft); border: 1px solid #F6D9A6; color: var(--kk-warn);
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; margin-bottom: 18px;
        }

        /* ---------- CARDS / FORM ---------- */
        #kk-admin .kk-card {
            background: var(--kk-surface); border: 1px solid var(--kk-line); border-radius: var(--kk-radius);
            padding: 22px; margin-bottom: 20px;
        }
        #kk-admin .kk-card-title {
            font-size: 12px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
            color: var(--kk-brand-ink); background: var(--kk-brand-soft);
            display: inline-block; padding: 4px 10px; border-radius: 6px; margin-bottom: 16px;
        }

        #kk-admin .kk-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }

        #kk-admin .kk-field { display: flex; flex-direction: column; margin-bottom: 14px; }
        #kk-admin .kk-field label {
            font-size: 12px; font-weight: 700; margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: .03em; color: var(--kk-ink-soft);
        }
        #kk-admin .kk-field label small { text-transform: none; font-weight: 500; letter-spacing: 0; }
        #kk-admin .kk-field input,
        #kk-admin .kk-field textarea,
        #kk-admin .kk-field select {
            font-family: inherit; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--kk-line);
            font-size: 14px; background: var(--kk-bg); color: var(--kk-ink);
        }
        #kk-admin .kk-field input:focus,
        #kk-admin .kk-field textarea:focus,
        #kk-admin .kk-field select:focus {
            outline: none; border-color: var(--kk-brand); background: #fff; box-shadow: 0 0 0 3px var(--kk-brand-soft);
        }
        #kk-admin .kk-field textarea { min-height: 76px; resize: vertical; }
        #kk-admin .kk-field small { margin-top: 5px; font-size: 12px; color: var(--kk-ink-soft); font-weight: 500; }
        #kk-admin .kk-field-readonly { background: var(--kk-bg); font-weight: 700; color: var(--kk-ink-soft); }
        #kk-admin .kk-inline-link { display: inline-block; margin-top: 6px; font-size: 12.5px; font-weight: 700; color: var(--kk-brand); text-decoration: none; }
        #kk-admin .kk-inline-link:hover { text-decoration: underline; }

        #kk-admin .kk-submit-wrap { text-align: center; margin-top: 6px; }

        #kk-admin .kk-btn-danger-block {
            width: 100%; border: none; padding: 12px; border-radius: 10px;
            background: var(--kk-danger); color: #fff; font-weight: 700; font-size: 14px;
            cursor: pointer; transition: .2s; font-family: inherit;
        }
        #kk-admin .kk-btn-danger-block:hover { background: #A9271F; }

        /* ---------- DRIVER AKTIF ---------- */
        #kk-admin .kk-driver-box {
            background: var(--kk-bg); border: 1px solid var(--kk-line); border-radius: 12px;
            padding: 14px; margin-bottom: 16px;
        }
        #kk-admin .kk-driver-box .kk-eyebrow { font-size: 11.5px; text-transform: uppercase; font-weight: 800; color: var(--kk-ink-soft); margin-bottom: 6px; letter-spacing: .04em; }
        #kk-admin .kk-driver-box .kk-driver-name { font-size: 16px; font-weight: 800; color: var(--kk-brand-ink); }
        #kk-admin .kk-driver-box .kk-driver-status {
            margin-top: 8px; display: inline-block; background: var(--kk-brand-soft); color: var(--kk-brand-ink);
            padding: 4px 10px; border-radius: 999px; font-size: 12.5px; font-weight: 700;
        }

        /* ---------- TIMELINE ---------- */
        #kk-admin .kk-timeline { display: flex; flex-direction: column; gap: 14px; margin-top: 4px; }
        #kk-admin .kk-timeline-item { border-left: 3px solid var(--kk-brand); padding: 2px 0 2px 14px; }
        #kk-admin .kk-timeline-driver { font-weight: 800; font-size: 14.5px; color: var(--kk-brand-ink); }
        #kk-admin .kk-timeline-status {
            display: inline-block; margin-top: 4px; padding: 4px 10px; border-radius: 999px;
            background: var(--kk-brand-soft); color: var(--kk-brand-ink); font-size: 12.5px; font-weight: 700;
        }
        #kk-admin .kk-timeline-date { margin-top: 6px; font-size: 12.5px; color: var(--kk-ink-soft); }
        #kk-admin .kk-empty-state {
            text-align: center; color: var(--kk-ink-soft); font-size: 13.5px; padding: 22px;
            background: var(--kk-bg); border-radius: 10px;
        }

        /* ---------- FOTO BUKTI ---------- */
        #kk-admin .kk-foto-group { border: 1px solid var(--kk-line); border-radius: 12px; padding: 16px; margin-bottom: 14px; }
        #kk-admin .kk-foto-group:last-child { margin-bottom: 0; }
        #kk-admin .kk-foto-label { font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--kk-ink-soft); margin-bottom: 10px; letter-spacing: .03em; }
        #kk-admin .kk-foto-preview { margin-bottom: 10px; }
        #kk-admin .kk-foto-preview img { width: 100%; max-width: 320px; border-radius: 10px; border: 1px solid var(--kk-line); object-fit: cover; display: block; }
        #kk-admin .kk-foto-empty { font-size: 13px; color: var(--kk-ink-soft); display: block; }

        #kk-admin .kk-btn-hapus-foto {
            background: var(--kk-danger-soft); color: var(--kk-danger); border: none; padding: 7px 14px;
            border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer; font-family: inherit;
            margin-top: 6px; display: inline-block;
        }
        #kk-admin .kk-btn-hapus-foto:hover { background: #F8DCDA; }

        #kk-admin .kk-btn-ambil-foto {
            background: var(--kk-brand); color: #fff; border: none; padding: 10px 18px; border-radius: 9px;
            font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit;
            display: inline-flex; align-items: center; gap: 8px;
        }
        #kk-admin .kk-btn-ambil-foto:hover { background: #234BDB; }
        #kk-admin .kk-foto-uploading { font-size: 12.5px; color: var(--kk-brand); display: none; margin-top: 6px; font-weight: 700; }

        /* ---------- MODAL ---------- */
        #kk-admin .kk-modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(15,18,28,0.5); z-index: 999;
            justify-content: center; align-items: center;
        }
        #kk-admin .kk-modal-overlay.kk-active { display: flex; }
        #kk-admin .kk-modal-box {
            background: var(--kk-surface); border-radius: 18px; padding: 30px 26px; max-width: 380px; width: 90%;
            box-shadow: 0 20px 60px rgba(15,18,28,0.25); text-align: center; animation: kkModalIn .2s ease;
        }
        @keyframes kkModalIn { from { transform: scale(.94); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        #kk-admin .kk-modal-icon { font-size: 38px; margin-bottom: 10px; }
        #kk-admin .kk-modal-title { font-size: 17px; font-weight: 800; color: var(--kk-ink); margin-bottom: 8px; }
        #kk-admin .kk-modal-desc { font-size: 13.5px; color: var(--kk-ink-soft); margin-bottom: 22px; line-height: 1.6; }
        #kk-admin .kk-modal-desc strong { color: var(--kk-ink); }
        #kk-admin .kk-modal-actions { display: flex; gap: 10px; }
        #kk-admin .kk-modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: .15s;
        }
        #kk-admin .kk-modal-cancel { background: var(--kk-bg); color: var(--kk-ink-soft); }
        #kk-admin .kk-modal-cancel:hover { background: var(--kk-line); }
        #kk-admin .kk-modal-confirm-lepas { background: var(--kk-danger); color: #fff; }
        #kk-admin .kk-modal-confirm-lepas:hover { background: #A9271F; }
        #kk-admin .kk-modal-confirm-update { background: var(--kk-brand); color: #fff; }
        #kk-admin .kk-modal-confirm-update:hover { background: #234BDB; }

        @media(max-width: 980px) {
            #kk-admin { flex-direction: column; }
            #kk-admin .kk-sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; }
            #kk-admin .kk-sidebar-foot { margin-top: 12px; padding-top: 12px; }
            #kk-admin .kk-form-grid { grid-template-columns: 1fr; }
        }
    </style>

</head>

<body>
<div id="kk-admin">

    {{-- ========================= --}}
    {{-- MODAL: HAPUS FOTO         --}}
    {{-- ========================= --}}
    <div class="kk-modal-overlay" id="modalHapusFoto">
        <div class="kk-modal-box">
            <div class="kk-modal-icon">🗑️</div>
            <div class="kk-modal-title">Hapus Foto?</div>
            <div class="kk-modal-desc">Foto yang dihapus tidak bisa dikembalikan. Yakin ingin menghapus?</div>
            <div class="kk-modal-actions">
                <button class="kk-modal-cancel" onclick="tutupModal('modalHapusFoto')">✖ Tidak</button>
                <button class="kk-modal-confirm-lepas" onclick="submitForm('modalHapusFoto')">✔ Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MODAL: SIMPAN UPDATE      --}}
    {{-- ========================= --}}
    <div class="kk-modal-overlay" id="modalSimpanUpdate">
        <div class="kk-modal-box">
            <div class="kk-modal-icon">💾</div>
            <div class="kk-modal-title">Simpan Perubahan?</div>
            <div class="kk-modal-desc">
                Perubahan data pesanan <strong>{{ $order->token }}</strong> akan disimpan. Lanjutkan?
            </div>
            <div class="kk-modal-actions">
                <button class="kk-modal-cancel" onclick="tutupModal('modalSimpanUpdate')">✖ Batal</button>
                <button class="kk-modal-confirm-update" onclick="submitForm('modalSimpanUpdate')">✔ Ya, Simpan</button>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MODAL: HAPUS PESANAN      --}}
    {{-- ========================= --}}
    <div class="kk-modal-overlay" id="modalHapusPesanan">
        <div class="kk-modal-box">
            <div class="kk-modal-icon">🗑️</div>
            <div class="kk-modal-title">Hapus Pesanan?</div>
            <div class="kk-modal-desc">
                Pesanan <strong>{{ $order->token }}</strong> beserta seluruh riwayat driver dan foto bukti akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.
            </div>
            <div class="kk-modal-actions">
                <button class="kk-modal-cancel" onclick="tutupModal('modalHapusPesanan')">✖ Batal</button>
                <button class="kk-modal-confirm-lepas" onclick="submitForm('modalHapusPesanan')">✔ Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <aside class="kk-sidebar">
        <div class="kk-brand">
            <div class="kk-brand-mark">KK</div>
            <div class="kk-brand-text">
                <strong>KlinKlin</strong>
                <span>Admin Panel</span>
            </div>
        </div>

        <div class="kk-nav-label">Menu</div>
        <ul class="kk-nav">
            <li><a href="{{ route('admin.dashboard') }}"><span class="kk-ico">&#9632;</span> Dashboard</a></li>
            <li><a href="{{ route('admin.orders') }}" class="is-active"><span class="kk-ico">&#9776;</span> Semua Pesanan</a></li>
            <li><a href="{{ route('admin.promosi.index') }}"><span class="kk-ico">&#9733;</span> Manajemen Promo</a></li>
            <li><a href="{{ route('admin.drivers.index') }}"><span class="kk-ico">&#128663;</span> Manajemen Driver</a></li>
            <li><a href="{{ route('admin.mitra.index') }}"><span class="kk-ico">&#127974;</span> Manajemen Mitra Laundry</a></li>
            <li><a href="{{ route('admin.verifikasi-profile') }}"><span class="kk-ico">&#10003;</span> Verifikasi Profile</a></li>
        </ul>

        <div class="kk-sidebar-foot">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="kk-logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="kk-main">

        <div class="kk-topbar">
            <div>
                <h1>Detail Pesanan</h1>
                <p>Token: <strong>{{ $order->token }}</strong></p>
            </div>
            <a href="{{ route('admin.orders') }}" class="kk-btn">← Kembali</a>
        </div>

        @if(session('success'))
            <div class="kk-alert kk-alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="kk-alert kk-alert-error">❌ {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="kk-alert kk-alert-error">
                ❌ Update gagal, mohon periksa kembali:
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(is_null($order->current_driver_id) && $order->status !== 'Selesai')
        <div class="kk-banner-warn">
            ⚠️ Pesanan ini belum punya driver. Jangan ubah status ke "Selesai" sebelum driver ditugaskan.
        </div>
        @endif

        {{--
            Form "Lepaskan Driver" tetap di LUAR form utama (tidak nested),
            supaya tombolnya tetap berfungsi normal — logika tidak diubah.
        --}}
        @if($order->currentDriver)
        <div class="kk-card">

            <div class="kk-card-title">Driver Aktif</div>

            <div class="kk-driver-box">
                <div class="kk-eyebrow">Driver Aktif</div>
                <div class="kk-driver-name">🚗 {{ $order->currentDriver->name }}</div>
                <div class="kk-driver-status">{{ $order->status }}</div>
            </div>

            <form action="{{ route('admin.orders.nullifyDriver', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="kk-btn-danger-block">
                    ❌ Lepaskan Driver dari Pesanan
                </button>
            </form>

        </div>
        @endif

        {{-- FORM UTAMA UPDATE PESANAN --}}
        <form id="formUpdatePesanan" method="POST"
          action="{{ route('admin.orders.update', $order->id) }}"
          enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="kk-form-grid">

                {{-- LEFT --}}
                <div class="kk-card">

                    <div class="kk-card-title">Customer</div>

                    <div class="kk-field">
                        <label>Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $order->nama) }}">
                    </div>

                    <div class="kk-field">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $order->phone) }}">
                    </div>

                    <div class="kk-field">
                        <label>Alamat Customer</label>
                        <textarea name="alamat_customer">{{ old('alamat_customer', $order->alamat_customer) }}</textarea>
                    </div>

                    <div class="kk-card-title" style="margin-top:4px;">Laundry</div>

                    <div class="kk-field">
                        <label>Pilih Mitra Laundry <small>(opsional)</small></label>
                        <select id="pilihMitraLaundry" onchange="pilihMitra(this)">
                            <option value="">-- Ketik manual / bukan mitra --</option>
                            @foreach($mitrasAktif as $mitra)
                                <option value="{{ $mitra->id }}"
                                    data-alamat="{{ $mitra->alamat }}"
                                    data-phone="{{ $mitra->phone }}"
                                    {{ old('mitra_laundry_id', $order->mitra_laundry_id) == $mitra->id ? 'selected' : '' }}>
                                    {{ $mitra->nama_laundry }}
                                </option>
                            @endforeach
                        </select>
                        <small>Pilih mitra untuk isi otomatis alamat & phone. Atau biarkan kosong untuk isi manual (laundry non-mitra).</small>
                    </div>

                    <input type="hidden" name="mitra_laundry_id" id="mitraLaundryId"
                        value="{{ old('mitra_laundry_id', $order->mitra_laundry_id) }}">

                    <div class="kk-field">
                        <label>Alamat Laundry</label>
                        <textarea name="alamat_laundry" id="alamatLaundry">{{ old('alamat_laundry', $order->alamat_laundry) }}</textarea>
                    </div>

                    <div class="kk-field">
                        <label>Phone Laundry</label>
                        <input type="text" name="phone_laundry" id="phoneLaundry"
                            value="{{ old('phone_laundry', $order->phone_laundry) }}">
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="kk-card">

                    <div class="kk-card-title">Order</div>

                    <div class="kk-field">
                        <label>Token</label>
                        <input type="text" value="{{ $order->token }}" class="kk-field-readonly" readonly>
                    </div>

                    <div class="kk-field">
                        <label>Status</label>
                        <select name="status">
                            @php $statusValue = old('status', $order->status); @endphp
                            <option value="Unconfirmed"    {{ $statusValue == 'Unconfirmed'    ? 'selected' : '' }}>Unconfirmed</option>
                            <option value="Diproses"        {{ $statusValue == 'Diproses'        ? 'selected' : '' }}>Diproses</option>
                            <option value="Dijemput"        {{ $statusValue == 'Dijemput'        ? 'selected' : '' }}>Dijemput</option>
                            <option value="Mencari Laundry" {{ $statusValue == 'Mencari Laundry' ? 'selected' : '' }}>Mencari Laundry</option>
                            <option value="Dicuci"          {{ $statusValue == 'Dicuci'          ? 'selected' : '' }}>Dicuci</option>
                            <option value="Diantar"         {{ $statusValue == 'Diantar'         ? 'selected' : '' }}>Diantar</option>
                            <option value="Selesai"         {{ $statusValue == 'Selesai'         ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="kk-field">
                        <label>Tipe Antar Jemput</label>
                        <select name="tipe_antar_jemput">
                            @php $tipeValue = old('tipe_antar_jemput', $order->tipe_antar_jemput); @endphp
                            <option value="Antar Saja"        {{ $tipeValue == 'Antar Saja'        ? 'selected' : '' }}>Antar Saja</option>
                            <option value="Jemput Saja"       {{ $tipeValue == 'Jemput Saja'       ? 'selected' : '' }}>Jemput Saja</option>
                            <option value="Antar Jemput (PP)" {{ $tipeValue == 'Antar Jemput (PP)' ? 'selected' : '' }}>Antar Jemput (PP)</option>
                        </select>
                    </div>

                    <div class="kk-field">
                        <label>Jenis Layanan</label>
                        <input type="text" name="jenis_layanan" value="{{ old('jenis_layanan', $order->jenis_layanan) }}">
                    </div>

                    <div class="kk-field">
                        <label>Berat Laundry (kg)</label>
                        <input type="text" name="estimasi_jumlah_laundry" value="{{ old('estimasi_jumlah_laundry', $order->estimasi_jumlah_laundry) }}">
                    </div>

                    <div class="kk-field">
                        <label>Estimasi Waktu Pengerjaan</label>
                        <input type="text" name="estimasi_waktu_pengerjaan"
                            value="{{ old('estimasi_waktu_pengerjaan', $order->estimasi_waktu_pengerjaan) }}"
                            placeholder="Contoh: 1-2 hari">
                    </div>

                    <div class="kk-field">
                        <label>Menggunakan Jasa Pemilahan Pakaian</label>
                        <select name="is_sorted">
                            @php $isSorted = old('is_sorted', $order->is_sorted); @endphp
                            <option value="0" {{ !$isSorted ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ $isSorted  ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>

                    <div class="kk-field">
                        <label>Catatan</label>
                        <textarea name="note">{{ old('note', $order->note) }}</textarea>
                    </div>

                    <div class="kk-field">
                        <label>Tanggal Penjemputan</label>
                        <input
                            type="text"
                            id="tanggal_penjemputan"
                            name="tanggal_penjemputan"
                            value="{{ old('tanggal_penjemputan', $order->tanggal_penjemputan ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->format('Y-m-d H:i') : '') }}"
                            placeholder="Pilih tanggal dan jam">
                    </div>

                   <div class="kk-field">
                        <label>Promo <small>(opsional)</small></label>
                        <select id="pilihPromo" onchange="hitungUlangFee()">
                            <option value="">-- Tidak pakai promo --</option>
                            @foreach($promosAktif as $promo)
                                <option value="{{ $promo->id }}"
                                    data-harga-awal="{{ $promo->harga_awal }}"
                                    data-harga-promo="{{ $promo->harga_promo }}"
                                    {{ old('promo_id', $order->promo_id) == $promo->id ? 'selected' : '' }}>
                                    {{ $promo->nama_promo }} (Diskon Rp {{ number_format($promo->harga_awal - $promo->harga_promo) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="promo_id" id="promoId" value="{{ old('promo_id', $order->promo_id) }}">

                    {{-- ✅ Fee dasar sebelum diskon promo — ini yang jadi sumber input admin,
                        bukan fee final. Fee final dihitung ulang otomatis oleh backend
                        setiap kali disimpan, supaya diskon tidak terpotong berkali-kali. --}}
                    <div class="kk-field">
                        <label>Fee Jasa Ongkir <small>(sebelum diskon promo)</small></label>
                        <input type="number" id="feeDasar" name="fee_sebelum_diskon"
                            value="{{ old('fee_sebelum_diskon', $order->fee_sebelum_diskon ?? $order->fee) }}"
                            oninput="hitungUlangFee()">
                        <small id="infoDiskon" style="color:#17A673; font-weight:700;"></small>
                    </div>

                    {{-- ✅ Tampilan fee final read-only, biar admin tetap lihat hasil akhirnya
                        tanpa bisa mengedit langsung (mencegah admin override manual yang
                        bisa bikin data tidak sinkron dengan fee_sebelum_diskon + promo). --}}
                    <div class="kk-field">
                        <label>Fee Final <small>(setelah diskon, otomatis)</small></label>
                        <input type="text" value="Rp {{ number_format($order->fee) }}" class="kk-field-readonly" readonly>
                        <small>Dihitung ulang otomatis saat disimpan, berdasarkan Fee Jasa Ongkir & Promo di atas.</small>
                    </div>

                    <div class="kk-field">
                        <label>Fee Laundry</label>
                        <input type="number" name="fee_laundry"
                            value="{{ old('fee_laundry', $order->fee_laundry) }}"
                            placeholder="Contoh: 25000">
                    </div>

                    <div class="kk-field">
                        <label>Dokumentasi</label>
                        <input type="text" name="dokumentasi_pakaian"
                            value="{{ old('dokumentasi_pakaian', $order->dokumentasi_pakaian) }}"
                            placeholder="Masukkan link dokumentasi">

                        @if($order->dokumentasi_pakaian)
                            <a href="{{ $order->dokumentasi_pakaian }}" target="_blank" class="kk-inline-link">
                                Lihat Dokumentasi
                            </a>
                        @endif
                    </div>

                </div>

            </div>

            {{-- SUBMIT --}}
            <div class="kk-submit-wrap">
                <button type="button" class="kk-btn kk-btn-primary"
                        onclick="bukaModalSimpanUpdate('formUpdatePesanan')">
                    Simpan Update
                </button>
            </div>

        </form>
        <br>
        {{-- ========================= --}}
        {{-- FOTO BUKTI (ADMIN: FULL AKSES, TANPA RESTRIKSI) --}}
        {{-- ========================= --}}
        @php
            $fotoPengambilan = $order->photos->where('type', 'pengambilan')->first();
            $fotoNota        = $order->photos->where('type', 'nota')->first();
            $fotoPengiriman  = $order->photos->where('type', 'pengiriman')->first();
        @endphp

        <div class="kk-card">
            <div class="kk-card-title">📷 Foto Bukti</div>

            {{-- BUKTI PENGAMBILAN --}}
            <div class="kk-foto-group">
                <div class="kk-foto-label">Bukti Pengambilan Baju</div>
                @if($fotoPengambilan)
                    <div class="kk-foto-preview">
                        <a href="{{ $fotoPengambilan->url }}" target="_blank">
                            <img src="{{ $fotoPengambilan->url }}" alt="Bukti Pengambilan">
                        </a>
                    </div>
                    <form id="formHapusFoto{{ $fotoPengambilan->id }}"
                          action="{{ route('admin.foto.delete', $fotoPengambilan->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="kk-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengambilan->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="kk-foto-empty">Belum ada foto</small>
                    <div style="margin-top:10px;">
                        <form id="formUploadPengambilan"
                              action="{{ route('admin.foto.pengambilan', $order->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="foto" id="inputAdminPengambilan"
                                   accept="image/*" style="display:none;"
                                   onchange="autoUploadAdmin(this, 'formUploadPengambilan', 'uploadingAdminPengambilan')">
                        </form>
                        <button type="button" class="kk-btn-ambil-foto"
                                onclick="document.getElementById('inputAdminPengambilan').click()">
                            ⬆️ Upload Foto
                        </button>
                        <span class="kk-foto-uploading" id="uploadingAdminPengambilan">⏳ Mengupload foto...</span>
                    </div>
                @endif
            </div>

            {{-- BUKTI NOTA --}}
            <div class="kk-foto-group">
                <div class="kk-foto-label">Bukti Nota Laundry</div>
                @if($fotoNota)
                    <div class="kk-foto-preview">
                        <a href="{{ $fotoNota->url }}" target="_blank">
                            <img src="{{ $fotoNota->url }}" alt="Bukti Nota">
                        </a>
                    </div>
                    <form id="formHapusFoto{{ $fotoNota->id }}"
                          action="{{ route('admin.foto.delete', $fotoNota->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="kk-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoNota->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="kk-foto-empty">Belum ada foto</small>
                    <div style="margin-top:10px;">
                        <form id="formUploadNota"
                              action="{{ route('admin.foto.nota', $order->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="foto" id="inputAdminNota"
                                   accept="image/*" style="display:none;"
                                   onchange="autoUploadAdmin(this, 'formUploadNota', 'uploadingAdminNota')">
                        </form>
                        <button type="button" class="kk-btn-ambil-foto"
                                onclick="document.getElementById('inputAdminNota').click()">
                            ⬆️ Upload Foto
                        </button>
                        <span class="kk-foto-uploading" id="uploadingAdminNota">⏳ Mengupload foto...</span>
                    </div>
                @endif
            </div>

            {{-- BUKTI PENGIRIMAN --}}
            <div class="kk-foto-group">
                <div class="kk-foto-label">Bukti Pengiriman Baju</div>
                @if($fotoPengiriman)
                    <div class="kk-foto-preview">
                        <a href="{{ $fotoPengiriman->url }}" target="_blank">
                            <img src="{{ $fotoPengiriman->url }}" alt="Bukti Pengiriman">
                        </a>
                    </div>
                    <form id="formHapusFoto{{ $fotoPengiriman->id }}"
                          action="{{ route('admin.foto.delete', $fotoPengiriman->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="kk-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengiriman->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="kk-foto-empty">Belum ada foto</small>
                    <div style="margin-top:10px;">
                        <form id="formUploadPengiriman"
                              action="{{ route('admin.foto.pengiriman', $order->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="foto" id="inputAdminPengiriman"
                                   accept="image/*" style="display:none;"
                                   onchange="autoUploadAdmin(this, 'formUploadPengiriman', 'uploadingAdminPengiriman')">
                        </form>
                        <button type="button" class="kk-btn-ambil-foto"
                                onclick="document.getElementById('inputAdminPengiriman').click()">
                            ⬆️ Upload Foto
                        </button>
                        <span class="kk-foto-uploading" id="uploadingAdminPengiriman">⏳ Mengupload foto...</span>
                    </div>
                @endif
            </div>

        </div>

        {{-- RIWAYAT DRIVER --}}
        <div class="kk-card">

            <div class="kk-card-title">Riwayat Driver</div>

            @if($order->driverLogs->isEmpty())
                <div class="kk-empty-state">
                    Belum ada driver yang mengambil pesanan ini.
                </div>
            @else
                <div class="kk-timeline">
                    @foreach($order->driverLogs->sortByDesc('taken_at') as $log)
                    <div class="kk-timeline-item">
                        <div class="kk-timeline-driver">
                            🚗 {{ $log->driver->name ?? 'Driver Tidak Diketahui' }}
                        </div>
                        <div class="kk-timeline-status">
                            {{ $log->status }}
                        </div>
                        <div class="kk-timeline-date">
                            {{ \Carbon\Carbon::parse($log->taken_at)->translatedFormat('d F Y - H:i') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- FORM HAPUS PESANAN (tersembunyi, di luar form update) --}}
        <form id="formHapusPesanan"
            action="{{ route('admin.orders.destroy', $order->id) }}"
            method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <div class="kk-card">
            <div class="kk-card-title">Zona Berbahaya</div>
            <button type="button" class="kk-btn-danger-block"
                    onclick="bukaModalHapusPesanan('formHapusPesanan')">
                🗑️ Hapus Pesanan Ini
            </button>
        </div>

    </main>
</div>

<script>
    let targetFormId = null;

    function autoUploadAdmin(input, formId, uploadingId) {
        if (input.files && input.files[0]) {
            document.getElementById(uploadingId).style.display = 'block';
            document.getElementById(formId).submit();
        }
    }

    function bukaModalHapusFoto(formId) {
        targetFormId = formId;
        document.getElementById('modalHapusFoto').classList.add('kk-active');
    }

    function bukaModalSimpanUpdate(formId) {
        targetFormId = formId;
        document.getElementById('modalSimpanUpdate').classList.add('kk-active');
    }

    function bukaModalHapusPesanan(formId) {
        targetFormId = formId;
        document.getElementById('modalHapusPesanan').classList.add('kk-active');
    }

    function tutupModal(modalId) {
        document.getElementById(modalId).classList.remove('kk-active');
        targetFormId = null;
    }

    function submitForm(modalId) {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
        tutupModal(modalId);
    }

    function pilihMitra(select) 
    {
    var opt = select.options[select.selectedIndex];
    var mitraIdField = document.getElementById('mitraLaundryId');
    var alamatField = document.getElementById('alamatLaundry');
    var phoneField = document.getElementById('phoneLaundry');

    if (opt.value === '') {
        // "-- Ketik manual / bukan mitra --" dipilih: kosongkan id mitra,
        // biarkan alamat & phone tetap bisa diisi manual oleh admin
        mitraIdField.value = '';
        return;
    }

    mitraIdField.value = opt.value;
    alamatField.value = opt.getAttribute('data-alamat') || '';
    phoneField.value = opt.getAttribute('data-phone') || '';
    }

    var feeAsliSebelumPromo = null; // simpan fee asli sebelum promo diterapkan

    function hitungUlangFee() {
        var select = document.getElementById('pilihPromo');
        var opt = select.options[select.selectedIndex];
        var promoIdField = document.getElementById('promoId');
        var feeDasarField = document.getElementById('feeDasar');
        var infoDiskon = document.getElementById('infoDiskon');

        var feeDasar = parseFloat(feeDasarField.value) || 0;

        if (opt.value === '') {
            promoIdField.value = '';
            infoDiskon.textContent = '';
            return; // fee dasar dipakai apa adanya, tanpa potongan
        }

        promoIdField.value = opt.value;

        var hargaAwal = parseFloat(opt.getAttribute('data-harga-awal')) || 0;
        var hargaPromo = parseFloat(opt.getAttribute('data-harga-promo')) || 0;
        var diskon = hargaAwal - hargaPromo;

        var feeFinal = feeDasar - diskon;
        if (feeFinal < 0) feeFinal = 0;

        infoDiskon.textContent = 'Diskon Rp ' + diskon.toLocaleString('id-ID') + ' → Fee final: Rp ' + feeFinal.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', hitungUlangFee);

    document.querySelectorAll('.kk-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('kk-active');
                targetFormId = null;
            }
        });
    });
</script>

</body>
</html>