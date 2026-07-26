<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Driver Panel — KlinKlin</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/css/landingpage.css') }}">
<style>
    /* ================= DRIVER DASHBOARD — solid colors, summary-first layout ================= */

    body{ padding-bottom: 40px; }

    /* ---- TOP NAVBAR (solid, warna paling terang dari gradasi lama) ---- */
    .driver-navbar{
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(90deg, #1F324E 0%, #4873B4 100%);
        color: #fff;
        box-shadow: 0 6px 20px rgba(31,50,78,.18);
    }
    .driver-brand{
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        font-size: 17px;
    }
    .driver-brand .ic{
        width: 38px; height: 38px;
        border-radius: 12px;
        background: rgba(255,255,255,.16);
        display: grid; place-items: center;
        font-size: 18px;
    }
    .driver-hello{
        font-size: 12px;
        font-weight: 500;
        color: rgba(255,255,255,.78);
        line-height: 1.3;
    }
    .driver-hello strong{
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }
    .btn-logout{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.28);
        color: #FF8A8E;
        font-size: 12.5px;
        font-weight: 700;
        padding: 9px 14px;
        border-radius: 12px;
        cursor: pointer;
        transition: background .2s var(--ease);
    }
    .btn-logout:hover{ background: rgba(255,255,255,.24); }

    /* ---- WRAPPER ---- */
    .driver-wrap{
        max-width: 780px;
        margin: 0 auto;
        padding: 22px 18px 10px;
        position: relative;
        z-index: 5;
    }

    /* ---- ALERTS ---- */
    .d-alert{
        padding: 13px 16px;
        border-radius: 14px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 18px;
    }
    .d-alert-success{ background: #E4F7EB; color: #1F8A4C; }
    .d-alert-error{ background: #FDECEC; color: #C0392B; }

    /* ---- SUMMARY / FILTER TILES ---- */
    .d-summary{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 26px;
    }
    .d-stat-tile{
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
        background: #fff;
        border: 1.5px solid #E1E8F5;
        border-radius: 18px;
        padding: 16px 16px 14px;
        cursor: pointer;
        text-align: left;
        font-family: inherit;
        transition: border-color .15s ease, background .15s ease, transform .15s ease;
    }
    .d-stat-tile:active{ transform: scale(.98); }
    .d-stat-tile .d-stat-ic{
        width: 30px; height: 30px;
        border-radius: 9px;
        display: grid; place-items: center;
        font-size: 15px;
        margin-bottom: 2px;
    }
    .d-stat-tile[data-group="tersedia"] .d-stat-ic{ background: #EEF3FC; }
    .d-stat-tile[data-group="aktif"] .d-stat-ic{ background: #FFF1E2; }
    .d-stat-tile[data-group="selesai"] .d-stat-ic{ background: #E4F7EB; }
    .d-stat-num{
        font-size: 26px;
        font-weight: 800;
        color: var(--navy);
        line-height: 1;
    }
    .d-stat-label{
        font-size: 12px;
        font-weight: 600;
        color: rgba(14,23,38,.55);
    }
    .d-stat-tile.active{
        border-color: var(--blue);
        background: #F5F8FE;
    }
    .d-stat-tile[data-group="aktif"].active{ border-color: #F0A93B; background: #FFFAF2; }
    .d-stat-tile[data-group="selesai"].active{ border-color: #2FAE64; background: #F2FBF6; }

    /* ---- GROUP HEADING ---- */
    .d-group-title{
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 14px;
    }
    .d-badge{
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        background: #EEF3FC;
        color: var(--blue);
    }
    .d-badge.orange{ background: #FFF1E2; color: #C97A1A; }
    .d-badge.green{ background: #E4F7EB; color: #1F8A4C; }

    /* ---- EMPTY STATE ---- */
    .d-empty{
        background: #fff;
        border: 1px dashed #E1E8F5;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        font-size: 13.5px;
        color: rgba(14,23,38,.55);
        font-weight: 500;
        margin-bottom: 10px;
    }

    /* ---- GROUP CONTAINERS (hanya satu terlihat sesuai filter) ---- */
    .d-group{ display: none; }
    .d-group.active{ display: block; }

    /* ---- ORDER CARD (driver) ---- */
    .d-grid{
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 8px;
    }
    .d-card{
        background: #fff;
        border-radius: 22px;
        padding: 20px 20px 18px;
        box-shadow: 0 8px 24px rgba(31,50,78,.08);
        border-left: 5px solid var(--blue);
    }
    .d-card.orange{ border-left-color: #F0A93B; }
    .d-card.green{ border-left-color: #2FAE64; }

    .d-card-top{
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
        gap: 10px;
    }
    .d-token{
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .04em;
        color: var(--blue);
        background: #EEF3FC;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-block;
    }
    .d-name{
        font-size: 17px;
        font-weight: 800;
        color: var(--navy);
        margin-top: 8px;
    }

    .d-info{
        font-size: 13.5px;
        color: rgba(14,23,38,.68);
        line-height: 1.6;
        margin-bottom: 3px;
    }
    .d-info span{ color: var(--navy); font-weight: 600; }

    .d-status-row{ margin: 12px 0 16px; }
    .d-status{
        display: inline-block;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: .03em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 20px;
        color: #fff;
    }
    .d-status-Diproses{ background: #64748B; }
    .d-status-Dijemput{ background: #3B82F6; }
    .d-status-Mencari-Laundry{ background: #8B5CF6; }
    .d-status-Dicuci{ background: #8B5CF6; }
    .d-status-Diantar{ background: #F0A93B; }
    .d-status-Selesai{ background: #2FAE64; }

    /* ---- BUTTONS (solid, tanpa gradasi) ---- */
    .d-btn{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        border-radius: 15px;
        font-size: 14.5px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform .2s var(--ease), background .2s var(--ease);
        font-family: inherit;
    }
    .d-btn:active{ transform: scale(.98); }

    .d-btn-update{
        background: var(--blue-cta);
        color: #fff;
        margin-bottom: 10px;
    }
    .d-btn-update:hover{ background: var(--blue); }
    .d-btn-ambil{
        background: var(--navy);
        color: #fff;
        margin-bottom: 10px;
    }
    .d-btn-ambil:hover{ background: #16233A; }
    .d-btn-detail{
        background: #F3F6FB;
        color: var(--navy);
        border: 1.5px solid #E1E8F5;
    }
    .d-btn-detail.orange{ border-color: #FBE0B4; color: #C97A1A; background: #FFF8EE; }
    .d-btn-detail.green{ border-color: #C9EDD8; color: #1F8A4C; background: #F1FBF5; }

    /* ---- MODAL ---- */
    .modal-overlay{
        display: none;
        position: fixed; inset: 0;
        background: rgba(31,50,78,.55);
        backdrop-filter: blur(3px);
        align-items: center; justify-content: center;
        z-index: 200;
        padding: 20px;
    }
    .modal-overlay.active{ display: flex; }
    .modal-box{
        background: #fff;
        border-radius: 22px;
        padding: 28px 24px;
        max-width: 380px;
        width: 100%;
        text-align: center;
        box-shadow: 0 24px 60px rgba(31,50,78,.30);
    }
    .modal-icon{ font-size: 34px; margin-bottom: 10px; }
    .modal-title{
        font-size: 18px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 10px;
    }
    .modal-desc{
        font-size: 13.5px;
        color: rgba(14,23,38,.65);
        line-height: 1.6;
        margin-bottom: 22px;
    }
    .modal-desc strong{ color: var(--navy); }
    .modal-actions{ display: flex; gap: 10px; }
    .modal-actions button{
        flex: 1;
        padding: 13px;
        border-radius: 13px;
        font-weight: 700;
        font-size: 13.5px;
        cursor: pointer;
        border: none;
        font-family: inherit;
    }
    .modal-cancel{ background: #F1F3F7; color: rgba(14,23,38,.6); }
    .modal-confirm-update{
        background: var(--blue-cta);
        color: #fff;
    }
    .modal-confirm-update:hover{ background: var(--blue); }

    @media (min-width: 760px){
        .d-grid{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 480px){
        .d-summary{ gap: 8px; }
        .d-stat-tile{ padding: 13px 12px 11px; border-radius: 15px; }
        .d-stat-num{ font-size: 21px; }
        .d-stat-label{ font-size: 10.5px; }
    }
</style>
</head>
<body>

{{-- ========================= --}}
{{-- MODAL: UPDATE STATUS      --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalUpdate">
    <div class="modal-box">
        <div class="modal-icon">🚗</div>
        <div class="modal-title">Konfirmasi Update Status</div>
        <div class="modal-desc" id="modalUpdateDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalUpdate')">✖ Tidak</button>
            <button class="modal-confirm-update" onclick="submitForm('modalUpdate')">✔ Ya, Lanjutkan</button>
        </div>
    </div>
</div>

{{-- NAVBAR --}}
<header class="driver-navbar">
    <div class="driver-brand">
        <span class="ic">🚗</span>
        <span>Driver Panel</span>
    </div>
    <div style="display:flex; align-items:center; gap:14px;">
        <div class="driver-hello">
            Halo,
            <strong>{{ session('driver_name') }}</strong>
        </div>
        <form action="{{ route('driver.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</header>

<div class="driver-wrap">

    @if(session('success'))
        <div class="d-alert d-alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="d-alert d-alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- ========================= --}}
    {{-- RINGKASAN / FILTER        --}}
    {{-- ========================= --}}
    <div class="d-summary">
        <button type="button" class="d-stat-tile active" data-group="tersedia" onclick="filterGroup('tersedia', this)">
            <span class="d-stat-ic">📦</span>
            <span class="d-stat-num">{{ $tersedia->count() }}</span>
            <span class="d-stat-label">Pesanan Tersedia</span>
        </button>
        <button type="button" class="d-stat-tile" data-group="aktif" onclick="filterGroup('aktif', this)">
            <span class="d-stat-ic">🚗</span>
            <span class="d-stat-num">{{ $pesananAktif->count() }}</span>
            <span class="d-stat-label">Pesanan Aktif</span>
        </button>
        <button type="button" class="d-stat-tile" data-group="selesai" onclick="filterGroup('selesai', this)">
            <span class="d-stat-ic">✅</span>
            <span class="d-stat-num">{{ $pesananSelesai->count() }}</span>
            <span class="d-stat-label">Selesai</span>
        </button>
    </div>

    {{-- ========================= --}}
    {{-- PESANAN TERSEDIA          --}}
    {{-- ========================= --}}
    <div class="d-group active" data-group="tersedia">
        <div class="d-group-title">
            Pesanan Tersedia
            <span class="d-badge">{{ $tersedia->count() }}</span>
        </div>

        @if($tersedia->isEmpty())
            <div class="d-empty">Tidak ada pesanan yang tersedia saat ini.</div>
        @else
            <div class="d-grid">
                @foreach($tersedia as $order)
                <div class="d-card">

                    <div class="d-card-top">
                        <div>
                            <span class="d-token">{{ $order->token }}</span>
                            <div class="d-name">{{ $order->nama }}</div>
                        </div>
                    </div>

                    <div class="d-info">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                    <div class="d-info">📞 Telp: <span>{{ $order->phone }}</span></div>
                    <div class="d-info">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>
                    <div class="d-info">📦 Estimasi: <span>{{ $order->estimasi_jumlah_laundry }}</span></div>

                    <div class="d-status-row">
                        <span class="d-status d-status-{{ str_replace(' ', '-', $order->status) }}">{{ $order->status }}</span>
                    </div>

                    <form action="{{ route('driver.ambil', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="d-btn d-btn-ambil">
                            {{ $order->status === 'Diproses' ? '🚗 Ambil Pesanan' : '📦 Ambil dari Laundry' }}
                        </button>
                    </form>

                    <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                       class="d-btn d-btn-detail">🔍 Detail Pesanan</a>

                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ========================= --}}
    {{-- PESANAN AKTIF             --}}
    {{-- ========================= --}}
    <div class="d-group" data-group="aktif">
        <div class="d-group-title">
            Pesanan Aktif
            <span class="d-badge orange">{{ $pesananAktif->count() }}</span>
        </div>

        @if($pesananAktif->isEmpty())
            <div class="d-empty">Tidak ada pesanan aktif saat ini.</div>
        @else
            <div class="d-grid">
                @foreach($pesananAktif as $order)
                <div class="d-card orange">

                    <div class="d-card-top">
                        <div>
                            <span class="d-token">{{ $order->token }}</span>
                            <div class="d-name">{{ $order->nama }}</div>
                        </div>
                    </div>

                    <div class="d-info">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                    <div class="d-info">📞 Telp: <span>{{ $order->phone }}</span></div>
                    <div class="d-info">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>

                    <div class="d-status-row">
                        <span class="d-status d-status-{{ str_replace(' ', '-', $order->status) }}">{{ $order->status }}</span>
                    </div>

                    @php
                        $labelBtn = match($order->status) {
                            'Diproses'        => '🚗 Jemput Cucian Customer',
                            'Dijemput'        => '🔍 Sudah Dijemput',
                            'Mencari Laundry' => '🧺 Sudah di Laundry',
                            'Dicuci'          => '🚚 Antar ke Customer',
                            'Diantar'         => '✅ Selesai Diantar',
                            default           => null,
                        };
                    @endphp

                    @if($labelBtn)
                    <form id="formUpdate{{ $order->id }}"
                          action="{{ route('driver.updateStatus', $order->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                    </form>
                    <button type="button" class="d-btn d-btn-update"
                            onclick="bukaModalUpdate(
                                'formUpdate{{ $order->id }}',
                                '{{ $order->token }}',
                                '{{ addslashes($order->nama) }}',
                                '{{ addslashes($labelBtn) }}',
                                '{{ $order->status }}'
                            )">
                        {{ $labelBtn }}
                    </button>
                    @endif

                    <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                       class="d-btn d-btn-detail orange">🔍 Detail Pesanan</a>

                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ========================= --}}
    {{-- RIWAYAT SELESAI           --}}
    {{-- ========================= --}}
    <div class="d-group" data-group="selesai">
        <div class="d-group-title">
            Riwayat Pesanan Selesai
            <span class="d-badge green">{{ $pesananSelesai->count() }}</span>
        </div>

        @if($pesananSelesai->isEmpty())
            <div class="d-empty">Belum ada riwayat pesanan selesai.</div>
        @else
            <div class="d-grid">
                @foreach($pesananSelesai as $order)
                <div class="d-card green">

                    <div class="d-card-top">
                        <div>
                            <span class="d-token">{{ $order->token }}</span>
                            <div class="d-name">{{ $order->nama }}</div>
                        </div>
                    </div>

                    <div class="d-info">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                    <div class="d-info">📞 Telp: <span>{{ $order->phone }}</span></div>
                    <div class="d-info">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>

                    <div class="d-status-row">
                        <span class="d-status d-status-Selesai">Selesai</span>
                    </div>

                    <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                       class="d-btn d-btn-detail green">🔍 Detail Pesanan</a>

                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
    let targetFormId = null;

    function filterGroup(group, btn){
        document.querySelectorAll('.d-stat-tile').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.d-group').forEach(g => {
            g.classList.toggle('active', g.dataset.group === group);
        });
    }

    function bukaModalUpdate(formId, token, nama, aksi, status) {
        targetFormId = formId;

        const syarat = {
            'Dijemput':        `⚠️ Pastikan sudah upload <strong>bukti pengambilan</strong> pesanan <strong>${token}</strong> di detail pesanan.`,
            'Mencari Laundry': `⚠️ Pastikan sudah upload <strong>bukti nota</strong> pesanan <strong>${token}</strong> di detail pesanan.`,
            'Diantar':         `⚠️ Pastikan sudah upload <strong>bukti pengiriman</strong> pesanan <strong>${token}</strong> di detail pesanan.`,
        };

        const pesanSyarat = syarat[status] ? `<br><br>${syarat[status]}` : '';

        document.getElementById('modalUpdateDesc').innerHTML =
            `Pesanan <strong>${token}</strong> atas nama <strong>${nama}</strong>.<br><br>` +
            `Aksi: <strong>${aksi}</strong>${pesanSyarat}`;
        document.getElementById('modalUpdate').classList.add('active');
    }

    function tutupModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        targetFormId = null;
    }

    function submitForm(modalId) {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
        tutupModal(modalId);
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                targetFormId = null;
            }
        });
    });
</script>

</body>
</html>