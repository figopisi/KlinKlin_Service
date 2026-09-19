<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mitra Laundry</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <style>
        /*
         * Sama seperti halaman admin lain: semua di-scope di dalam #kk-admin,
         * semua class baru pakai prefix kk- supaya tidak bentrok dengan
         * style.css yang sudah ada. IDs dibiarkan sama persis karena
         * dipakai oleh JS di bawah — jangan diubah.
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

        /* ---------- SIDEBAR (identik dengan dashboard) ---------- */
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
        #kk-admin .kk-main { flex: 1; min-width: 0; padding: 28px 32px 48px; }

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

        #kk-admin .kk-alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; font-weight: 600; }
        #kk-admin .kk-alert-success { background: var(--kk-good-soft); border: 1px solid #B9EBD7; color: #0E6B4E; }
        #kk-admin .kk-alert-error { background: #FDECEA; border: 1px solid #F4C2BE; color: #B71C1C; }
        #kk-admin .kk-alert-error ul { margin: 6px 0 0; padding-left: 18px; font-weight: 500; }

        /* ---------- TOP ACTIONS ---------- */
        #kk-admin .kk-top-actions {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            margin-bottom: 20px; flex-wrap: wrap;
        }
        #kk-admin .kk-search-input {
            font-family: inherit; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--kk-line);
            font-size: 13.5px; min-width: 220px; background: var(--kk-surface); color: var(--kk-ink);
        }
        #kk-admin .kk-search-input:focus {
            outline: none; border-color: var(--kk-brand); box-shadow: 0 0 0 3px var(--kk-brand-soft);
        }

        /* ---------- TABLE ---------- */
        #kk-admin .kk-table-wrap {
            background: var(--kk-surface); border: 1px solid var(--kk-line); border-radius: var(--kk-radius);
            padding: 8px; overflow-x: auto;
        }
        #kk-admin table.kk-mitra-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        #kk-admin table.kk-mitra-table th {
            text-align: left; padding: 13px 14px; font-size: 11.5px; text-transform: uppercase;
            font-weight: 700; letter-spacing: .04em; color: var(--kk-ink-soft); border-bottom: 1px solid var(--kk-line);
            white-space: nowrap;
        }
        #kk-admin table.kk-mitra-table td { padding: 13px 14px; border-bottom: 1px solid var(--kk-line); vertical-align: middle; }
        #kk-admin table.kk-mitra-table tr:last-child td { border-bottom: none; }
        #kk-admin table.kk-mitra-table tr:hover td { background: #FAFBFF; }

        #kk-admin .kk-mitra-name-cell { font-weight: 700; color: var(--kk-brand-ink); }
        #kk-admin .kk-mitra-alamat-cell { max-width: 260px; color: var(--kk-ink-soft); font-size: 12.5px; line-height: 1.5; }

        #kk-admin .kk-badge {
            display: inline-block; padding: 4px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 700;
        }
        #kk-admin .kk-badge-active { background: var(--kk-good-soft); color: #0E6B4E; }
        #kk-admin .kk-badge-inactive { background: var(--kk-bg); color: var(--kk-ink-soft); }

        #kk-admin .kk-persen-cell { display: flex; flex-direction: column; gap: 2px; }
        #kk-admin .kk-persen-bisnis { font-weight: 800; color: var(--kk-brand-ink); }
        #kk-admin .kk-persen-laundry { font-size: 12px; color: var(--kk-good); font-weight: 700; }

        #kk-admin .kk-catatan-cell { max-width: 200px; font-size: 12px; color: var(--kk-ink-soft); font-style: italic; }

        #kk-admin .kk-action-cell { display: flex; gap: 8px; flex-wrap: wrap; }
        #kk-admin .kk-btn-small {
            border: none; padding: 7px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 700;
            cursor: pointer; font-family: inherit; text-decoration: none; display: inline-block;
        }
        #kk-admin .kk-btn-toggle-on { background: var(--kk-warn-soft); color: var(--kk-warn); }
        #kk-admin .kk-btn-toggle-off { background: var(--kk-good-soft); color: #0E6B4E; }
        #kk-admin .kk-btn-edit { background: var(--kk-brand-soft); color: var(--kk-brand-ink); }

        #kk-admin .kk-empty-row { text-align: center; color: var(--kk-ink-soft); padding: 36px; }

        /* ---------- MODAL ---------- */
        #kk-admin .kk-modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(15,18,28,0.5); z-index: 999;
            justify-content: center; align-items: center;
        }
        #kk-admin .kk-modal-overlay.kk-active { display: flex; }
        #kk-admin .kk-modal-box {
            background: var(--kk-surface); border-radius: 16px; padding: 26px; max-width: 420px; width: 90%;
            box-shadow: 0 20px 60px rgba(15,18,28,0.25); animation: kkModalIn .2s ease;
            max-height: 88vh; overflow-y: auto;
        }
        @keyframes kkModalIn { from { transform: scale(.94); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        #kk-admin .kk-modal-title { font-size: 17px; font-weight: 800; color: var(--kk-ink); margin-bottom: 18px; }

        #kk-admin .kk-field { display: flex; flex-direction: column; margin-bottom: 14px; }
        #kk-admin .kk-field label {
            font-size: 12px; font-weight: 700; margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: .03em; color: var(--kk-ink-soft);
        }
        #kk-admin .kk-field input,
        #kk-admin .kk-field textarea,
        #kk-admin .kk-field select {
            font-family: inherit; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--kk-line);
            font-size: 14px; background: var(--kk-bg); color: var(--kk-ink);
        }
        #kk-admin .kk-field textarea { resize: vertical; min-height: 60px; }
        #kk-admin .kk-field input:focus,
        #kk-admin .kk-field textarea:focus,
        #kk-admin .kk-field select:focus {
            outline: none; border-color: var(--kk-brand); background: #fff; box-shadow: 0 0 0 3px var(--kk-brand-soft);
        }
        #kk-admin .kk-field small { color: var(--kk-ink-soft); font-size: 12px; margin-top: 5px; }

        #kk-admin .kk-modal-actions { display: flex; gap: 10px; margin-top: 18px; }
        #kk-admin .kk-modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: .15s;
        }
        #kk-admin .kk-modal-cancel { background: var(--kk-bg); color: var(--kk-ink-soft); }
        #kk-admin .kk-modal-cancel:hover { background: var(--kk-line); }
        #kk-admin .kk-modal-confirm { background: var(--kk-brand); color: #fff; }
        #kk-admin .kk-modal-confirm:hover { background: #234BDB; }

        @media (max-width: 980px) {
            #kk-admin { flex-direction: column; }
            #kk-admin .kk-sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; }
            #kk-admin .kk-sidebar-foot { margin-top: 12px; padding-top: 12px; }
        }
    </style>
