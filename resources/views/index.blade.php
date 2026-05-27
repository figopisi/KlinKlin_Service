<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>KlinKlin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">


</head>

<body>

<!-- FLOATING DECOR -->
<div class="circle-main"></div>
<div class="circle-shadow"></div>

<div class="container">

<!-- NAVBAR WRAPPER -->
<div class="navbar-wrapper">

    <!-- HAMBURGER (mobile only) -->
    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- NAVBAR -->
    <div class="navbar" id="navMenu">
        <a href="{{ route('landing') }}">Landing</a>
        <a href="#" class="active">Dashboard</a>
        <a href="{{ route('pesanan') }}">Cari Riwayat Pesanan</a>
    </div>

</div>

<!-- SCRIPT -->
<script>
const hamburger = document.getElementById("hamburger");
const navMenu = document.getElementById("navMenu");

hamburger.addEventListener("click", () => {
    navMenu.classList.toggle("active");
});
</script>

<!-- HERO -->
<section class="hero">
    <h1>Hello, Kliners</h1>
    <p>Kamu sudah siap <strong>mencuci lagi</strong> hari ini?</p>
</section>

<!-- CARDS -->
<section class="cards">

    <div class="card">
        <img src="{{ asset('Lottie/Lottie_I.gif') }}" alt="Cucian">
        <div class="card-content">
            <h3>Cucian udah numpuk?</h3>
            <p>Jangan sampai cuciannya <strong>menggunung</strong> yaa</p>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('Lottie/Lottie_II.gif') }}" alt="Cek Pesanan">
        <div class="card-content">
            <h3>Mau cek status pesanan</h3>
            <p>Engga perlu khawatir, <strong>Cek disini</strong> aja!</p>
            <a href="{{ route('pesanan') }}" class="action">PERIKSA →</a>
        </div>
    </div>

</section>

<!-- BIG CTA -->
<section class="big-card">

    <div class="big-left">
        <img src="{{ asset('Lottie/Icon_I.png') }}" alt="Icon">
        <div class="big-text">
            <h3>Buat keranjang cucianmu sekarang!!</h3>
            <p>Sat-set, biarin KlinKlin yang urus cucianmu sampai wangi</p>
        </div>
    </div>

    <a href="{{ route('buat-pesanan') }}" class="action">BUAT →</a>

</section>

<!-- ================= BUSINESS INFO ================= -->
<section class="business-info-container">
    <div class="business-info">
        <h2>Apa itu KlinKlin?</h2>
        <p>KlinKlin adalah layanan antar jemput laundry yang memudahkan hidupmu. Kamu tinggal pilih laundry favoritmu, kami yang menjemput cucian di rumah dan mengantarkan kembali setelah selesai. Dengan layanan ini, kamu tidak perlu repot membawa cucian sendiri. Praktis, aman, dan nyaman di area Denpasar.</p>
        <br>
        <h2>Layanan Kami</h2>
        <div class="service-cards">

            <!-- Service Card 1: Jemput & Antar Cucian -->
            <div class="service-card">
                <h3>Jemput & Antar Cucian</h3>
                <p>Kami menjemput cucianmu di rumah dan mengantarkannya kembali setelah selesai dicuci. Kamu bebas memilih laundry favoritmu, sementara kami memastikan prosesnya cepat dan aman.</p>
                <ul>
                    <li>Zona 1 Area Denpasar Selatan & Barat: <strong>Rp 10.000</strong></li>
                    <li>Zona 2 Area Denpasar Timur & Utara: <strong>Rp 15.000</strong></li>
                    <li>Zona 3 Area di luar Denpasar: <strong>Rp 20.000</strong></li>
                </ul>
            </div>

            <!-- Service Card 2: Pemilahan Pakaian -->
            <div class="service-card">
                <h3>Pemilahan Pakaian</h3>
                <p>Kami membantu memisahkan pakaian berdasarkan jenis dan kebutuhan perawatan, agar setiap pakaian dicuci dengan cara yang paling tepat dan tetap awet.</p>
                <p><strong>Fee: Rp 10.000 / pesanan</strong></p>
            </div>

            <!-- Service Card 3: Dokumentasi & Riwayat Cucian -->
            <div class="service-card">
                <h3>Dokumentasi Pakaian</h3>
                <p>Bagi pelanggan yang memesan <strong>layanan pemilahan pakaian</strong>, setiap pakaian akan difoto sebelum dicuci. Fitur ini membantu memastikan cucianmu diperlakukan dengan benar dan dapat menjadi bukti jika terjadi kesalahan, seperti pakaian tertukar atau hilang. Layanan ini termasuk secara otomatis dalam paket pemilahan <strong>tanpa biaya tambahan!</strong></p>
            </div>

        </div>
    </div>
</section>

</div>
{{-- Footer include di luar container --}}
@include('footer')
</body>
</html>