@extends('layouts.user')

@push('css')
<style>
    .contract-container {
        padding: 8rem 7% 4rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .contract-title {
        font-size: 2rem;
        color: #1a7f5a;
        margin-bottom: 2rem;
        font-weight: 700;
    }

    .contract-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contract-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .profile-header {
        padding: 2rem;
        text-align: center;
        background: linear-gradient(90deg, #1a7f5a 0%, #16c79a 100%);
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }

    .info-section {
        padding: 2rem;
    }

    .info-section h2 {
        color: #1a7f5a;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .info-group {
        margin-bottom: 1.2rem;
    }

    .info-group label {
        display: block;
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
    }

    .info-group p {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .extend-contract-btn {
        display: block;
        width: calc(100% - 4rem);
        margin: 0 2rem 2rem;
        padding: 1rem;
        background: linear-gradient(90deg, #1a7f5a 0%, #16c79a 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .extend-contract-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 127, 90, 0.2);
    }

    .no-contract {
        text-align: center;
        padding: 3rem;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-width: 500px;
        margin: 2rem auto;
    }

    .no-contract p {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    .no-contract .btn-book {
        display: inline-block;
        padding: 0.8rem 2rem;
        background: linear-gradient(90deg, #1a7f5a 0%, #16c79a 100%);
        color: #fff;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .no-contract .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 127, 90, 0.2);
    }

    @media (max-width: 768px) {
        .contract-container {
            padding: 6rem 5% 3rem;
        }

        .contract-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="contract-container">
    <h1 class="contract-title">Kontrak</h1>

    @if($contract)
    <div class="contract-grid">
        <!-- Informasi Pribadi Card -->
        <div class="contract-card">
            <div class="profile-header">
                <img src="{{ asset('storage/' . $contract->ktp_image) }}" alt="KTP" class="profile-image">
            </div>

            <div class="info-section">
                <h2>Informasi Pribadi</h2>

                <div class="info-group">
                    <label>Nama Lengkap</label>
                    <p>{{ $contract->name }}</p>
                </div>

                <div class="info-group">
                    <label>Email</label>
                    <p>{{ $contract->email }}</p>
                </div>

                <div class="info-group">
                    <label>Alamat</label>
                    <p>{{ $contract->alamat }}</p>
                </div>

                <div class="info-group">
                    <label>No. HP</label>
                    <p>{{ $contract->phone }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Kontrak Card -->
        <div class="contract-card">
            <div class="info-section">
                <h2>Informasi Kontrak</h2>

                <div class="info-group">
                    <label>Sisa Kontrak</label>
                    <p>{{ $contract->duration - $contract->tanggal_masuk->diffInMonths(now()) }} Bulan</p>
                </div>

                <div class="info-group">
                    <label>No. Kamar</label>
                    <p>Kamar {{ $contract->kost->nomor_kamar }}</p>
                </div>

                <div class="info-group">
                    <label>Tanggal Masuk</label>
                    <p>{{ $contract->tanggal_masuk->format('d F Y') }}</p>
                </div>

                <div class="info-group">
                    <label>Tanggal Keluar</label>
                    <p>{{ $contract->tanggal_keluar->format('d F Y') }}</p>
                </div>
            </div>

            @if($contract->tanggal_keluar->diffInDays(now()) <= 30)
                <button class="extend-contract-btn">
                    Perpanjang Kontrak
                </button>
            @endif
        </div>
    </div>
    @else
    <div class="no-contract">
        <p>Anda belum memiliki kontrak aktif</p>
        <a href="{{ route('kamar') }}" class="btn-book">Pesan Kamar</a>
    </div>
    @endif
</div>
@endsection
