{{-- filepath: resources/views/admin/account/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Akun Penghuni')

@push('css')
<style>
    .form-container {
        padding: 32px;
        background: #f6f8fa;
        min-height: 100vh;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a7f5a;
        margin-bottom: 24px;
    }

    .form-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 32px;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #1a7f5a;
        box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
        outline: none;
    }

    .btn-update {
        background: #1a7f5a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        background: #156c4a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26,127,90,0.15);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 24px;
    }

    .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #1a7f5a;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Manajemen</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.account.index') }}">Akun Penghuni</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Akun</li>
        </ol>
    </nav>

    <h1 class="page-title">Edit Akun Penghuni</h1>

    <div class="form-card">
        <form action="{{ route('admin.account.update', ['user' => $user->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">No. HP</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kost_id" class="form-label">Pilih Kamar</label>
                <select class="form-select @error('kost_id') is-invalid @enderror"
                        id="kost_id" name="kost_id" required>
                    <option value="">Pilih Kamar</option>
                    @foreach($kosts as $kost)
                        <option value="{{ $kost->id }}"
                            {{ old('kost_id', $user->kost_id) == $kost->id ? 'selected' : '' }}>
                            Kamar {{ $kost->nomor_kamar }} - Rp {{ number_format($kost->harga, 0, ',', '.') }}/bulan
                        </option>
                    @endforeach
                </select>
                @error('kost_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                       id="tanggal_masuk" name="tanggal_masuk"
                       value="{{ old('tanggal_masuk', optional($order)->tanggal_masuk?->format('Y-m-d')) }}" required>
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror"
                       id="tanggal_keluar" name="tanggal_keluar"
                       value="{{ old('tanggal_keluar', optional($order)->tanggal_keluar?->format('Y-m-d')) }}" required>
                @error('tanggal_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
