<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <style>
        .foto-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .foto-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: 10px;
        }
        .foto-preview { margin-bottom: 10px; }
        .foto-preview img {
            width: 100%;
            max-width: 320px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            object-fit: cover;
        }
        .foto-locked {
            font-size: 12px;
            color: #94a3b8;
            display: block;
            margin-top: 6px;
        }
        .foto-empty { font-size: 13px; color: #94a3b8; display: block; }
        .foto-uploading {
            font-size: 13px;
            color: #2563eb;
            display: none;
            margin-top: 6px;
            font-weight: 600;
        }
        .btn-ambil-foto {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-ambil-foto:hover { background: #1d4ed8; }
        .btn-hapus-foto {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            margin-top: 6px;
            display: inline-block;
        }
        .btn-hapus-foto:hover { background: #fecaca; }
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
            padding: 32px 28px;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center;
            animation: modalIn .2s ease;
        }
        @keyframes modalIn {
            from { transform: scale(.9); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal-icon  { font-size: 42px; margin-bottom: 12px; }
        .modal-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
        .modal-desc  { font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.6; }
        .modal-desc strong { color: #1e293b; }
        .modal-actions { display: flex; gap: 10px; }
        .modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: .15s;
        }
        .modal-cancel { background: #f1f5f9; color: #475569; }
        .modal-cancel:hover { background: #e2e8f0; }
        .modal-confirm-update { background: #f97316; color: white; }
        .modal-confirm-update:hover { background: #ea6c00; }
        .modal-confirm-lepas { background: #ef4444; color: white; }
        .modal-confirm-lepas:hover { background: #dc2626; }
        .status-Diproses        { background: #dbeafe; color: #1d4ed8; }
        .status-Dijemput        { background: #fef9c3; color: #a16207; }
        .status-Mencari-Laundry { background: #ffedd5; color: #c2410c; }
        .status-Dicuci          { background: #ede9fe; color: #6d28d9; }
        .status-Diantar         { background: #d1fae5; color: #065f46; }
        .status-Selesai         { background: #dcfce7; color: #15803d; }
        .btn {
            width: 100%; padding: 10px; border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            font-family: inherit; margin-top: 8px;
            text-align: center; display: block; text-decoration: none;
        }
        .btn-update { background: #f97316; color: white; }
        .btn-update:hover { background: #ea6c00; }
        .btn-lepas { background: #ef4444; color: white; }
        .btn-lepas:hover { background: #dc2626; }
        .back-btn-container { margin-bottom: 20px; }
        .back-btn {
            display: inline-block; padding: 10px 20px;
            background: #05558E; color: white; border-radius: 10px;
            text-decoration: none; font-weight: 700; font-size: 14px;
            border: none; cursor: pointer; font-family: inherit;
        }
        .back-btn:hover { background: #044a7a; }
        .inline-link { color: #2563eb; font-weight: 600; font-size: 14px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f6fb; min-height: 100vh; padding: 32px 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 14px; }
        .form-group label {
            font-size: 13px; font-weight: 700; margin-bottom: 4px;
            text-transform: uppercase; opacity: .7;
        }
        .form-group input, .form-group textarea {
            padding: 12px 14px; border-radius: 12px;
            border: 1px solid #ddd; font-size: 15px; font-family: inherit;
        }
        .form-group textarea { min-height: 80px; resize: vertical; }
        .readonly {
            background: #f1f5f9; color: #475569;
            font-weight: 600; cursor: not-allowed; border-color: #e2e8f0;
        }
        .editable { background: #fffbeb; border: 2px solid #f59e0b; }
        .editable:focus {
            outline: none; border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
        }
        .editable-badge {
            display: inline-block; margin-left: 6px; font-size: 10px;
            background: #fef3c7; color: #92400e; padding: 2px 7px;
            border-radius: 999px; font-weight: 700;
            text-transform: uppercase; vertical-align: middle;
        }
        .section-card {
            background: white; padding: 24px; border-radius: 20px;
            box-shadow: 0 10px 22px rgba(0,0,0,.08);
        }
        .section-title { font-size: 18px; font-weight: 800; margin-bottom: 16px; color: #05558E; }
        .info-banner {
            border-radius: 12px; padding: 13px 16px; margin-bottom: 20px;
            font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;
        }
        .info-banner.editable-mode { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .info-banner.readonly-mode { background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; }
        .status-badge {
            display: inline-block; padding: 6px 14px;
            border-radius: 999px; font-size: 13px; font-weight: 700;
        }
        .timeline { display: flex; flex-direction: column; gap: 14px; margin-top: 10px; }
        .timeline-item { border-left: 4px solid #05558E; padding-left: 14px; padding-top: 2px; padding-bottom: 2px; }
        .timeline-driver { font-weight: 800; font-size: 15px; color: #05558E; }
        .timeline-status {
            display: inline-block; margin-top: 4px; padding: 4px 10px;
            border-radius: 999px; background: #eef6ff; color: #05558E;
            font-size: 13px; font-weight: 700;
        }
        .timeline-date { margin-top: 6px; font-size: 13px; color: #666; }
        .empty {
            text-align: center; color: #94a3b8; font-size: 14px;
            padding: 20px; background: #f8fafc; border-radius: 12px;
        }
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .submit-wrap { text-align: center; margin-top: 30px; }
        @media(max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>

<body>

{{-- MODAL: UPDATE STATUS --}}
<div class="modal-overlay" id="modalUpdate">
    <div class="modal-box">
        <div class="modal-icon">🚗</div>
        <div class="modal-title">Konfirmasi Update Status</div>
        <div class="modal-desc" id="modalUpdateDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalUpdate')">✖ Tidak</button>
            <button class="modal-confirm-update" onclick="submitForm('modalUpdate')">✔ Ya, Lanjutkan</button>
        </div>
    </div>
</div>

{{-- MODAL: LEPAS PESANAN --}}
<div class="modal-overlay" id="modalLepas">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title">Lepaskan Pesanan?</div>
        <div class="modal-desc" id="modalLepasDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalLepas')">✖ Tidak</button>
            <button class="modal-confirm-lepas" onclick="submitForm('modalLepas')">✔ Ya, Lepaskan</button>
        </div>
    </div>
</div>

{{-- MODAL: HAPUS FOTO --}}
<div class="modal-overlay" id="modalHapusFoto">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <div class="modal-title">Hapus Foto?</div>
        <div class="modal-desc">Foto yang dihapus tidak bisa dikembalikan. Yakin ingin menghapus?</div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalHapusFoto')">✖ Tidak</button>
            <button class="modal-confirm-lepas" onclick="submitForm('modalHapusFoto')">✔ Ya, Hapus</button>
        </div>
    </div>
</div>

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
            ✏️ Kamu bisa mengubah field berlatar kuning karena pesanan sedang berstatus Dijemput atau mencari Laundry.
        </div>
    @else
        <div class="info-banner readonly-mode">
            🔒 Kamu hanya bisa melihat detail pesanan ini.
            @if($order->status === 'Dijemput' && $order->current_driver_id != session('driver_id'))
                (Pesanan dipegang driver lain.)
            @elseif(in_array($order->status, ['Dicuci', 'Diantar', 'Selesai']))
                (Detail tidak bisa diubah setelah status {{ $order->status }})
            @else
                (Ambil pesanan terlebih dahulu untuk bisa mengedit.)
            @endif
        </div>
    @endif

    {{-- ========================= --}}
    {{-- FORM DETAIL               --}}
    {{-- ========================= --}}
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
            {{-- END KIRI --}}

            {{-- KANAN --}}
            <div class="section-card">
                <div class="section-title">Order</div>
                <div class="form-group">
                    <label>Token</label>
                    <input type="text" value="{{ $order->token }}" class="readonly" readonly>
                </div>
                <div class="form-group">
                    <label>Status</label><br>
                    <span class="status-badge status-{{ str_replace(' ', '-', $order->status) }}">
                        {{ $order->status }}
                    </span>
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
                    @if($bisaEdit)
                        <label>Foto Semua Pakaian <span class="editable-badge">✏️ bisa diubah</span></label>
                        <input type="text" name="dokumentasi_pakaian"
                               value="{{ $order->dokumentasi_pakaian }}"
                               class="editable" placeholder="Masukkan link dokumentasi">
                        @error('dokumentasi_pakaian')
                            <span style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    @else
                        <label>Dokumentasi</label>
                        <input type="text" value="{{ $order->dokumentasi_pakaian ?? '-' }}" class="readonly" readonly>
                    @endif
                    @if($order->dokumentasi_pakaian)
                        <a href="{{ $order->dokumentasi_pakaian }}" target="_blank"
                           class="inline-link" style="margin-top:6px;display:inline-block;">
                            🔗 Lihat Dokumentasi
                        </a>
                    @endif
                </div>
            </div>
            {{-- END KANAN --}}

        </div>
        {{-- END FORM-GRID --}}

        @if($bisaEdit)
        <div class="submit-wrap">
            <button type="submit" class="back-btn">💾 Simpan Perubahan</button>
        </div>
        @endif

    @if($bisaEdit)
    </form>
    @endif

    {{-- ========================= --}}
    {{-- SECTION FOTO BUKTI        --}}
    {{-- ========================= --}}
    @php
        $statusOrder     = ['Diproses', 'Dijemput', 'Mencari Laundry', 'Dicuci', 'Diantar', 'Selesai'];
        $statusIndex     = array_search($order->status, $statusOrder);
        $isCurrentDriver = $order->current_driver_id == session('driver_id');

        $fotoPengambilan = $order->photos->where('type', 'pengambilan')->first();
        $fotoNota        = $order->photos->where('type', 'nota')->first();
        $fotoPengiriman  = $order->photos->where('type', 'pengiriman')->first();

        $bisaHapusPengambilan = $isCurrentDriver && $statusIndex < array_search('Mencari Laundry', $statusOrder);
        $bisaHapusNota        = $isCurrentDriver && $statusIndex < array_search('Dicuci', $statusOrder);
        $bisaHapusPengiriman  = $isCurrentDriver && $statusIndex < array_search('Selesai', $statusOrder);
    @endphp

    @if($statusIndex >= array_search('Dijemput', $statusOrder))
    <div class="section-card" style="margin-top: 24px;">
        <div class="section-title">📷 Foto Bukti</div>

        @php
            $statusBerikutnya = match($order->status) {
                'Dijemput'        => 'Mencari Laundry',
                'Mencari Laundry' => 'Dicuci',
                'Diantar'         => 'Selesai',
                default           => null,
            };
            $fotoYangDibutuhkan = match($order->status) {
                'Dijemput'        => 'bukti pengambilan',
                'Mencari Laundry' => 'bukti nota',
                'Diantar'         => 'bukti pengiriman',
                default           => null,
            };
        @endphp

        @if($isCurrentDriver && $statusBerikutnya && $fotoYangDibutuhkan)
        <div class="info-banner" style="background:#fffbeb; border:1px solid #fde68a; color:#92400e; margin-bottom: 16px;">
            ⚠️ Upload {{ $fotoYangDibutuhkan }} terlebih dahulu sebelum update status ke {{ $statusBerikutnya }}.
        </div>
        @endif

        {{-- BUKTI PENGAMBILAN --}}
        <div class="foto-group">
            <div class="foto-label">Bukti Pengambilan Baju</div>
            @if($fotoPengambilan)
                <div class="foto-preview">
                    <img src="{{ $fotoPengambilan->url }}" alt="Bukti Pengambilan">
                </div>
                @if($bisaHapusPengambilan)
                    <form id="formHapusFoto{{ $fotoPengambilan->id }}"
                          action="{{ route('driver.foto.delete', $fotoPengambilan->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengambilan->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Dijemput')
                    <form id="formFotoPengambilan"
                          action="{{ route('driver.foto.pengambilan', $order->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputPengambilan"
                               accept="image/*" capture="environment"
                               style="display:none;"
                               onchange="autoUpload(this, 'formFotoPengambilan', 'uploadingPengambilan')">
                    </form>
                    <button type="button" class="btn-ambil-foto"
                            onclick="document.getElementById('inputPengambilan').click()">
                        📷 Ambil Foto
                    </button>
                    <span class="foto-uploading" id="uploadingPengambilan">⏳ Mengupload foto...</span>
                @else
                    <small class="foto-empty">Belum ada foto</small>
                @endif
            @endif
        </div>

        {{-- BUKTI NOTA --}}
        @if($statusIndex >= array_search('Mencari Laundry', $statusOrder))
        <div class="foto-group">
            <div class="foto-label">Bukti Nota Laundry</div>
            @if($fotoNota)
                <div class="foto-preview">
                    <img src="{{ $fotoNota->url }}" alt="Bukti Nota">
                </div>
                @if($bisaHapusNota)
                    <form id="formHapusFoto{{ $fotoNota->id }}"
                          action="{{ route('driver.foto.delete', $fotoNota->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoNota->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Mencari Laundry')
                    <form id="formFotoNota"
                          action="{{ route('driver.foto.nota', $order->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputNota"
                               accept="image/*" capture="environment"
                               style="display:none;"
                               onchange="autoUpload(this, 'formFotoNota', 'uploadingNota')">
                    </form>
                    <button type="button" class="btn-ambil-foto"
                            onclick="document.getElementById('inputNota').click()">
                        📷 Ambil Foto
                    </button>
                    <span class="foto-uploading" id="uploadingNota">⏳ Mengupload foto...</span>
                @else
                    <small class="foto-empty">Belum ada foto</small>
                @endif
            @endif
        </div>
        @endif

        {{-- BUKTI PENGIRIMAN --}}
        @if($statusIndex >= array_search('Diantar', $statusOrder))
        <div class="foto-group">
            <div class="foto-label">Bukti Pengiriman Baju</div>
            @if($fotoPengiriman)
                <div class="foto-preview">
                    <img src="{{ $fotoPengiriman->url }}" alt="Bukti Pengiriman">
                </div>
                @if($bisaHapusPengiriman)
                    <form id="formHapusFoto{{ $fotoPengiriman->id }}"
                          action="{{ route('driver.foto.delete', $fotoPengiriman->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengiriman->id }}')">
                        🗑️ Hapus Foto
                    </button>
                @else
                    <small class="foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Diantar')
                    <form id="formFotoPengiriman"
                          action="{{ route('driver.foto.pengiriman', $order->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputPengiriman"
                               accept="image/*" capture="environment"
                               style="display:none;"
                               onchange="autoUpload(this, 'formFotoPengiriman', 'uploadingPengiriman')">
                    </form>
                    <button type="button" class="btn-ambil-foto"
                            onclick="document.getElementById('inputPengiriman').click()">
                        📷 Ambil Foto
                    </button>
                    <span class="foto-uploading" id="uploadingPengiriman">⏳ Mengupload foto...</span>
                @else
                    <small class="foto-empty">Belum ada foto</small>
                @endif
            @endif
        </div>
        @endif

    </div>
    @endif

    {{-- ========================= --}}
    {{-- UPDATE STATUS             --}}
    {{-- ========================= --}}
    @if($isCurrentDriver && $order->status !== 'Selesai')
    @php
        $labelUpdate = match($order->status) {
            'Dijemput'        => '🔍 Sudah Dijemput',
            'Mencari Laundry' => '🧺 Sudah di Laundry',
            'Dicuci'          => '🚚 Antar ke Customer',
            'Diantar'         => '✅ Selesai Diantar',
            default           => null,
        };
    @endphp

    @if($labelUpdate)
    <div class="section-card" style="margin-top: 24px;">
        <div class="section-title">🔄 Update Status</div>
        <form id="formUpdateStatus"
              action="{{ route('driver.updateStatus', $order->id) }}"
              method="POST" style="display:none;">
            @csrf
        </form>
        <button type="button" class="btn btn-update" style="max-width: 320px;"
                onclick="bukaModalUpdate(
                    'formUpdateStatus',
                    '{{ $order->token }}',
                    '{{ addslashes($order->nama) }}',
                    '{{ addslashes($labelUpdate) }}',
                    '{{ $order->status }}'
                )">
            {{ $labelUpdate }}
        </button>
    </div>
    @endif
    @endif

    {{-- ========================= --}}
    {{-- RIWAYAT DRIVER            --}}
    {{-- ========================= --}}
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

    {{-- ========================= --}}
    {{-- LEPAS PESANAN             --}}
    {{-- ========================= --}}
    @if($isCurrentDriver && !in_array($order->status, ['Selesai']))
    <div class="section-card" style="margin-top: 24px; border-left: 4px solid #ef4444;">
        <div class="section-title" style="color: #ef4444;">⚠️ Zona Berbahaya</div>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 12px;">
            Melepaskan pesanan akan mengembalikan status ke tahap sebelumnya dan pesanan bisa diambil driver lain.
        </p>
        <form id="formLepasPesanan"
              action="{{ route('driver.lepas', $order->id) }}"
              method="POST" style="display:none;">
            @csrf
        </form>
        <button type="button" class="btn btn-lepas" style="max-width: 320px;"
                onclick="bukaModalLepas(
                    'formLepasPesanan',
                    '{{ $order->token }}',
                    '{{ addslashes($order->nama) }}'
                )">
            ❌ Lepaskan Pesanan
        </button>
    </div>
    @endif

</div>

<script>
    let targetFormId = null;

    function autoUpload(input, formId, uploadingId) {
        if (input.files && input.files[0]) {
            document.getElementById(uploadingId).style.display = 'block';
            document.getElementById(formId).submit();
        }
    }

    function bukaModalUpdate(formId, token, nama, aksi, status) {
        targetFormId = formId;
        const syarat = {
            'Dijemput':        `⚠️ Pastikan sudah upload <strong>bukti pengambilan</strong> pesanan <strong>${token}</strong>.`,
            'Mencari Laundry': `⚠️ Pastikan sudah upload <strong>bukti nota</strong> pesanan <strong>${token}</strong>.`,
            'Diantar':         `⚠️ Pastikan sudah upload <strong>bukti pengiriman</strong> pesanan <strong>${token}</strong>.`,
        };
        const pesanSyarat = syarat[status] ? `<br><br>${syarat[status]}` : '';
        document.getElementById('modalUpdateDesc').innerHTML =
            `Pesanan <strong>${token}</strong> atas nama <strong>${nama}</strong>.<br><br>` +
            `Aksi: <strong>${aksi}</strong>${pesanSyarat}`;
        document.getElementById('modalUpdate').classList.add('active');
    }

    function bukaModalLepas(formId, token, nama) {
        targetFormId = formId;
        document.getElementById('modalLepasDesc').innerHTML =
            `Pesanan <strong>${token}</strong> atas nama <strong>${nama}</strong> ` +
            `akan dikembalikan ke daftar tersedia.<br><br>Yakin ingin melepaskan?`;
        document.getElementById('modalLepas').classList.add('active');
    }

    function bukaModalHapusFoto(formId) {
        targetFormId = formId;
        document.getElementById('modalHapusFoto').classList.add('active');
    }

    function tutupModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        targetFormId = null;
    }

    function submitForm(modalId) {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
        tutupModal(modalId);
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                targetFormId = null;
            }
        });
    });
</script>

</body>
</html>