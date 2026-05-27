<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

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
        .form-group textarea {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
            font-family: inherit;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        /* Abu-abu = tidak bisa diedit */
        .readonly {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        /* Kuning = bisa diedit */
        .editable {
            background: #fffbeb;
            border: 2px solid #f59e0b;
        }

        .editable:focus {
            outline: none;
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
        }

        .editable-badge {
            display: inline-block;
            margin-left: 6px;
            font-size: 10px;
            background: #fef3c7;
            color: #92400e;
            padding: 2px 7px;
            border-radius: 999px;
            font-weight: 700;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .section-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 10px 22px rgba(0,0,0,.08);
        }

        .section-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 16px;
            color: #05558E;
        }

        /* Banner info mode */
        .info-banner {
            border-radius: 12px;
            padding: 13px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-banner.editable-mode {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .info-banner.readonly-mode {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .status-Diproses { background: #dbeafe; color: #1d4ed8; }
        .status-Dijemput { background: #fef9c3; color: #a16207; }
        .status-Dicuci   { background: #ede9fe; color: #6d28d9; }
        .status-Diantar  { background: #d1fae5; color: #065f46; }
        .status-Selesai  { background: #dcfce7; color: #15803d; }

        /* Timeline */
        .timeline { display: flex; flex-direction: column; gap: 14px; margin-top: 10px; }
        .timeline-item {
            border-left: 4px solid #05558E;
            padding-left: 14px; padding-top: 2px; padding-bottom: 2px;
        }
        .timeline-driver { font-weight: 800; font-size: 15px; color: #05558E; }
        .timeline-status {
            display: inline-block; margin-top: 4px;
            padding: 4px 10px; border-radius: 999px;
            background: #eef6ff; color: #05558E;
            font-size: 13px; font-weight: 700;
        }
        .timeline-date { margin-top: 6px; font-size: 13px; color: #666; }

        .empty {
            text-align: center; color: #94a3b8; font-size: 14px;
            padding: 20px; background: #f8fafc; border-radius: 12px;
        }

        .alert {
            padding: 14px 18px; border-radius: 12px;
            margin-bottom: 20px; font-weight: 600; font-size: 14px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        .submit-wrap { text-align: center; margin-top: 30px; }

        @media(max-width: 900px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="back-btn-container">
        <a href="{{ route('driver.dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>
    </div>

    <h1>Detail Pesanan</h1>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- BANNER INFO MODE --}}
    @if($bisaEdit)
        <div class="info-banner editable-mode">
            ✏️ Kamu bisa mengubah field berlatar <strong>kuning</strong> karena pesanan sedang berstatus <strong>Dijemput</strong>.
        </div>
    @else
        <div class="info-banner readonly-mode">
            🔒Kamu hanya bisa melihat detail pesanan ini.
            @if($order->status === 'Dijemput' && $order->current_driver_id != session('driver_id'))
                (Pesanan dipegang driver lain.)
            @elseif(in_array($order->status, ['Dicuci', 'Diantar', 'Selesai']))
                (Detail tidak bisa diubah setelah status <strong>{{ $order->status }}</strong>)
            @else
                (Ambil pesanan terlebih dahulu untuk bisa mengedit.)
            @endif
        </div>
    @endif

    {{-- Bungkus dengan form hanya jika bisaEdit, supaya tidak ada form dummy --}}
    @if($bisaEdit)
    <form method="POST" action="{{ route('driver.pesanan.update', $order->id) }}">
        @csrf
    @endif

        <div class="form-grid">

            {{-- KIRI --}}
            <div class="section-card">

                <div class="section-title">Customer</div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" value="{{ $order->nama }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" value="{{ $order->phone }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Alamat Customer</label>
                    <textarea class="readonly" readonly>{{ $order->alamat_customer }}</textarea>
                </div>

                <div class="section-title">Laundry</div>

                <div class="form-group">
                    @if($bisaEdit)
                        <label>Alamat Laundry <span class="editable-badge">✏️ bisa diubah</span></label>
                        <textarea name="alamat_laundry" class="editable">{{ $order->alamat_laundry }}</textarea>
                        @error('alamat_laundry')
                            <span style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    @else
                        <label>Alamat Laundry</label>
                        <textarea class="readonly" readonly>{{ $order->alamat_laundry }}</textarea>
                    @endif
                </div>

                <div class="form-group">
                    @if($bisaEdit)
                        <label>Phone Laundry <span class="editable-badge">✏️ bisa diubah</span></label>
                        <input type="text" name="phone_laundry" value="{{ $order->phone_laundry }}" class="editable">
                        @error('phone_laundry')
                            <span style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    @else
                        <label>Phone Laundry</label>
                        <input type="text" value="{{ $order->phone_laundry }}" class="readonly" readonly>
                    @endif
                </div>

            </div>

            {{-- KANAN --}}
            <div class="section-card">

                <div class="section-title">Order</div>

                <div class="form-group">
                    <label>Token</label>
                    <input type="text" value="{{ $order->token }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Status</label><br>
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
                </div>

                <div class="form-group">
                    <label>Jenis Layanan</label>
                    <input type="text" value="{{ $order->jenis_layanan ?? '-' }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    @if($bisaEdit)
                        <label>Estimasi Jumlah Laundry <span class="editable-badge">✏️ bisa diubah</span></label>
                        <input type="text" name="estimasi_jumlah_laundry"
                               value="{{ $order->estimasi_jumlah_laundry }}"
                               class="editable" placeholder="Contoh: 5 kg">
                        @error('estimasi_jumlah_laundry')
                            <span style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    @else
                        <label>Estimasi Jumlah Laundry</label>
                        <input type="text" value="{{ $order->estimasi_jumlah_laundry ?? '-' }}" class="readonly" readonly>
                    @endif
                </div>

                <div class="form-group">
                    <label>Menggunakan Pemilahan Pakaian</label>
                    <input type="text" value="{{ $order->is_sorted ? 'Ya' : 'Tidak' }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="readonly" readonly>{{ $order->note ?? '-' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjemputan</label>
                    <input type="text"
                           value="{{ $order->tanggal_penjemputan ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->translatedFormat('d F Y - H:i') : '-' }}"
                           class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Fee</label>
                    <input type="text" value="Rp {{ number_format($order->fee, 0, ',', '.') }}" class="readonly" readonly>
                </div>

                @if($order->dokumentasi_pakaian)
                <div class="form-group">
                    <label>Dokumentasi</label>
                    <a href="{{ $order->dokumentasi_pakaian }}" target="_blank" class="inline-link">
                        Lihat Dokumentasi
                    </a>
                </div>
                @endif

            </div>

        </div>

        {{-- RIWAYAT DRIVER --}}
        <div class="section-card" style="margin-top: 24px;">
            <div class="section-title">Riwayat Driver</div>
            @if($order->driverLogs->isEmpty())
                <div class="empty">Belum ada riwayat driver untuk pesanan ini.</div>
            @else
                <div class="timeline">
                    @foreach($order->driverLogs->sortByDesc('taken_at') as $log)
                    <div class="timeline-item">
                        <div class="timeline-driver">🚗 {{ $log->driver->name ?? 'Driver Tidak Diketahui' }}</div>
                        <div class="timeline-status">{{ $log->status }}</div>
                        <div class="timeline-date">
                            {{ \Carbon\Carbon::parse($log->taken_at)->translatedFormat('d F Y - H:i') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tombol simpan hanya muncul jika bisa edit --}}
        @if($bisaEdit)
        <div class="submit-wrap">
            <button type="submit" class="back-btn">💾 Simpan Perubahan</button>
        </div>
        @endif

    @if($bisaEdit)
    </form>
    @endif

</div>

</body>
</html>