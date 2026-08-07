<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Profil Customer</title>

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

        table.profile-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.profile-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            border-bottom: 2px solid #eef1f6;
        }

        table.profile-table td {
            padding: 14px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
        }

        table.profile-table tr:last-child td {
            border-bottom: none;
        }

        .profile-phone-cell {
            font-weight: 700;
            color: #05558E;
        }

        .profile-alamat-cell {
            max-width: 280px;
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

        .badge-unconfirmed { background: #fef3c7; color: #92400e; }
        .badge-mahasiswa   { background: #dbeafe; color: #1e40af; }
        .badge-biasa       { background: #d1fae5; color: #065f46; }

        .status-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            background: white;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-tab {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
            transition: .15s;
        }

        .filter-tab:hover {
            background: #eef6ff;
            color: #05558E;
        }

        .filter-tab.active {
            background: #05558E;
            color: white;
            border-color: #05558E;
        }

        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-error ul { margin: 0; padding-left: 18px; }

        .empty-row {
            text-align: center;
            color: #94a3b8;
            padding: 30px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="back-btn-container">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>Verifikasi Profil Customer</h1>

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
        <div class="filter-tabs">
            <a href="?status=unconfirmed" class="filter-tab {{ request('status', 'unconfirmed') === 'unconfirmed' ? 'active' : '' }}">⏳ Menunggu</a>
            <a href="?status=mahasiswa" class="filter-tab {{ request('status') === 'mahasiswa' ? 'active' : '' }}">🎓 Mahasiswa</a>
            <a href="?status=biasa" class="filter-tab {{ request('status') === 'biasa' ? 'active' : '' }}">👤 Biasa</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="profile-table">
            <thead>
                <tr>
                    <th>No HP</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profiles as $p)
                <tr>
                    <td class="profile-phone-cell">📱 {{ $p->phone }}</td>
                    <td>{{ $p->nama ?? '-' }}</td>
                    <td class="profile-alamat-cell">{{ $p->alamat_customer ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.verifikasi-profile.update', $p->id) }}">
                            @csrf
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="unconfirmed" {{ $p->status == 'unconfirmed' ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="mahasiswa" {{ $p->status == 'mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                                <option value="biasa" {{ $p->status == 'biasa' ? 'selected' : '' }}>👤 Biasa</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-row">Belum ada profil customer pada kategori ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>