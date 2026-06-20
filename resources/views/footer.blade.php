<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>KlinKlin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/footer.css') }}">
</head>

<!-- ================= FOOTER ================= -->
<footer class="kk-footer">
    <div class="kk-footer-container">
        <div class="kk-footer-left">
            <h3>KlinKlin</h3>
            <p>Layanan antar jemput laundry profesional di Denpasar.</p>
        </div>
        <div class="kk-footer-right">
            <h4>Kontak Kami</h4>
            <div class="kk-footer-contacts">
                <a href="https://wa.me/6282236405141" target="_blank" class="kk-footer-contact-card">
                    <img src="{{ asset('Lottie/wa.gif') }}" alt="WhatsApp">
                    <span>WhatsApp</span>
                </a>
                <a href="https://www.instagram.com/klinklin.service" target="_blank" class="kk-footer-contact-card">
                    <img src="{{ asset('Lottie/ig.gif') }}" alt="Instagram">
                    <span>Instagram</span>
                </a>
            </div>
        </div>
    </div>
    <div class="kk-footer-bottom">
        &copy; 2025 - {{ date('Y') }} KlinKlin. All Rights Reserved.
    </div>
</footer>