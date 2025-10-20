@extends('layouts.auth')

@section('title', Request::is('admin/login') ? 'Admin Login - Kost Lolita' : 'Login - Kost Lolita')

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
        padding: 1rem;
    }

    .login-card-wrapper {
        width: 100%;
        max-width: 1000px;
        margin: auto;
    }

    .login-card {
        background: var(--surface);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .login-form-panel {
        padding: 3.5rem;
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .login-header {
        text-align: center;
    }

    .login-logo {
        height: 48px;
        margin-bottom: 2rem;
    }

    .login-title {
        font-size: 2rem;
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-subtitle {
        color: var(--muted);
        font-size: 1rem;
        line-height: 1.6;
    }

    .login-greeting {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .login-greeting h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .login-greeting p {
        font-size: 1.1rem;
        color: var(--muted);
        line-height: 1.6;
        max-width: 400px;
        margin: 0 auto;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-floating {
        position: relative;
    }

    .form-control {
        height: 60px;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(26,127,90,0.1);
        background: white;
    }

    .form-floating label {
        padding: 1rem 1.25rem;
        color: var(--muted);
    }

    .input-group-text {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 16px 0 0 16px;
        padding: 0.75rem 1.25rem;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 16px 16px 0;
    }

    .btn-submit {
        height: 60px;
        border-radius: 16px;
        font-size: 1.1rem;
        font-weight: 600;
        background: var(--primary);
        border: none;
        color: white;
        transition: all 0.3s ease;
        padding: 1rem;
    }

    .btn-submit:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    .separator {
        display: flex;
        align-items: center;
        text-align: center;
        color: var(--muted);
        font-size: 0.875rem;
    }

    .separator::before,
    .separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .separator:not(:empty)::before {
        margin-right: 1rem;
    }

    .separator:not(:empty)::after {
        margin-left: 1rem;
    }

    .login-image-panel {
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                    url('{{ asset('img/login.jpg') }}') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: relative;
    }

    .image-overlay {
        position: absolute;
        inset: 0;
        /* background: linear-gradient(to right, rgba(26,127,90,0.8), rgba(22,198,154,0.8)); */
        mix-blend-mode: overlay;
    }

    .image-content {
        color: white;
        text-align: center;
        position: relative;
        z-index: 2;
        max-width: 400px;
    }

    .image-content h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .image-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .decoration-circle {
        position: absolute;
        border-radius: 50%;
        background: var(--primary);
        opacity: 0.1;
    }

    .circle-1 {
        width: 300px;
        height: 300px;
        top: -150px;
        right: -150px;
    }

    .circle-2 {
        width: 200px;
        height: 200px;
        bottom: -100px;
        left: -100px;
    }

    @media (max-width: 991.98px) {
        .login-form-panel {
            padding: 2rem;
        }
        .login-image-panel {
            min-height: 300px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-card-wrapper">
    <div class="container-fluid" style="max-width: 1100px;">
        <div class="login-card card">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="login-form-panel">
                        <div class="decoration-circle circle-1"></div>
                        <div class="decoration-circle circle-2"></div>
                        <div>
                            <div class="login-logo-container mb-4">
                                <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Kost Lolita Logo" class="login-logo">
                            </div>

                            <div class="login-greeting">
                                <h2>Selamat Datang!</h2>
                                <p>
                                    Temukan hunian nyaman dan terjangkau untuk masa studi Anda yang lebih baik
                                </p>
                            </div>

                            <form method="POST" action="{{ Request::is('admin/login') ? route('admin.login') : route('login') }}" class="mt-4">
                                @csrf

                                @include('layouts.alert')
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                               placeholder="Alamat email Anda">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input id="password" type="password" class="form-control password-input @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="current-password"
                                               placeholder="Kata sandi Anda">
                                        <button class="btn btn-password-toggle" type="button" id="togglePassword">
                                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Ingat Saya') }}
                                        </label>
                                    </div>
                                    @if (Route::has('password.request') && !Request::is('admin/login'))
                                        <a class="forgot-password-link" href="{{ route('password.request') }}">
                                            {{ __('Lupa Password?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-submit">
                                        {{ __('Masuk Sekarang') }}
                                    </button>
                                </div>

                                <div class="separator">ATAU</div>

                                @if (Route::has('register') && !Request::is('admin/login'))
                                    <p class="text-center text-muted mb-0" style="font-size: 0.95rem;">
                                        Belum punya akun? <a href="{{ route('register') }}" class="link-secondary fw-bold">Daftar di sini</a>
                                    </p>
                                @endif
                            </form>
                        </div>

                        {{-- Bagian bawah: Copyright --}}
                        <div class="login-footer-left mt-4">
                            <p class="login-copyright text-center">
                                &copy; {{ date('Y') }} Kost Lolita. Semua hak cipta dilindungi.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- === AKHIR KOLOM KIRI === --}}

                {{-- === KOLOM KANAN: GAMBAR === --}}
                <div class="col-lg-6 d-none d-lg-block login-image-panel">
                    <div class="image-overlay"></div>
                    <div class="image-content">
                        <h3>Kualitas Terbaik di Ruteng</h3>
                        <p>Nikmati fasilitas modern, lokasi strategis, dan lingkungan yang nyaman untuk mendukung kesuksesan studi Anda</p>
                    </div>
                </div>
                {{-- === AKHIR KOLOM KANAN === --}}

            </div>
        </div>
        {{-- === AKHIR CARD UTAMA === --}}

    </div>
</div>
@endsection

{{-- Script untuk Toggle Password (Sama seperti sebelumnya) --}}
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                e.preventDefault(); // Mencegah form submit jika tombol ada di dalam form

                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'password') {
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            });
        }
    });
</script>
@endpush
