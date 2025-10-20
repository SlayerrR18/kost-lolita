@extends('layouts.auth')

@section('title', 'Register - Kost Lolita')

@push('css')
<style>
    :root {
        --primary: #1a7f5a;
        --primary-hover: #156348;
        --surface: #ffffff;
        --muted: #64748b;
    }

    body {
        min-height: 100vh;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 2rem;
    }

    .register-card-wrapper {
        width: 100%;
        max-width: 1200px;
        margin: auto;
    }

    .register-card {
        background: var(--surface);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .register-form-panel {
        padding: 3rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1;
    }

    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .register-header h2 {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }

    .register-header p {
        color: var(--muted);
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .form-floating {
        margin-bottom: 1.25rem;
    }

    .form-floating > label {
        padding-left: 1rem;
    }

    .form-control {
        height: 56px;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(26,127,90,0.1);
        background-color: #ffffff;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
        color: var(--primary);
    }

    .btn-register {
        width: 100%;
        padding: 1rem;
        border-radius: 12px;
        background: var(--primary);
        color: white;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .btn-register:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(26,127,90,0.2);
    }

    .register-image-panel {
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                    url('{{ asset('img/fasilitas.jpg') }}') center/cover;
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: relative;
    }

    .image-content {
        color: white;
        text-align: center;
        max-width: 400px;
        position: relative;
        z-index: 2;
    }

    .image-content h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .image-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-group .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .login-link {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        color: var(--muted);
        font-size: 0.95rem;
    }

    .login-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .register-form-panel {
            padding: 2rem;
        }

        .register-image-panel {
            min-height: 300px;
        }

        .register-header h2 {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="register-card-wrapper">
    <div class="register-card">
        <div class="row g-0">
            <!-- Left: Register Form -->
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="register-form-panel">
                    <div class="register-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Mulai perjalanan Anda dengan kami untuk mendapatkan hunian nyaman dan terjangkau</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" placeholder="Nama Lengkap"
                                   value="{{ old('name') }}" required>
                            <label for="name">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" placeholder="Email"
                                   value="{{ old('email') }}" required>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Konfirmasi Password" required>
                            <label for="password_confirmation">Konfirmasi Password</label>
                        </div>

                        <button type="submit" class="btn-register">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </button>

                        <div class="login-link">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Image & Message -->
            <div class="col-lg-6 order-1 order-lg-2 d-none d-lg-block">
                <div class="register-image-panel">
                    <div class="image-content">
                        <h3>Mulai Perjalanan Anda</h3>
                        <p>Bergabung dengan Kost Lolita dan nikmati fasilitas modern serta lingkungan yang mendukung kesuksesan anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
