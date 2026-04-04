<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/buat_pesanan.css') }}">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
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
            <div class="step-card">
                <h3>Langkah 1: Hubungi Admin KlinKlin</h3>
                <p>Silakan hubungi admin kami melalui salah satu kontak berikut. Pastikan kamu sudah menyiapkan detail cucianmu.</p>
                <div class="contact-cards">
                    <a href="https://wa.me/6282236405141" target="_blank" class="contact-card wa-card">
                        <img src="Lottie/wa.gif" alt="WhatsApp">
                        <span>WhatsApp</span>
                    </a>
                    <a href="https://www.instagram.com/klinklin.service" target="_blank" class="contact-card ig-card">
                        <img src="Lottie/ig.gif" alt="Instagram">
                        <span>Instagram</span>
                    </a>
                </div>
            </div>

            <div class="step-card">
                <h3>Langkah 2: Ikuti Panduan Pemesanan</h3>
                <ol class="steps-list">
                    <li>Hubungi admin melalui WA atau IG. Sampaikan bahwa kamu ingin melakukan pemesanan laundry.</li>
                    <li>
                        Berikan informasi detail cucian kamu dengan format berikut:
                        <div class="copy-container">
                            <label>Salin format pesan ini:</label>
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
                            <small>*Klik dalam kotak untuk menyeleksi semua teks</small>
                        </div>
                    </li>
                    <li>Khusus pembayaran transfer, harap tunggu admin mengirimkan nomor rekening resmi melalui chat sebelum melakukan transaksi.</li>
                    <li>Kirim bukti pembayaran (jika transfer) melalui chat WA/IG.</li>
                    <li>Admin akan memberikan <strong>kode token</strong> untuk cek status pesanan yang telah terkonfirmasi.</li>
                </ol>
            </div>

            <div class="step-card">
                <h3>Langkah 3: Pantau Status Pesanan</h3>
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
</body>
</html>