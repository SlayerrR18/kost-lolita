<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Masuk - Kost Lolita</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        :root {
            --brand: #1a7f5a;
            --brand-light: #16c79a;
            --ink: #0f172a;
            --muted: #64748b;
            --ring: #e2e8f0;
            --danger: #ef4444;
            --surface: #ffffff;
            --bg-soft: #f8fafc;
            --radius-lg: 24px;
            --radius-md: 12px;
            --shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        @keyframes animateGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* [BARU] Keyframes untuk animasi ikon yang melayang */
        @keyframes floatUp {
            0% {
                transform: translateY(0);
                opacity: 0;
            }
            10%, 90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh);
                opacity: 0;
            }
        }

        html { height: 100%; }

        body {
            position: relative; /* Diperlukan agar z-index pada child bekerja */
            height: 100%;
            font-family: 'Poppins', sans-serif;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #059669, #10b981, #34d399, #065f46);
            background-size: 400% 400%;
            animation: animateGradient 15s ease infinite;
            overflow: hidden; /* Mencegah scroll dari ikon yang melayang */
        }

        /* [BARU] Container untuk ikon-ikon di background */
        .background-icons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1; /* Di bawah kartu login */
        }

        /* [BARU] Style untuk setiap ikon */
        .background-icons span {
            position: absolute;
            display: block;
            width: 40px; /* Ukuran default */
            height: 40px;
            bottom: -100px; /* Mulai dari bawah layar */
            color: rgba(255, 255, 255, 0.15); /* Warna ikon transparan */
            animation: floatUp 25s infinite linear;
            pointer-events: none; /* Agar tidak bisa di-klik */
        }

        /* [BARU] Variasi untuk setiap ikon (ini kuncinya!) */
        .background-icons span:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-duration: 22s; animation-delay: 0s; }
        .background-icons span:nth-child(2) { left: 10%; width: 30px; height: 30px; animation-duration: 18s; animation-delay: 2s; }
        .background-icons span:nth-child(3) { left: 70%; width: 50px; height: 50px; animation-duration: 28s; animation-delay: 4s; }
        .background-icons span:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-duration: 20s; animation-delay: 0s; }
        .background-icons span:nth-child(5) { left: 65%; width: 25px; height: 25px; animation-duration: 15s; animation-delay: 1s; }
        .background-icons span:nth-child(6) { left: 85%; width: 90px; height: 90px; animation-duration: 35s; animation-delay: 5s; }
        .background-icons span:nth-child(7) { left: 5%; width: 45px; height: 45px; animation-duration: 19s; animation-delay: 7s; }
        .background-icons span:nth-child(8) { left: 50%; width: 35px; height: 35px; animation-duration: 24s; animation-delay: 3s; }

        .login-card {
            width: 100%;
            max-width: 450px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid #e7eaf0;
            padding: 2.5rem 2.75rem;
            box-shadow: var(--shadow-lg);
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
            color: var(--ink);
            position: relative; /* Menaikkan posisi di atas background */
            z-index: 2; /* Di atas ikon */
        }

        /* ... Sisa CSS Anda sama persis seperti sebelumnya ... */
        @media (max-width: 576px) { .login-card { padding: 2rem 1.5rem; } }
        .brand-logo { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; }
        .brand-logo img { width: 56px; height: 56px; }
        .brand-logo span { font-weight: 700; font-size: 1.5rem; color: var(--ink); }
        .header-text { margin-bottom: 2rem; }
        .title { font-weight: 600; margin-bottom: 0.5rem; color: var(--ink); }
        .subtitle { color: var(--muted); font-size: 0.95rem; }
        .login-form { text-align: left; }
        .form-group { margin-bottom: 1.25rem; }
        .input-field { position: relative; }
        .input-field .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); transition: color 0.3s ease; }
        .input { width: 100%; padding: 0.8rem 1rem 0.8rem 2.75rem; border: 1px solid var(--ring); border-radius: var(--radius-md); background: var(--bg-soft); color: var(--ink); font-size: 0.95rem; outline: none; transition: all 0.3s ease; }
        .input::placeholder { color: var(--muted); opacity: 0.8; }
        .input:focus { background: var(--surface); border-color: var(--brand); box-shadow: 0 0 0 4px rgba(26, 127, 90, 0.1); }
        .input:focus + .input-icon { color: var(--brand); }
        #password.input { padding-right: 3.5rem; }
        .toggle-pass { position: absolute; right: 0.625rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0.5rem; cursor: pointer; color: var(--muted); transition: color 0.3s ease; }
        .toggle-pass:hover { color: var(--ink); }
        .form-row { display: flex; justify-content: space-between; margin: 1rem 0 1.5rem; font-size: 0.875rem; }
        .form-row label { display: flex; align-items: center; gap: 0.5rem; color: var(--muted); cursor: pointer; }
        .form-row a { color: var(--muted); text-decoration: none; transition: color 0.3s ease; }
        .form-row a:hover { color: var(--brand); }
        .btn-submit { width: 100%; border: none; border-radius: var(--radius-md); padding: 0.85rem 1rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%); color: var(--surface); box-shadow: 0 8px 20px rgba(26, 127, 90, 0.2); }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(26, 127, 90, 0.3); }
        .btn-submit:disabled { cursor: not-allowed; opacity: 0.7; transform: translateY(0); }
        .spinner { width: 18px; height: 18px; border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .divider { display: flex; align-items: center; text-align: center; color: var(--muted); font-size: 0.8rem; margin: 1.75rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid var(--ring); }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }
        .btn-social { width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--ring); background: var(--surface); color: var(--ink); font-weight: 500; text-decoration: none; transition: all 0.3s ease; }
        .btn-social:hover { background: var(--bg-soft); border-color: #d1d9e4; }
        .btn-social img { width: 20px; height: 20px; }
        .auth-footer { margin-top: 1.75rem; color: var(--muted); font-size: 0.9rem; }
        .auth-footer a { color: var(--brand); font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>

    <div class="background-icons">
        <span><i data-feather="home"></i></span>
        <span><i data-feather="key"></i></span>
        <span><i data-feather="bed"></i></span>
        <span><i data-feather="wifi"></i></span>
        <span><i data-feather="moon"></i></span>
        <span><i data-feather="map-pin"></i></span>
        <span><i data-feather="home"></i></span>
        <span><i data-feather="key"></i></span>
    </div>

    <main class="login-card">
        <div class="brand-logo">
            <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Logo Kost Lolita">
            <span>Kost Lolita</span>
        </div>
        <div class="header-text">
            <h1 class="title">Selamat Datang</h1>
            <p class="subtitle">Kost Aman, Nyaman Dan Tentram.</p>
        </div>
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
        <form class="login-form" action="{{ route('login') }}" method="POST" id="loginForm" novalidate>
            @csrf
            <div class="form-group">
                <div class="input-field">
                    <input type="email" class="input" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="username">
                    <i data-feather="mail" class="input-icon"></i>
                </div>
            </div>
            <div class="form-group">
                <div class="input-field">
                    <input type="password" class="input" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                    <i data-feather="lock" class="input-icon"></i>
                    <button type="button" class="toggle-pass" aria-label="Tampilkan/Sembunyikan Password">
                        <i data-feather="eye"></i>
                    </button>
                </div>
            </div>
            <div class="form-row">
                <label>
                    <input type="checkbox" name="remember" style="accent-color: var(--brand);" checked> Ingat Saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <button type="submit" class="btn-submit" id="submitBtn">
                Masuk
            </button>
        </form>
        <div class="divider">atau</div>
        <a href="#" class="btn-social">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google icon">
            Masuk dengan Google
        </a>
        <p class="auth-footer">
            Belum punya akun?
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Daftar sekarang</a>
            @else
                <a href="{{ url('/') }}">Kembali ke Beranda</a>
            @endif
        </p>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace({ 'stroke-width': 1.5 });

            // ... Sisa JavaScript sama persis ...
            const togglePassBtn = document.querySelector('.toggle-pass');
            const passwordInput = document.getElementById('password');
            if (togglePassBtn && passwordInput) {
                togglePassBtn.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    const eyeIcon = type === 'password' ? 'eye' : 'eye-off';
                    togglePassBtn.innerHTML = feather.icons[eyeIcon].toSvg({ 'stroke-width': 1.5 });
                });
            }

            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            if(loginForm && submitBtn) {
                loginForm.addEventListener('submit', () => {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<div class="spinner"></div> Sedang memproses...`;
                });
            }
        });
    </script>
</body>
</html>
