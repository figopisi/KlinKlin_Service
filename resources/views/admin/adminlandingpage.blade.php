<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — KlinKlin</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
<style>
    /* ================= ADMIN LOGIN — satu keluarga dgn driver login, aksen dibedakan (dark + emas) ================= */

    html, body{ min-height: 100vh; }
    body{
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .login-blob-1{ width: 240px; height: 240px; top: -6%; right: -8%; }
    .login-blob-2{ width: 300px; height: 300px; bottom: -10%; left: -10%; }

    .login-shell{
        position: relative;
        z-index: 5;
        width: 100%;
        max-width: 920px;
        display: grid;
        grid-template-columns: 1fr 1.05fr;
        background: #fff;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 30px 70px rgba(14,23,38,.28);
    }

    /* ---- LEFT: FORM (admin di kiri, kebalikan driver — biar sekilas beda pola) ---- */
    .login-form-panel{
        padding: 52px 44px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        order: 1;
    }
    .lf-head{ margin-bottom: 32px; }
    .lf-eyebrow{
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #B8860B;
        margin-bottom: 8px;
    }
    .lf-head h1{
        font-size: 26px;
        font-weight: 800;
        color: var(--navy);
        letter-spacing: -.2px;
    }
    .lf-head p{
        margin-top: 8px;
        font-size: 13.5px;
        color: rgba(14,23,38,.55);
    }

    .lf-error{
        display: flex;
        align-items: center;
        gap: 8px;
        background: #FDECEC;
        color: #C0392B;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 14px;
        border-radius: 12px;
        margin-bottom: 18px;
    }

    .lf-field{ margin-bottom: 18px; }
    .lf-field label{
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 8px;
    }
    .lf-input-wrap{ position: relative; }
    .lf-input-wrap .ic{
        position: absolute;
        left: 15px; top: 50%;
        transform: translateY(-50%);
        color: rgba(14,23,38,.35);
        display: flex;
    }
    .lf-input-wrap input{
        width: 100%;
        box-sizing: border-box;
        padding: 14px 44px 14px 44px;
        border-radius: 14px;
        border: 1.5px solid #E5E9F1;
        background: #F8FAFD;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14.5px;
        color: var(--navy);
        outline: none;
        transition: border-color .2s var(--ease), background .2s var(--ease), box-shadow .2s var(--ease);
    }
    .lf-input-wrap input::placeholder{ color: #A6AFC0; }
    .lf-input-wrap input:focus{
        border-color: #C9971F;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(201,151,31,.14);
    }

    .lf-toggle-pw{
        position: absolute;
        right: 14px; top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(14,23,38,.4);
        display: flex;
        padding: 4px;
    }

    .lf-submit{
        width: 100%;
        padding: 15px;
        border-radius: 15px;
        border: none;
        background: linear-gradient(263deg, #E8B94A 0%, #1F324E 120%);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: .04em;
        cursor: pointer;
        box-shadow: 0 14px 28px rgba(31,50,78,.30);
        transition: transform .2s var(--ease), box-shadow .2s var(--ease);
        margin-top: 6px;
    }
    .lf-submit:hover{ transform: translateY(-2px); box-shadow: 0 18px 36px rgba(31,50,78,.4); }
    .lf-submit:active{ transform: translateY(0) scale(.98); }

    .lf-foot{
        margin-top: 24px;
        text-align: center;
        font-size: 12.5px;
        color: rgba(14,23,38,.45);
    }
    .lf-foot a{
        color: #B8860B;
        font-weight: 700;
        text-decoration: none;
    }

    /* ---- RIGHT: BRAND PANEL (dark solid, bukan gradient biru seperti driver) ---- */
    .login-brand-panel{
        background:
            radial-gradient(circle at 20% 20%, rgba(232,185,74,.10) 0%, transparent 45%),
            linear-gradient(165deg, #0E1726 0%, #1F324E 100%);
        color: #fff;
        padding: 52px 42px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        order: 2;
    }
    .login-brand-panel::after{
        content: "";
        position: absolute;
        top: -60px; left: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232,185,74,.14) 0%, rgba(232,185,74,0) 70%);
    }

    .lb-top{ position: relative; z-index: 1; }
    .lb-logo{
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 46px;
    }
    .lb-logo img{ width: 42px; height: 42px; object-fit: contain; }
    .lb-logo span{
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .lb-badge{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(232,185,74,.14);
        border: 1px solid rgba(232,185,74,.35);
        color: #F0CE7C;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 7px 14px;
        border-radius: 30px;
        margin-bottom: 22px;
    }
    .lb-badge .dot{
        width: 6px; height: 6px; border-radius: 50%;
        background: #F0CE7C;
    }

    .lb-title{
        font-size: 30px;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: .3px;
        color: #fff;
    }
    .lb-title .hi{
        background: linear-gradient(90deg, #F0CE7C 0%, #E8B94A 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .lb-desc{
        margin-top: 14px;
        font-size: 14.5px;
        line-height: 1.65;
        color: rgba(255,255,255,.65);
        max-width: 320px;
    }

    .lb-features{
        position: relative; z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 40px;
    }
    .lb-feature{
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: rgba(255,255,255,.82);
    }
    .lb-feature .ic{
        width: 30px; height: 30px;
        border-radius: 9px;
        background: rgba(232,185,74,.14);
        display: grid;
        place-items: center;
        font-size: 15px;
        flex: none;
    }

    .lb-bottom{
        position: relative; z-index: 1;
        font-size: 12px;
        color: rgba(255,255,255,.4);
        margin-top: 40px;
    }

    @media (max-width: 860px){
        .login-shell{
            grid-template-columns: 1fr;
            max-width: 420px;
        }
        .login-form-panel{ order: 1; padding: 40px 32px 44px; }
        .login-brand-panel{ order: 0; padding: 36px 32px; }
        .lb-features{ display: none; }
        .lb-bottom{ margin-top: 20px; }
    }
</style>
</head>
<body>

<span class="blob login-blob-1" aria-hidden="true"></span>
<span class="blob login-blob-2" aria-hidden="true"></span>

<div class="login-shell">

    <!-- LEFT: FORM -->
    <div class="login-form-panel">
        <div class="lf-head">
            <div class="lf-eyebrow">Restricted Access</div>
            <h1>Admin Login</h1>
            <p>Masuk untuk mengelola pesanan, driver, dan promo.</p>
        </div>

        @if(session('error'))
            <div class="lf-error">⚠️ {{ session('error') }}</div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="lf-field">
                <label>Username</label>
                <div class="lf-input-wrap">
                    <span class="ic">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" name="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="lf-field">
                <label>Password</label>
                <div class="lf-input-wrap">
                    <span class="ic">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" name="password" id="pwInput" placeholder="Masukkan password" required>
                    <button type="button" class="lf-toggle-pw" onclick="togglePw()">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="lf-submit">MASUK</button>
        </form>

        <div class="lf-foot">Bukan admin? <a href="{{ route('index') }}">Kembali ke Beranda</a></div>
    </div>

    <!-- RIGHT: BRAND PANEL -->
    <div class="login-brand-panel">
        <div class="lb-top">
            <div class="lb-logo">
                <img src="{{ asset('image/Logo.png') }}" alt="KlinKlin">
                <span>KlinKlin</span>
            </div>

            <span class="lb-badge"><span class="dot"></span>Admin Portal</span>

            <h1 class="lb-title">Kendalikan seluruh<br><span class="hi">operasional</span> dari sini.</h1>
            <p class="lb-desc">Pantau pesanan, atur driver, dan kelola promo dalam satu dashboard terpusat.</p>
        </div>

        <div class="lb-features">
            <div class="lb-feature"><span class="ic">📊</span>Kelola seluruh pesanan</div>
            <div class="lb-feature"><span class="ic">🚗</span>Kelola akun driver</div>
            <div class="lb-feature"><span class="ic">🏷️</span>Kelola promo & diskon</div>
        </div>

        <div class="lb-bottom">© {{ date('Y') }} KlinKlin. Akses terbatas hanya untuk admin.</div>
    </div>

</div>

<script>
    function togglePw(){
        const input = document.getElementById('pwInput');
        const icon = document.getElementById('eyeIcon');
        if(input.type === 'password'){
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }
</script>

</body>
</html>