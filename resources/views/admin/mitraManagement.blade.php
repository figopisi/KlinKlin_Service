<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mitra Laundry</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <style>
        .table-wrap {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 22px rgba(0,0,0,.08);
            padding: 20px;
            overflow-x: auto;
        }

        table.mitra-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.mitra-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            border-bottom: 2px solid #eef1f6;
        }

        table.mitra-table td {
            padding: 14px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
        }

        table.mitra-table tr:last-child td {
            border-bottom: none;
        }

        .mitra-name-cell {
            font-weight: 700;
            color: #05558E;
        }

        .mitra-alamat-cell {
            max-width: 260px;
            color: #475569;
            font-size: 13px;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #f1f5f9; color: #64748b; }

        .persen-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .persen-bisnis {
            font-weight: 800;
            color: #05558E;
        }

        .persen-laundry {
            font-size: 12.5px;
            color: #16a34a;
            font-weight: 700;
        }

        .action-cell {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-small {
            border: none;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-toggle-on { background: #fef3c7; color: #92400e; }
        .btn-toggle-off { background: #d1fae5; color: #065f46; }
        .btn-edit { background: #eef6ff; color: #05558E; }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            min-width: 220px;
        }

        .btn-tambah {
            background: #05558E;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-tambah:hover { background: #044672; }

        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-error ul { margin: 0; padding-left: 18px; }

        .empty-row {
            text-align: center;
            color: #94a3b8;
            padding: 30px;
        }

        .catatan-cell {
            max-width: 200px;
            font-size: 12.5px;
            color: #64748b;
            font-style: italic;
        }

        /* ===================== MODAL ===================== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: white;
            border-radius: 20px;
            padding: 28px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: modalIn .2s ease;
            max-height: 88vh;
            overflow-y: auto;
        }
        @keyframes modalIn {
            from { transform: scale(.9); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 18px; }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 14px;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
            opacity: .7;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
            font-family: inherit;
        }
        .form-group textarea { resize: vertical; min-height: 60px; }
        .form-group small { color: #888; font-size: 12px; margin-top: 4px; }

        .modal-actions { display: flex; gap: 10px; margin-top: 18px; }
        .modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: .15s;
        }
        .modal-cancel { background: #f1f5f9; color: #475569; }
        .modal-cancel:hover { background: #e2e8f0; }
        .modal-confirm { background: #05558E; color: white; }
        .modal-confirm:hover { background: #044672; }
    </style>
</head>

<body>

{{-- ========================= --}}
{{-- MODAL: TAMBAH MITRA       --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalTambahMitra">
    <div class="modal-box">
        <div class="modal-title">➕ Tambah Mitra Laundry</div>

        <form action="{{ route('admin.mitra.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Laundry</label>
                <input type="text" name="nama_laundry" value="{{ old('nama_laundry') }}" required>
            </div>

            <div class="form-group">
                <label>Phone <small>(opsional)</small></label>
                <input type="text" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="form-group">
                <label>Persentase Bisnis (%)</label>
                <input type="number" name="persentase_bisnis" value="{{ old('persentase_bisnis', 10) }}" min="0" max="100" step="0.01" required>
                <small>Persentase yang diambil bisnis dari fee laundry tiap pesanan. Sisanya jadi bagian mitra.</small>
            </div>

            <div class="form-group">
                <label>Catatan <small>(opsional)</small></label>
                <textarea name="catatan">{{ old('catatan') }}</textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="tutupModal('modalTambahMitra')">✖ Batal</button>
                <button type="submit" class="modal-confirm">✔ Simpan Mitra</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================= --}}
{{-- MODAL: EDIT MITRA         --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalEditMitra">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit Mitra Laundry</div>

        <form id="formEditMitra" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Laundry</label>
                <input type="text" name="nama_laundry" id="editNamaLaundry" required>
            </div>

            <div class="form-group">
                <label>Phone <small>(opsional)</small></label>
                <input type="text" name="phone" id="editPhone">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" id="editAlamat" required></textarea>
            </div>

            <div class="form-group">
                <label>Persentase Bisnis (%)</label>
                <input type="number" name="persentase_bisnis" id="editPersentase" min="0" max="100" step="0.01" required>
                <small>Persentase yang diambil bisnis dari fee laundry tiap pesanan. Sisanya jadi bagian mitra.</small>
            </div>

            <div class="form-group">
                <label>Catatan <small>(opsional)</small></label>
                <textarea name="catatan" id="editCatatan"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="tutupModal('modalEditMitra')">✖ Batal</button>
                <button type="submit" class="modal-confirm">✔ Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="container">

    <div class="back-btn-container">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>Manajemen Mitra Laundry</h1>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            ❌ Terjadi kesalahan:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="top-actions">
        <form method="GET" action="{{ route('admin.mitra.index') }}">
            <input type="text" name="search" class="search-input"
                   placeholder="Cari nama laundry..."
                   value="{{ request('search') }}">
        </form>

        <button type="button" class="btn-tambah" onclick="bukaModalTambah()">
            ➕ Tambah Mitra
        </button>
    </div>

    <div class="table-wrap">
        <table class="mitra-table">
            <thead>
                <tr>
                    <th>Nama Laundry</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Pembagian Fee</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mitras as $mitra)
                <tr>
                    <td class="mitra-name-cell">🧺 {{ $mitra->nama_laundry }}</td>
                    <td>{{ $mitra->phone ?? '-' }}</td>
                    <td class="mitra-alamat-cell">{{ $mitra->alamat }}</td>
                    <td>
                        @if($mitra->status === 'aktif')
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="persen-cell">
                            <span class="persen-bisnis">Bisnis {{ rtrim(rtrim(number_format($mitra->persentase_bisnis, 2), '0'), '.') }}%</span>
                            <span class="persen-laundry">Laundry {{ rtrim(rtrim(number_format(100 - $mitra->persentase_bisnis, 2), '0'), '.') }}%</span>
                        </div>
                    </td>
                    <td class="catatan-cell">{{ $mitra->catatan ?? '-' }}</td>
                    <td>
                        <div class="action-cell">
                            <button type="button" class="btn-small btn-edit"
                                    onclick="bukaModalEdit({{ $mitra->id }}, '{{ addslashes($mitra->nama_laundry) }}', '{{ addslashes($mitra->phone ?? '') }}', '{{ addslashes($mitra->alamat) }}', '{{ $mitra->persentase_bisnis }}', '{{ addslashes($mitra->catatan ?? '') }}')">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.mitra.toggleStatus', $mitra->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-small {{ $mitra->status === 'aktif' ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                                    {{ $mitra->status === 'aktif' ? '⏸ Nonaktifkan' : '▶ Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-row">Belum ada mitra laundry terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    function bukaModalTambah() {
        document.getElementById('modalTambahMitra').classList.add('active');
    }

    function bukaModalEdit(id, nama, phone, alamat, persentase, catatan) {
        var form = document.getElementById('formEditMitra');
        form.action = '/admin/mitra/' + id;

        document.getElementById('editNamaLaundry').value = nama;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editPersentase').value = persentase;
        document.getElementById('editCatatan').value = catatan;

        document.getElementById('modalEditMitra').classList.add('active');
    }

    function tutupModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    @if($errors->any() && old('nama_laundry') !== null)
        bukaModalTambah();
    @endif
</script>

</body>
</html>