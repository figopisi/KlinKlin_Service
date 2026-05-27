<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .navbar .brand { font-size: 18px; font-weight: 700; color: #2563eb; }
        .navbar .driver-name { font-size: 14px; color: #555; }
        .navbar .btn-logout {
            background: #ef4444; color: #fff; border: none;
            padding: 8px 18px; border-radius: 8px; font-size: 13px;
            cursor: pointer; font-family: inherit;
        }

        /* WRAPPER */
        .wrapper { max-width: 1100px; margin: 32px auto; padding: 0 20px; }

        /* SECTION */
        .section-title {
            font-size: 17px; font-weight: 700; color: #1e293b;
            margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }
        .section-title .badge {
            background: #2563eb; color: white;
            font-size: 12px; padding: 2px 10px; border-radius: 20px;
        }
        .section-title .badge.orange { background: #f97316; }
        .section-title .badge.green  { background: #16a34a; }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px; margin-bottom: 40px;
        }

        /* CARD */
        .card {
            background: white; border-radius: 14px; padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid #2563eb;
        }
        .card.orange { border-left-color: #f97316; }
        .card.green  { border-left-color: #16a34a; }

        .order-id { font-size: 12px; color: #94a3b8; margin-bottom: 6px; }
        .customer-name { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .info-row { font-size: 13px; color: #64748b; margin-bottom: 4px; display: flex; gap: 6px; }
        .info-row span { font-weight: 600; color: #334155; }

        /* STATUS */
        .status-badge {
            display: inline-block; padding: 4px 12px;
            border-radius: 20px; font-size: 12px; font-weight: 600; margin: 10px 0;
        }
        .status-Diproses { background: #dbeafe; color: #1d4ed8; }
        .status-Dijemput { background: #fef9c3; color: #a16207; }
        .status-Dicuci   { background: #ede9fe; color: #6d28d9; }
        .status-Diantar  { background: #dcfce7; color: #15803d; }
        .status-Selesai  { background: #f1f5f9; color: #475569; }

        /* BUTTON */
        .btn {
            width: 100%; padding: 10px; border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            font-family: inherit; margin-top: 8px;
            text-align: center; display: block; text-decoration: none;
        }
        .btn-ambil  { background: #2563eb; color: white; }
        .btn-ambil:hover  { background: #1d4ed8; }
        .btn-update { background: #f97316; color: white; }
        .btn-update:hover { background: #ea6c00; }
        .btn-lepas  { background: #ef4444; color: white; }
        .btn-lepas:hover  { background: #dc2626; }

        .btn-detail { background: transparent; color: #2563eb; border: 2px solid #2563eb; }
        .btn-detail:hover { background: #eff6ff; }
        .btn-detail.orange { color: #f97316; border-color: #f97316; }
        .btn-detail.orange:hover { background: #fff7ed; }
        .btn-detail.green  { color: #16a34a; border-color: #16a34a; }
        .btn-detail.green:hover  { background: #f0fdf4; }

        .btn-divider { height: 1px; background: #f1f5f9; margin: 10px 0 2px; }

        /* ALERT */
        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-error   { background: #fee2e2; color: #b91c1c; }

        /* EMPTY */
        .empty {
            text-align: center; color: #94a3b8; font-size: 14px;
            padding: 30px; background: white; border-radius: 14px; margin-bottom: 40px;
        }

        /* ===================== */
        /* MODAL KONFIRMASI      */
        /* ===================== */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 999;
            justify-content: center; align-items: center;
        }
        .modal-overlay.active { display: flex; }

        .modal-box {
            background: white; border-radius: 20px; padding: 32px 28px;
            max-width: 380px; width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center;
            animation: modalIn .2s ease;
        }

        @keyframes modalIn {
            from { transform: scale(.9); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }

        .modal-icon   { font-size: 42px; margin-bottom: 12px; }
        .modal-title  { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
        .modal-desc   { font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.6; }
        .modal-desc strong { color: #1e293b; }

        .modal-actions { display: flex; gap: 10px; }
        .modal-actions button {
            flex: 1; padding: 11px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: .15s;
        }

        .modal-cancel { background: #f1f5f9; color: #475569; }
        .modal-cancel:hover { background: #e2e8f0; }

        .modal-confirm-update { background: #f97316; color: white; }
        .modal-confirm-update:hover { background: #ea6c00; }

        .modal-confirm-lepas { background: #ef4444; color: white; }
        .modal-confirm-lepas:hover { background: #dc2626; }

        @media(max-width: 768px) {
            .navbar { flex-direction: column; gap: 12px; text-align: center; }
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

{{-- ========================= --}}
{{-- MODAL: LEPAS PESANAN      --}}
{{-- ========================= --}}
<div class="modal-overlay" id="modalLepas">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title">Lepaskan Pesanan?</div>
        <div class="modal-desc" id="modalLepasDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalLepas')">✖ Tidak</button>
            <button class="modal-confirm-lepas" onclick="submitForm('modalLepas')">✔ Ya, Lepaskan</button>
        </div>
    </div>
</div>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="brand">🚗 Driver Panel</div>
    <div class="driver-name">Halo, <strong>{{ session('driver_name') }}</strong></div>
    <form action="{{ route('driver.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
    </form>
</nav>

<div class="wrapper">

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- ========================= --}}
    {{-- PESANAN AKTIF             --}}
    {{-- ========================= --}}
    <div class="section-title">
        Pesanan Aktif
        <span class="badge orange">{{ $pesananAktif->count() }}</span>
    </div>

    @if($pesananAktif->isEmpty())
        <div class="empty">Tidak ada pesanan aktif saat ini.</div>
    @else
        <div class="grid">
            @foreach($pesananAktif as $order)
            <div class="card orange">

                <div class="order-id">{{ $order->token }}</div>
                <div class="customer-name">{{ $order->nama }}</div>
                <div class="info-row">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                <div class="info-row">📞 Telp: <span>{{ $order->phone }}</span></div>
                <div class="info-row">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>
                <div><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></div>

                @php
                    $labelBtn = match($order->status) {
                        'Diproses' => '🚗 Jemput Cucian Customer',
                        'Dijemput' => '✅ Sudah di Laundry',
                        'Dicuci'   => '🚚 Antar ke Customer',
                        'Diantar'  => '✅ Selesai Diantar',
                        default    => null,
                    };
                @endphp

                {{-- Form update — hidden, dipanggil modal --}}
                @if($labelBtn)
                <form id="formUpdate{{ $order->id }}"
                      action="{{ route('driver.updateStatus', $order->id) }}"
                      method="POST" style="display:none;">
                    @csrf
                </form>
                <button type="button" class="btn btn-update"
                        onclick="bukaModalUpdate(
                            'formUpdate{{ $order->id }}',
                            '{{ $order->token }}',
                            '{{ addslashes($order->nama) }}',
                            '{{ addslashes($labelBtn) }}'
                        )">
                    {{ $labelBtn }}
                </button>
                @endif

                {{-- Form lepas — hidden, dipanggil modal --}}
                @if($order->status !== 'Selesai')
                <form id="formLepas{{ $order->id }}"
                      action="{{ route('driver.lepas', $order->id) }}"
                      method="POST" style="display:none;">
                    @csrf
                </form>
                <button type="button" class="btn btn-lepas"
                        onclick="bukaModalLepas(
                            'formLepas{{ $order->id }}',
                            '{{ $order->token }}',
                            '{{ addslashes($order->nama) }}'
                        )">
                    ❌ Lepaskan Pesanan
                </button>
                @endif

                <div class="btn-divider"></div>
                <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                   class="btn btn-detail orange">🔍 Detail Pesanan</a>

            </div>
            @endforeach
        </div>
    @endif

    {{-- ========================= --}}
    {{-- PESANAN TERSEDIA          --}}
    {{-- ========================= --}}
    <div class="section-title">
        Pesanan Tersedia
        <span class="badge">{{ $tersedia->count() }}</span>
    </div>

    @if($tersedia->isEmpty())
        <div class="empty">Tidak ada pesanan yang tersedia saat ini.</div>
    @else
        <div class="grid">
            @foreach($tersedia as $order)
            <div class="card">

                <div class="order-id">{{ $order->token }}</div>
                <div class="customer-name">{{ $order->nama }}</div>
                <div class="info-row">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                <div class="info-row">📞 Telp: <span>{{ $order->phone }}</span></div>
                <div class="info-row">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>
                <div class="info-row">📦 Estimasi: <span>{{ $order->estimasi_jumlah_laundry }}</span></div>
                <div><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></div>

                <form action="{{ route('driver.ambil', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ambil">
                        {{ $order->status === 'Diproses' ? '🚗 Ambil Pesanan' : '📦 Ambil dari Laundry' }}
                    </button>
                </form>

                <div class="btn-divider"></div>
                <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                   class="btn btn-detail">🔍 Detail Pesanan</a>

            </div>
            @endforeach
        </div>
    @endif

    {{-- ========================= --}}
    {{-- RIWAYAT SELESAI           --}}
    {{-- ========================= --}}
    <div class="section-title">
        Riwayat Pesanan Selesai
        <span class="badge green">{{ $pesananSelesai->count() }}</span>
    </div>

    @if($pesananSelesai->isEmpty())
        <div class="empty">Belum ada riwayat pesanan selesai.</div>
    @else
        <div class="grid">
            @foreach($pesananSelesai as $order)
            <div class="card green">

                <div class="order-id">{{ $order->token }}</div>
                <div class="customer-name">{{ $order->nama }}</div>
                <div class="info-row">📍 Alamat: <span>{{ $order->alamat_customer }}</span></div>
                <div class="info-row">📞 Telp: <span>{{ $order->phone }}</span></div>
                <div class="info-row">🧺 Layanan: <span>{{ $order->jenis_layanan }}</span></div>
                <div><span class="status-badge status-Selesai">Selesai</span></div>

                <div class="btn-divider"></div>
                <a href="{{ route('driver.pesanan.detail', $order->id) }}"
                   class="btn btn-detail green">🔍 Detail Pesanan</a>

            </div>
            @endforeach
        </div>
    @endif

</div>

<script>
    let targetFormId = null;

    function bukaModalUpdate(formId, token, nama, aksi) {
        targetFormId = formId;
        document.getElementById('modalUpdateDesc').innerHTML =
            `Pesanan <strong>${token}</strong> atas nama <strong>${nama}</strong>.<br><br>` +
            `Aksi: <strong>${aksi}</strong>`;
        document.getElementById('modalUpdate').classList.add('active');
    }

    function bukaModalLepas(formId, token, nama) {
        targetFormId = formId;
        document.getElementById('modalLepasDesc').innerHTML =
            `Pesanan <strong>${token}</strong> atas nama <strong>${nama}</strong> ` +
            `akan dikembalikan ke daftar tersedia dan bisa diambil driver lain.<br><br>` +
            `Yakin ingin melepaskan?`;
        document.getElementById('modalLepas').classList.add('active');
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

    // Klik area gelap di luar modal = tutup
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