<!-- ===== NAVBAR ===== -->
<header class="navbar" data-reveal="down">
    <button class="hamburger" type="button" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
    <a href="{{ route('index') }}#" class="brand">
        <img class="brand-icon" src="{{ asset('image/Logo.png') }}" alt="KlinKlin">
        <img class="brand-word" src="{{ asset('image/logo-text.png') }}" alt="klinklin"
             onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'brand-text',textContent:'klinklin'}))">
    </a>

    <nav class="nav-links">
    <a href="{{ route('index') }}#tentang" data-i18n="nav_about">Tentang Kami</a>
    <a href="{{ route('index') }}" class="active" data-i18n="nav_home">Beranda</a>
    <a href="{{ route('pesanan') }}" data-i18n="nav_history">Cek Pesanan</a>

    <button class="lang-switch lang-switch-mobile" type="button" aria-label="Ganti bahasa">
        <span class="globe">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#F3F3F3" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M3 12h18M12 3c2.5 2.5 2.5 15 0 18M12 3c-2.5 2.5-2.5 15 0 18"/>
            </svg>
        </span>
        <span class="lang-label">IDN</span>
    </button>
</nav>

    <button class="lang-switch" type="button" aria-label="Ganti bahasa">
        <span class="globe">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#F3F3F3" stroke-width="2">
                <circle cx="12" cy="12" r="9"/>
                <path d="M3 12h18M12 3c2.5 2.5 2.5 15 0 18M12 3c-2.5 2.5-2.5 15 0 18"/>
            </svg>
        </span>
        <span class="lang-label">IDN</span>
    </button>
</header>
