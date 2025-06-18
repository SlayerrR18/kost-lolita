{{-- filepath: d:\Kampus\Web\kost-lolita\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.main')

@section('title', 'Dashboard Admin')

@push('css')
<style>
    .main-content {
        background: #f6f8fa;
        min-height: 100vh;
        padding: 24px 16px;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0;
        padding-left: 0;
    }

    .welcome-date {
        background: #fff;
        color: #1a7f5a;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .welcome-text {
        font-size: 1.1rem;
        color: #64748b;
        margin: 24px 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stat-card:hover .stat-icon {
        opacity: 0.2;
        transform: scale(1.1);
    }

    .stat-title {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a7f5a;
        margin-bottom: 8px;
    }

    .stat-icon {
        position: absolute;
        right: 24px;
        bottom: 24px;
        opacity: 0.1;
        font-size: 3rem;
        color: #1a7f5a;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .dashboard-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a7f5a;
    }

    /* Tambahan animasi */
    .stat-card {
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Dashboard</h1>
            <div class="welcome-date">
                <i data-feather="calendar" class="me-2"></i>
                {{ now()->format('d M Y') }}
            </div>
        </div>
    </div>

    <div class="welcome-text">
        Selamat datang, <span class="fw-bold">Admin</span>! Berikut adalah ringkasan informasi kost Anda.
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Jumlah Kamar Kosong</div>
            <div class="stat-value">{{ $total_kamar_kosong }}</div>
            <div class="stat-icon">
                <i data-feather="home"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Jumlah Penghuni Kost</div>
            <div class="stat-value">{{ $total_penghuni }}</div>
            <div class="stat-icon">
                <i data-feather="users"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Total Pengeluaran</div>
            <div class="stat-value">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
            <div class="stat-icon">
                <i data-feather="arrow-down"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Total Pemasukan</div>
            <div class="stat-value">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
            <div class="stat-icon">
                <i data-feather="arrow-up"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endpush
