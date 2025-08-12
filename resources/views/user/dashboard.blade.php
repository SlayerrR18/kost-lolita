{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')
@section('title','Dashboard')

@push('css')
<style>
  :root{ --brand:#1a7f5a; --brand2:#16c79a; --ink:#1e293b; --muted:#64748b; --bg:#f8fafc; }
  .page{min-height:100vh;background:var(--bg);padding:28px 20px;}
  .container-narrow{max-width:1200px;margin:0 auto;}
  .hero{
    background:linear-gradient(135deg,var(--brand) 0%,var(--brand2) 100%);
    color:#fff;border-radius:20px;padding:20px 24px;margin-bottom:20px;
    display:flex;align-items:center;justify-content:space-between;gap:16px;
    box-shadow:0 6px 24px rgba(0,0,0,.08);
  }
  .hero h1{font-size:1.25rem;margin:0}
  .hero .sub{opacity:.9;font-size:.9rem}
  .hero .badge{background:#fff;color:var(--brand);border-radius:999px;padding:.4rem .75rem;font-weight:600}

  .grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
  .card{background:#fff;border-radius:16px;padding:18px 18px;box-shadow:0 4px 14px rgba(0,0,0,.06)}
  .card h4{font-size:.9rem;color:var(--muted);margin:0 0 .4rem}
  .value{font-size:1.5rem;color:var(--ink);font-weight:700}
  .hint{color:var(--muted);font-size:.85rem}

  /* blok data panjang */
  .row-2{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));margin-top:16px}
  .kicker{display:flex;align-items:center;gap:.5rem;color:var(--muted)}
  .kicker i{width:16px;height:16px}

  .actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:16px}
  .btn{border:none;border-radius:10px;padding:.7rem 1rem;font-weight:600;display:inline-flex;align-items:center;gap:.5rem}
  .btn-primary{background:var(--brand);color:#fff}
  .btn-outline{background:#eef2f7;color:#0f172a}

  /* responsive safe padding supaya nggak “nempel” ke kiri/atas */
  @media (min-width: 1280px){
    .page{padding:32px 28px}
  }
</style>
@endpush

@section('content')
@php
  $contract = $contract ?? ($latestContract ?? null); // kalau controller sudah kirim $contract, pakai itu
@endphp
<div class="page">
  <div class="container-narrow">
    <div class="hero">
      <div>
        <h1>Dashboard</h1>
        <div class="sub">Hai, {{ auth()->user()->name }} — {{ now()->translatedFormat('d M Y') }}</div>
      </div>
      @if(session('success')) <span class="badge">{{ session('success') }}</span> @endif
    </div>

    {{-- Kartu ringkas --}}
    <div class="grid">
      <div class="card">
        <h4>Sisa Kontrak</h4>
        <div class="value" id="remainText"
             @if($contract) data-end="{{ $contract->tanggal_keluar?->copy()->endOfDay()->toIso8601String() }}" @endif>
          @if($contract)
            {{ $contract->tanggal_keluar->diffInDays(now()) }} Hari
          @else
            -
          @endif
        </div>
        <div class="hint" id="remainSub"></div>
      </div>

      <div class="card">
        <h4>Nomor Kamar</h4>
        <div class="value">
          {{ $contract?->kost?->nomor_kamar ? 'Kamar '.$contract->kost->nomor_kamar : '-' }}
        </div>
        <div class="hint">Rp {{ number_format($contract?->kost?->harga ?? 0,0,',','.') }}/bulan</div>
      </div>

      <div class="card">
        <h4>Periode</h4>
        <div class="value">
          {{ $contract?->tanggal_masuk?->translatedFormat('d M Y') ?? '-' }}
          — {{ $contract?->tanggal_keluar?->translatedFormat('d M Y') ?? '-' }}
        </div>
        <div class="hint">Durasi: {{ $contract?->duration ?? '-' }} bulan</div>
      </div>

      <div class="card">
        <h4>Kontak Tersimpan</h4>
        <div class="kicker"><i data-feather="phone"></i> {{ $contract?->phone ?? '-' }}</div>
        <div class="kicker"><i data-feather="mail"></i> {{ $contract?->email ?? '-' }}</div>
      </div>
    </div>

    {{-- Detail tambahan --}}
    <div class="row-2">
      <div class="card">
        <h4>Alamat</h4>
        <div class="value" style="font-size:1rem;font-weight:600">{{ $contract?->alamat ?? '-' }}</div>
      </div>
      <div class="card">
        <h4>Status</h4>
        <div class="value" style="text-transform:capitalize">{{ $contract?->status ?? '-' }}</div>
        <div class="hint">Terakhir diperbarui: {{ $contract?->updated_at?->diffForHumans() ?? '-' }}</div>
      </div>
      <div class="card">
        <h4>Tanggal Masuk</h4>
        <div class="value">{{ $contract?->tanggal_masuk?->translatedFormat('d F Y') ?? '-' }}</div>
      </div>
      <div class="card">
        <h4>Tanggal Keluar</h4>
        <div class="value">{{ $contract?->tanggal_keluar?->translatedFormat('d F Y') ?? '-' }}</div>
      </div>
    </div>

    <div class="actions">
      <a href="{{ route('user.contract') }}" class="btn btn-outline"><i data-feather="file-text"></i> Detail Kontrak</a>
      @if($contract && $contract->tanggal_keluar->diffInDays(now()) <= 120)
        <a href="{{ route('user.contract') }}#extendModal" class="btn btn-primary">
          <i data-feather="refresh-ccw"></i> Perpanjang Kontrak
        </a>
      @endif
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  // icon
  document.addEventListener('DOMContentLoaded', ()=> window.feather && feather.replace());

  // live countdown sisa kontrak
  (function(){
    const el = document.getElementById('remainText');
    const sub = document.getElementById('remainSub');
    if(!el || !el.dataset.end) return;
    const end = new Date(el.dataset.end);

    const tick = () => {
      const now = new Date();
      let diff = Math.max(0, end - now); // ms
      const days = Math.floor(diff / 86400000); diff -= days*86400000;
      const hrs  = Math.floor(diff / 3600000);  diff -= hrs*3600000;
      const min  = Math.floor(diff / 60000);
      el.textContent = `${days} Hari`;
      sub.textContent = `${hrs}j ${min}m sisa`;
    };
    tick();
    setInterval(tick, 60000); // tiap menit
  })();
</script>
@endpush
