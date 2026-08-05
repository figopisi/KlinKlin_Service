<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Driver</title>

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

        table.driver-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.driver-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            border-bottom: 2px solid #eef1f6;
        }

        table.driver-table td {
            padding: 14px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
        }

        table.driver-table tr:last-child td {
            border-bottom: none;
        }

        .driver-name-cell {
            font-weight: 700;
            color: #05558E;
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

        .pencapaian-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .pencapaian-total {
            font-weight: 800;
            color: #05558E;
        }

        .pencapaian-fee {
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
        .btn-reset { background: #eef6ff; color: #05558E; }

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
        .form-group input {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
        }
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
{{-- MODAL: TAMBAH DRIVER      --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalTambahDriver">
    <div class="modal-box">
        <div class="modal-title">➕ Tambah Driver Baru</div>

        <form action="{{ route('admin.drivers.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Username (untuk login)</label>
                <input type="text" name="username" value="{{ old('username') }}" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
                <small>Minimal 6 karakter.</small>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required minlength="6">
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="tutupModal('modalTambahDriver')">✖ Batal</button>
                <button type="submit" class="modal-confirm">✔ Simpan Driver</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================= --}}
{{-- MODAL: RESET PASSWORD     --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalResetPassword">
    <div class="modal-box">
        <div class="modal-title">🔑 Reset Password Driver</div>
        <form id="formResetPassword" method="POST">
            @csrf
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required minlength="6">
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="tutupModal('modalResetPassword')">✖ Batal</button>
                <button type="submit" class="modal-confirm">✔ Reset Password</button>
            </div>
        </form>
    </div>
</div>

<div class="container">

    <div class="back-btn-container">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>Manajemen Driver</h1>

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
        <form method="GET" action="{{ route('admin.drivers.index') }}">
            <input type="text" name="search" class="search-input"
                   placeholder="Cari nama / username..."
                   value="{{ request('search') }}">
        </form>

        <button type="button" class="btn-tambah" onclick="bukaModalTambah()">
            ➕ Tambah Driver
        </button>
    </div>

    <div class="table-wrap">
        <table class="driver-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Pencapaian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td class="driver-name-cell">🚗 {{ $driver->name }}</td>
                    <td>{{ $driver->username }}</td>
                    <td>
                        {{ $driver->phone ?? '-' }}<br>
                        <small style="color:#888;">{{ $driver->email ?? '-' }}</small>
                    </td>
                    <td>
                        @if($driver->is_active)
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="pencapaian-cell">
                            <span class="pencapaian-total">{{ $driver->total_selesai }} pesanan selesai</span>
                            <span class="pencapaian-fee">Rp {{ number_format($driver->total_fee) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="action-cell">
                            <form action="{{ route('admin.drivers.toggleActive', $driver->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-small {{ $driver->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                                    {{ $driver->is_active ? '⏸ Nonaktifkan' : '▶ Aktifkan' }}
                                </button>
                            </form>
                            <button type="button" class="btn-small btn-reset"
                                    onclick="bukaModalReset({{ $driver->id }})">
                                🔑 Reset Password
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-row">Belum ada driver terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    function bukaModalTambah() {
        document.getElementById('modalTambahDriver').classList.add('active');
    }

    function bukaModalReset(driverId) {
        var form = document.getElementById('formResetPassword');
        form.action = '/admin/drivers/' + driverId + '/reset-password';
        document.getElementById('modalResetPassword').classList.add('active');
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

    @if($errors->any() && old('username') !== null)
        bukaModalTambah();
    @endif
</script>

</body>
</html>