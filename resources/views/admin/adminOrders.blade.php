<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders List</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <style>
        /*
         * Sama seperti di dashboard: semua di-scope di dalam #kk-admin,
         * semua class baru pakai prefix kk- supaya tidak bentrok dengan
         * buat_pesanan.css / style.css yang sudah ada.
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
            --kk-radius: 14px;

            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--kk-bg);
            color: var(--kk-ink);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        #kk-admin .kk-mono { font-family: 'JetBrains Mono', monospace; }

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

        #kk-admin .kk-topbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
        #kk-admin .kk-topbar h1 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
        #kk-admin .kk-topbar p { margin: 0; color: var(--kk-ink-soft); font-size: 13.5px; }

        #kk-admin .kk-topbar-actions { display: flex; gap: 10px; }
        #kk-admin .kk-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 700;
            text-decoration: none; border: 1px solid var(--kk-line); background: var(--kk-surface);
            color: var(--kk-ink); cursor: pointer; font-family: inherit; transition: all .15s ease;
        }
        #kk-admin .kk-btn:hover { border-color: var(--kk-brand); color: var(--kk-brand-ink); }
        #kk-admin .kk-btn-primary { background: var(--kk-brand); border-color: var(--kk-brand); color: #fff; }
        #kk-admin .kk-btn-primary:hover { background: #234BDB; color: #fff; }
        #kk-admin .kk-btn-danger { background: var(--kk-danger-soft); border-color: #F4C2BE; color: var(--kk-danger); }
        #kk-admin .kk-btn-danger:hover { background: #F8DCDA; border-color: var(--kk-danger); }
        #kk-admin .kk-btn-sm { padding: 7px 12px; font-size: 12.5px; }

        #kk-admin .kk-alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; }
        #kk-admin .kk-alert-success { background: #E7F9F1; border: 1px solid #B9EBD7; color: #0E6B4E; }
        #kk-admin .kk-alert-error { background: #FDECEA; border: 1px solid #F4C2BE; color: #B71C1C; }

        /* ---------- FILTER BAR ---------- */
        #kk-admin .kk-filter-card {
            background: var(--kk-surface); border: 1px solid var(--kk-line); border-radius: var(--kk-radius);
            padding: 14px 16px; margin-bottom: 20px;
        }
        #kk-admin .kk-filter-form { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        #kk-admin .kk-filter-form input[type="text"],
        #kk-admin .kk-filter-form select {
            font-family: inherit; font-size: 13.5px; padding: 9px 11px; border-radius: 9px;
            border: 1px solid var(--kk-line); background: var(--kk-bg); color: var(--kk-ink);
        }
        #kk-admin .kk-filter-form input[type="text"] { flex: 1; min-width: 180px; }
        #kk-admin .kk-filter-form input:focus, #kk-admin .kk-filter-form select:focus {
            outline: none; border-color: var(--kk-brand); background: #fff; box-shadow: 0 0 0 3px var(--kk-brand-soft);
        }

        /* ---------- TABLE ---------- */
        #kk-admin .kk-table-wrap {
            background: var(--kk-surface); border: 1px solid var(--kk-line); border-radius: var(--kk-radius);
            overflow: hidden;
        }
        #kk-admin .kk-table-scroll { overflow-x: auto; }
        #kk-admin table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        #kk-admin thead { background: var(--kk-bg); }
        #kk-admin thead th {
            padding: 13px 14px; text-align: left; font-size: 11.5px; font-weight: 700;
            letter-spacing: .04em; text-transform: uppercase; color: var(--kk-ink-soft);
            border-bottom: 1px solid var(--kk-line);
        }
        #kk-admin tbody td { padding: 13px 14px; border-bottom: 1px solid var(--kk-line); vertical-align: middle; }
        #kk-admin tbody tr:last-child td { border-bottom: none; }
        #kk-admin tbody tr:hover { background: #FAFBFF; }
        #kk-admin .kk-cell-id { color: var(--kk-ink-soft); }
        #kk-admin .kk-cell-token { font-weight: 700; }
        #kk-admin .kk-cell-fee { font-weight: 700; }
        #kk-admin .kk-cell-empty { text-align: center; padding: 40px 14px; color: var(--kk-ink-soft); }
        #kk-admin .kk-cell-actions { display: flex; gap: 6px; }

        /* ---------- STATUS TAG ---------- */
        #kk-admin .kk-status-tag {
            display: inline-block; padding: 5px 12px; border-radius: 999px;
            font-size: 11.5px; font-weight: 700; letter-spacing: .02em; text-transform: uppercase; color: #fff;
        }
        #kk-admin .kk-status-unconfirmed { background-color: #6B7280; }
        #kk-admin .kk-status-diproses    { background-color: #374151; }
        #kk-admin .kk-status-dijemput    { background-color: #3B82F6; }
        #kk-admin .kk-status-dicuci      { background-color: #8B5CF6; }
        #kk-admin .kk-status-diantar     { background-color: #FACC15; color: #053659; }
        #kk-admin .kk-status-selesai     { background-color: #16A34A; }

        @media (max-width: 980px) {
            #kk-admin { flex-direction: column; }
            #kk-admin .kk-sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; }
            #kk-admin .kk-sidebar-foot { margin-top: 12px; padding-top: 12px; }
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
            <li><a href="{{ route('admin.dashboard') }}"><span class="kk-ico">&#9632;</span> Dashboard</a></li>
            <li><a href="/admin/orders" class="is-active"><span class="kk-ico">&#9776;</span> Semua Pesanan</a></li>
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
                <h1>Daftar Pesanan</h1>
                <p>Cari, filter, dan kelola semua pesanan yang masuk.</p>
            </div>
            <div class="kk-topbar-actions">
                <a href="{{ route('admin.orders.export', request()->query()) }}" class="kk-btn">⬇ Export CSV</a>
                <a href="{{ route('admin.dashboard') }}" class="kk-btn kk-btn-primary">← Dashboard</a>
            </div>
        </div>

        @if(session('success'))
            <div class="kk-alert kk-alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="kk-alert kk-alert-error">{{ session('error') }}</div>
        @endif

        <!-- SEARCH + SORT + FILTER -->
        <div class="kk-filter-card">
            <form method="GET" action="{{ route('admin.orders') }}" class="kk-filter-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Cari token..."
                    value="{{ request('search') }}"
                >

                <select name="sort">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Waktu</option>
                    <option value="fee" {{ request('sort') == 'fee' ? 'selected' : '' }}>Fee</option>
                </select>

                <select name="direction">
                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Terbesar / Terbaru</option>
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Terkecil / Terlama</option>
                </select>

                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="Unconfirmed" {{ request('status') == 'Unconfirmed' ? 'selected' : '' }}>Unconfirmed</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Dijemput" {{ request('status') == 'Dijemput' ? 'selected' : '' }}>Dijemput</option>
                    <option value="Dicuci" {{ request('status') == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                    <option value="Diantar" {{ request('status') == 'Diantar' ? 'selected' : '' }}>Diantar</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button type="submit" class="kk-btn kk-btn-primary">Terapkan</button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="kk-table-wrap">
            <div class="kk-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Token</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Fee</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)

                        @php
                            $statusClass = match($order->status) {
                                'Unconfirmed' => 'kk-status-unconfirmed',
                                'Diproses' => 'kk-status-diproses',
                                'Dijemput' => 'kk-status-dijemput',
                                'Dicuci'   => 'kk-status-dicuci',
                                'Diantar'  => 'kk-status-diantar',
                                'Selesai'  => 'kk-status-selesai',
                                default => ''
                            };
                        @endphp

                        <tr>
                            <td class="kk-cell-id">{{ $order->id }}</td>
                            <td class="kk-cell-token kk-mono">{{ $order->token }}</td>
                            <td>{{ $order->nama }}</td>

                            <td>
                                <span class="kk-status-tag {{ $statusClass }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="kk-cell-fee kk-mono">Rp {{ number_format($order->fee) }}</td>

                            <td>
                                <div class="kk-cell-actions">
                                    <a href="{{ route('admin.orders.detail', $order->id) }}" class="kk-btn kk-btn-sm">
                                        Detail
                                    </a>

                                    <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus pesanan {{ $order->token }}? Data tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="kk-btn kk-btn-sm kk-btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="kk-cell-empty">
                                Tidak ada pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>