</head>

<body>
<div id="kk-admin">

    {{-- ========================= --}}
    {{-- MODAL: TAMBAH MITRA       --}}
    {{-- ========================= --}}
    <div class="kk-modal-overlay" id="modalTambahMitra">
        <div class="kk-modal-box">
            <div class="kk-modal-title">➕ Tambah Mitra Laundry</div>

            <form action="{{ route('admin.mitra.store') }}" method="POST">
                @csrf

                <div class="kk-field">
                    <label>Nama Laundry</label>
                    <input type="text" name="nama_laundry" value="{{ old('nama_laundry') }}" required>
                </div>

                <div class="kk-field">
                    <label>Phone <small>(opsional)</small></label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="kk-field">
                    <label>Alamat</label>
                    <textarea name="alamat" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="kk-field">
                    <label>Persentase Bisnis (%)</label>
                    <input type="number" name="persentase_bisnis" value="{{ old('persentase_bisnis', 10) }}" min="0" max="100" step="0.01" required>
                    <small>Persentase yang diambil bisnis dari fee laundry tiap pesanan. Sisanya jadi bagian mitra.</small>
                </div>

                <div class="kk-field">
                    <label>Catatan <small>(opsional)</small></label>
                    <textarea name="catatan">{{ old('catatan') }}</textarea>
                </div>

                <div class="kk-modal-actions">
                    <button type="button" class="kk-modal-cancel" onclick="tutupModal('modalTambahMitra')">✖ Batal</button>
                    <button type="submit" class="kk-modal-confirm">✔ Simpan Mitra</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MODAL: EDIT MITRA         --}}
    {{-- ========================= --}}
    <div class="kk-modal-overlay" id="modalEditMitra">
        <div class="kk-modal-box">
            <div class="kk-modal-title">✏️ Edit Mitra Laundry</div>

            <form id="formEditMitra" method="POST">
                @csrf
                @method('PUT')

                <div class="kk-field">
                    <label>Nama Laundry</label>
                    <input type="text" name="nama_laundry" id="editNamaLaundry" required>
                </div>

                <div class="kk-field">
                    <label>Phone <small>(opsional)</small></label>
                    <input type="text" name="phone" id="editPhone">
                </div>

                <div class="kk-field">
                    <label>Alamat</label>
                    <textarea name="alamat" id="editAlamat" required></textarea>
                </div>

                <div class="kk-field">
                    <label>Persentase Bisnis (%)</label>
                    <input type="number" name="persentase_bisnis" id="editPersentase" min="0" max="100" step="0.01" required>
                    <small>Persentase yang diambil bisnis dari fee laundry tiap pesanan. Sisanya jadi bagian mitra.</small>
                </div>

                <div class="kk-field">
                    <label>Catatan <small>(opsional)</small></label>
                    <textarea name="catatan" id="editCatatan"></textarea>
                </div>

                <div class="kk-modal-actions">
                    <button type="button" class="kk-modal-cancel" onclick="tutupModal('modalEditMitra')">✖ Batal</button>
                    <button type="submit" class="kk-modal-confirm">✔ Simpan Perubahan</button>
                </div>
            </form>
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
            <li><a href="{{ route('admin.orders') }}"><span class="kk-ico">&#9776;</span> Semua Pesanan</a></li>
            <li><a href="{{ route('admin.promosi.index') }}"><span class="kk-ico">&#9733;</span> Manajemen Promo</a></li>
            <li><a href="{{ route('admin.drivers.index') }}"><span class="kk-ico">&#128663;</span> Manajemen Driver</a></li>
            <li><a href="{{ route('admin.mitra.index') }}" class="is-active"><span class="kk-ico">&#127974;</span> Manajemen Mitra Laundry</a></li>
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
                <h1>Manajemen Mitra Laundry</h1>
                <p>Kelola akun, status, dan pembagian fee seluruh mitra laundry.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="kk-btn">← Kembali</a>
        </div>

        @if(session('success'))
            <div class="kk-alert kk-alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="kk-alert kk-alert-error">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="kk-alert kk-alert-error">
                ❌ Terjadi kesalahan:
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="kk-top-actions">
            <form method="GET" action="{{ route('admin.mitra.index') }}">
                <input type="text" name="search" class="kk-search-input"
                       placeholder="Cari nama laundry..."
                       value="{{ request('search') }}">
            </form>

            <button type="button" class="kk-btn kk-btn-primary" onclick="bukaModalTambah()">
                ➕ Tambah Mitra
            </button>
        </div>

        <div class="kk-table-wrap">
            <table class="kk-mitra-table">
                <thead>
                    <tr>
                        <th>Nama Laundry</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Pembagian Fee</th>
                        <th>Total Order Selesai</th>
                        <th>Total Pendapatan</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mitras as $mitra)
                    <tr>
                        <td class="kk-mitra-name-cell">🧺 {{ $mitra->nama_laundry }}</td>
                        <td>{{ $mitra->phone ?? '-' }}</td>
                        <td class="kk-mitra-alamat-cell">{{ $mitra->alamat }}</td>
                        <td>
                            @if($mitra->status === 'aktif')
                                <span class="kk-badge kk-badge-active">Aktif</span>
                            @else
                                <span class="kk-badge kk-badge-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="kk-persen-cell">
                                <span class="kk-persen-bisnis">Bisnis {{ rtrim(rtrim(number_format($mitra->persentase_bisnis, 2), '0'), '.') }}%</span>
                                <span class="kk-persen-laundry">Laundry {{ rtrim(rtrim(number_format(100 - $mitra->persentase_bisnis, 2), '0'), '.') }}%</span>
                            </div>
                        </td>
                        <td>{{ $mitra->total_order ?? 0 }}</td>
                        <td>Rp {{ number_format($mitra->total_pendapatan ?? 0, 0, ',', '.') }}</td>
                        <td class="kk-catatan-cell">{{ $mitra->catatan ?? '-' }}</td>
                        <td>
                            <div class="kk-action-cell">
                                <button type="button" class="kk-btn-small kk-btn-edit"
                                        onclick="bukaModalEdit({{ $mitra->id }}, '{{ addslashes($mitra->nama_laundry) }}', '{{ addslashes($mitra->phone ?? '') }}', '{{ addslashes($mitra->alamat) }}', '{{ $mitra->persentase_bisnis }}', '{{ addslashes($mitra->catatan ?? '') }}')">
                                    ✏️ Edit
                                </button>
                                <form action="{{ route('admin.mitra.toggleStatus', $mitra->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="kk-btn-small {{ $mitra->status === 'aktif' ? 'kk-btn-toggle-on' : 'kk-btn-toggle-off' }}">
                                        {{ $mitra->status === 'aktif' ? '⏸ Nonaktifkan' : '▶ Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="kk-empty-row">Belum ada mitra laundry terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>

<script>
    function bukaModalTambah() {
        document.getElementById('modalTambahMitra').classList.add('kk-active');
    }

    function bukaModalEdit(id, nama, phone, alamat, persentase, catatan) {
        var form = document.getElementById('formEditMitra');
        form.action = '/admin/mitra/' + id;

        document.getElementById('editNamaLaundry').value = nama;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editPersentase').value = persentase;
        document.getElementById('editCatatan').value = catatan;

        document.getElementById('modalEditMitra').classList.add('kk-active');
    }

    function tutupModal(modalId) {
        document.getElementById(modalId).classList.remove('kk-active');
    }

    document.querySelectorAll('.kk-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('kk-active');
            }
        });
    });

    @if($errors->any() && old('nama_laundry') !== null)
        bukaModalTambah();
    @endif
</script>

</body>
</html>