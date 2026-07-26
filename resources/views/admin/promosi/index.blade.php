<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Promo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root{
        --ink:#1F324E;
        --ink-soft:#5A6B85;
        --canvas:#EEF2F8;
        --surface:#FFFFFF;
        --brand:#4873B4;
        --brand-dark:#1F324E;
        --brand-tint:#EAF1FD;
        --accent:#669CF2;
        --accent-tint:#EAF1FD;
        --muted:#9AA5B1;
        --muted-tint:#EEF0F3;
        --line:#E2E9F5;
        --danger:#E5484D;
        --danger-tint:#FDEBEC;
        --radius:14px;
        --shadow-sm: 0 1px 2px rgba(31,50,78,0.05);
        --shadow-md: 0 8px 24px rgba(31,50,78,0.10);
    }
    *{box-sizing:border-box;}
    body{
        margin:0;
        background:var(--canvas);
        font-family:'Plus Jakarta Sans',sans-serif;
        color:var(--ink);
        -webkit-font-smoothing:antialiased;
    }
    .wrap{
        max-width:1160px;
        margin:0 auto;
        padding:40px 24px 80px;
    }

    /* ===== HEADER ===== */
    .page-header{
        display:flex;
        justify-content:space-between;
        align-items:flex-end;
        flex-wrap:wrap;
        gap:20px;
        margin-bottom:32px;
    }
    .back-link{
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-size:13px;
        font-weight:600;
        color:var(--ink-soft);
        text-decoration:none;
        margin-bottom:16px;
    }
    .back-link:hover{ color:var(--brand); }
    .page-header .eyebrow{
        font-family:'Plus Jakarta Sans', sans-serif;
        font-size:12px;
        letter-spacing:0.08em;
        text-transform:uppercase;
        color:var(--brand);
        font-weight:700;
        margin-bottom:6px;
        display:block;
    }
    .page-header h1{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:32px;
        font-weight:800;
        margin:0;
        letter-spacing:-0.02em;
    }
    .page-header p{
        margin:6px 0 0;
        color:var(--ink-soft);
        font-size:14px;
    }
    .btn-new{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:var(--brand);
        color:#fff;
        font-weight:600;
        font-size:14px;
        padding:13px 22px;
        border-radius:10px;
        text-decoration:none;
        box-shadow:var(--shadow-sm);
        transition:background .15s ease, transform .15s ease;
        border:none;
        cursor:pointer;
        white-space:nowrap;
    }
    .btn-new:hover{ background:var(--brand-dark); transform:translateY(-1px); }

    /* ===== ALERTS ===== */
    .alert{
        padding:14px 18px;
        border-radius:10px;
        font-size:14px;
        font-weight:500;
        margin-bottom:24px;
        border:1px solid transparent;
    }
    .alert-success{ background:var(--brand-tint); color:var(--brand-dark); border-color:#c7d9f2; }
    .alert-error{ background:var(--danger-tint); color:#B4232A; border-color:#f5c6c8; }

    /* ===== STAT STRIP ===== */
    .stat-strip{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:14px;
        margin-bottom:28px;
    }
    .stat-box{
        background:var(--surface);
        border:1px solid var(--line);
        border-radius:var(--radius);
        padding:18px 20px;
    }
    .stat-box .num{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:26px;
        font-weight:800;
        line-height:1;
    }
    .stat-box .label{
        font-size:12.5px;
        color:var(--ink-soft);
        margin-top:6px;
        font-weight:500;
    }
    .stat-box.brand .num{ color:var(--brand); }
    .stat-box.accent .num{ color:var(--accent); }
    .stat-box.muted .num{ color:var(--muted); }

    /* ===== TABS / FILTER ===== */
    .filter-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:18px;
    }
    .tabs{
        display:inline-flex;
        background:var(--surface);
        border:1px solid var(--line);
        border-radius:10px;
        padding:4px;
        gap:2px;
    }
    .tab-btn{
        border:none;
        background:transparent;
        padding:9px 18px;
        border-radius:7px;
        font-size:13.5px;
        font-weight:600;
        color:var(--ink-soft);
        cursor:pointer;
        transition:all .15s ease;
        font-family:'Plus Jakarta Sans',sans-serif;
    }
    .tab-btn.active{
        background:var(--ink);
        color:#fff;
    }
    .tab-count{
        display:inline-block;
        margin-left:6px;
        opacity:0.6;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:11.5px;
    }
    .search-box{
        position:relative;
    }
    .search-box input{
        border:1px solid var(--line);
        background:var(--surface);
        border-radius:10px;
        padding:10px 14px 10px 36px;
        font-size:13.5px;
        font-family:'Plus Jakarta Sans',sans-serif;
        width:220px;
        outline:none;
        transition:border-color .15s ease;
    }
    .search-box input:focus{ border-color:var(--brand); }
    .search-box::before{
        content:"⌕";
        position:absolute;
        left:13px;
        top:50%;
        transform:translateY(-50%);
        color:var(--muted);
        font-size:16px;
    }

    /* ===== PROMO LIST ===== */
    .promo-list{
        display:flex;
        flex-direction:column;
        gap:10px;
    }
    .promo-row{
        background:var(--surface);
        border:1px solid var(--line);
        border-radius:var(--radius);
        padding:18px 20px;
        display:grid;
        grid-template-columns:1fr auto auto auto;
        align-items:center;
        gap:24px;
        box-shadow:var(--shadow-sm);
        transition:box-shadow .15s ease, border-color .15s ease;
    }
    .promo-row:hover{ box-shadow:var(--shadow-md); border-color:#c7d5ec; }
    .promo-row.inactive{ opacity:0.6; }

    .promo-info .name{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:700;
        font-size:16px;
        margin-bottom:4px;
    }
    .promo-info .desc{
        font-size:13px;
        color:var(--ink-soft);
        max-width:340px;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }
    .promo-info .meta{
        margin-top:8px;
        display:flex;
        gap:14px;
        flex-wrap:wrap;
    }
    .meta-chip{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:11px;
        font-weight:600;
        color:var(--ink-soft);
        background:var(--muted-tint);
        padding:3px 8px;
        border-radius:5px;
        display:inline-flex;
        align-items:center;
        gap:4px;
    }
    .meta-chip.warn{ background:var(--accent-tint); color:#2455A4; }

    /* signature price tag */
    .price-tag{
        position:relative;
        background:var(--ink);
        color:#fff;
        border-radius:9px;
        padding:10px 16px 10px 20px;
        min-width:150px;
        text-align:right;
    }
    .price-tag::before{
        content:"";
        position:absolute;
        left:-1px;
        top:50%;
        transform:translateY(-50%);
        width:10px;
        height:10px;
        background:var(--canvas);
        border-radius:50%;
        border:1px solid var(--line);
    }
    .price-tag .old{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:12px;
        color:#9AB0D6;
        text-decoration:line-through;
        display:block;
    }
    .price-tag .new{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:17px;
        font-weight:700;
        color:#fff;
        display:block;
        margin-top:1px;
    }
    .price-tag .off{
        position:absolute;
        top:-9px;
        right:10px;
        background:var(--accent);
        color:#fff;
        font-size:10.5px;
        font-weight:700;
        padding:2px 7px;
        border-radius:5px;
        font-family:'Plus Jakarta Sans',sans-serif;
    }

    /* toggle switch */
    .toggle-wrap{
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:5px;
    }
    .switch{
        position:relative;
        display:inline-block;
        width:42px;
        height:24px;
    }
    .switch input{ opacity:0; width:0; height:0; }
    .slider{
        position:absolute;
        cursor:pointer;
        inset:0;
        background:var(--muted);
        border-radius:24px;
        transition:.2s;
    }
    .slider::before{
        content:"";
        position:absolute;
        height:18px; width:18px;
        left:3px; bottom:3px;
        background:#fff;
        border-radius:50%;
        transition:.2s;
    }
    .switch input:checked + .slider{ background:var(--brand); }
    .switch input:checked + .slider::before{ transform:translateX(18px); }
    .toggle-label{
        font-size:10.5px;
        font-weight:700;
        color:var(--ink-soft);
        font-family:'Plus Jakarta Sans',sans-serif;
        letter-spacing:0.02em;
    }

    /* actions */
    .row-actions{
        display:flex;
        gap:8px;
    }
    .icon-btn{
        width:36px; height:36px;
        border-radius:9px;
        border:1px solid var(--line);
        background:var(--surface);
        display:flex; align-items:center; justify-content:center;
        cursor:pointer;
        text-decoration:none;
        font-size:15px;
        color:var(--ink-soft);
        transition:all .15s ease;
    }
    .icon-btn:hover{ border-color:var(--brand); color:var(--brand); background:var(--brand-tint); }
    .icon-btn.danger:hover{ border-color:var(--danger); color:var(--danger); background:var(--danger-tint); }

    /* empty state */
    .empty-state{
        background:var(--surface);
        border:1px dashed var(--line);
        border-radius:var(--radius);
        padding:60px 20px;
        text-align:center;
    }
    .empty-state .icon{ font-size:34px; margin-bottom:12px; }
    .empty-state h3{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:17px;
        margin:0 0 6px;
    }
    .empty-state p{
        color:var(--ink-soft);
        font-size:13.5px;
        margin:0 0 18px;
    }

    /* ===== MODAL ===== */
    .modal-overlay{
        display:none;
        position:fixed; inset:0;
        background:rgba(31,50,78,0.55);
        backdrop-filter:blur(2px);
        align-items:center; justify-content:center;
        z-index:100;
        padding:20px;
    }
    .modal-overlay.active{ display:flex; }
    .modal-box{
        background:#fff;
        border-radius:16px;
        padding:28px 26px;
        max-width:380px;
        width:100%;
        text-align:center;
        box-shadow:0 20px 60px rgba(31,50,78,0.25);
    }
    .modal-icon{ font-size:32px; margin-bottom:10px; }
    .modal-title{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:700;
        font-size:18px;
        margin-bottom:8px;
    }
    .modal-desc{
        font-size:13.5px;
        color:var(--ink-soft);
        line-height:1.55;
        margin-bottom:22px;
    }
    .modal-actions{ display:flex; gap:10px; }
    .modal-actions button{
        flex:1;
        padding:12px;
        border-radius:10px;
        font-weight:600;
        font-size:13.5px;
        cursor:pointer;
        border:none;
        font-family:'Plus Jakarta Sans',sans-serif;
    }
    .modal-cancel{ background:var(--muted-tint); color:var(--ink-soft); }
    .modal-confirm-danger{ background:var(--danger); color:#fff; }
    .modal-confirm-brand{ background:var(--brand); color:#fff; }

    @media (max-width: 720px){
        .stat-strip{ grid-template-columns:repeat(2,1fr); }
        .promo-row{ grid-template-columns:1fr; }
        .price-tag{ text-align:left; }
        .row-actions{ justify-content:flex-start; }
        .search-box input{ width:150px; }
    }
</style>
</head>
<body>

{{-- MODAL: HAPUS PROMO --}}
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <div class="modal-title">Hapus Promo?</div>
        <div class="modal-desc" id="modalHapusDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalHapus')">Batal</button>
            <button class="modal-confirm-danger" onclick="submitForm('modalHapus')">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- MODAL: TOGGLE STATUS --}}
<div class="modal-overlay" id="modalToggle">
    <div class="modal-box">
        <div class="modal-icon" id="modalToggleIcon">🔔</div>
        <div class="modal-title" id="modalToggleTitle">Ubah Status Promo?</div>
        <div class="modal-desc" id="modalToggleDesc"></div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="tutupModal('modalToggle')">Batal</button>
            <button class="modal-confirm-brand" onclick="submitForm('modalToggle')">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<div class="wrap">

    <div class="page-header">
        <div>
            <a href="/admin/dashboard" class="back-link">&larr; Kembali ke Dashboard</a>
            <span class="eyebrow">Manajemen Promo</span>
            <h1>Promo &amp; Diskon</h1>
            <p>Kelola semua promo yang tampil ke pelanggan dari satu tempat.</p>
        </div>
        <a href="{{ route('admin.promosi.create') }}" class="btn-new">+ Buat Promo Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @php
        $totalPromo   = $promotions->count();
        $totalAktif   = $promotions->where('is_active', true)->count();
        $totalNonaktif= $promotions->where('is_active', false)->count();
        $totalHabis   = $promotions->filter(function($p){
            return $p->kuota !== null && $p->terpakai >= $p->kuota;
        })->count();
    @endphp

    <div class="stat-strip">
        <div class="stat-box">
            <div class="num">{{ $totalPromo }}</div>
            <div class="label">Total Promo</div>
        </div>
        <div class="stat-box brand">
            <div class="num">{{ $totalAktif }}</div>
            <div class="label">Sedang Aktif</div>
        </div>
        <div class="stat-box muted">
            <div class="num">{{ $totalNonaktif }}</div>
            <div class="label">Nonaktif</div>
        </div>
        <div class="stat-box accent">
            <div class="num">{{ $totalHabis }}</div>
            <div class="label">Kuota Habis</div>
        </div>
    </div>

    <div class="filter-row">
        <div class="tabs">
            <button class="tab-btn active" onclick="filterPromo('all', this)">
                Semua <span class="tab-count">{{ $totalPromo }}</span>
            </button>
            <button class="tab-btn" onclick="filterPromo('active', this)">
                Aktif <span class="tab-count">{{ $totalAktif }}</span>
            </button>
            <button class="tab-btn" onclick="filterPromo('inactive', this)">
                Nonaktif <span class="tab-count">{{ $totalNonaktif }}</span>
            </button>
        </div>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari nama promo..." onkeyup="searchPromo()">
        </div>
    </div>

    <div class="promo-list" id="promoList">
        @forelse($promotions as $promo)
            @php
                $habis = $promo->kuota !== null && $promo->terpakai >= $promo->kuota;
                $expired = $promo->tanggal_selesai && now()->gt($promo->tanggal_selesai);
            @endphp
            <div class="promo-row {{ !$promo->is_active ? 'inactive' : '' }}"
                 data-status="{{ $promo->is_active ? 'active' : 'inactive' }}"
                 data-name="{{ strtolower($promo->nama_promo) }}">

                <div class="promo-info">
                    <div class="name">{{ $promo->nama_promo }}</div>
                    @if($promo->deskripsi)
                        <div class="desc">{{ $promo->deskripsi }}</div>
                    @endif
                    <div class="meta">
                        @if($promo->tanggal_mulai || $promo->tanggal_selesai)
                            <span class="meta-chip {{ $expired ? 'warn' : '' }}">
                                📅 {{ $promo->tanggal_mulai ? \Carbon\Carbon::parse($promo->tanggal_mulai)->translatedFormat('d M Y') : '—' }}
                                &rarr;
                                {{ $promo->tanggal_selesai ? \Carbon\Carbon::parse($promo->tanggal_selesai)->translatedFormat('d M Y') : '—' }}
                                @if($expired) (Berakhir) @endif
                            </span>
                        @endif
                        @if($promo->kuota !== null)
                            <span class="meta-chip {{ $habis ? 'warn' : '' }}">
                                🎟️ {{ $promo->terpakai }}/{{ $promo->kuota }} terpakai
                                @if($habis) (Habis) @endif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="price-tag">
                    <span class="off">-{{ $promo->persen_diskon }}%</span>
                    <span class="old">Rp{{ number_format($promo->harga_awal, 0, ',', '.') }}</span>
                    <span class="new">Rp{{ number_format($promo->harga_promo, 0, ',', '.') }}</span>
                </div>

                <div class="toggle-wrap">
                    <form id="formToggle{{ $promo->id }}"
                          action="{{ route('admin.promosi.update', $promo->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="nama_promo" value="{{ $promo->nama_promo }}">
                        <input type="hidden" name="deskripsi" value="{{ $promo->deskripsi }}">
                        <input type="hidden" name="harga_awal" value="{{ $promo->harga_awal }}">
                        <input type="hidden" name="harga_promo" value="{{ $promo->harga_promo }}">
                        <input type="hidden" name="kuota" value="{{ $promo->kuota }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ $promo->tanggal_mulai }}">
                        <input type="hidden" name="tanggal_selesai" value="{{ $promo->tanggal_selesai }}">
                        <input type="hidden" name="is_active" value="{{ $promo->is_active ? '0' : '1' }}">
                    </form>
                    <label class="switch">
                        <input type="checkbox" {{ $promo->is_active ? 'checked' : '' }}
                               onclick="event.preventDefault(); bukaModalToggle('formToggle{{ $promo->id }}', '{{ addslashes($promo->nama_promo) }}', {{ $promo->is_active ? 'true' : 'false' }})">
                        <span class="slider"></span>
                    </label>
                    <span class="toggle-label">{{ $promo->is_active ? 'AKTIF' : 'OFF' }}</span>
                </div>

                <div class="row-actions">
                    <a href="{{ route('admin.promosi.edit', $promo->id) }}" class="icon-btn" title="Edit">✏️</a>
                    <form id="formHapus{{ $promo->id }}"
                          action="{{ route('admin.promosi.destroy', $promo->id) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="icon-btn danger" title="Hapus"
                            onclick="bukaModalHapus('formHapus{{ $promo->id }}', '{{ addslashes($promo->nama_promo) }}')">🗑️</button>
                </div>

            </div>
        @empty
            <div class="empty-state">
                <div class="icon">🏷️</div>
                <h3>Belum ada promo</h3>
                <p>Buat promo pertama untuk mulai menarik pelanggan.</p>
                <a href="{{ route('admin.promosi.create') }}" class="btn-new">+ Buat Promo Baru</a>
            </div>
        @endforelse
    </div>

</div>

<script>
    let targetFormId = null;

    function bukaModalHapus(formId, nama){
        targetFormId = formId;
        document.getElementById('modalHapusDesc').innerHTML =
            `Promo <strong>${nama}</strong> akan dihapus permanen dan tidak bisa dikembalikan.`;
        document.getElementById('modalHapus').classList.add('active');
    }

    function bukaModalToggle(formId, nama, sedangAktif){
        targetFormId = formId;
        const icon = sedangAktif ? '⏸️' : '▶️';
        const title = sedangAktif ? 'Nonaktifkan Promo?' : 'Aktifkan Promo?';
        const desc = sedangAktif
            ? `Promo <strong>${nama}</strong> akan disembunyikan dari pelanggan.`
            : `Promo <strong>${nama}</strong> akan tampil kembali ke pelanggan.`;
        document.getElementById('modalToggleIcon').innerText = icon;
        document.getElementById('modalToggleTitle').innerText = title;
        document.getElementById('modalToggleDesc').innerHTML = desc;
        document.getElementById('modalToggle').classList.add('active');
    }

    function tutupModal(modalId){
        document.getElementById(modalId).classList.remove('active');
        targetFormId = null;
    }

    function submitForm(modalId){
        if(targetFormId){
            document.getElementById(targetFormId).submit();
        }
        tutupModal(modalId);
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e){
            if(e.target === this){
                this.classList.remove('active');
                targetFormId = null;
            }
        });
    });

    function filterPromo(status, btn){
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.promo-row').forEach(row => {
            if(status === 'all' || row.dataset.status === status){
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function searchPromo(){
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.promo-row').forEach(row => {
            row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
    }
</script>

</body>
</html>