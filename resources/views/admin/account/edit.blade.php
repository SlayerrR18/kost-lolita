{{-- filepath: resources/views/admin/account/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Akun Penghuni')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --secondary: #f1f5f9;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #16a34a;
        --danger: #dc2626;
        --info: #0ea5e9;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* === Layout Form === */
    .form-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    /* Header baru dengan gradien */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(26,127,90,0.15);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff; /* Ubah warna teks menjadi putih */
        margin: 0;
    }

    /* Breadcrumb styling di dalam header */
    .breadcrumb-header {
        background: transparent;
        margin-bottom: 0;
        padding: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .breadcrumb-header .breadcrumb-item,
    .breadcrumb-header .breadcrumb-item a {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-header .breadcrumb-item a:hover {
        color: white;
    }

    .breadcrumb-header .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }

    .form-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2.5rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--ring);
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        color: var(--muted);
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    /* === Input Fields === */
    .input-field {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.125rem;
        height: 1.125rem;
        color: var(--muted);
        transition: color 0.2s ease;
        pointer-events: none;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1.25rem;
        border: 2px solid var(--ring);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: var(--surface);
        color: var(--ink);
        padding-left: 2.75rem;
    }

    .form-control::placeholder {
        color: var(--muted);
        opacity: 0.7;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 127, 90, 0.1);
        outline: none;
    }

    .form-control:focus + .input-icon, .form-select:focus + .input-icon {
        color: var(--primary);
    }

    textarea.form-control {
        padding: 0.75rem 1.25rem;
        min-height: 100px;
        padding-left: 1.25rem; /* Remove icon padding for textarea */
    }

    .invalid-feedback {
        font-size: 0.8125rem;
    }

    /* === Buttons === */
    .btn-action {
        border: none;
        border-radius: var(--radius-md);
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-update {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(26,127,90,0.15);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26,127,90,0.2);
    }

    .btn-cancel {
        background: var(--secondary);
        color: var(--ink);
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-group-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    @media (max-width: 576px) {
        .form-card {
            padding: 1.5rem;
        }
        .btn-group-actions {
            flex-direction: column;
        }
        .btn-group-actions .btn-action {
            width: 100%;
            justify-content: center;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    {{-- Header baru --}}
    <header class="page-header">
        <div>
            <h1 class="page-title">Edit Akun Penghuni</h1>
            <p class="mb-0" style="opacity: 0.9;">Formulir untuk memperbarui data penghuni</p>
        </div>
        <nav aria-label="breadcrumb" class="breadcrumb-header">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.account.index') }}">Akun</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </header>

    @include('layouts.alert')

    <div class="form-card">
        <form action="{{ route('admin.account.update', ['user' => $user->id]) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Bagian Informasi Akun --}}
            <div class="form-section-title">Informasi Akun</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama</label>
                        <div class="input-field">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            <i data-feather="user" class="input-icon"></i>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-field">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            <i data-feather="mail" class="input-icon"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">No. HP</label>
                        <div class="input-field">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                            <i data-feather="phone" class="input-icon"></i>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                        <div class="input-field">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <i data-feather="lock" class="input-icon"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bagian Detail Kontrak --}}
            <div class="form-section-title mt-4">Detail Kontrak</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="kost_id" class="form-label">Pilih Kamar</label>
                        <div class="input-field">
                            <select class="form-select @error('kost_id') is-invalid @enderror"
                                    id="kost_id" name="kost_id" required>
                                <option value="">Pilih Kamar</option>
                                @foreach($kosts as $kost)
                                    <option value="{{ $kost->id }}"
                                        {{ old('kost_id', optional($user->kost)->id) == $kost->id ? 'selected' : '' }}>
                                        Kamar {{ $kost->nomor_kamar }} - Rp {{ number_format($kost->harga, 0, ',', '.') }}/bulan
                                    </option>
                                @endforeach
                            </select>
                            <i data-feather="home" class="input-icon"></i>
                        </div>
                        @error('kost_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <div class="input-field">
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   id="tanggal_masuk" name="tanggal_masuk"
                                   value="{{ old('tanggal_masuk', optional($order)->tanggal_masuk?->format('Y-m-d')) }}" required>
                            <i data-feather="calendar" class="input-icon"></i>
                        </div>
                        @error('tanggal_masuk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <div class="input-field">
                            <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                   id="tanggal_keluar" name="tanggal_keluar"
                                   value="{{ old('tanggal_keluar', optional($order)->tanggal_keluar?->format('Y-m-d')) }}" required>
                            <i data-feather="calendar" class="input-icon"></i>
                        </div>
                        @error('tanggal_keluar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="btn-group-actions mt-4">
                <a href="{{ route('admin.account.index') }}" class="btn btn-action btn-cancel">
                    <i data-feather="x"></i> Batal
                </a>
                <button type="submit" class="btn btn-action btn-update">
                    <i data-feather="save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
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
