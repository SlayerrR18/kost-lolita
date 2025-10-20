@extends('layouts.order')

@section('title', 'Formulir Pemesanan Kamar')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Kolom Gambar Latar Belakang -->
        <div class="col-lg-7 col-md-6 d-none d-md-block" style="background-image: url('{{ asset('img/order.jpg') }}'); background-size: cover; background-position: center;">
        </div>

        <!-- Kolom Form -->
        <div class="col-lg-5 col-md-6 d-flex align-items-center justify-content-center bg-light">
            <div class="p-4" style="width: 100%; max-width: 500px;">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Logo Kost Lolita" style="width: 100px;">
                    </a>
                    <h3 class="mt-3">Pesan Kamar: {{ $kost->nomor_kamar }}</h3>
                    <p class="text-muted">Lengkapi data di bawah untuk menyelesaikan pesanan Anda.</p>
                </div>

                @include('layouts.alert')

                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kost_id" value="{{ $kost->id }}">

                    <!-- Info Kamar (Read-only) -->
                    <div class="card mb-3 bg-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $kost->nama_kost }}</h5>
                            <p class="card-text">
                                <i class="fas fa-door-closed"></i> Tipe: {{ $kost->tipe }} <br>
                                <i class="fas fa-ruler-combined"></i> Ukuran: {{ $kost->ukuran }} <br>
                                <i class="fas fa-money-bill-wave"></i> Harga: Rp {{ number_format($kost->harga, 0, ',', '.') }} / bulan
                            </p>
                        </div>
                    </div>

                    <!-- Data Diri dari Akun (Read-only) -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Pemesan</label>
                        <input type="text" id="name" class="form-control" value="{{ $user->name }}" disabled>
                        <div class="form-text">Nama sesuai dengan akun Anda yang terdaftar.</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>

                    <!-- Data Diri yang Perlu Diisi -->
                    <div class="mb-3">
                        <label for="phone" class="form-label required">No. WhatsApp Aktif</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label required">Alamat Lengkap (Sesuai KTP)</label>
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $user->address) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Detail Pemesanan -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label required">Durasi Sewa (Bulan)</label>
                            <select name="duration" id="duration" class="form-select @error('duration') is-invalid @enderror" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                                @endfor
                            </select>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_masuk" class="form-label required">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk') }}" min="{{ date('Y-m-d') }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div class="mb-3">
                        <label for="ktp_image" class="form-label required">Upload Foto KTP</label>
                        <input type="file" name="ktp_image" id="ktp_image" class="form-control @error('ktp_image') is-invalid @enderror" required>
                        <div class="form-text">Format: JPG, PNG. Maksimal 5MB.</div>
                        @error('ktp_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label required">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" required>
                        <div class="form-text">Silakan transfer sesuai total harga dan durasi sewa.</div>
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
