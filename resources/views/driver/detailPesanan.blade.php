<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan — Driver</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
<style>
    /* ================= DETAIL PESANAN DRIVER — token landingpage.css, mobile-first ================= */

    body{ padding-bottom: 40px; }

    .d-topbar{
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(90deg, #1F324E 0%, #4873B4 100%);
        box-shadow: 0 6px 20px rgba(31,50,78,.22);
    }
    .d-back{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fff;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 700;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.26);
        padding: 9px 14px;
        border-radius: 12px;
        transition: background .2s var(--ease);
    }
    .d-back:hover{ background: rgba(255,255,255,.24); }
    .d-topbar h1{
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        margin: 0;
    }

    .d-wrap{
        max-width: 640px;
        margin: 0 auto;
        padding: 20px 18px 10px;
        position: relative;
        z-index: 5;
    }

    .d-alert{
        padding: 13px 16px;
        border-radius: 14px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 16px;
    }
    .d-alert-success{ background: #E4F7EB; color: #1F8A4C; }
    .d-alert-error{ background: #FDECEC; color: #C0392B; }

    /* ---- INFO BANNER ---- */
    .d-banner{
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 14px 16px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.55;
        font-weight: 500;
        margin-bottom: 18px;
    }
    .d-banner.editable{ background: #FFF7E0; color: #8A6D1F; border: 1px solid #F5E1A0; }
    .d-banner.readonly{ background: #EEF3FC; color: var(--blue); border: 1px solid #D7E3F7; }
    .d-banner.warn{ background: #FFF7E0; color: #8A6D1F; border: 1px solid #F5E1A0; }

    /* ---- CARD ---- */
    .d-card{
        background: #fff;
        border-radius: 22px;
        padding: 22px 20px;
        box-shadow: 0 8px 24px rgba(31,50,78,.09);
        margin-bottom: 16px;
    }
    .d-card-title{
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14.5px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #EEF1F6;
    }
    .d-card-title:not(:first-child){ margin-top: 22px; }

    .d-field{ margin-bottom: 14px; }
    .d-field:last-child{ margin-bottom: 0; }
    .d-field label{
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        color: rgba(14,23,38,.5);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 6px;
    }
    .d-field .editable-badge{
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        text-transform: none;
        letter-spacing: 0;
        color: #8A6D1F;
        background: #FFF3C4;
        padding: 2px 8px;
        border-radius: 8px;
        margin-left: 6px;
    }
    .d-field input,
    .d-field textarea{
        width: 100%;
        border: none;
        background: #F4F6FA;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--navy);
        font-weight: 600;
        box-sizing: border-box;
    }
    .d-field textarea{ resize: vertical; min-height: 60px; line-height: 1.5; }
    .d-field input[readonly],
    .d-field textarea[readonly]{
        color: rgba(14,23,38,.65);
        font-weight: 500;
    }
    .d-field input.editable,
    .d-field textarea.editable{
        background: #FFF9E6;
        border: 1.5px solid #F5DE8A;
        outline: none;
    }
    .d-field input.editable:focus,
    .d-field textarea.editable:focus{
        border-color: var(--blue-cta);
        box-shadow: 0 0 0 4px rgba(102,156,242,.18);
    }
    .d-err{ color: #C0392B; font-size: 11.5px; margin-top: 5px; font-weight: 600; }

    .d-status-inline{
        display: inline-block;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: .03em;
        text-transform: uppercase;
        padding: 7px 15px;
        border-radius: 20px;
        color: #fff;
    }
    .d-status-Diproses{ background: #64748B; }
    .d-status-Dijemput{ background: #3B82F6; }
    .d-status-Mencari-Laundry{ background: #8B5CF6; }
    .d-status-Dicuci{ background: #8B5CF6; }
    .d-status-Diantar{ background: #F0A93B; }
    .d-status-Selesai{ background: #2FAE64; }

    .d-inline-link{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--blue);
        text-decoration: none;
    }

    .d-submit-wrap{ margin-top: 4px; }

    /* ---- BUTTONS ---- */
    .d-btn{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        border-radius: 15px;
        font-size: 14.5px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform .2s var(--ease), opacity .2s ease;
        font-family: inherit;
        box-sizing: border-box;
    }
    .d-btn:active{ transform: scale(.98); }
    .d-btn-save{
        background: var(--navy);
        color: #fff;
        box-shadow: 0 10px 22px rgba(31,50,78,.25);
    }
    .d-btn-update{
        background: linear-gradient(263deg, #669CF2 0%, #3B5A8C 100%);
        color: #fff;
        box-shadow: 0 10px 22px rgba(102,156,242,.35);
    }
    .d-btn-lepas{
        background: #FDECEC;
        color: #C0392B;
        border: 1.5px solid #F7C9C9;
    }
    .d-btn-foto{
        flex: 1;
        background: #F3F6FB;
        color: var(--navy);
        border: 1.5px solid #E1E8F5;
        padding: 12px;
        font-size: 13.5px;
    }
    .d-btn-hapus-foto{
        width: 100%;
        background: #FDECEC;
        color: #C0392B;
        border: 1.5px solid #F7C9C9;
        padding: 11px;
        font-size: 13px;
        margin-top: 10px;
    }
    .d-foto-row{ display: flex; gap: 10px; margin-top: 4px; }

    /* ---- FOTO GROUP ---- */
    .d-foto-group{ margin-bottom: 20px; }
    .d-foto-group:last-child{ margin-bottom: 0; }
    .d-foto-label{
        font-size: 13px;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 10px;
    }
    .d-foto-preview img{
        width: 100%;
        border-radius: 16px;
        display: block;
        box-shadow: 0 6px 18px rgba(31,50,78,.14);
        margin-bottom: 4px;
    }
    .d-foto-locked, .d-foto-empty{
        display: block;
        font-size: 12px;
        color: rgba(14,23,38,.45);
        font-weight: 500;
        margin-top: 6px;
    }
    .d-foto-uploading{
        display: none;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--blue);
        margin-top: 8px;
    }

    /* ---- TIMELINE ---- */
    .d-timeline{ display: flex; flex-direction: column; gap: 12px; }
    .d-timeline-item{
        padding: 12px 14px;
        background: #F7F9FC;
        border-radius: 14px;
        border-left: 3px solid var(--blue);
    }
    .d-timeline-driver{ font-size: 13px; font-weight: 700; color: var(--navy); }
    .d-timeline-status{ font-size: 12.5px; font-weight: 600; color: var(--blue); margin-top: 2px; }
    .d-timeline-date{ font-size: 11.5px; color: rgba(14,23,38,.5); margin-top: 3px; }
    .d-empty-text{ font-size: 13px; color: rgba(14,23,38,.5); }

    /* ---- DANGER ZONE ---- */
    .d-danger-card{ border: 1.5px solid #F7C9C9; }
    .d-danger-card .d-card-title{ color: #C0392B; border-bottom-color: #FBE0E0; }
    .d-danger-desc{
        font-size: 12.5px;
        color: rgba(14,23,38,.55);
        line-height: 1.6;
        margin-bottom: 14px;
    }

    /* ---- MODAL ---- */
    .modal-overlay{
        display: none;
        position: fixed; inset: 0;
        background: rgba(31,50,78,.55);
        backdrop-filter: blur(3px);
        align-items: center; justify-content: center;
        z-index: 200;
        padding: 20px;
    }
    .modal-overlay.active{ display: flex; }
    .modal-box{
        background: #fff;
        border-radius: 22px;
        padding: 28px 24px;
        max-width: 380px;
        width: 100%;
        text-align: center;
        box-shadow: 0 24px 60px rgba(31,50,78,.30);
    }
    .modal-icon{ font-size: 34px; margin-bottom: 10px; }
    .modal-title{ font-size: 18px; font-weight: 800; color: var(--navy); margin-bottom: 10px; }
    .modal-desc{ font-size: 13.5px; color: rgba(14,23,38,.65); line-height: 1.6; margin-bottom: 22px; }
    .modal-desc strong{ color: var(--navy); }
    .modal-actions{ display: flex; gap: 10px; }
    .modal-actions button{
        flex: 1; padding: 13px; border-radius: 13px;
        font-weight: 700; font-size: 13.5px; cursor: pointer;
        border: none; font-family: inherit;
    }
    .modal-cancel{ background: #F1F3F7; color: rgba(14,23,38,.6); }
    .modal-confirm-update{ background: linear-gradient(263deg, #669CF2 0%, #3B5A8C 100%); color: #fff; }
    .modal-confirm-lepas{ background: #E5484D; color: #fff; }

    @media (min-width: 700px){
        .d-foto-preview img{ max-width: 360px; }
    }
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

<div class="d-topbar">
    <a href="{{ route('driver.dashboard') }}" class="d-back">← Dashboard</a>
    <h1>Detail Pesanan</h1>
</div>

<div class="d-wrap">

    @if(session('success'))
        <div class="d-alert d-alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="d-alert d-alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- BANNER INFO MODE --}}
    @if($bisaEdit)
        <div class="d-banner editable">
            ✏️ Kamu bisa mengubah field berlatar kuning karena pesanan sedang berstatus Dijemput atau mencari Laundry.
        </div>
    @else
        <div class="d-banner readonly">
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

        <div class="d-card">
            <div class="d-card-title">👤 Customer</div>
            <div class="d-field">
                <label>Nama</label>
                <input type="text" value="{{ $order->nama }}" readonly>
            </div>
            <div class="d-field">
                <label>Phone</label>
                <input type="text" value="{{ $order->phone }}" readonly>
            </div>
            <div class="d-field">
                <label>Alamat Customer</label>
                <textarea readonly>{{ $order->alamat_customer }}</textarea>
            </div>

            <div class="d-card-title">🧺 Laundry</div>
            <div class="d-field">
                @if($bisaEdit)
                    <label>Alamat Laundry <span class="editable-badge">bisa diubah</span></label>
                    <textarea name="alamat_laundry" class="editable">{{ $order->alamat_laundry }}</textarea>
                    @error('alamat_laundry') <div class="d-err">{{ $message }}</div> @enderror
                @else
                    <label>Alamat Laundry</label>
                    <textarea readonly>{{ $order->alamat_laundry }}</textarea>
                @endif
            </div>
            <div class="d-field">
                @if($bisaEdit)
                    <label>Phone Laundry <span class="editable-badge">bisa diubah</span></label>
                    <input type="text" name="phone_laundry" value="{{ $order->phone_laundry }}" class="editable">
                    @error('phone_laundry') <div class="d-err">{{ $message }}</div> @enderror
                @else
                    <label>Phone Laundry</label>
                    <input type="text" value="{{ $order->phone_laundry }}" readonly>
                @endif
            </div>
        </div>

        <div class="d-card">
            <div class="d-card-title">📦 Order</div>
            <div class="d-field">
                <label>Token</label>
                <input type="text" value="{{ $order->token }}" readonly>
            </div>
            <div class="d-field">
                <label>Status</label><br>
                <span class="d-status-inline d-status-{{ str_replace(' ', '-', $order->status) }}">{{ $order->status }}</span>
            </div>
            <div class="d-field">
                <label>Jenis Layanan</label>
                <input type="text" value="{{ $order->jenis_layanan ?? '-' }}" readonly>
            </div>
            <div class="d-field">
                @if($bisaEdit)
                    <label>Estimasi Jumlah Laundry <span class="editable-badge">bisa diubah</span></label>
                    <input type="text" name="estimasi_jumlah_laundry"
                           value="{{ $order->estimasi_jumlah_laundry }}"
                           class="editable" placeholder="Contoh: 5 kg">
                    @error('estimasi_jumlah_laundry') <div class="d-err">{{ $message }}</div> @enderror
                @else
                    <label>Estimasi Jumlah Laundry</label>
                    <input type="text" value="{{ $order->estimasi_jumlah_laundry ?? '-' }}" readonly>
                @endif
            </div>
            <div class="d-field">
                <label>Pemilahan Pakaian</label>
                <input type="text" value="{{ $order->is_sorted ? 'Ya' : 'Tidak' }}" readonly>
            </div>
            <div class="d-field">
                <label>Catatan</label>
                <textarea readonly>{{ $order->note ?? '-' }}</textarea>
            </div>
            <div class="d-field">
                <label>Tanggal Penjemputan</label>
                <input type="text"
                       value="{{ $order->tanggal_penjemputan ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->translatedFormat('d F Y - H:i') : '-' }}"
                       readonly>
            </div>
            <div class="d-field">
                @if($bisaEdit)
                    <label>Foto Semua Pakaian <span class="editable-badge">bisa diubah</span></label>
                    <input type="text" name="dokumentasi_pakaian"
                           value="{{ $order->dokumentasi_pakaian }}"
                           class="editable" placeholder="Masukkan link dokumentasi">
                    @error('dokumentasi_pakaian') <div class="d-err">{{ $message }}</div> @enderror
                @else
                    <label>Dokumentasi</label>
                    <input type="text" value="{{ $order->dokumentasi_pakaian ?? '-' }}" readonly>
                @endif
                @if($order->dokumentasi_pakaian)
                    <a href="{{ $order->dokumentasi_pakaian }}" target="_blank" class="d-inline-link">🔗 Lihat Dokumentasi</a>
                @endif
            </div>
        </div>

        @if($bisaEdit)
        <div class="d-submit-wrap">
            <button type="submit" class="d-btn d-btn-save">💾 Simpan Perubahan</button>
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
    <div class="d-card">
        <div class="d-card-title">📷 Foto Bukti</div>

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
        <div class="d-banner warn" style="margin-bottom:18px;">
            ⚠️ Upload {{ $fotoYangDibutuhkan }} terlebih dahulu sebelum update status ke {{ $statusBerikutnya }}.
        </div>
        @endif

        {{-- BUKTI PENGAMBILAN --}}
        <div class="d-foto-group">
            <div class="d-foto-label">Bukti Pengambilan Baju</div>
            @if($fotoPengambilan)
                <div class="d-foto-preview">
                    <img src="{{ $fotoPengambilan->url }}" alt="Bukti Pengambilan">
                </div>
                @if($bisaHapusPengambilan)
                    <form id="formHapusFoto{{ $fotoPengambilan->id }}"
                          action="{{ route('driver.foto.delete', $fotoPengambilan->id) }}" method="POST">
                        @csrf @method('DELETE')
                    </form>
                    <button type="button" class="d-btn d-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengambilan->id }}')">🗑️ Hapus Foto</button>
                @else
                    <small class="d-foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Dijemput')
                    <form id="formFotoPengambilan"
                        action="{{ route('driver.foto.pengambilan', $order->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputPengambilanKamera"
                            accept="image/*" capture="environment" style="display:none;"
                            onchange="autoUpload(this, 'formFotoPengambilan', 'uploadingPengambilan')">
                        <input type="file" name="foto" id="inputPengambilanGaleri"
                            accept="image/*" style="display:none;"
                            onchange="autoUpload(this, 'formFotoPengambilan', 'uploadingPengambilan')">
                    </form>
                    <div class="d-foto-row">
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputPengambilanKamera').click()">📷 Kamera</button>
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputPengambilanGaleri').click()">🖼️ Galeri</button>
                    </div>
                    <span class="d-foto-uploading" id="uploadingPengambilan">⏳ Mengupload foto...</span>
                @else
                    <small class="d-foto-empty">Belum ada foto</small>
                @endif
            @endif
        </div>

        {{-- BUKTI NOTA --}}
        @if($statusIndex >= array_search('Mencari Laundry', $statusOrder))
        <div class="d-foto-group">
            <div class="d-foto-label">Bukti Nota Laundry</div>
            @if($fotoNota)
                <div class="d-foto-preview">
                    <img src="{{ $fotoNota->url }}" alt="Bukti Nota">
                </div>
                @if($bisaHapusNota)
                    <form id="formHapusFoto{{ $fotoNota->id }}"
                          action="{{ route('driver.foto.delete', $fotoNota->id) }}" method="POST">
                        @csrf @method('DELETE')
                    </form>
                    <button type="button" class="d-btn d-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoNota->id }}')">🗑️ Hapus Foto</button>
                @else
                    <small class="d-foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Mencari Laundry')
                    <form id="formFotoNota"
                        action="{{ route('driver.foto.nota', $order->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputNotaKamera"
                            accept="image/*" capture="environment" style="display:none;"
                            onchange="autoUpload(this, 'formFotoNota', 'uploadingNota')">
                        <input type="file" name="foto" id="inputNotaGaleri"
                            accept="image/*" style="display:none;"
                            onchange="autoUpload(this, 'formFotoNota', 'uploadingNota')">
                    </form>
                    <div class="d-foto-row">
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputNotaKamera').click()">📷 Kamera</button>
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputNotaGaleri').click()">🖼️ Galeri</button>
                    </div>
                    <span class="d-foto-uploading" id="uploadingNota">⏳ Mengupload foto...</span>
                @else
                    <small class="d-foto-empty">Belum ada foto</small>
                @endif
            @endif
        </div>
        @endif

        {{-- BUKTI PENGIRIMAN --}}
        @if($statusIndex >= array_search('Diantar', $statusOrder))
        <div class="d-foto-group">
            <div class="d-foto-label">Bukti Pengiriman Baju</div>
            @if($fotoPengiriman)
                <div class="d-foto-preview">
                    <img src="{{ $fotoPengiriman->url }}" alt="Bukti Pengiriman">
                </div>
                @if($bisaHapusPengiriman)
                    <form id="formHapusFoto{{ $fotoPengiriman->id }}"
                          action="{{ route('driver.foto.delete', $fotoPengiriman->id) }}" method="POST">
                        @csrf @method('DELETE')
                    </form>
                    <button type="button" class="d-btn d-btn-hapus-foto"
                            onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengiriman->id }}')">🗑️ Hapus Foto</button>
                @else
                    <small class="d-foto-locked">🔒 Foto tidak bisa dihapus setelah status berubah</small>
                @endif
            @else
                @if($isCurrentDriver && $order->status === 'Diantar')
                    <form id="formFotoPengiriman"
                        action="{{ route('driver.foto.pengiriman', $order->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="foto" id="inputPengirimanKamera"
                            accept="image/*" capture="environment" style="display:none;"
                            onchange="autoUpload(this, 'formFotoPengiriman', 'uploadingPengiriman')">
                        <input type="file" name="foto" id="inputPengirimanGaleri"
                            accept="image/*" style="display:none;"
                            onchange="autoUpload(this, 'formFotoPengiriman', 'uploadingPengiriman')">
                    </form>
                    <div class="d-foto-row">
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputPengirimanKamera').click()">📷 Kamera</button>
                        <button type="button" class="d-btn d-btn-foto" onclick="document.getElementById('inputPengirimanGaleri').click()">🖼️ Galeri</button>
                    </div>
                    <span class="d-foto-uploading" id="uploadingPengiriman">⏳ Mengupload foto...</span>
                @else
                    <small class="d-foto-empty">Belum ada foto</small>
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
    <div class="d-card">
        <div class="d-card-title">🔄 Update Status</div>
        <form id="formUpdateStatus"
              action="{{ route('driver.updateStatus', $order->id) }}"
              method="POST" style="display:none;">
            @csrf
        </form>
        <button type="button" class="d-btn d-btn-update"
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
    <div class="d-card">
        <div class="d-card-title">🕓 Riwayat Driver</div>
        @if($order->driverLogs->isEmpty())
            <div class="d-empty-text">Belum ada riwayat driver untuk pesanan ini.</div>
        @else
            <div class="d-timeline">
                @foreach($order->driverLogs->sortByDesc('taken_at') as $log)
                <div class="d-timeline-item">
                    <div class="d-timeline-driver">🚗 {{ $log->driver->name ?? 'Driver Tidak Diketahui' }}</div>
                    <div class="d-timeline-status">{{ $log->status }}</div>
                    <div class="d-timeline-date">
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
    <div class="d-card d-danger-card">
        <div class="d-card-title">⚠️ Zona Berbahaya</div>
        <p class="d-danger-desc">
            Melepaskan pesanan akan mengembalikan status ke tahap sebelumnya dan pesanan bisa diambil driver lain.
        </p>
        <form id="formLepasPesanan"
              action="{{ route('driver.lepas', $order->id) }}"
              method="POST" style="display:none;">
            @csrf
        </form>
        <button type="button" class="d-btn d-btn-lepas"
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