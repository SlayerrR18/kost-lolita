{{-- filepath: resources/views/admin/account/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Akun Penghuni')

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

    .btn-submit {
        background: #1a7f5a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
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
</style>
@endpush

@section('content')
<div class="form-container">
    <h1 class="page-title">Tambah Akun Penghuni</h1>

    <div class="form-card">
        <form action="{{ route('admin.account.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Nama Penghuni</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="kost_id">Pilih Kamar</label>
                <select id="kost_id" name="kost_id" class="form-control" required>
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($kosts as $kost)
                        <option value="{{ $kost->id }}">{{ $kost->nomor_kamar }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                       id="tanggal_masuk" name="tanggal_masuk"
                       value="{{ old('tanggal_masuk') }}" required>
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror"
                       id="tanggal_keluar" name="tanggal_keluar"
                       value="{{ old('tanggal_keluar') }}" required>
                @error('tanggal_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="penghuni">Status Penghuni</label>
                <select id="penghuni" name="penghuni" class="form-control">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn-submit">
                    <i data-feather="save" class="me-2"></i>
                    Simpan Akun
                </button>
                <a href="{{ route('admin.account.index') }}" class="btn-cancel">
                    <i data-feather="x" class="me-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endpush
@endsection
