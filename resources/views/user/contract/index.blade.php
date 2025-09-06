{{-- resources/views/user/contract/index.blade.php (Improved V2) --}}
@extends('layouts.user')

@section('title', 'Kontrak Saya')

@push('css')
<style>
    /* === Palette & Base === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
    }

    /* === Layout & Containers === */
    .app-container {
        padding: 2rem;
        background-color: var(--bg);
        min-height: 100vh;
    }

    @media (max-width: 768px) {
        .app-container {
            padding: 1rem;
        }
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(26, 127, 90, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .page-header .text-content {
        flex-grow: 1;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .content-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* === Cards & Sections === */
    .card-base {
        background-color: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .profile-section, .info-section {
        padding: 1.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        border: 2px dashed var(--ring);
        border-radius: var(--radius-lg);
        color: var(--muted);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--ink);
    }

    .empty-state .icon {
        width: 48px;
        height: 48px;
        color: #cbd5e1;
    }

    /* === Profile Card === */
    .profile-header {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .profile-image {
        width: 72px;
        height: 72px;
        border-radius: var(--radius-md);
        object-fit: cover;
        background-color: var(--bg);
        border: 1px solid var(--ring);
        flex-shrink: 0;
    }

    .profile-image-placeholder {
        width: 72px;
        height: 72px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg);
        border: 1px solid var(--ring);
        color: var(--muted);
        flex-shrink: 0;
    }

    .profile-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 0.25rem;
    }

    .profile-info .subtle {
        color: var(--muted);
        font-size: 0.875rem;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 1.5rem 0;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background-color: var(--bg);
        border-radius: var(--radius-md);
        padding: 1rem;
        border: 1px dashed var(--ring);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--muted);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--ink);
    }

    /* === Info Card Right === */
    .tabs-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--ring);
        padding-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .tab-item {
        padding: 0.6rem 1rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        color: #475569;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .tab-item:hover {
        background-color: #f1f5f9;
    }

    .tab-item.active {
        background-color: rgba(26, 127, 90, 0.1);
        color: var(--primary);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-card {
        background-color: var(--bg);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        border: 1px dashed var(--ring);
        margin-top: 1rem;
    }

    .info-card:first-of-type {
        margin-top: 0;
    }

    .info-card-title {
        font-size: 0.875rem;
        color: var(--muted);
        margin-bottom: 0.25rem;
    }

    .info-card-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--ink);
    }

    .info-card-meta {
        font-size: 0.875rem;
        color: var(--muted);
    }

    .actions-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--ring);
    }

    /* === Badges & Buttons === */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .badge-status.active { background-color: #dcfce7; color: #166534; }
    .badge-status.pending { background-color: #fef3c7; color: #92400e; }
    .badge-status.expired { background-color: #fee2e2; color: #991b1b; }
    .badge-status.muted { background-color: #f1f5f9; color: #475569; }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(26, 127, 90, 0.15);
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 127, 90, 0.2);
        color: #fff;
    }

    .btn-secondary {
        background-color: #f1f5f9;
        color: #0f172a;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #e7eef6;
        transform: translateY(-2px);
    }

    /* === Modal === */
    .modal-content { border-radius: var(--radius-md); border: none; }
    .modal-header { border-bottom: 1px solid var(--ring); padding: 1.25rem 1.5rem; }
    .modal-body { padding: 1.5rem; }
    .modal-footer { border-top: 1px solid var(--ring); padding: 1.25rem 1.5rem; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    /** @var \App\Models\Contract|null $contract */
    $status = $contract->status ?? null;
    $statusClass = match(strtolower((string)$status)){
        'active','aktif' => 'active',
        'pending','menunggu' => 'pending',
        'expired','selesai','berakhir' => 'expired',
        default => 'muted'
    };
@endphp

<div class="app-container">
    <header class="page-header" role="banner">
        <div class="text-content">
            <h1 class="page-title">Kontrak Saya</h1>
            <p class="page-subtitle">Ringkasan status kontrak, masa berlaku, dan tindakan cepat</p>
        </div>

        @if(isset($contract) && optional($contract->tanggal_keluar)->diffInDays(now()) <= 30)
            <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#extendModal" type="button">
                <i data-feather="refresh-ccw" aria-hidden="true"></i>
                <span>Ajukan Perpanjangan</span>
            </button>
        @endif
    </header>

    {{-- Main Content --}}
    @if($contract)
        <div class="content-grid">
            {{-- Kiri: Profil & Statistik Kontrak --}}
            <section class="card-base profile-section" aria-labelledby="profile-heading">
                <div class="profile-header">
                    @if($contract->ktp_image_url)
                        <img src="{{ $contract->ktp_image_url }}" alt="Foto KTP {{ e($contract->name) }}" class="profile-image" onerror="handleImageError(this)">
                    @else
                        <div class="profile-image-placeholder" aria-hidden="true">
                            <i data-feather="user"></i>
                        </div>
                    @endif
                    <div class="profile-info">
                        <h3>{{ e($contract->name) }}</h3>
                        <div class="subtle">{{ e($contract->email) }}</div>
                        <div class="subtle">{{ e($contract->phone) }}</div>
                    </div>
                </div>

                <div class="tags-container">
                    <span class="tag"><i data-feather="home"></i> Kamar {{ e(optional($contract->kost)->nomor_kamar) }}</span>
                    <span class="tag"><i data-feather="credit-card"></i> Rp {{ number_format(optional($contract->kost)->harga ?? 0,0,',','.') }}/bulan</span>
                    <span class="tag"><i data-feather="calendar"></i> {{ (int)($contract->duration ?? 0) }} bulan</span>
                    <span class="badge-status {{ $statusClass }}">
                        <i data-feather="{{ $statusClass==='active'?'check-circle':($statusClass==='pending'?'clock':'x-circle') }}"></i>
                        {{ \Illuminate\Support\Str::headline($contract->status ?? 'Menunggu') }}
                    </span>
                </div>

                <div class="subtle">{{ e($contract->alamat) }}</div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Tanggal Masuk</div>
                        <div class="stat-value">{{ optional($contract->tanggal_masuk)->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tanggal Keluar</div>
                        <div class="stat-value">{{ optional($contract->tanggal_keluar)->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Hari</div>
                        <div class="stat-value">{{ (int)($totalDays ?? 0) }} hari</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Sisa Kontrak</div>
                        <div class="stat-value">{{ (int)($remainingDays ?? 0) }} hari</div>
                    </div>
                </div>
            </section>

            {{-- Kanan: Ringkasan & Aksi --}}
            <section class="card-base info-section">
                <nav class="tabs-container" role="tablist">
                    <span class="tab-item active" role="tab" aria-selected="true"><i data-feather="file-text"></i> Ringkasan</span>
                    <a class="tab-item" href="{{ route('user.history.index') }}" role="tab"><i data-feather="activity"></i> Riwayat Pembayaran</a>
                    {{-- <a class="tab-item" href="{{ route('user.contract') }}" role="tab"><i data-feather="clipboard"></i> Detail Kontrak</a> --}}
                </nav>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-card-title">Tagihan Bulanan</div>
                        <div class="info-card-value">Rp {{ number_format(optional($contract->kost)->harga ?? 0,0,',','.') }}</div>
                        <div class="info-card-meta">Termasuk listrik/air: <strong>{{ optional($contract->kost)->include_utility ? 'Ya' : 'Tidak' }}</strong></div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Metode Pembayaran</div>
                        <div class="info-card-value">{{ e($contract->payment_method ?? 'Transfer Bank') }}</div>
                        <div class="info-card-meta">Detail di menu Riwayat Pembayaran</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Perpanjangan Mulai</div>
                        @php $mulai = optional($contract->tanggal_keluar)->copy()?->addDay(); @endphp
                        <div class="info-card-value">{{ optional($mulai)->translatedFormat('d M Y') }}</div>
                        <div class="info-card-meta">Tanggal efektif setelah kontrak berakhir</div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-title">Kontak Pengelola</div>
                        <div class="info-card-value">{{ e($contract->manager_name ?? 'Admin Kost') }}</div>
                        <div class="info-card-meta">{{ e($contract->manager_phone ?? '081238036180') }}</div>
                    </div>
                </div>

                {{-- Informasi Tambahan (di dalam info-grid yang terpisah) --}}
                <div class="info-card" aria-live="polite">
                    <div class="info-card-title d-flex justify-content-between align-items-center">
                        <span>Informasi Tambahan</span>
                        <button class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#editInfoModal" type="button" aria-label="Edit Informasi Tambahan">
                            <i data-feather="edit-2"></i> Edit
                        </button>
                    </div>
                    <div class="info-card-value">
                        <div class="mb-2">
                            <small class="text-muted">No. KTP:</small><br>
                            {{ $contract->ktp_number ? e($contract->ktp_number) : '-' }}
                        </div>
                        <div>
                            <small class="text-muted">Kontak Darurat:</small><br>
                            {{ $contract->emergency_phone ? e($contract->emergency_phone) : '-' }}
                        </div>
                    </div>
                </div>

                <div class="actions-container">
                    <a href="{{ route('user.dashboard') }}" class="btn-secondary"><i data-feather="home"></i> Ke Dashboard</a>
                    <a href="{{ route('user.history.index') }}" class="btn-secondary"><i data-feather="credit-card"></i> Lihat Riwayat</a>
                    @if(optional($contract->tanggal_keluar)->diffInDays(now()) <= 30)
                        <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#extendModal" type="button">
                            <i data-feather="refresh-ccw"></i> Ajukan Perpanjangan
                        </button>
                    @endif
                </div>
            </section>
        </div>
    @else
        <div class="card-base empty-state">
            <i data-feather="file-text" class="icon mb-3" aria-hidden="true"></i>
            <h3>Belum ada kontrak aktif</h3>
            <p class="text-muted">Silakan hubungi pengelola untuk memulai kontrak.</p>
            <a href="{{ route('user.dashboard') }}" class="btn-primary">
                <i data-feather="home"></i>
                <span>Ke Dashboard</span>
            </a>
        </div>
    @endif
</div>
@endsection

{{-- Modal Perpanjangan --}}
<div class="modal fade" id="extendModal" tabindex="-1" aria-labelledby="extendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('user.contract.extend') }}" enctype="multipart/form-data" id="extendForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="extendModalLabel">Perpanjang Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="roomPrice" value="{{ (int)(optional($contract->kost)->harga ?? 0) }}">

                <div class="mb-3">
                    <label class="form-label" for="duration">Durasi</label>
                    <select name="duration" class="form-select" id="duration" required>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    <div class="form-text">Harga: Rp {{ number_format(optional($contract->kost)->harga ?? 0,0,',','.') }} / bulan</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Pembayaran</label>
                    <div class="form-control bg-light" id="totalPayment">Rp {{ number_format(optional($contract->kost)->harga ?? 0,0,',','.') }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" required>
                </div>

                @php $mulai = optional($contract->tanggal_keluar)->copy()?->addDay(); @endphp
                <div class="alert alert-info">Perpanjangan mulai: <strong>{{ optional($mulai)->translatedFormat('d F Y') }}</strong></div>

                <div id="extendErrors" class="alert alert-danger d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                <button class="btn-primary" id="extSubmitBtn" type="submit">
                    <i data-feather="send"></i>
                    <span>Kirim Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sukses --}}
<div class="modal fade" id="successExtendModal" tabindex="-1" aria-labelledby="successExtendLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successExtendLabel">Berhasil!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="successExtendMsg"></div>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Periode Baru</span>
                        <strong id="successPeriod"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Status</span>
                        <strong id="successStatus"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>ID Permohonan</span>
                        <strong id="successOrderId"></strong>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Informasi --}}
<div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('user.contract.update-info') }}" id="editInfoForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editInfoLabel">Edit Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required" for="ktp_number">No. KTP</label>
                    <input type="text" name="ktp_number" id="ktp_number"
                               class="form-control @error('ktp_number') is-invalid @enderror"
                               value="{{ old('ktp_number', $contract->ktp_number) }}" required
                               inputmode="numeric" autocomplete="off"
                               placeholder="Masukkan nomor KTP">
                    @error('ktp_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label required" for="emergency_phone">No. HP Keluarga yang Bisa Dihubungi</label>
                    <input type="tel" name="emergency_phone" id="emergency_phone"
                               class="form-control @error('emergency_phone') is-invalid @enderror"
                               value="{{ old('emergency_phone', $contract->emergency_phone) }}" required
                               placeholder="Contoh: 081234567890">
                    @error('emergency_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="updateErrors" class="alert alert-danger d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-primary" id="updateInfoBtn">
                    <i data-feather="save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
(function() {
    'use strict';

    const onReady = (cb) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', cb, { once: true });
        } else {
            cb();
        }
    };

    function featherInit() {
        if (window.feather) {
            feather.replace({ 'stroke-width': 1.5, width: 16, height: 16 });
        }
    }

    function handleImageError(img) {
        const placeholder = document.createElement('div');
        placeholder.className = 'profile-image-placeholder';
        placeholder.innerHTML = '<i data-feather="user"></i>';
        img.replaceWith(placeholder);
        featherInit();
    }
    window.handleImageError = handleImageError;

    function formatRupiah(n) {
        return new Intl.NumberFormat('id-ID').format(Number(n || 0));
    }

    function formatTanggalID(iso) {
        if (!iso) return '-';
        try {
            return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch (e) {
            console.error('Failed to format date:', e);
            return '-';
        }
    }

    async function submitFormJson(form, { button }) {
        const errBox = form.querySelector('[id$="Errors"]');
        if (errBox) {
            errBox.classList.add('d-none');
            errBox.innerHTML = '';
        }

        if (button) {
            button.disabled = true;
            button.dataset.originalHtml = button.innerHTML;
            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Mengirim...';
        }

        try {
            const res = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            const data = await res.json().catch(() => ({ message: 'Terjadi kesalahan saat memproses respons.' }));

            if (!res.ok) {
                const errorMessage = (res.status === 422)
                    ? Object.values(data.errors || {}).flat().join('<br>') || 'Validasi gagal.'
                    : data.message || 'Gagal memproses permintaan.';
                if (errBox) {
                    errBox.innerHTML = errorMessage;
                    errBox.classList.remove('d-none');
                }
                return { ok: false, data, status: res.status };
            }
            return { ok: true, data };
        } catch (e) {
            if (errBox) {
                errBox.textContent = e.message || 'Terjadi kesalahan jaringan.';
                errBox.classList.remove('d-none');
            }
            return { ok: false, error: e };
        } finally {
            if (button) {
                button.disabled = false;
                if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                }
                featherInit();
            }
        }
    }

    onReady(() => {
        featherInit();

        // Kalkulasi total perpanjangan
        const durationSelect = document.getElementById('duration');
        const totalPaymentEl = document.getElementById('totalPayment');
        const roomPriceEl = document.getElementById('roomPrice');
        if (durationSelect && totalPaymentEl && roomPriceEl) {
            const calcTotal = () => {
                const duration = parseInt(durationSelect.value || '1');
                const price = parseInt(roomPriceEl.value || '0');
                const total = (duration > 0 ? duration : 1) * price;
                totalPaymentEl.textContent = `Rp ${formatRupiah(total)}`;
            };
            durationSelect.addEventListener('change', calcTotal);
            calcTotal();
        }

        // Submit Perpanjangan (AJAX)
        const extendForm = document.getElementById('extendForm');
        if (extendForm) {
            const extSubmitBtn = document.getElementById('extSubmitBtn');
            const successModal = new bootstrap.Modal(document.getElementById('successExtendModal'));

            extendForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const result = await submitFormJson(extendForm, { button: extSubmitBtn });
                if (result.ok) {
                    const data = result.data;
                    const extendModalInstance = bootstrap.Modal.getInstance(document.getElementById('extendModal'));
                    extendModalInstance && extendModalInstance.hide();

                    document.getElementById('successExtendMsg').textContent = data.message || 'Permohonan perpanjangan berhasil dikirim.';
                    document.getElementById('successPeriod').textContent = (data.start && data.end) ? `${formatTanggalID(data.start)} — ${formatTanggalID(data.end)}` : 'N/A';
                    document.getElementById('successStatus').textContent = data.status || 'pending';
                    document.getElementById('successOrderId').textContent = data.order_id || '-';

                    successModal.show();
                    extendForm.reset();
                    // Reset total pembayaran
                    if (totalPaymentEl && roomPriceEl) {
                        totalPaymentEl.textContent = `Rp ${formatRupiah(roomPriceEl.value)}`;
                    }
                }
            });
        }

        // Submit Edit Info (AJAX)
        const editInfoForm = document.getElementById('editInfoForm');
        if (editInfoForm) {
            const updateInfoBtn = document.getElementById('updateInfoBtn');
            editInfoForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const result = await submitFormJson(editInfoForm, { button: updateInfoBtn });
                if (result.ok) {
                    window.location.reload();
                }
            });
        }
    });
})();
</script>
<style>
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endpush
