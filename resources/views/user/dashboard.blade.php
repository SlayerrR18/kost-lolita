{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')
@section('title','Dashboard')

@push('css')
<style>
  :root{
    --primary:#1a7f5a; --primary-2:#16c79a;
    --bg:#f8fafc; --ink:#1e293b; --muted:#64748b; --ring:#e2e8f0;
    /* skala padding/spacing responsif */
    --xpad: clamp(0.75rem, 2vw, 1.75rem);
    --ypad: clamp(0.75rem, 2.5vw, 2rem);
    --gap: clamp(0.75rem, 2vw, 1rem);
    --radius: 24px;
  }

  /* Bungkus halaman */
  .report-container{
    min-height:100vh;
    background:var(--bg);
    padding: var(--ypad) var(--xpad);
  }

  /* Kontainer fluid: lebar mengikuti layar tapi tetap ada max agar tetap enak dibaca */
  .container-narrow{
    width:100%;
    max-width: clamp(1024px, 92vw, 1440px); /* naik dari 1200 -> 1440 dan fleksibel */
    margin-inline:auto;
    /* jangan kasih padding tetap di sini; pakai padding dari .report-container */
  }

  /* Header gradient konsisten, padding adaptif */
  .page-header{
    background:linear-gradient(135deg,var(--primary) 0%,var(--primary-2) 100%);
    color:#fff;border-radius:var(--radius);
    padding: clamp(1rem, 3vw, 2rem);
    margin-bottom: clamp(1rem, 3vw, 2rem);
    box-shadow:0 6px 24px rgba(26,127,90,.15);
  }
  .header-content{
    display:flex;align-items:center;justify-content:space-between;gap:var(--gap);flex-wrap:wrap
  }
  .page-title{font-size:clamp(1.25rem, 2.2vw, 1.75rem);font-weight:700;margin:0}
  .page-subtitle{opacity:.9;margin-top:.4rem;font-size:clamp(.85rem,1.6vw,.95rem)}

  /* Kartu */
  .card{
    background:#fff;border-radius:var(--radius);
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    padding: clamp(1rem, 2.2vw, 1.25rem);
  }
  .kicker{display:flex;align-items:center;gap:.5rem;color:var(--muted);font-size:clamp(.85rem,1.6vw,.95rem)}
  .kicker i{width:16px;height:16px}
  .value{font-size:clamp(1.25rem, 3vw, 1.6rem);color:var(--ink);font-weight:700}
  .hint{color:var(--muted);font-size:clamp(.8rem,1.5vw,.9rem)}

  /* Grid yang lebih elastis */
  .grid{
    display:grid;gap:var(--gap);
    /* auto-fill + min kolom lebih kecil & fleksibel agar nggak cepat “jatuh” saat zoom */
    grid-template-columns: repeat(auto-fill, minmax(clamp(220px, 26vw, 360px), 1fr));
  }

  .row-2{
    display:grid;gap:var(--gap);margin-top:var(--gap);
    grid-template-columns: repeat(auto-fill, minmax(clamp(280px, 32vw, 520px), 1fr));
  }

  /* Tombol konsisten */
  .btn-add{
    background:linear-gradient(135deg,var(--primary) 0%,var(--primary-2) 100%);
    color:#fff;padding:.75rem 1.5rem;border-radius:12px;font-weight:600;
    display:inline-flex;align-items:center;gap:.5rem;border:none;
    box-shadow:0 4px 12px rgba(26,127,90,.15);transition:all .3s
  }
  .btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(26,127,90,.2);color:#fff}

  .btn-secondary{
    background:#f1f5f9;color:#0f172a;padding:.75rem 1.25rem;border-radius:12px;
    display:inline-flex;align-items:center;gap:.5rem;border:none;transition:all .25s
  }
  .btn-secondary:hover{background:#e7eef6;transform:translateY(-2px)}

  .badge{background:#fff;color:var(--primary);border-radius:999px;padding:.4rem .75rem;font-weight:700}

  /* Micro-tuning untuk layar super lebar: longgarkan max-width dikit */
  @media (min-width: 1600px){
    .container-narrow{max-width: min(1600px, 92vw);}
  }
</style>
@endpush


@section('content')
@php
  $contract = $contract ?? ($latestContract ?? null);
@endphp

<div class="report-container">
  <div class="container-narrow">
    {{-- Header konsisten --}}
    <div class="page-header">
      <div class="header-content">
        <div>
          <h1 class="page-title">Dashboard</h1>
          <div class="page-subtitle">Hai, {{ auth()->user()->name }} — {{ now()->translatedFormat('d M Y') }}</div>
        </div>
        @if(session('success'))
          <span class="badge" aria-live="polite">{{ session('success') }}</span>
        @endif
      </div>
    </div>

    {{-- Kartu ringkas --}}
    <div class="grid">
      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem">
          <h4 class="kicker"><i data-feather="clock"></i> Sisa Kontrak</h4>
          @if($contract && $contract->tanggal_keluar->diffInDays(now()) <= 30)
            <span class="badge">Segera berakhir</span>
          @endif
        </div>
        <div class="value" id="remainText"
             @if($contract) data-end="{{ $contract->tanggal_keluar?->copy()->endOfDay()->toIso8601String() }}" @endif>
          @if($contract) {{ $contract->tanggal_keluar->diffInDays(now()) }} Hari @else - @endif
        </div>
        <div class="hint" id="remainSub"></div>
      </div>

      <div class="card">
        <h4 class="kicker"><i data-feather="home"></i> Nomor Kamar</h4>
        <div class="value">
          {{ $contract?->kost?->nomor_kamar ? 'Kamar '.$contract->kost->nomor_kamar : '-' }}
        </div>
        <div class="hint">Rp {{ number_format($contract?->kost?->harga ?? 0,0,',','.') }}/bulan</div>
      </div>

      <div class="card">
        <h4 class="kicker"><i data-feather="calendar"></i> Periode</h4>
        <div class="value">
          {{ $contract?->tanggal_masuk?->translatedFormat('d M Y') ?? '-' }}
          — {{ $contract?->tanggal_keluar?->translatedFormat('d M Y') ?? '-' }}
        </div>
        <div class="hint">Durasi: {{ $contract?->duration ?? '-' }} bulan</div>
      </div>

      <div class="card">
        <h4 class="kicker"><i data-feather="book-open"></i> Kontak Tersimpan</h4>
        <div class="kicker"><i data-feather="phone"></i> {{ $contract?->phone ?? '-' }}</div>
        <div class="kicker"><i data-feather="mail"></i> {{ $contract?->email ?? '-' }}</div>
      </div>
    </div>

    {{-- Detail tambahan --}}
    <div class="row-2">
      <div class="card">
        <h4 class="kicker"><i data-feather="map-pin"></i> Alamat</h4>
        <div class="value" style="font-size:1rem;font-weight:600">{{ $contract?->alamat ?? '-' }}</div>
      </div>
      <div class="card">
        <h4 class="kicker"><i data-feather="activity"></i> Status</h4>
        <div class="value" style="text-transform:capitalize">{{ $contract?->status ?? '-' }}</div>
        <div class="hint">Terakhir diperbarui: {{ $contract?->updated_at?->diffForHumans() ?? '-' }}</div>
      </div>
      <div class="card">
        <h4 class="kicker"><i data-feather="log-in"></i> Tanggal Masuk</h4>
        <div class="value">{{ $contract?->tanggal_masuk?->translatedFormat('d F Y') ?? '-' }}</div>
      </div>
      <div class="card">
        <h4 class="kicker"><i data-feather="log-out"></i> Tanggal Keluar</h4>
        <div class="value">{{ $contract?->tanggal_keluar?->translatedFormat('d F Y') ?? '-' }}</div>
      </div>
    </div>

    <div class="actions" style="display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:16px">
      <a href="{{ route('user.contract') }}" class="btn-secondary"><i data-feather="file-text"></i> Detail Kontrak</a>
      @if($contract && $contract->tanggal_keluar->diffInDays(now()) <= 30)
        <a href="{{ route('user.contract') }}#extendModal" class="btn-add">
          <i data-feather="refresh-ccw"></i> Perpanjang Kontrak
        </a>
      @endif
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
function initIcons(){
  if(!window.feather) return;
  document.querySelectorAll('[data-feather]').forEach(el=>{
    const name = el.getAttribute('data-feather');
    if (name && window.feather.icons[name]) {
      el.outerHTML = window.feather.icons[name].toSvg({
        'stroke-width': 1.5, width: 16, height: 16, class: 'feather-16'
      });
    }
  });
}
document.addEventListener('DOMContentLoaded', initIcons);
</script>
@endpush
