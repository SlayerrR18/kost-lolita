{{-- resources/views/user/reports/create.blade.php --}}
@extends('layouts.user')

@section('title','Buat Laporan')

@push('css')
<style>
  .report-wrap{min-height:100vh;background:#f8fafc;padding:2rem}
  .page-header{background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);color:#fff;border-radius:24px;padding:1.75rem;margin-bottom:1.5rem;box-shadow:0 4px 20px rgba(26,127,90,.15)}
  .page-title{font-size:1.6rem;font-weight:800;margin:0}
  .page-sub{opacity:.92;margin:.35rem 0 0}

  .card-soft{background:#fff;border-radius:20px;box-shadow:0 8px 24px rgba(0,0,0,.06);padding:1.5rem}
  .form-label{font-weight:600;color:#0f172a}
  .form-control, .form-select{
    border:2px solid #e2e8f0;border-radius:12px;padding:.8rem .95rem;
    transition:border .2s, box-shadow .2s;
  }
  .form-control:focus, .form-select:focus{
    border-color:#1a7f5a; box-shadow:0 0 0 4px rgba(26,127,90,.12);
  }
  .hint{font-size:.85rem;color:#64748b}
  .field-error{font-size:.85rem;color:#dc2626;margin-top:.35rem}

  .counter{font-size:.85rem;color:#64748b}
  .counter.too-much{color:#dc2626;font-weight:700}

  .dropzone{
    border:2px dashed #cbd5e1;border-radius:16px;padding:1.25rem;text-align:center;background:#f8fafc;
    cursor:pointer; transition:background .15s, border-color .15s;
  }
  .dropzone.dragover{background:#eef7f3;border-color:#1a7f5a}
  .preview{margin-top:1rem;display:none}
  .preview img{max-width:100%;height:auto;border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.08)}

  .btn-primary{
    background:linear-gradient(135deg,#1a7f5a 0%,#16c79a 100%);border:none;color:#fff;
    padding:.85rem 1.25rem;border-radius:12px;font-weight:600;box-shadow:0 6px 16px rgba(26,127,90,.18);
  }
  .btn-primary:hover{opacity:.95;transform:translateY(-1px)}
  .btn-light{
    background:#eef2f7;border:none;color:#0f172a;padding:.85rem 1.1rem;border-radius:12px;font-weight:600;
  }
  @media (max-width:768px){ .report-wrap{padding:1rem} .page-header{padding:1.25rem}}
</style>
@endpush

@section('content')
<div class="report-wrap">
  <div class="page-header">
    <h1 class="page-title">Sampaikan Keluhan Anda</h1>
    <p class="page-sub">Biar masalahnya tercatat rapi, bukan cuma jadi cerita warung kopi.</p>
  </div>

  <div class="card-soft">
    @if(session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger mb-3">
        <strong>Periksa lagi</strong>. Ada isian yang masih salah.
      </div>
    @endif

    <form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
      @csrf

      {{-- Pesan --}}
      <div class="mb-3">
        <label class="form-label" for="message">Saran atau masalah yang dihadapi</label>
        <textarea
          id="message"
          name="message"
          rows="5"
          class="form-control @error('message') is-invalid @enderror"
          placeholder="Tuliskan keluhan atau masukan Anda..."
          maxlength="1000"
          aria-describedby="msgHelp msgCount"
        >{{ old('message') }}</textarea>
        <div id="msgHelp" class="hint">Maks 1000 karakter. Jelaskan singkat, jelas, tidak perlu puisi.</div>
        <div id="msgCount" class="counter mt-1">0/1000</div>
        @error('message')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>

      {{-- Upload Foto --}}
      <div class="mb-3">
        <label class="form-label">Upload Foto (opsional)</label>
        <div id="dropzone" class="dropzone" tabindex="0">
          <div>
            <i data-feather="image" style="width:20px;height:20px;margin-bottom:4px;"></i>
            <div><strong>Tarik & letakkan</strong> foto di sini, atau <u>klik</u> untuk memilih</div>
            <div class="hint mt-1">Format: JPG/PNG/GIF. Maks 10 MB.</div>
          </div>
          <input type="file" name="photo" id="photo" accept="image/*" hidden>
        </div>
        @error('photo')
          <div class="field-error">{{ $message }}</div>
        @enderror
        <div class="preview" id="previewWrap">
          <img id="previewImg" alt="Preview foto">
        </div>
      </div>

      {{-- Tanggal --}}
      <div class="mb-4">
        <label class="form-label" for="date">Tanggal Laporan</label>
        <input
          id="date"
          type="date"
          name="date"
          class="form-control @error('date') is-invalid @enderror"
          value="{{ old('date', date('Y-m-d')) }}"
        >
        @error('date')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('user.reports.index') }}" class="btn-light">Kembali</a>
        <button type="submit" class="btn-primary" id="submitBtn">
          <span class="btn-text">Kirim Sekarang</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('js')
<script>
(function(){
  // Feather icons
  if (window.feather) { feather.replace({ 'stroke-width': 1.5 }); }

  // Char counter
  const message = document.getElementById('message');
  const counter = document.getElementById('msgCount');
  const maxLen = 1000;
  function updateCount(){
    const len = message.value.length;
    counter.textContent = `${len}/${maxLen}`;
    counter.classList.toggle('too-much', len > maxLen);
  }
  if (message && counter) {
    updateCount();
    message.addEventListener('input', updateCount);
  }

  // Dropzone + preview
  const dz = document.getElementById('dropzone');
  const fileInput = document.getElementById('photo');
  const previewWrap = document.getElementById('previewWrap');
  const previewImg = document.getElementById('previewImg');
  const MAX = 10 * 1024 * 1024; // 10MB

  function handleFiles(files){
    const f = files && files[0];
    if (!f) return;
    if (!f.type.startsWith('image/')) { alert('File harus berupa gambar.'); return; }
    if (f.size > MAX) { alert('Ukuran gambar maksimal 10 MB.'); return; }
    // set ke input kalau datang dari drop
    if (fileInput.files !== files) {
      const dt = new DataTransfer(); dt.items.add(f); fileInput.files = dt.files;
    }
    const reader = new FileReader();
    reader.onload = e => {
      previewImg.src = e.target.result;
      previewWrap.style.display = 'block';
      if (window.feather) feather.replace();
    };
    reader.readAsDataURL(f);
  }

  if (dz && fileInput) {
    dz.addEventListener('click', () => fileInput.click());
    dz.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fileInput.click(); }});
    fileInput.addEventListener('change', e => handleFiles(e.target.files));

    ['dragenter','dragover'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); dz.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); dz.classList.remove('dragover'); }));
    dz.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
  }

  // Disable tombol saat submit biar user tidak marathon klik
  const form = document.getElementById('reportForm');
  const submitBtn = document.getElementById('submitBtn');
  if (form && submitBtn) {
    form.addEventListener('submit', function(){
      submitBtn.disabled = true;
      submitBtn.style.opacity = .8;
      submitBtn.querySelector('.btn-text').textContent = 'Mengirim...';
    });
  }
})();
</script>
@endpush
