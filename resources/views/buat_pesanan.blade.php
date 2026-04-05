<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Pesanan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
<style>
/* Spacing tambahan supaya teks tidak mepet */
.step-card {
    padding: 20px;
    margin-bottom: 25px;
}

.step-card h3 {
    margin-bottom: 15px;
}

.step-card p, .step-card ol, .step-card label {
    margin-bottom: 1rem;
    line-height: 1.7;
}

ol.sub-steps {
    margin-left: 1.8rem;
    margin-top: 0.5rem;
}

ol.sub-steps li {
    margin-bottom: 1rem;
}

.copy-container {
    margin-top: 0.8rem;
    margin-bottom: 1.5rem;
}

.copy-box {
    width: 100%;
    min-height: 240px;
    padding: 12px;
    font-size: 0.95rem;
    line-height: 1.6;
    resize: none;
}
.contact-cards {
    margin-bottom: 1rem;
}
</style>
</head>
<body>
<div class="circle-main"></div>
<div class="circle-shadow"></div>

<div class="container">
    <div class="back-btn-container">
        <a href="{{ route('dashboard') }}" class="back-btn">← Kembali ke Dashboard</a>
    </div>

    <h1>Mulai Buat Pesanan Kamu!</h1>

    <div class="step-container">
        <!-- Langkah 1: Hubungi Admin & Panduan -->
        <div class="step-card">
            <h3>Langkah 1: Hubungi Admin & Ikuti Panduan</h3>
            <ol class="sub-steps">
                <li>
                    Pilih media untuk menghubungi admin:
                    <div class="contact-cards" style="margin-top:0.5rem;">
                        <a href="https://wa.me/6282236405141?text=Nama%20lengkap:%0AAlamat%20customer:%0ANomor%20telepon%20yang%20aktif:%0ATanggal%20Penjemputan:%0AJam%20Penjemputan:%0AJenis%20Layanan:%0AEstimasi%20Jumlah%20Laundry:%20(kg/pcs)%0AAlamat%20laundry%20Pilihan:%20(Jika%20tidak%20ada%20alamat%20spesifik,%20kami%20yang%20akan%20menentukan%20laundry)%0AJasa%20pilah:%20Iya/Tidak%0AMetode%20bayar:%20cash%20di%20tempat/transfer%0ACatatan%20Khusus:" target="_blank" class="contact-card wa-card">
                            <img src="Lottie/wa.gif" alt="WhatsApp">
                            <span>WhatsApp</span>
                        </a>
                        <a href="https://www.instagram.com/klinklin.service" target="_blank" class="contact-card ig-card">
                            <img src="Lottie/ig.gif" alt="Instagram">
                            <span>Instagram</span>
                        </a>
                    </div>
                </li>
                <li>
                    Jika menggunakan Instagram, salin format pesan berikut untuk dikirim melalui DM:
                    <div class="copy-container">
                        <label>Salin format pesan:</label>
                        <textarea class="copy-box" readonly onclick="this.select()">
Nama lengkap:
Alamat customer:
Nomor telepon yang aktif:
Tanggal Penjemputan:
Jam Penjemputan:
Jenis Layanan:
Estimasi Jumlah Laundry: (kg/pcs)
Alamat laundry Pilihan: (Jika tidak ada alamat spesifik, kami yang akan menentukan laundry)
Jasa pilah: Iya/Tidak
Metode bayar: cash di tempat/transfer
Catatan Khusus: </textarea>
                        <small>*Klik dalam kotak untuk menyeleksi semua teks lalu salin</small>
                    </div>
                    <p>Khusus pembayaran transfer, tunggu admin mengirimkan nomor rekening resmi sebelum melakukan transaksi. Setelah itu Kirim bukti pembayaran melalui DM Instagram atau chat WhatsApp. Admin akan memvalidasi pesananmu dan memberikan <strong>Token</strong></p>
                </li>
            </ol>
        </div>

        <!-- Langkah 2: Pantau Status Pesanan -->
        <div class="step-card">
            <h3>Langkah 2: Pantau Status Pesanan</h3>
            <p>
                Gunakan <strong>kode token</strong> resmi dari admin untuk memantau progres laundry kamu secara real-time. 
                Kamu bisa <a href="{{ route('pesanan') }}" class="inline-link">Cek Pesanan di Sini</a>. 
                Berikut adalah arti dari setiap status pesananmu:
            </p>
            
            <div class="status-explanation">
                <div class="status-item">
                    <span class="dot process"></span>
                    <p><strong>Diproses:</strong> Admin telah mengonfirmasi pesananmu dan sedang menyiapkan jadwal penjemputan.</p>
                </div>
                
                <div class="status-item">
                    <span class="dot pickup"></span>
                    <p><strong>Dijemput:</strong> Driver kami sedang dalam perjalanan menuju lokasimu untuk mengambil pakaian.</p>
                </div>
                
                <div class="status-item">
                    <span class="dot washing"></span>
                    <p><strong>Dicuci:</strong> Pakaianmu sudah sampai di outlet dan sedang ditangani oleh tim profesional kami.</p>
                </div>

                <div class="status-item">
                    <span class="dot delivery"></span>
                    <p><strong>Diantar:</strong> Pakaian sudah bersih dan wangi! Saat ini kurir sedang mengantarkannya kembali ke rumahmu.</p>
                </div>
                
                <div class="status-item">
                    <span class="dot finished"></span>
                    <p><strong>Selesai:</strong> Pesanan telah diterima dengan baik. Terima kasih telah mempercayakan laundry kamu kepada kami!</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>