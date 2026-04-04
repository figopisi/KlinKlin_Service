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
            opacity: 0.7;
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

        @media(max-width: 900px){
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- BACK -->
    <div class="back-btn-container">
        <a href="{{ route('admin.orders') }}" class="back-btn">← Kembali</a>
    </div>

    <h1>Detail Pesanan</h1>

    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <!-- LEFT -->
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

                <div class="form-group">
                    <label>Alamat Laundry</label>
                    <textarea name="alamat_laundry">{{ $order->alamat_laundry }}</textarea>
                </div>

                <div class="form-group">
                    <label>Phone Laundry</label>
                    <input type="text" name="phone_laundry" value="{{ $order->phone_laundry }}">
                </div>
            </div>

            <!-- RIGHT -->
            <div class="section-card">
                <div class="section-title">Order</div>

                <div class="form-group">
                    <label>Token</label>
                    <input type="text" value="{{ $order->token }}" class="readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Dijemput" {{ $order->status == 'Dijemput' ? 'selected' : '' }}>Dijemput</option>
                        <option value="Dicuci" {{ $order->status == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                        <option value="Diantar" {{ $order->status == 'Diantar' ? 'selected' : '' }}>Diantar</option>
                        <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fee</label>
                    <input type="number" name="fee" value="{{ $order->fee }}">
                </div>

                <div class="form-group">
                    <label>Menggunakan Jasa Pemilahan Pakaian</label>
                    <select name="is_sorted">
                        <option value="0" {{ !$order->is_sorted ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ $order->is_sorted ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="note">{{ $order->note }}</textarea>
                </div>

              <div class="form-group">
                    <label>Dokumentasi</label>

                    <input 
                        type="text" 
                        name="dokumentasi_pakaian" 
                        value="{{ $order->dokumentasi_pakaian }}"
                        placeholder="Masukkan link dokumentasi (Google Drive / dll)"
                    >

                    @if($order->dokumentasi_pakaian)
                        <a href="{{ $order->dokumentasi_pakaian }}" target="_blank" class="inline-link">
                            Lihat Dokumentasi
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <!-- SUBMIT -->
        <div class="submit-wrap">
            <button type="submit" class="back-btn">
                Simpan Update
            </button>
        </div>

    </form>

</div>

</body>
</html>