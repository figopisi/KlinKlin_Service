<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <style>
        .tipe-keterangan {
            display: block;
            margin-top: 6px;
            font-size: 12.5px;
            color: #888;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="container">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>Admin Dashboard</h1>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.promosi.index') }}" class="back-btn">Manajemen Promo</a>
            <a href="{{ route('admin.drivers.index') }}" class="back-btn">Manajemen Driver</a>
            <a href="{{ route('admin.mitra.index') }}" class="back-btn">Manajemen Mitra Laundry</a>
            <a href="/admin/orders" class="back-btn">Lihat Semua Pesanan</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#e6f9ed; border:1px solid #34c759; color:#1b7a3d; padding:10px 14px; border-radius:8px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fdecea; border:1px solid #e53935; color:#b71c1c; padding:10px 14px; border-radius:8px; margin-bottom:16px;">
            <strong>Pesanan gagal disimpan:</strong>
            <ul style="margin:6px 0 0 18px; padding:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

    <form method="POST" action="{{ route('buat-pesanan.store') }}" id="adminOrderForm">
        @csrf

        <div class="form-grid">

            <!-- LEFT -->
            <div>

                <!-- CUSTOMER -->
                <div class="section-title">Customer</div>

                <div class="detail-item">
                    <strong>Nama Customer</strong>
                    <input type="text" name="nama" value="{{ old('nama') }}" required>
                </div>

                <div class="detail-item">
                    <strong>No HP Customer</strong>
                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                </div>

                <div class="detail-item">
                    <strong>Alamat Customer</strong>
                    <textarea name="alamat_customer" required>{{ old('alamat_customer') }}</textarea>
                </div>

                <!-- LAUNDRY -->
                <div class="section-title" style="margin-top:20px;">Laundry</div>

                <div class="detail-item">
                    <strong>Alamat Laundry</strong>
                    <textarea name="alamat_laundry">{{ old('alamat_laundry') }}</textarea>
                    <small style="color:#888;">Kosongkan jika diserahkan ke KlinKlin.</small>
                </div>

                <div class="detail-item">
                    <strong>No HP Laundry</strong>
                    <input type="text" name="phone_laundry" value="{{ old('phone_laundry') }}">
                </div>

                <!-- JADWAL & LAYANAN -->
                <div class="section-title" style="margin-top:20px;">Jadwal &amp; Layanan</div>

                <div class="detail-item">
                    <strong>Tanggal &amp; Jam Penjemputan</strong>
                    <input
                        type="datetime-local"
                        id="tanggal_penjemputan_local"
                        required
                        onchange="document.getElementById('tanggal_penjemputan_hidden').value = this.value.replace('T', ' ')"
                    >
                    <input
                        type="hidden"
                        id="tanggal_penjemputan_hidden"
                        name="tanggal_penjemputan"
                        value="{{ old('tanggal_penjemputan') }}"
                    >
                </div>

                <div class="detail-item">
                    <strong>Tipe Antar Jemput</strong>
                    <select name="tipe_antar_jemput" id="tipe_antar_jemput" required>
                        <option value="">Pilih tipe</option>
                        <option value="Antar Jemput (PP)" @selected(old('tipe_antar_jemput') === 'Antar Jemput (PP)')>Antar Jemput (PP)</option>
                        <option value="Antar Saja" @selected(old('tipe_antar_jemput') === 'Antar Saja')>Antar Saja</option>
                        <option value="Jemput Saja" @selected(old('tipe_antar_jemput') === 'Jemput Saja')>Jemput Saja</option>
                    </select>
                    <small class="tipe-keterangan" id="tipe_keterangan">
                        Pilih salah satu untuk melihat penjelasannya.
                    </small>
                </div>

                <div class="detail-item">
                    <strong>Jenis Layanan</strong>
                    <select name="jenis_layanan" required>
                        <option value="">Pilih layanan</option>
                        <option @selected(old('jenis_layanan') === 'Cuci + Setrika')>Cuci + Setrika</option>
                        <option @selected(old('jenis_layanan') === 'Cuci Kering')>Cuci Kering</option>
                        <option @selected(old('jenis_layanan') === 'Setrika Saja')>Setrika Saja</option>
                        <option @selected(old('jenis_layanan') === 'Cuci Sepatu')>Cuci Sepatu</option>
                        <option @selected(old('jenis_layanan') === 'Bed Cover / Selimut')>Bed Cover / Selimut</option>
                    </select>
                </div>

                <div class="detail-item">
                    <strong>Estimasi Jumlah (kg/pcs)</strong>
                    <input type="text" name="estimasi_jumlah_laundry" value="{{ old('estimasi_jumlah_laundry') }}" placeholder="mis. 5 kg">
                </div>

            </div>

            <!-- RIGHT -->
            <div>

                <!-- OPERASIONAL -->
                <div class="section-title">Operasional</div>

                <div class="detail-item">
                    <strong>Fee (Rp)</strong>
                    <input type="number" name="fee" value="{{ old('fee') }}" required>
                </div>

                <div class="detail-item">
                    <strong>Pemilahan Pakaian</strong>
                    <select name="is_sorted">
                        <option value="0" @selected(old('is_sorted') === '0' || old('is_sorted') === null)>Tidak</option>
                        <option value="1" @selected(old('is_sorted') === '1')>Ya (+ biaya tambahan)</option>
                    </select>
                </div>

                <div class="detail-item">
                    <strong>Catatan</strong>
                    <textarea name="note">{{ old('note') }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="section-title" style="margin-top:20px;">Status</div>

                <div class="detail-item">
                    <strong>Status</strong>
                    <select name="status" id="status_select">
                        <option value="Unconfirmed" @selected(old('status') === 'Unconfirmed')>Unconfirmed</option>
                        <option value="Diproses" @selected(old('status') === 'Diproses' || old('status') === null)>Diproses</option>
                        <option value="Dijemput" @selected(old('status') === 'Dijemput')>Dijemput</option>
                        <option value="Mencari Laundry" @selected(old('status') === 'Mencari Laundry')>Mencari Laundry</option>
                        <option value="Dicuci" @selected(old('status') === 'Dicuci')>Dicuci</option>
                        <option value="Diantar" @selected(old('status') === 'Diantar')>Diantar</option>
                        <option value="Selesai" @selected(old('status') === 'Selesai')>Selesai</option>
                    </select>
                    <small class="tipe-keterangan">
                        Untuk tipe "Jemput Saja", status akan otomatis diarahkan ke <strong>Dicuci</strong> oleh sistem, apa pun yang dipilih di sini.
                    </small>
                </div>

                <!-- DOKUMENTASI -->
                <div class="detail-item">
                    <strong>Dokumentasi</strong>
                    <input type="text" name="dokumentasi_pakaian" value="{{ old('dokumentasi_pakaian') }}" placeholder="Link dokumentasi">
                </div>

            </div>

        </div>

        <!-- SUBMIT -->
        <div style="text-align:center; margin-top:25px;">
            <button class="back-btn" type="submit">
                Simpan Pesanan
            </button>
        </div>

    </form>
</div>

<form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" style="
        background-color: #e53935;
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

<script>
document.getElementById('adminOrderForm').addEventListener('submit', function (e) {
    var localInput = document.getElementById('tanggal_penjemputan_local');
    var hidden = document.getElementById('tanggal_penjemputan_hidden');

    // Jaga-jaga kalau onchange belum sempat trigger (mis. autofill / submit cepat)
    if (localInput.value && !hidden.value) {
        hidden.value = localInput.value.replace('T', ' ');
    }

    if (!hidden.value) {
        e.preventDefault();
        alert('Tanggal & jam penjemputan wajib diisi.');
        localInput.focus();
    }
});

// Keterangan singkat tiap tipe antar jemput, biar admin tidak salah pilih
// (mengingat status akhirnya bergantung pada tipe ini di server).
var tipeKeteranganMap = {
    'Antar Jemput (PP)': 'Alur lengkap: driver jemput dari customer → antar ke laundry → jemput dari laundry → antar ke customer.',
    'Antar Saja': 'Driver hanya mengantar baju ke laundry. Pesanan otomatis selesai setelah bukti nota diupload.',
    'Jemput Saja': 'Driver hanya mengambil baju dari laundry dan mengantarnya ke customer. Tidak ada tahap penjemputan dari customer. Status akan otomatis menjadi "Dicuci" saat disimpan.'
};

document.getElementById('tipe_antar_jemput').addEventListener('change', function () {
    var keteranganEl = document.getElementById('tipe_keterangan');
    keteranganEl.textContent = tipeKeteranganMap[this.value] || 'Pilih salah satu untuk melihat penjelasannya.';
});

// Trigger sekali saat load, kalau old() sudah terisi (mis. setelah validasi gagal)
window.addEventListener('DOMContentLoaded', function () {
    var tipeEl = document.getElementById('tipe_antar_jemput');
    if (tipeEl.value) {
        tipeEl.dispatchEvent(new Event('change'));
    }
});
</script>

</body>
</html>