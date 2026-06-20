<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_penjemputan", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultDate: "{{ $order->tanggal_penjemputan ?? '' }}",
            });
        });
    </script>

    <style>

        .modal-confirm-update { background: #f97316; color: white; }
        .modal-confirm-update:hover { background: #ea6c00; }

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
        .form-group textarea,
        .form-group select {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .readonly {
            background: #f1f5f9;
            font-weight: 700;
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

        .submit-wrap {
            text-align: center;
            margin-top: 30px;
        }

        .btn-danger {
            width: 100%;
            border: none;
            padding: 12px;
            border-radius: 12px;
            background: #dc2626;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 10px;
        }

        .timeline-item {
            border-left: 4px solid #05558E;
            padding-left: 14px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .timeline-driver {
            font-weight: 800;
            font-size: 15px;
            color: #05558E;
        }

        .timeline-status {
            display: inline-block;
            margin-top: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef6ff;
            color: #05558E;
            font-size: 13px;
            font-weight: 700;
        }

        .timeline-date {
            margin-top: 6px;
            font-size: 13px;
            color: #666;
        }

        .empty {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .driver-active-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .driver-active-box .title {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            margin-bottom: 6px;
        }

        .driver-active-box .driver-name {
            font-size: 16px;
            font-weight: 800;
            color: #05558E;
        }

        .driver-active-box .driver-status {
            margin-top: 8px;
            display: inline-block;
            background: #eef6ff;
            color: #05558E;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        /* ===================== */
        /* FOTO BUKTI            */
        /* ===================== */
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
            display: block;
        }
        .foto-empty {
            font-size: 13px;
            color: #94a3b8;
            display: block;
        }
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
        .btn-ambil-foto {
            background: #2563eb;
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-ambil-foto:hover { background: #1d4ed8; }
        .foto-uploading {
            font-size: 13px;
            color: #2563eb;
            display: none;
            margin-top: 6px;
            font-weight: 600;
        }

        /* ===================== */
        /* MODAL HAPUS FOTO      */
        /* ===================== */
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
        .modal-icon { font-size: 42px; margin-bottom: 12px; }
        .modal-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
        .modal-desc { font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.6; }
        .modal-desc strong { color: #1e293b; }
        .modal-actions { display: flex; gap: 10px; }
        .modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: .15s;
        }
        .modal-cancel { background: #f1f5f9; color: #475569; }
        .modal-cancel:hover { background: #e2e8f0; }
        .modal-confirm-lepas { background: #ef4444; color: white; }
        .modal-confirm-lepas:hover { background: #dc2626; }

        @media(max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    </style>

</head>

<body>

{{-- ========================= --}}
{{-- MODAL: HAPUS FOTO         --}}
{{-- ========================= --}}
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

{{-- ========================= --}}
{{-- MODAL: SIMPAN UPDATE      --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalSimpanUpdate">
    <div class="modal-box">
        <div class="modal-icon">💾</div>
        <div class="modal-title">Simpan Perubahan?</div>
        <div class="modal-desc">
            Perubahan data pesanan <strong>{{ $order->token }}</strong> akan disimpan. Lanjutkan?
        </div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalSimpanUpdate')">✖ Batal</button>
            <button class="modal-confirm-update" onclick="submitForm('modalSimpanUpdate')">✔ Ya, Simpan</button>
        </div>
    </div>
</div>

<div class="container">

    {{-- BACK --}}
    <div class="back-btn-container">
        <a href="{{ route('admin.orders') }}" class="back-btn">
            ← Kembali
        </a>
    </div>

    <h1>Detail Pesanan</h1>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{--
        ✅ FIX: Form "Lepaskan Driver" diletakkan di LUAR form utama.
        Sebelumnya form ini bersarang (nested) di dalam form update,
        yang menyebabkan browser mengabaikannya dan tombol tidak berfungsi.
    --}}
    @if($order->currentDriver)
    <div class="section-card" style="margin-bottom: 24px;">

        <div class="section-title">Driver Aktif</div>

        <div class="driver-active-box">
            <div class="title">Driver Aktif</div>
            <div class="driver-name">🚗 {{ $order->currentDriver->name }}</div>
            <div class="driver-status">{{ $order->status }}</div>
        </div>

        <form action="{{ route('admin.orders.nullifyDriver', $order->id) }}"
              method="POST">
            @csrf
            <button type="submit" class="btn-danger">
                ❌ Lepaskan Driver dari Pesanan
            </button>
        </form>

    </div>
    @endif

    {{-- FORM UTAMA UPDATE PESANAN --}}
    <form id="formUpdatePesanan" method="POST"
      action="{{ route('admin.orders.update', $order->id) }}"
      enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-grid">

            {{-- LEFT --}}
            <div class="section-card">

                <div class="section-title">Customer</div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="{{ $order->nama }}">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ $order->phone }}">
                </div>

                <div class="form-group">
                    <label>Alamat Customer</label>
                    <textarea name="alamat_customer">{{ $order->alamat_customer }}</textarea>
                </div>

                <div class="section-title">Laundry</div>

                <div class="form-group">
                    <label>Alamat Laundry</label>
                    <textarea name="alamat_laundry">{{ $order->alamat_laundry }}</textarea>
                </div>

                <div class="form-group">
                    <label>Phone Laundry</label>
                    <input type="text"
                           name="phone_laundry"
                           value="{{ $order->phone_laundry }}">
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="section-card">

                <div class="section-title">Order</div>

                <div class="form-group">
                    <br>
                    <label>Token</label>
                    <input type="text"
                           value="{{ $order->token }}"
                           class="readonly"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Diproses"        {{ $order->status == 'Diproses'        ? 'selected' : '' }}>Diproses</option>
                        <option value="Dijemput"        {{ $order->status == 'Dijemput'        ? 'selected' : '' }}>Dijemput</option>
                        <option value="Mencari Laundry" {{ $order->status == 'Mencari Laundry' ? 'selected' : '' }}>Mencari Laundry</option>
                        <option value="Dicuci"          {{ $order->status == 'Dicuci'          ? 'selected' : '' }}>Dicuci</option>
                        <option value="Diantar"         {{ $order->status == 'Diantar'         ? 'selected' : '' }}>Diantar</option>
                        <option value="Selesai"         {{ $order->status == 'Selesai'         ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Layanan</label>
                    <input type="text"
                           name="jenis_layanan"
                           value="{{ $order->jenis_layanan }}">
                </div>

                <div class="form-group">
                    <label>Estimasi Jumlah Laundry</label>
                    <input type="text"
                           name="estimasi_jumlah_laundry"
                           value="{{ $order->estimasi_jumlah_laundry }}">
                </div>

                <div class="form-group">
                    <label>Menggunakan Jasa Pemilahan Pakaian</label>
                    <select name="is_sorted">
                        <option value="0" {{ !$order->is_sorted ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ $order->is_sorted  ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="note">{{ $order->note }}</textarea>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjemputan</label>
                    <input
                        type="text"
                        id="tanggal_penjemputan"
                        name="tanggal_penjemputan"
                        value="{{ $order->tanggal_penjemputan ? \Carbon\Carbon::parse($order->tanggal_penjemputan)->format('Y-m-d H:i') : '' }}"
                        placeholder="Pilih tanggal dan jam">
                </div>

                <div class="form-group">
                    <label>Fee</label>
                    <input type="number"
                           name="fee"
                           value="{{ $order->fee }}">
                </div>

                <div class="form-group">
                    <label>Dokumentasi</label>
                    <input
                        type="text"
                        name="dokumentasi_pakaian"
                        value="{{ $order->dokumentasi_pakaian }}"
                        placeholder="Masukkan link dokumentasi">

                    @if($order->dokumentasi_pakaian)
                        <a href="{{ $order->dokumentasi_pakaian }}"
                           target="_blank"
                           class="inline-link">
                            Lihat Dokumentasi
                        </a>
                    @endif
                </div>

            </div>

        </div>

        {{-- SUBMIT --}}
        <div class="submit-wrap">
            <button type="button" class="back-btn"
                    onclick="bukaModalSimpanUpdate('formUpdatePesanan')">
                Simpan Update
            </button>
        </div>

    </form>

    {{-- ========================= --}}
    {{-- FOTO BUKTI (ADMIN: FULL AKSES, TANPA RESTRIKSI) --}}
    {{-- ========================= --}}
    @php
        $fotoPengambilan = $order->photos->where('type', 'pengambilan')->first();
        $fotoNota        = $order->photos->where('type', 'nota')->first();
        $fotoPengiriman  = $order->photos->where('type', 'pengiriman')->first();
    @endphp

    <div class="section-card" style="margin-top: 24px;">
        <div class="section-title">📷 Foto Bukti</div>

        {{-- BUKTI PENGAMBILAN --}}
<div class="foto-group">
    <div class="foto-label">Bukti Pengambilan Baju</div>
    @if($fotoPengambilan)
        <div class="foto-preview">
            <a href="{{ $fotoPengambilan->url }}" target="_blank">
                <img src="{{ $fotoPengambilan->url }}" alt="Bukti Pengambilan">
            </a>
        </div>
        <form id="formHapusFoto{{ $fotoPengambilan->id }}"
              action="{{ route('admin.foto.delete', $fotoPengambilan->id) }}"
              method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" class="btn-hapus-foto"
                onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengambilan->id }}')">
            🗑️ Hapus Foto
        </button>
    @else
        <small class="foto-empty">Belum ada foto</small>
        <div style="margin-top:10px;">
            <form id="formUploadPengambilan"
                  action="{{ route('admin.foto.pengambilan', $order->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="foto" id="inputAdminPengambilan"
                       accept="image/*" style="display:none;"
                       onchange="autoUploadAdmin(this, 'formUploadPengambilan', 'uploadingAdminPengambilan')">
            </form>
            <button type="button" class="btn-ambil-foto"
                    onclick="document.getElementById('inputAdminPengambilan').click()">
                ⬆️ Upload Foto
            </button>
            <span class="foto-uploading" id="uploadingAdminPengambilan">⏳ Mengupload foto...</span>
        </div>
    @endif
</div>

{{-- BUKTI NOTA --}}
<div class="foto-group">
    <div class="foto-label">Bukti Nota Laundry</div>
    @if($fotoNota)
        <div class="foto-preview">
            <a href="{{ $fotoNota->url }}" target="_blank">
                <img src="{{ $fotoNota->url }}" alt="Bukti Nota">
            </a>
        </div>
        <form id="formHapusFoto{{ $fotoNota->id }}"
              action="{{ route('admin.foto.delete', $fotoNota->id) }}"
              method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" class="btn-hapus-foto"
                onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoNota->id }}')">
            🗑️ Hapus Foto
        </button>
    @else
        <small class="foto-empty">Belum ada foto</small>
        <div style="margin-top:10px;">
            <form id="formUploadNota"
                  action="{{ route('admin.foto.nota', $order->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="foto" id="inputAdminNota"
                       accept="image/*" style="display:none;"
                       onchange="autoUploadAdmin(this, 'formUploadNota', 'uploadingAdminNota')">
            </form>
            <button type="button" class="btn-ambil-foto"
                    onclick="document.getElementById('inputAdminNota').click()">
                ⬆️ Upload Foto
            </button>
            <span class="foto-uploading" id="uploadingAdminNota">⏳ Mengupload foto...</span>
        </div>
    @endif
</div>

{{-- BUKTI PENGIRIMAN --}}
<div class="foto-group">
    <div class="foto-label">Bukti Pengiriman Baju</div>
    @if($fotoPengiriman)
        <div class="foto-preview">
            <a href="{{ $fotoPengiriman->url }}" target="_blank">
                <img src="{{ $fotoPengiriman->url }}" alt="Bukti Pengiriman">
            </a>
        </div>
        <form id="formHapusFoto{{ $fotoPengiriman->id }}"
              action="{{ route('admin.foto.delete', $fotoPengiriman->id) }}"
              method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" class="btn-hapus-foto"
                onclick="bukaModalHapusFoto('formHapusFoto{{ $fotoPengiriman->id }}')">
            🗑️ Hapus Foto
        </button>
    @else
        <small class="foto-empty">Belum ada foto</small>
        <div style="margin-top:10px;">
            <form id="formUploadPengiriman"
                  action="{{ route('admin.foto.pengiriman', $order->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="foto" id="inputAdminPengiriman"
                       accept="image/*" style="display:none;"
                       onchange="autoUploadAdmin(this, 'formUploadPengiriman', 'uploadingAdminPengiriman')">
            </form>
            <button type="button" class="btn-ambil-foto"
                    onclick="document.getElementById('inputAdminPengiriman').click()">
                ⬆️ Upload Foto
            </button>
            <span class="foto-uploading" id="uploadingAdminPengiriman">⏳ Mengupload foto...</span>
        </div>
    @endif
</div>

    </div>

    {{-- RIWAYAT DRIVER --}}
    <div class="section-card" style="margin-top: 24px;">

        <div class="section-title">Riwayat Driver</div>

        @if($order->driverLogs->isEmpty())
            <div class="empty">
                Belum ada driver yang mengambil pesanan ini.
            </div>
        @else
            <div class="timeline">
                @foreach($order->driverLogs->sortByDesc('taken_at') as $log)
                <div class="timeline-item">
                    <div class="timeline-driver">
                        🚗 {{ $log->driver->name ?? 'Driver Tidak Diketahui' }}
                    </div>
                    <div class="timeline-status">
                        {{ $log->status }}
                    </div>
                    <div class="timeline-date">
                        {{ \Carbon\Carbon::parse($log->taken_at)->translatedFormat('d F Y - H:i') }}
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

<script>
    let targetFormId = null;

    function autoUploadAdmin(input, formId, uploadingId) {
        if (input.files && input.files[0]) {
            document.getElementById(uploadingId).style.display = 'block';
            document.getElementById(formId).submit();
        }
    }

    function bukaModalHapusFoto(formId) {
        targetFormId = formId;
        document.getElementById('modalHapusFoto').classList.add('active');
    }

    function bukaModalSimpanUpdate(formId) {
        targetFormId = formId;
        document.getElementById('modalSimpanUpdate').classList.add('active');
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