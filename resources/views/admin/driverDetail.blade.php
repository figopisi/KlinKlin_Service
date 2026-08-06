<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Driver - {{ $driver->name }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        .container.container-wide {
            width: min(94%, 1100px);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: minmax(280px, 380px) 1fr;
            align-items: start;
            gap: 24px;
        }
        @media (max-width: 800px) {
            .detail-grid { grid-template-columns: 1fr; }
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 22px rgba(0,0,0,.08);
            padding: 28px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #eef1f6;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eef1f6;
            font-size: 14px;
            gap: 16px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; white-space: nowrap; }
        .info-value { color: #1e293b; font-weight: 700; text-align: right; }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #f1f5f9; color: #64748b; }

        .pencapaian-box {
            display: flex;
            gap: 16px;
            margin-top: 18px;
        }
        .pencapaian-item {
            flex: 1;
            background: #f8fafc;
            border-radius: 14px;
            padding: 18px 12px;
            text-align: center;
        }
        .pencapaian-item .angka {
            font-size: 22px;
            font-weight: 800;
            color: #05558E;
            word-break: break-word;
        }
        .pencapaian-item .label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        .dokumen-wrap {
            width: 100%;
            max-width: 460px;
            margin: 0 auto 20px;
            background: #f8fafc;
            border-radius: 14px;
            border: 1px solid #eef1f6;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dokumen-preview {
            width: 100%;
            max-height: 420px;
            object-fit: contain;
            display: block;
        }

        .dokumen-empty {
            color: #94a3b8;
            font-size: 14px;
            padding: 50px 0;
            text-align: center;
            border: 2px dashed #eef1f6;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .upload-form {
            max-width: 460px;
            margin: 0 auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 14px;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
            opacity: .7;
        }
        .form-group input[type="file"] {
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .form-group small { color: #888; font-size: 12px; margin-top: 4px; }

        .btn-primary {
            background: #05558E;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary:hover { background: #044672; }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-danger:hover { background: #fecaca; }

        .doc-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 460px;
            margin: 0 auto;
        }
        .doc-actions form { flex: 1; min-width: 140px; }
        .doc-actions form button { width: 100%; }
        .doc-actions a { flex: 1; min-width: 140px; text-align: center; }

        .doc-divider {
            margin: 24px auto;
            max-width: 460px;
            border: none;
            border-top: 1px solid #eef1f6;
        }

        .doc-hint {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
            text-align: center;
            max-width: 460px;
            margin-left: auto;
            margin-right: auto;
        }

        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-error ul { margin: 0; padding-left: 18px; }
    </style>
</head>

<body>

<div class="container container-wide">

    <div class="back-btn-container">
        <a href="{{ route('admin.drivers.index') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>🚗 {{ $driver->name }}</h1>

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

    <div class="detail-grid">

        {{-- ================= INFO DRIVER ================= --}}
        <div class="card">
            <div class="card-title">Informasi Driver</div>

            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value">{{ $driver->username }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone</span>
                <span class="info-value">{{ $driver->phone ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $driver->email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    @if($driver->is_active)
                        <span class="badge badge-active">Aktif</span>
                    @else
                        <span class="badge badge-inactive">Nonaktif</span>
                    @endif
                </span>
            </div>

            <div class="pencapaian-box">
                <div class="pencapaian-item">
                    <div class="angka">{{ $driver->total_selesai }}</div>
                    <div class="label">Pesanan Selesai</div>
                </div>
                <div class="pencapaian-item">
                    <div class="angka">Rp {{ number_format($driver->total_fee) }}</div>
                    <div class="label">Total Fee</div>
                </div>
            </div>
        </div>

        {{-- ================= DOKUMEN / SURAT PERSETUJUAN ================= --}}
        <div class="card">
            <div class="card-title">📄 Surat Persetujuan</div>

            @if($driver->document_url)
                <div class="dokumen-wrap">
                    <img src="{{ $driver->document_url }}" alt="Surat Persetujuan" class="dokumen-preview">
                </div>

                <div class="doc-actions">
                    <a href="{{ $driver->document_url }}" target="_blank" class="btn-primary" style="text-decoration:none; display:inline-block;">
                        🔍 Lihat Full
                    </a>
                    <form action="{{ route('admin.drivers.document.delete', $driver->id) }}" method="POST"
                          onsubmit="return confirm('Hapus dokumen ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">🗑 Hapus Dokumen</button>
                    </form>
                </div>

                <hr class="doc-divider">
                <p class="doc-hint">Ganti dokumen dengan yang baru:</p>
            @else
                <div class="dokumen-empty">Belum ada dokumen diupload</div>
            @endif

            <form action="{{ route('admin.drivers.document.upload', $driver->id) }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf
                <div class="form-group">
                    <label>Upload {{ $driver->document_url ? 'Dokumen Baru' : 'Dokumen' }}</label>
                    <input type="file" name="dokumen" accept="image/jpeg" required>
                    <small>Format JPG/JPEG, maksimal 5MB.</small>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">⬆ Upload Dokumen</button>
            </form>
        </div>

    </div>

</div>

</body>
</html>