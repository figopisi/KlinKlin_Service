<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <style>
        /*
         * Semua style di bawah ini di-scope di dalam #kk-admin agar tidak
         * bentrok dengan class generik (.card, .container, dst) yang mungkin
         * sudah dipakai di buat_pesanan.css / style.css. Kalau nanti dua
         * stylesheet itu ternyata juga pakai selector #kk-admin (kecil
         * kemungkinannya), tinggal ganti prefix ini.
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
            --kk-good: #17A673;
            --kk-good-soft: #E7F9F1;
            --kk-warn: #E5A400;
            --kk-radius: 14px;

            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--kk-bg);
            color: var(--kk-ink);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        #kk-admin .kk-mono { font-family: 'JetBrains Mono', monospace; }

        /* ---------- SIDEBAR ---------- */
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

        #kk-admin .kk-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px 24px;
        }

        #kk-admin .kk-brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--kk-brand), #6E8CFF);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
        }

        #kk-admin .kk-brand-text { line-height: 1.2; }
        #kk-admin .kk-brand-text strong { display: block; font-size: 15px; font-weight: 800; }
        #kk-admin .kk-brand-text span { font-size: 11.5px; color: var(--kk-ink-soft); }

        #kk-admin .kk-nav-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--kk-ink-soft);
            padding: 10px 10px 6px;
        }

        #kk-admin .kk-nav { list-style: none; margin: 0 0 8px; padding: 0; display: flex; flex-direction: column; gap: 2px; }

        #kk-admin .kk-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--kk-ink);
            font-size: 14px;
            font-weight: 600;
            transition: background .15s ease, color .15s ease;
        }

        #kk-admin .kk-nav a:hover { background: var(--kk-brand-soft); color: var(--kk-brand-ink); }
        #kk-admin .kk-nav a.is-active { background: var(--kk-brand); color: #fff; }
        #kk-admin .kk-nav a .kk-ico { width: 18px; text-align: center; font-size: 15px; }

        #kk-admin .kk-sidebar-foot { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--kk-line); }

        #kk-admin .kk-logout-btn {
            width: 100%;
            background: #FCEEEE;
            color: #C4342B;
            border: none;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 13.5px;
            font-family: inherit;
            transition: background-color .15s ease;
        }
        #kk-admin .kk-logout-btn:hover { background: #F8DCDA; }

        /* ---------- MAIN ---------- */
        #kk-admin .kk-main { flex: 1; min-width: 0; padding: 28px 32px 48px; }

        #kk-admin .kk-topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            gap: 16px;
        }
        #kk-admin .kk-topbar h1 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
        #kk-admin .kk-topbar p { margin: 0; color: var(--kk-ink-soft); font-size: 13.5px; }

        #kk-admin .kk-alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13.5px;
        }
        #kk-admin .kk-alert-success { background: var(--kk-good-soft); border: 1px solid #B9EBD7; color: #0E6B4E; }
        #kk-admin .kk-alert-error { background: #FDECEA; border: 1px solid #F4C2BE; color: #B71C1C; }
        #kk-admin .kk-alert-error strong { display: block; margin-bottom: 4px; }
        #kk-admin .kk-alert-error ul { margin: 4px 0 0 18px; padding: 0; }

        /* ---------- STAT / REVENUE CARDS ---------- */
        #kk-admin .kk-stat-row {
            display: grid;
            grid-template-columns: minmax(0,1fr) minmax(0,1.6fr);
            gap: 16px;
            margin-bottom: 24px;
            align-items: stretch;
        }

        #kk-admin .kk-card {
            background: var(--kk-surface);
            border: 1px solid var(--kk-line);
            border-radius: var(--kk-radius);
            padding: 20px;
        }

        #kk-admin .kk-stack { display: flex; flex-direction: column; gap: 16px; }

        #kk-admin .kk-stat-label {
            font-size: 12.5px;
            color: var(--kk-ink-soft);
            font-weight: 600;
            margin: 0 0 8px;
        }
        #kk-admin .kk-stat-value { font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -0.02em; }
        #kk-admin .kk-stat-value.kk-currency::before { content: 'Rp '; font-size: 15px; font-weight: 700; color: var(--kk-ink-soft); }

        /* Revenue breakdown card */
        #kk-admin .kk-revenue-card { display: flex; flex-direction: column; }
        #kk-admin .kk-revenue-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px; }
        #kk-admin .kk-revenue-head .kk-stat-label { margin: 0; }
        #kk-admin .kk-revenue-total { font-size: 26px; font-weight: 800; letter-spacing: -0.02em; }

        #kk-admin .kk-breakdown { margin-top: 14px; display: flex; flex-direction: column; gap: 12px; }

        #kk-admin .kk-breakdown-row { display: flex; flex-direction: column; gap: 5px; }
        #kk-admin .kk-breakdown-top { display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        #kk-admin .kk-breakdown-name { display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--kk-ink); }
        #kk-admin .kk-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
        #kk-admin .kk-dot-driver { background: var(--kk-brand); }
        #kk-admin .kk-dot-mitra { background: var(--kk-warn); }
        #kk-admin .kk-breakdown-value { font-weight: 700; }
        #kk-admin .kk-breakdown-value small { font-weight: 500; color: var(--kk-ink-soft); margin-left: 4px; }

        #kk-admin .kk-bar { height: 6px; border-radius: 4px; background: var(--kk-bg); overflow: hidden; }
        #kk-admin .kk-bar-fill { height: 100%; border-radius: 4px; }
        #kk-admin .kk-bar-fill.kk-driver { background: var(--kk-brand); }
        #kk-admin .kk-bar-fill.kk-mitra { background: var(--kk-warn); }

        /* ---------- FORM SECTION ---------- */
        #kk-admin .kk-step-card {
            background: var(--kk-surface);
            border: 1px solid var(--kk-line);
            border-radius: var(--kk-radius);
            padding: 24px;
        }
        #kk-admin .kk-step-card > h3 { margin: 0 0 18px; font-size: 16px; font-weight: 800; }

        #kk-admin .kk-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 32px; }
        #kk-admin .kk-section-title {
            font-size: 12px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
            color: var(--kk-brand-ink); background: var(--kk-brand-soft);
            display: inline-block; padding: 4px 10px; border-radius: 6px; margin-bottom: 12px;
        }
        #kk-admin .kk-detail-item { margin-bottom: 14px; display: flex; flex-direction: column; gap: 6px; }
        #kk-admin .kk-detail-item strong { font-size: 13px; font-weight: 600; color: var(--kk-ink); }
        #kk-admin .kk-detail-item input[type="text"],
        #kk-admin .kk-detail-item input[type="number"],
        #kk-admin .kk-detail-item input[type="datetime-local"],
        #kk-admin .kk-detail-item select,
        #kk-admin .kk-detail-item textarea {
            font-family: inherit;
            font-size: 13.5px;
            border: 1px solid var(--kk-line);
            border-radius: 9px;
            padding: 9px 11px;
            background: var(--kk-bg);
            color: var(--kk-ink);
        }
        #kk-admin .kk-detail-item input:focus,
        #kk-admin .kk-detail-item select:focus,
        #kk-admin .kk-detail-item textarea:focus {
            outline: none;
            border-color: var(--kk-brand);
            background: #fff;
            box-shadow: 0 0 0 3px var(--kk-brand-soft);
        }
        #kk-admin .kk-detail-item textarea { min-height: 64px; resize: vertical; }
        #kk-admin .kk-tipe-keterangan { font-size: 12.5px; color: var(--kk-ink-soft); line-height: 1.5; }

        #kk-admin .kk-submit-row { text-align: center; margin-top: 22px; }
        #kk-admin .kk-btn-primary {
            background: var(--kk-brand);
            color: #fff;
            border: none;
            padding: 11px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            font-family: inherit;
            cursor: pointer;
            transition: background-color .15s ease;
        }
        #kk-admin .kk-btn-primary:hover { background: #234BDB; }

        @media (max-width: 980px) {
            #kk-admin { flex-direction: column; }
            #kk-admin .kk-sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; }
            #kk-admin .kk-sidebar-foot { margin-top: 12px; padding-top: 12px; }
            #kk-admin .kk-stat-row { grid-template-columns: 1fr; }
            #kk-admin .kk-form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div id="kk-admin">

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
            <li><a href="{{ url()->current() }}" class="is-active"><span class="kk-ico">&#9632;</span> Dashboard</a></li>
            <li><a href="/admin/orders"><span class="kk-ico">&#9776;</span> Semua Pesanan</a></li>
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
                <h1>Dashboard</h1>
                <p>Ringkasan performa dan pembuatan pesanan baru.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="kk-alert kk-alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="kk-alert kk-alert-error">
                <strong>Pesanan gagal disimpan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            // Dipakai untuk lebar bar proporsional pada rincian pendapatan.
            $kkBersih = $pendapatanBersih ?? 0;
            $kkDriver = $pendapatanDariDriver ?? 0;
            $kkMitra  = $pendapatanDariMitra ?? 0;
            $kkTotalBreakdown = max($kkBersih, 1);
            $kkDriverPct = round($kkDriver / $kkTotalBreakdown * 100);
            $kkMitraPct  = round($kkMitra  / $kkTotalBreakdown * 100);
        @endphp

        <div class="kk-stat-row">

            <!-- LEFT: Total Pesanan + Total Pemasukan Kotor -->
            <div class="kk-stack">
                <div class="kk-card">
                    <p class="kk-stat-label">Total Pesanan</p>
                    <p class="kk-stat-value">{{ $totalPesanan }}</p>
                </div>
                <div class="kk-card">
                    <p class="kk-stat-label">Total Pemasukan (Kotor)</p>
                    <p class="kk-stat-value kk-currency">{{ number_format($totalPemasukan) }}</p>
                </div>
            </div>

            <!-- RIGHT: Rincian Pendapatan (bersih + share driver + share mitra jadi satu card) -->
            <div class="kk-card kk-revenue-card">
                <div class="kk-revenue-head">
                    <p class="kk-stat-label">Pendapatan Bersih KlinKlin</p>
                </div>
                <div class="kk-revenue-total kk-currency">{{ number_format($kkBersih) }}</div>

                <div class="kk-breakdown">
                    <div class="kk-breakdown-row">
                        <div class="kk-breakdown-top">
                            <span class="kk-breakdown-name"><span class="kk-dot kk-dot-driver"></span>Bagian Driver (20%)</span>
                            <span class="kk-breakdown-value kk-mono">Rp {{ number_format($kkDriver) }}<small>{{ $kkDriverPct }}%</small></span>
                        </div>
                        <div class="kk-bar"><div class="kk-bar-fill kk-driver" style="width: {{ $kkDriverPct }}%;"></div></div>
                    </div>

                    <div class="kk-breakdown-row">
                        <div class="kk-breakdown-top">
                            <span class="kk-breakdown-name"><span class="kk-dot kk-dot-mitra"></span>Bagian Mitra Laundry</span>
                            <span class="kk-breakdown-value kk-mono">Rp {{ number_format($kkMitra) }}<small>{{ $kkMitraPct }}%</small></span>
                        </div>
                        <div class="kk-bar"><div class="kk-bar-fill kk-mitra" style="width: {{ $kkMitraPct }}%;"></div></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FORM: BUAT PESANAN -->
        <div class="kk-step-card">
            <h3>Buat Pesanan</h3>

            <form method="POST" action="{{ route('buat-pesanan.store') }}" id="adminOrderForm">
                @csrf

                <div class="kk-form-grid">

                    <!-- LEFT -->
                    <div>
                        <div class="kk-section-title">Customer</div>

                        <div class="kk-detail-item">
                            <strong>Nama Customer</strong>
                            <input type="text" name="nama" value="{{ old('nama') }}" required>
                        </div>

                        <div class="kk-detail-item">
                            <strong>No HP Customer</strong>
                            <input type="text" name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Alamat Customer</strong>
                            <textarea name="alamat_customer" required>{{ old('alamat_customer') }}</textarea>
                        </div>

                        <div class="kk-section-title" style="margin-top:8px;">Laundry</div>

                        <div class="kk-detail-item">
                            <strong>Alamat Laundry</strong>
                            <textarea name="alamat_laundry">{{ old('alamat_laundry') }}</textarea>
                            <span class="kk-tipe-keterangan">Kosongkan jika diserahkan ke KlinKlin.</span>
                        </div>

                        <div class="kk-detail-item">
                            <strong>No HP Laundry</strong>
                            <input type="text" name="phone_laundry" value="{{ old('phone_laundry') }}">
                        </div>

                        <div class="kk-section-title" style="margin-top:8px;">Jadwal &amp; Layanan</div>

                        <div class="kk-detail-item">
                            <strong>Tanggal &amp; Jam Penjemputan</strong>
                            <input
                                type="datetime-local"
                                id="tanggal_penjemputan_local"
                                required
                                onchange="document.getElementById('tanggal_penjemputan_hidden').value = this.value.replace('T', ' ')"
                            >
                            <input
                                type="hidden"
                                id="tanggal_penjemputan_hidden"
                                name="tanggal_penjemputan"
                                value="{{ old('tanggal_penjemputan') }}"
                            >
                        </div>

                        <div class="kk-detail-item">
                            <strong>Tipe Antar Jemput</strong>
                            <select name="tipe_antar_jemput" id="tipe_antar_jemput" required>
                                <option value="">Pilih tipe</option>
                                <option value="Antar Jemput (PP)" @selected(old('tipe_antar_jemput') === 'Antar Jemput (PP)')>Antar Jemput (PP)</option>
                                <option value="Antar Saja" @selected(old('tipe_antar_jemput') === 'Antar Saja')>Antar Saja</option>
                                <option value="Jemput Saja" @selected(old('tipe_antar_jemput') === 'Jemput Saja')>Jemput Saja</option>
                            </select>
                            <span class="kk-tipe-keterangan" id="tipe_keterangan">
                                Pilih salah satu untuk melihat penjelasannya.
                            </span>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Jenis Layanan</strong>
                            <select name="jenis_layanan" required>
                                <option value="">Pilih layanan</option>
                                <option @selected(old('jenis_layanan') === 'Cuci + Setrika')>Cuci + Setrika</option>
                                <option @selected(old('jenis_layanan') === 'Cuci Kering')>Cuci Kering</option>
                                <option @selected(old('jenis_layanan') === 'Setrika Saja')>Setrika Saja</option>
                                <option @selected(old('jenis_layanan') === 'Cuci Sepatu')>Cuci Sepatu</option>
                                <option @selected(old('jenis_layanan') === 'Bed Cover / Selimut')>Bed Cover / Selimut</option>
                            </select>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Berat Laundry (kg)</strong>
                            <input type="text" name="estimasi_jumlah_laundry" value="{{ old('estimasi_jumlah_laundry') }}" placeholder="mis. 5 kg">
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div>
                        <div class="kk-section-title">Operasional</div>

                        <div class="kk-detail-item">
                            <strong>Fee (jasa Ongkir)</strong>
                            <input type="number" name="fee" value="{{ old('fee') }}" required>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Pemilahan Pakaian</strong>
                            <select name="is_sorted">
                                <option value="0" @selected(old('is_sorted') === '0' || old('is_sorted') === null)>Tidak</option>
                                <option value="1" @selected(old('is_sorted') === '1')>Ya (+ biaya tambahan)</option>
                            </select>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Catatan</strong>
                            <textarea name="note">{{ old('note') }}</textarea>
                        </div>

                        <div class="kk-section-title" style="margin-top:8px;">Status</div>

                        <div class="kk-detail-item">
                            <strong>Status</strong>
                            <select name="status" id="status_select">
                                <option value="Unconfirmed" @selected(old('status') === 'Unconfirmed')>Unconfirmed</option>
                                <option value="Diproses" @selected(old('status') === 'Diproses' || old('status') === null)>Diproses</option>
                                <option value="Dijemput" @selected(old('status') === 'Dijemput')>Dijemput</option>
                                <option value="Mencari Laundry" @selected(old('status') === 'Mencari Laundry')>Mencari Laundry</option>
                                <option value="Dicuci" @selected(old('status') === 'Dicuci')>Dicuci</option>
                                <option value="Diantar" @selected(old('status') === 'Diantar')>Diantar</option>
                                <option value="Selesai" @selected(old('status') === 'Selesai')>Selesai</option>
                            </select>
                            <span class="kk-tipe-keterangan">
                                Untuk tipe "Jemput Saja", status akan otomatis diarahkan ke <strong>Dicuci</strong> oleh sistem, apa pun yang dipilih di sini.
                            </span>
                        </div>

                        <div class="kk-detail-item">
                            <strong>Dokumentasi</strong>
                            <input type="text" name="dokumentasi_pakaian" value="{{ old('dokumentasi_pakaian') }}" placeholder="Link dokumentasi">
                        </div>
                    </div>

                </div>

                <div class="kk-submit-row">
                    <button class="kk-btn-primary" type="submit">Simpan Pesanan</button>
                </div>
            </form>
        </div>

    </main>
</div>

<script>
document.getElementById('adminOrderForm').addEventListener('submit', function (e) {
    var localInput = document.getElementById('tanggal_penjemputan_local');
    var hidden = document.getElementById('tanggal_penjemputan_hidden');

    if (localInput.value && !hidden.value) {
        hidden.value = localInput.value.replace('T', ' ');
    }

    if (!hidden.value) {
        e.preventDefault();
        alert('Tanggal & jam penjemputan wajib diisi.');
        localInput.focus();
    }
});

var tipeKeteranganMap = {
    'Antar Jemput (PP)': 'Alur lengkap: driver jemput dari customer → antar ke laundry → jemput dari laundry → antar ke customer.',
    'Antar Saja': 'Driver hanya mengantar baju ke laundry. Pesanan otomatis selesai setelah bukti nota diupload.',
    'Jemput Saja': 'Driver hanya mengambil baju dari laundry dan mengantarnya ke customer. Tidak ada tahap penjemputan dari customer. Status akan otomatis menjadi "Dicuci" saat disimpan.'
};

document.getElementById('tipe_antar_jemput').addEventListener('change', function () {
    var keteranganEl = document.getElementById('tipe_keterangan');
    keteranganEl.textContent = tipeKeteranganMap[this.value] || 'Pilih salah satu untuk melihat penjelasannya.';
});

window.addEventListener('DOMContentLoaded', function () {
    var tipeEl = document.getElementById('tipe_antar_jemput');
    if (tipeEl.value) {
        tipeEl.dispatchEvent(new Event('change'));
    }
});
</script>

</body>
</html>