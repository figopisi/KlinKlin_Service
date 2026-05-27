<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
</head>
<body>
<div class="container">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Admin Dashboard</h1>
        <a href="/admin/orders" class="back-btn">Lihat Semua Pesanan</a>
    </div>

         <div class="cards">

            <!-- TOTAL PESANAN -->
            <div class="card">
                <div class="card-content">
                    <h3>{{ $totalPesanan }}</h3>
                    <p>Total Pesanan</p>
                </div>
            </div>

            <!-- TOTAL PEMASUKAN -->
            <div class="card">
                <div class="card-content">
                    <h3>Rp {{ number_format($totalPemasukan) }}</h3>
                    <p>Total Pemasukan</p>
                </div>
            </div>

        </div>
<br>
<!-- FORM -->
<div class="step-card">
    <h3>Buat Pesanan</h3>

    <form method="POST" action="{{ route('buat-pesanan.store') }}">
        @csrf

        <div class="form-grid">

            <!-- LEFT -->
            <div>

                <!-- CUSTOMER -->
                <div class="section-title">Customer</div>

                <div class="detail-item">
                    <strong>Nama Customer</strong>
                    <input type="text" name="nama" required>
                </div>

                <div class="detail-item">
                    <strong>No HP Customer</strong>
                    <input type="text" name="phone" required>
                </div>

                <div class="detail-item">
                    <strong>Alamat Customer</strong>
                    <textarea name="alamat_customer" required></textarea>
                </div>

                <!-- LAUNDRY -->
                <div class="section-title" style="margin-top:20px;">Laundry</div>

                <div class="detail-item">
                    <strong>Alamat Laundry</strong>
                    <textarea name="alamat_laundry" required></textarea>
                </div>

                <div class="detail-item">
                    <strong>No HP Laundry</strong>
                    <input type="text" name="phone_laundry">
                </div>

            </div>

            <!-- RIGHT -->
            <div>

                <!-- OPERASIONAL -->
                <div class="section-title">Operasional</div>

                <div class="detail-item">
                    <strong>Fee (Rp)</strong>
                    <input type="number" name="fee" required>
                </div>

                <div class="detail-item">
                    <strong>Pemilahan Pakaian</strong>
                    <select name="is_sorted">
                        <option value="0">Tidak</option>
                        <option value="1">Ya (+ biaya tambahan)</option>
                    </select>
                </div>

                <div class="detail-item">
                    <strong>Catatan</strong>
                    <textarea name="note"></textarea>
                </div>

                <!-- STATUS -->
                <div class="section-title" style="margin-top:20px;">Status</div>

                <div class="detail-item">
                    <strong>Status</strong>
                    <select name="status">
                        <option selected>Diproses</option>
                        <option>Dijemput</option>
                        <option>Dicuci</option>
                        <option>Diantar</option>
                        <option>Selesai</option>
                    </select>
                </div>

                <!-- DOKUMENTASI -->
                <div class="detail-item">
                    <strong>Dokumentasi</strong>
                    <input type="text" name="dokumentasi_pakaian" placeholder="Link dokumentasi">
                </div>

            </div>

        </div>

        <!-- SUBMIT -->
        <div style="text-align:center; margin-top:25px;">
            <button class="back-btn">
                Simpan Pesanan
            </button>
        </div>

    </form>
</div>
<form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" style="
        background-color: #e53935; /* merah terang */
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
    " 
    onmouseover="this.style.backgroundColor='#d32f2f';" 
    onmouseout="this.style.backgroundColor='#e53935';">
        Logout
    </button>
</form>
</div>
</body>
</html> 