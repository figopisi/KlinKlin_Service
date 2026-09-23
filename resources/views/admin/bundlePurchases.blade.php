<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Bundle - KlinKlin Admin</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            margin: 0;
            background: #f4f5f7;
            color: #1f2328;
        }
        .container { max-width: 1100px; margin: 0 auto; padding: 32px 20px 60px; }
        header h1 { margin: 0 0 4px; font-size: 22px; }
        header p { margin: 0 0 28px; color: #6b7280; font-size: 14px; }

        section { margin-bottom: 40px; }
        section h2 {
            font-size: 16px;
            margin: 0 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th, td { padding: 10px 14px; text-align: left; }
        thead th {
            background: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }
        tbody tr:not(:last-child) td { border-bottom: 1px solid #f0f0f0; }
        tbody tr:hover { background: #fafafa; }

        .empty-state {
            padding: 28px 14px;
            text-align: center;
            color: #9ca3af;
            font-size: 13.5px;
        }

        .badge {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }
        .badge-pending   { background: #d97706; }
        .badge-disetujui { background: #16a34a; }
        .badge-ditolak   { background: #dc2626; }

        .actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
        .btn {
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-approve { background: #16a34a; color: #fff; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject { background: #dc2626; color: #fff; }
        .btn-reject:hover { background: #b91c1c; }
        .btn-search { background: #2563eb; color: #fff; }
        .btn-search:hover { background: #1d4ed8; }

        input[type=text] {
            padding: 7px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13.5px;
            width: 260px;
        }
        .search-form { display: flex; gap: 8px; margin-bottom: 16px; }
        .bukti-input {
            width: 150px;
            padding: 5px 8px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error   { background: #fee2e2; color: #991b1b; }

        .customer-info { font-size: 14px; margin-bottom: 10px; }
        .customer-info strong { font-size: 15px; }
    </style>
</head>
<body>
<div class="container">

    <header>
        <h1>📦 Manajemen Bundle</h1>
        <p>Kelola pengajuan, bundle aktif, dan riwayat pembelian bundle customer.</p>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- ============ PENDING ============ --}}
    <section>
        <h2>🕒 Menunggu Approval ({{ $pending->count() }})</h2>
        <div class="card">
            @if ($pending->isEmpty())
                <div class="empty-state">Tidak ada pengajuan yang menunggu.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>No. HP</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Trip</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pending as $p)
                            <tr>
                                <td>{{ $p->customer->nama ?? '-' }}</td>
                                <td>{{ $p->customer->phone ?? '-' }}</td>
                                <td>{{ $p->nama_paket_snapshot }}</td>
                                <td>Rp {{ number_format($p->harga_snapshot, 0, ',', '.') }}</td>
                                <td>{{ $p->jumlah_trip_snapshot }}x</td>
                                <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="actions">
                                        <form method="POST" action="{{ route('admin.bundlePurchases.approve', $p->id) }}">
                                            @csrf
                                            <input type="text" name="bukti_pembayaran_url" class="bukti-input" placeholder="URL bukti bayar">
                                            <button type="submit" class="btn btn-approve" onclick="return confirm('Approve bundle ini?')">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.bundlePurchases.reject', $p->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-reject" onclick="return confirm('Tolak bundle ini?')">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    {{-- ============ BUNDLE AKTIF ============ --}}
    <section>
        <h2>✅ Bundle Aktif ({{ $active->count() }})</h2>
        <div class="card">
            @if ($active->isEmpty())
                <div class="empty-state">Belum ada bundle aktif saat ini.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>No. HP</th>
                            <th>Paket</th>
                            <th>Kuota Terpakai</th>
                            <th>Sisa</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($active as $a)
                            <tr>
                                <td>{{ $a->customer->nama ?? '-' }}</td>
                                <td>{{ $a->customer->phone ?? '-' }}</td>
                                <td>{{ $a->nama_paket_snapshot }}</td>
                                <td>{{ $a->kuota_terpakai }} / {{ $a->jumlah_trip_snapshot }}</td>
                                <td>{{ $a->kuota_tersisa }}</td>
                                <td>{{ $a->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $a->tanggal_berakhir?->format('d M Y') ?? 'Unlimited' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    {{-- ============ CEK PER CUSTOMER ============ --}}
    <section>
        <h2>🔍 Cek Bundle per Customer</h2>

        <form class="search-form" method="GET" action="{{ route('admin.bundlePurchases.index') }}">
            <input type="text" name="phone" placeholder="Nomor HP (contoh: 628123456789)" value="{{ $searchPhone }}">
            <button type="submit" class="btn btn-search">Cari</button>
        </form>

        @if ($searchPhone)
            <div class="card">
                @if (!$searchedCustomer)
                    <div class="empty-state">Customer dengan nomor {{ $searchPhone }} tidak ditemukan.</div>
                @elseif ($riwayat->isEmpty())
                    <div class="empty-state">{{ $searchedCustomer->nama }} belum pernah membeli bundle.</div>
                @else
                    <div style="padding: 14px 14px 0;">
                        <p class="customer-info"><strong>{{ $searchedCustomer->nama }}</strong> — {{ $searchedCustomer->phone }}</p>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Status</th>
                                <th>Kuota</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Diajukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $r)
                                <tr>
                                    <td>{{ $r->nama_paket_snapshot }}</td>
                                    <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                                    <td>
                                        @if ($r->status === 'disetujui')
                                            {{ $r->kuota_terpakai }} / {{ $r->jumlah_trip_snapshot }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $r->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $r->tanggal_berakhir?->format('d M Y') ?? ($r->status === 'disetujui' ? 'Unlimited' : '-') }}</td>
                                    <td>{{ $r->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
    </section>

</div>
</body>
</html>