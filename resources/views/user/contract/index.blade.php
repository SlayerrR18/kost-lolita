{{-- resources/views/user/contract/index.blade.php --}}
@extends('layouts.user')
@section('title','Kontrak')

@push('css')
<style>
  :root{ --brand:#1a7f5a; --brand2:#16c79a; --bg:#f8fafc; --ink:#0f172a; --muted:#64748b; }
  .page{min-height:100vh;background:var(--bg);padding:28px 20px;}
  .container-narrow{max-width:1200px;margin:0 auto;}
  .hero{background:linear-gradient(135deg,var(--brand) 0%,var(--brand2) 100%);
        color:#fff;border-radius:20px;padding:20px 24px;margin-bottom:20px;
        display:flex;align-items:center;justify-content:space-between}
  .hero h1{font-size:1.25rem;margin:0}
  .grid{display:grid;gap:18px;grid-template-columns:repeat(12,1fr)}
  .col-5{grid-column:span 5}
  .col-7{grid-column:span 7}
  @media (max-width:992px){ .col-5,.col-7{grid-column:span 12} }

  .card{background:#fff;border-radius:18px;padding:22px;box-shadow:0 4px 14px rgba(0,0,0,.06)}
  .profile{display:flex;flex-direction:column;align-items:center;gap:14px}
  .profile img{width:128px;height:128px;border-radius:50%;object-fit:cover;border:4px solid #fff;
               box-shadow:0 6px 18px rgba(0,0,0,.1)}
  .h6{font-size:1rem;color:var(--muted);margin:0 0 .4rem}
  .val{font-size:1.15rem;color:var(--ink);font-weight:700}
  .row{display:grid;gap:14px;grid-template-columns:repeat(2,1fr)}
  .row .cell{background:#f8fafc;border-radius:12px;padding:14px}
  .actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:16px}
  .btn{border:none;border-radius:10px;padding:.7rem 1rem;font-weight:600;display:inline-flex;align-items:center;gap:.5rem}
  .btn-primary{background:var(--brand);color:#fff}
  .btn-outline{background:#eef2f7}
</style>
@endpush

@section('content')
<div class="page">
  <div class="container-narrow">
    <div class="hero">
      <div><h1>Kontrak</h1><div style="opacity:.9;font-size:.9rem">{{ now()->translatedFormat('d M Y') }}</div></div>
      @if($contract && optional($contract->tanggal_keluar)->diffInDays(now()) <= 120)
        <a class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#extendModal">
          <i data-feather="refresh-ccw"></i> Perpanjang Kontrak
        </a>
      @endif
    </div>

    @if($contract)
      <div class="grid">
        <div class="col-5">
          <div class="card profile">
            <img src="{{ $contract->ktp_image_url ?? asset('images/placeholder.jpg') }}" alt="KTP">
            <div class="val">{{ $contract->name }}</div>
            <div style="color:#64748b">{{ $contract->email }}</div>
            <div style="color:#64748b">{{ $contract->phone }}</div>
            <div style="color:#64748b;text-align:center">{{ $contract->alamat }}</div>
          </div>
        </div>

        <div class="col-7">
          <div class="card">
            <div class="row">
              <div class="cell">
                <div class="h6">Kamar</div>
                <div class="val">Kamar {{ $contract->kost->nomor_kamar }}</div>
                <div style="color:#64748b">Rp {{ number_format($contract->kost->harga,0,',','.') }}/bulan</div>
              </div>
              <div class="cell">
                <div class="h6">Sisa Kontrak</div>
                <div class="val">{{ $contract->tanggal_keluar->diffInDays(now()) }} Hari</div>
              </div>
              <div class="cell">
                <div class="h6">Tanggal Masuk</div>
                <div class="val">{{ $contract->tanggal_masuk->translatedFormat('d F Y') }}</div>
              </div>
              <div class="cell">
                <div class="h6">Tanggal Keluar</div>
                <div class="val">{{ $contract->tanggal_keluar->translatedFormat('d F Y') }}</div>
              </div>
              <div class="cell">
                <div class="h6">Durasi</div>
                <div class="val">{{ $contract->duration }} Bulan</div>
              </div>
              <div class="cell">
                <div class="h6">Status</div>
                <div class="val" style="text-transform:capitalize">{{ $contract->status }}</div>
              </div>
            </div>

            <div class="actions">
              <a href="{{ route('user.dashboard') }}" class="btn btn-outline"><i data-feather="home"></i> Ke Dashboard</a>
              @if($contract->tanggal_keluar->diffInDays(now()) <= 120)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#extendModal">
                  <i data-feather="refresh-ccw"></i> Ajukan Perpanjangan
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="card">
        <div class="h6">Belum ada kontrak aktif</div>
        <a class="btn btn-primary" href="{{ route('kamar') }}"><i data-feather="shopping-bag"></i> Pesan Kamar</a>
      </div>
    @endif
  </div>
</div>

{{-- Modal Perpanjang (tetap pakai route & validasi yang sudah kamu buat) --}}
@if($contract)
<div class="modal fade" id="extendModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('user.contract.extend') }}" enctype="multipart/form-data" id="extendForm">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Perpanjang Kontrak</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Durasi</label>
          <select name="duration" class="form-select" id="extDuration" required>
            @for($i=1;$i<=12;$i++)
              <option value="{{ $i }}">{{ $i }} Bulan</option>
            @endfor
          </select>
          <div class="form-text">Harga: Rp {{ number_format($contract->kost->harga,0,',','.') }} / bulan</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Total</label>
          <div class="form-control" id="extTotal" readonly>Rp {{ number_format($contract->kost->harga,0,',','.') }}</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Upload Bukti Pembayaran</label>
          <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
        </div>
        @php $mulai = $contract->tanggal_keluar->copy()->addDay(); @endphp
        <div class="alert alert-info">Perpanjangan mulai: <strong>{{ $mulai->translatedFormat('d F Y') }}</strong></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" id="extSubmitBtn">Kirim Permohonan</button>
      </div>
    </form>
  </div>
</div>
@endif
@endsection

@push('js')
<script>
  document.addEventListener('DOMContentLoaded', ()=> window.feather && feather.replace());
  (function(){
    const harga = {{ (int) ($contract->kost->harga ?? 0) }};
    const durSel = document.getElementById('extDuration');
    const totalEl = document.getElementById('extTotal');
    function rupiah(n){ return new Intl.NumberFormat('id-ID').format(n); }
    if(durSel && totalEl){
      const upd=()=> totalEl.textContent = 'Rp ' + rupiah(harga * parseInt(durSel.value||1));
      upd(); durSel.addEventListener('change', upd);
    }
    const form = document.getElementById('extendForm');
    const btn  = document.getElementById('extSubmitBtn');
    if(form && btn){ form.addEventListener('submit', ()=>{ btn.disabled=true; btn.textContent='Mengirim...'; }); }
  })();
</script>
@endpush
