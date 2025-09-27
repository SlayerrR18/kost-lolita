{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')
@section('title','Dashboard')

@push('css')
<style>
    /* ... (CSS yang sudah Anda berikan) ... */
    :root {
        /* Modern color palette */
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --primary-light: rgba(26,127,90,0.1);
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #16a34a; /* Adjusted to match admin success */
        --warning: #f59e0b;
        --danger: #ef4444;

        /* Responsive spacing */
        --xpad: clamp(1rem, 3vw, 2rem);
        --ypad: clamp(1.5rem, 4vw, 3rem);
        --gap: clamp(1rem, 2vw, 1.5rem);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .dashboard-container {
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
        border-radius: var(--radius-lg);
        padding: clamp(1.5rem, 4vw, 2.5rem);
        margin-bottom: var(--gap);
        box-shadow: 0 8px 32px rgba(26,127,90,0.15);
        transition: all 0.3s ease;
    }

    .page-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(26,127,90,0.2);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-title {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    /* Modern cards */
    .card-base {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: clamp(1.25rem, 3vw, 2rem);
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .card-base:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }

    .card-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    /* Status badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .status-badge.pending { background: var(--warning); color: #fff; }
    .status-badge.confirmed { background: var(--success); color: #fff; }
    .status-badge.expired { background: var(--danger); color: #fff; }

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

    /* Modern typography & card elements */
    .kicker {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .value {
        font-size: clamp(1.25rem, 2.5vw, 1.75rem);
        font-weight: 700;
        color: var(--ink);
        margin: 0.5rem 0;
        line-height: 1.2;
    }
    .value.large {
        font-size: clamp(2rem, 3.5vw, 2.5rem);
    }

    .hint {
        color: var(--muted);
        font-size: 0.875rem;
    }

    .card .icon-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--ink);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .card .icon-label i {
        color: var(--primary);
    }

    .card-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
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
        text-decoration: none;
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
    <div class="dashboard-container">
        <div class="container-narrow">
            <div class="page-header">
                <div class="header-content">
                    <div>
                        <h1 class="page-title">Selamat Datang, {{ auth()->user()->name }}!</h1>
                        <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    @if($latestContract && $latestContract->tanggal_keluar->diffInDays(now()) <= 30)
                        <div class="status-badge pending">
                            <i data-feather="alert-circle"></i> Kontrak Akan Berakhir
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kartu ringkas --}}
            <div class="grid">
                <div class="card-base card-content">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem">
                        <h4 class="kicker"><i data-feather="clock"></i> Sisa Kontrak</h4>
                        @if($latestContract && $latestContract->tanggal_keluar->diffInDays(now()) <= 30)
                            <span class="badge status-badge pending">Segera berakhir</span>
                        @endif
                    </div>
                    <div class="value large" id="remainText"
                        @if($latestContract) data-end="{{ $latestContract->tanggal_keluar?->copy()->endOfDay()->toIso8601String() }}" @endif>
                        @if($latestContract)
                            @php
                                $now = now();
                                $end = $latestContract->tanggal_keluar;
                                $days = $end->diffInDays($now);
                                $months = floor($days / 30);
                                $remainingDays = $days % 30;
                            @endphp

                            @if($days <= 30)
                                {{ $days }} Hari
                            @else
                                {{ $months }} Bulan {{ $remainingDays }} Hari
                            @endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="hint" id="remainSub">
                        @if($latestContract)
                            Berakhir pada {{ $latestContract->tanggal_keluar?->translatedFormat('d F Y') ?? '-' }}
                        @endif
                    </div>
                </div>

                <div class="card-base card-content">
                    <h4 class="kicker"><i data-feather="home"></i> Nomor Kamar</h4>
                    <div class="value large">
                        {{ $latestContract?->kost?->nomor_kamar ? 'Kamar '.$latestContract->kost->nomor_kamar : '-' }}
                    </div>
                    <div class="hint">Rp {{ number_format($latestContract?->kost?->harga ?? 0,0,',','.') }}/bulan</div>
                </div>

                <div class="card-base card-content">
                    <h4 class="kicker"><i data-feather="book-open"></i> Kontak Tersimpan</h4>
                    <div class="card-actions">
                        <div class="icon-label">
                            <i data-feather="phone"></i>
                            <span>{{ $latestContract?->phone ?? '-' }}</span>
                        </div>
                        <div class="icon-label">
                            <i data-feather="mail"></i>
                            <span>{{ $latestContract?->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail tambahan --}}
            <div class="grid">
                <div class="card-base card-content">
                    <h4 class="kicker"><i data-feather="map-pin"></i> Alamat</h4>
                    <div class="value" style="font-size:1rem;font-weight:600">{{ $latestContract?->alamat ?? '-' }}</div>
                </div>

                <div class="card-base card-content">
                    <h4 class="kicker"><i data-feather="log-in"></i> Tanggal Masuk</h4>
                    <div class="value">{{ $firstContract?->tanggal_masuk?->translatedFormat('d F Y') ?? '-' }}</div>
                </div>
                <div class="card-base card-content">
                    <h4 class="kicker"><i data-feather="log-out"></i> Tanggal Keluar</h4>
                    <div class="value">{{ $latestContract?->tanggal_keluar?->translatedFormat('d F Y') ?? '-' }}</div>
                </div>
            </div>
            <br>

            {{-- Action buttons --}}
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('user.contract') }}" class="btn-action btn-secondary">
                    <i data-feather="file-text"></i> Detail Kontrak
                </a>
                @if($latestContract && $latestContract->tanggal_keluar->diffInDays(now()) <= 30)
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
        const targetId = this.getAttribute('href');
        const targetEl = document.querySelector(targetId);
        if (targetEl) {
            targetEl.scrollIntoView({ behavior: 'smooth' });
            // For modal, open it after scrolling if it's an ID
            if (targetId.startsWith('#') && targetEl.classList.contains('modal')) {
                const modal = new bootstrap.Modal(targetEl);
                modal.show();
            }
        }
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
    const badgeEl = document.querySelector('.status-badge.pending');

    if (remainEl && remainEl.dataset.end) {
        const end = new Date(remainEl.dataset.end);
        const now = new Date();
        const diffTime = end.getTime() - now.getTime(); // Use getTime for accurate difference
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 0) {
            if (diffDays <= 30) {
                remainEl.textContent = `${diffDays} Hari`;
            } else {
                const months = Math.floor(diffDays / 30);
                const remainingDays = diffDays % 30;
                remainEl.textContent = `${months} Bulan ${remainingDays} Hari`;
            }

            subEl.textContent = `Berakhir pada ${end.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            })}`;
        } else {
            remainEl.textContent = 'Kontrak Telah Berakhir';
            subEl.textContent = 'Segera hubungi pengelola.';
            if(badgeEl) badgeEl.textContent = 'Telah berakhir';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initIcons();
    updateRemaining();
});
</script>
@endpush
