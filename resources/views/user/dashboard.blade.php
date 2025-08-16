{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')
@section('title','Dashboard')

@push('css')
<style>
  :root {
    /* Modern color palette */
    --primary: #1a7f5a;
    --primary-2: #16c79a;
    --primary-light: rgba(26,127,90,0.1);
    --bg: #f8fafc;
    --ink: #1e293b;
    --muted: #64748b;
    --ring: #e2e8f0;
    --success: #22c55e;
    --warning: #f59e0b;
    --danger: #ef4444;

    /* Responsive spacing */
    --xpad: clamp(1rem, 3vw, 2rem);
    --ypad: clamp(1.5rem, 4vw, 3rem);
    --gap: clamp(1rem, 2vw, 1.5rem);
    --radius: 24px;
  }

  .report-container {
    min-height: 100vh;
    background: var(--bg);
    padding: var(--ypad) var(--xpad);
  }

  .container-narrow {
    width: 100%;
    max-width: clamp(1024px, 92vw, 1600px);
    margin: 0 auto;
  }

  /* Modern header with gradient */
  .page-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
    color: #fff;
    border-radius: var(--radius);
    padding: clamp(1.5rem, 4vw, 2.5rem);
    margin-bottom: var(--gap);
    box-shadow: 0 8px 32px rgba(26,127,90,0.15);
    transition: all 0.3s ease;
  }

  .page-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(26,127,90,0.2);
  }

  /* Modern cards */
  .card {
    background: #fff;
    border-radius: var(--radius);
    padding: clamp(1.25rem, 3vw, 2rem);
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
  }

  /* Status badges */
  .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.875rem;
  }

  .status-badge.pending {
    background: var(--warning);
    color: #fff;
  }

  .status-badge.confirmed {
    background: var(--success);
    color: #fff;
  }

  .status-badge.expired {
    background: var(--danger);
    color: #fff;
  }

  /* Modern grid layout */
  .grid {
    display: grid;
    gap: var(--gap);
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    margin-bottom: var(--gap);
  }

  .row-2 {
    display: grid;
    gap: var(--gap);
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  }

  /* Modern typography */
  .value {
    font-size: clamp(1.25rem, 2.5vw, 1.75rem);
    font-weight: 700;
    color: var(--ink);
    margin: 0.5rem 0;
    line-height: 1.2;
  }

  .hint {
    color: var(--muted);
    font-size: 0.875rem;
  }

  /* Modern buttons */
  .btn-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(26,127,90,0.2);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(26,127,90,0.3);
  }

  .btn-secondary {
    background: var(--primary-light);
    color: var(--primary);
  }

  .btn-secondary:hover {
    transform: translateY(-2px);
    background: rgba(26,127,90,0.15);
  }

  /* Modern icons */
  .icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    margin-bottom: 0.5rem;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .header-content {
      flex-direction: column;
      align-items: flex-start;
    }

    .actions {
      margin-top: var(--gap);
      justify-content: flex-start;
    }
  }
</style>
@endpush

{{-- Update content section with new classes and structure --}}
@section('content')
  @php
    $contract = $contract ?? ($latestContract ?? null);
  @endphp

  <div class="report-container">
    <div class="container-narrow">
      <div class="page-header">
        <div class="header-content">
          <div>
            <h1 class="page-title">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
          </div>
          @if($contract && $contract->tanggal_keluar->diffInDays(now()) <= 30)
            <div class="status-badge warning">
              <i data-feather="alert-circle"></i> Kontrak Akan Berakhir
            </div>
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
      <br>
      
      {{-- Action buttons --}}
      <div class="actions">
        <a href="{{ route('user.contract') }}" class="btn-action btn-secondary">
          <i data-feather="file-text"></i> Detail Kontrak
        </a>
        @if($contract && $contract->tanggal_keluar->diffInDays(now()) <= 30)
          <a href="{{ route('user.contract') }}#extendModal" class="btn-action btn-primary">
            <i data-feather="refresh-ccw"></i> Perpanjang Kontrak
          </a>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('js')
<script>
// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});

// Initialize Feather icons
function initIcons() {
  if (typeof feather !== 'undefined') {
    feather.replace({
      'stroke-width': 2,
      width: 20,
      height: 20
    });
  }
}

// Update remaining days counter
function updateRemaining() {
  const remainEl = document.getElementById('remainText');
  const subEl = document.getElementById('remainSub');

  if (remainEl && remainEl.dataset.end) {
    const end = new Date(remainEl.dataset.end);
    const now = new Date();
    const days = Math.ceil((end - now) / (1000 * 60 * 60 * 24));

    if (days > 0) {
      remainEl.textContent = `${days} Hari`;
      subEl.textContent = `Berakhir pada ${end.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })}`;
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initIcons();
  updateRemaining();
});
</script>
@endpush
