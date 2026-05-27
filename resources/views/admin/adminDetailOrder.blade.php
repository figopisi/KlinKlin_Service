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

        @media(max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body>

<div class="container">

    {{-- BACK --}}
    <div class="back-btn-container">
        <a href="{{ route('admin.orders') }}" class="back-btn">
            ← Kembali
        </a>
    </div>

    <h1>Detail Pesanan</h1>

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
    <form method="POST"
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
                        <option value="Diproses"  {{ $order->status == 'Diproses'  ? 'selected' : '' }}>Diproses</option>
                        <option value="Dijemput"  {{ $order->status == 'Dijemput'  ? 'selected' : '' }}>Dijemput</option>
                        <option value="Dicuci"    {{ $order->status == 'Dicuci'    ? 'selected' : '' }}>Dicuci</option>
                        <option value="Diantar"   {{ $order->status == 'Diantar'   ? 'selected' : '' }}>Diantar</option>
                        <option value="Selesai"   {{ $order->status == 'Selesai'   ? 'selected' : '' }}>Selesai</option>
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

        {{-- SUBMIT --}}
        <div class="submit-wrap">
            <button type="submit" class="back-btn">
                Simpan Update
            </button>
        </div>

    </form>

</div>

</body>
</html>
