<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Promo Baru</title>
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
        max-width:920px;
        margin:0 auto;
        padding:40px 24px 80px;
    }

    /* HEADER */
    .page-header{ margin-bottom:28px; }
    .back-link{
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-size:13px;
        font-weight:600;
        color:var(--ink-soft);
        text-decoration:none;
        margin-bottom:14px;
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
        font-size:28px;
        font-weight:800;
        margin:0;
        letter-spacing:-0.02em;
    }
    .page-header p{
        margin:6px 0 0;
        color:var(--ink-soft);
        font-size:14px;
    }

    .alert{
        padding:14px 18px;
        border-radius:10px;
        font-size:14px;
        font-weight:500;
        margin-bottom:24px;
        border:1px solid transparent;
    }
    .alert-error{ background:var(--danger-tint); color:#B4232A; border-color:#f5c6c8; }
    .alert-error ul{ margin:6px 0 0; padding-left:18px; }

    /* LAYOUT */
    .form-layout{
        display:grid;
        grid-template-columns:1fr 300px;
        gap:20px;
        align-items:start;
    }

    .card{
        background:var(--surface);
        border:1px solid var(--line);
        border-radius:var(--radius);
        padding:26px 24px;
        box-shadow:var(--shadow-sm);
    }
    .card + .card{ margin-top:16px; }

    .section-title{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:700;
        font-size:14.5px;
        margin-bottom:16px;
        display:flex;
        align-items:center;
        gap:8px;
    }
    .section-title .dot{
        width:6px;height:6px;border-radius:50%;
        background:var(--brand);
    }

    .field{ margin-bottom:16px; }
    .field:last-child{ margin-bottom:0; }
    .field label{
        display:block;
        font-size:12.5px;
        font-weight:600;
        color:var(--ink-soft);
        margin-bottom:6px;
    }
    .field .hint{
        font-size:11.5px;
        color:var(--muted);
        margin-top:5px;
        font-weight:400;
    }
    .field input[type="text"],
    .field input[type="number"],
    .field input[type="datetime-local"],
    .field textarea{
        width:100%;
        border:1px solid var(--line);
        background:var(--canvas);
        border-radius:9px;
        padding:11px 13px;
        font-size:14px;
        font-family:'Plus Jakarta Sans',sans-serif;
        color:var(--ink);
        outline:none;
        transition:border-color .15s ease, background .15s ease;
    }
    .field input:focus, .field textarea:focus{
        border-color:var(--brand);
        background:#fff;
    }
    .field textarea{ resize:vertical; min-height:80px; }
    .field.error input, .field.error textarea{ border-color:var(--danger); background:var(--danger-tint); }
    .field .err-msg{ color:var(--danger); font-size:11.5px; margin-top:5px; font-weight:500; }

    .row-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
    }

    .price-input-wrap{ position:relative; }
    .price-input-wrap span{
        position:absolute; left:13px; top:50%; transform:translateY(-50%);
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:13px; font-weight:600; color:var(--ink-soft);
    }
    .price-input-wrap input{ padding-left:38px !important; font-family:'Plus Jakarta Sans',sans-serif; }

    /* TOGGLE ROW */
    .toggle-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        background:var(--canvas);
        border:1px solid var(--line);
        border-radius:9px;
        padding:12px 14px;
    }
    .toggle-row .txt{ font-size:13px; font-weight:600; }
    .toggle-row .sub{ font-size:11.5px; color:var(--ink-soft); font-weight:400; margin-top:2px; }
    .switch{ position:relative; display:inline-block; width:42px; height:24px; flex-shrink:0; }
    .switch input{ opacity:0; width:0; height:0; }
    .slider{
        position:absolute; cursor:pointer; inset:0;
        background:var(--muted); border-radius:24px; transition:.2s;
    }
    .slider::before{
        content:""; position:absolute; height:18px; width:18px;
        left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s;
    }
    .switch input:checked + .slider{ background:var(--brand); }
    .switch input:checked + .slider::before{ transform:translateX(18px); }

    /* SIDEBAR PREVIEW */
    .preview-sticky{ position:sticky; top:24px; }
    .preview-label{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:11px;
        letter-spacing:0.08em;
        text-transform:uppercase;
        color:var(--muted);
        font-weight:700;
        margin-bottom:12px;
    }
    .preview-card{
        background:var(--ink);
        border-radius:16px;
        padding:22px 20px;
        color:#fff;
        position:relative;
        overflow:hidden;
    }
    .preview-card::after{
        content:"";
        position:absolute;
        top:-40px; right:-40px;
        width:120px; height:120px;
        background:radial-gradient(circle, rgba(102,156,242,0.28), transparent 70%);
    }
    .preview-off{
        display:inline-block;
        background:var(--accent);
        color:#fff;
        font-size:11px;
        font-weight:700;
        padding:4px 10px;
        border-radius:6px;
        margin-bottom:12px;
    }
    .preview-name{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:700;
        font-size:17px;
        margin-bottom:6px;
        line-height:1.3;
        min-height:22px;
    }
    .preview-desc{
        font-size:12.5px;
        color:#9AB0D6;
        line-height:1.5;
        margin-bottom:18px;
        min-height:19px;
    }
    .preview-price .old{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:12.5px;
        color:#9AB0D6;
        text-decoration:line-through;
        display:block;
    }
    .preview-price .new{
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:24px;
        font-weight:700;
        display:block;
        margin-top:2px;
    }
    .preview-note{
        font-size:11.5px;
        color:var(--ink-soft);
        margin-top:14px;
        line-height:1.5;
    }

    /* SUBMIT BAR */
    .submit-bar{
        display:flex;
        gap:10px;
        margin-top:20px;
    }
    .btn{
        flex:1;
        padding:13px;
        border-radius:10px;
        font-weight:600;
        font-size:14px;
        cursor:pointer;
        border:none;
        text-align:center;
        text-decoration:none;
        font-family:'Plus Jakarta Sans',sans-serif;
        transition:background .15s ease, transform .15s ease;
    }
    .btn-cancel{ background:var(--muted-tint); color:var(--ink-soft); flex:0 0 auto; padding:13px 22px; }
    .btn-cancel:hover{ background:#e3e6ea; }
    .btn-submit{ background:var(--brand); color:#fff; }
    .btn-submit:hover{ background:var(--brand-dark); transform:translateY(-1px); }

    @media (max-width: 800px){
        .form-layout{ grid-template-columns:1fr; }
        .preview-sticky{ position:static; }
        .row-2{ grid-template-columns:1fr; }
    }
</style>
</head>
<body>

<div class="wrap">

    <div class="page-header">
        <a href="{{ route('admin.promosi.index') }}" class="back-link">&larr; Kembali ke Manajemen Promo</a>
        <span class="eyebrow">Promo Baru</span>
        <h1>Buat Promo</h1>
        <p>Isi detail promo di bawah — preview akan muncul otomatis di samping.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            Ada beberapa isian yang perlu diperbaiki:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.promosi.store') }}" id="promoForm">
        @csrf

        <div class="form-layout">

            <!-- LEFT: FORM FIELDS -->
            <div>
                <div class="card">
                    <div class="section-title"><span class="dot"></span>Informasi Promo</div>

                    <div class="field {{ $errors->has('nama_promo') ? 'error' : '' }}">
                        <label>Nama Promo</label>
                        <input type="text" name="nama_promo" id="input_nama"
                               placeholder="Contoh: Diskon Akhir Bulan"
                               value="{{ old('nama_promo') }}" oninput="updatePreview()">
                        @error('nama_promo') <div class="err-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="field {{ $errors->has('deskripsi') ? 'error' : '' }}">
                        <label>Deskripsi / Syarat &amp; Ketentuan</label>
                        <textarea name="deskripsi" id="input_deskripsi"
                                  placeholder="Contoh: Berlaku untuk layanan cuci setrika, minimal 3kg"
                                  oninput="updatePreview()">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="err-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="section-title"><span class="dot"></span>Harga</div>

                    <div class="row-2">
                        <div class="field {{ $errors->has('harga_awal') ? 'error' : '' }}">
                            <label>Harga Awal</label>
                            <div class="price-input-wrap">
                                <span>Rp</span>
                                <input type="number" name="harga_awal" id="input_harga_awal"
                                       placeholder="0" min="0"
                                       value="{{ old('harga_awal') }}" oninput="updatePreview()">
                            </div>
                            @error('harga_awal') <div class="err-msg">{{ $message }}</div> @enderror
                        </div>

                        <div class="field {{ $errors->has('harga_promo') ? 'error' : '' }}">
                            <label>Harga Setelah Promo</label>
                            <div class="price-input-wrap">
                                <span>Rp</span>
                                <input type="number" name="harga_promo" id="input_harga_promo"
                                       placeholder="0" min="0"
                                       value="{{ old('harga_promo') }}" oninput="updatePreview()">
                            </div>
                            @error('harga_promo') <div class="err-msg">{{ $message }}</div> @enderror
                            <div class="hint">Harus lebih murah dari harga awal.</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="section-title"><span class="dot"></span>Batas &amp; Masa Berlaku</div>

                    <div class="field {{ $errors->has('kuota') ? 'error' : '' }}">
                        <label>Kuota Pemakaian</label>
                        <input type="number" name="kuota" min="1"
                               placeholder="Kosongkan jika tanpa batas"
                               value="{{ old('kuota') }}">
                        @error('kuota') <div class="err-msg">{{ $message }}</div> @enderror
                        <div class="hint">Promo otomatis berhenti tampil jika kuota terpakai habis.</div>
                    </div>

                    <div class="row-2">
                        <div class="field {{ $errors->has('tanggal_mulai') ? 'error' : '' }}">
                            <label>Tanggal Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai') <div class="err-msg">{{ $message }}</div> @enderror
                        </div>
                        <div class="field {{ $errors->has('tanggal_selesai') ? 'error' : '' }}">
                            <label>Tanggal Selesai</label>
                            <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai') <div class="err-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="hint" style="margin-top:-6px;">Kosongkan keduanya jika promo berlaku tanpa batas waktu.</div>

                    <div class="field" style="margin-top:16px;">
                        <div class="toggle-row">
                            <div>
                                <div class="txt">Aktifkan promo sekarang</div>
                                <div class="sub">Promo langsung tampil ke pelanggan setelah disimpan</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="is_active" value="1" checked id="input_active" onchange="updatePreview()">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="submit-bar">
                    <a href="{{ route('admin.promosi.index') }}" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-submit">Simpan Promo</button>
                </div>
            </div>

            <!-- RIGHT: LIVE PREVIEW -->
            <div class="preview-sticky">
                <div class="preview-label">Preview Tampilan</div>
                <div class="preview-card">
                    <span class="preview-off" id="preview_off">-0%</span>
                    <div class="preview-name" id="preview_name">Nama promo</div>
                    <div class="preview-desc" id="preview_desc">Deskripsi akan muncul di sini</div>
                    <div class="preview-price">
                        <span class="old" id="preview_old">Rp0</span>
                        <span class="new" id="preview_new">Rp0</span>
                    </div>
                </div>
                <div class="preview-note">Preview ini menunjukkan gambaran kasar tampilan promo untuk pelanggan.</div>
            </div>

        </div>
    </form>

</div>

<script>
    function formatRupiah(angka){
        angka = parseInt(angka) || 0;
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function updatePreview(){
        const nama = document.getElementById('input_nama').value || 'Nama promo';
        const desc = document.getElementById('input_deskripsi').value || 'Deskripsi akan muncul di sini';
        const awal = parseInt(document.getElementById('input_harga_awal').value) || 0;
        const promo = parseInt(document.getElementById('input_harga_promo').value) || 0;

        document.getElementById('preview_name').innerText = nama;
        document.getElementById('preview_desc').innerText = desc;
        document.getElementById('preview_old').innerText = formatRupiah(awal);
        document.getElementById('preview_new').innerText = formatRupiah(promo);

        let persen = 0;
        if(awal > 0 && promo > 0 && promo < awal){
            persen = Math.round(((awal - promo) / awal) * 100);
        }
        document.getElementById('preview_off').innerText = '-' + persen + '%';
    }

    updatePreview();
</script>

</body>
</html>