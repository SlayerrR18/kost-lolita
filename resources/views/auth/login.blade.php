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
            --danger: #dc2626;
            --bg: #f8fafc;
            --surface: #fff;
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 30px rgba(2, 6, 23, 0.08);
            --shadow-lg: 0 14px 24px rgba(26, 127, 90, 0.22);
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--ink);
            display: grid;
            place-items: center;
            padding: 1rem;
        }

        /* === Container Utama === */
        .auth-container {
            width: 100%;
            max-width: 1200px;
            min-height: 520px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            overflow: hidden;
        }

        @media (max-width: 1024px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
        }

        /* === Kolom Kiri (Form) === */
        .auth-form-section {
            padding: 2.5rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 1.75rem;
        }

        .brand-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand-logo span {
            font-weight: 700;
            letter-spacing: 0.2px;
            font-size: 1.25rem;
        }

        .header-text {
            margin-bottom: 1.5rem;
        }

        .title {
            font-size: clamp(1.625rem, 3vw, 2.25rem);
            line-height: 1.2;
            margin: 0.5rem 0 0.5rem;
        }

        .subtitle {
            color: var(--muted);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .input-field {
            position: relative; /* Penting: agar ikon absolut di dalamnya relatif terhadap ini */
        }

        .input-field .input-icon { /* Kelas baru untuk ikon */
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.125rem;
            height: 1.125rem;
            color: var(--muted);
            transition: color 0.2s ease;
            pointer-events: none; /* Agar tidak menghalangi klik input */
        }

        .input {
            width: 100%;
            padding: 0.75rem 2.75rem 0.75rem 2.75rem; /* Tambah padding kiri untuk ikon */
            border: 2px solid var(--ring);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
            background: var(--surface);
            color: var(--ink);
        }

        /* Tambahkan padding kanan untuk input password agar tidak tumpang tindih dengan toggle button */
        #password.input {
            padding-right: 3.5rem; /* Cukup ruang untuk ikon mata */
        }

        .input::placeholder {
            color: var(--muted);
            opacity: 0.7;
        }

        .input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(26, 127, 90, 0.1);
        }

        .input:focus + .input-icon { /* Saat input fokus, ubah warna ikon */
            color: var(--brand);
        }

        .toggle-pass {
            position: absolute;
            right: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            color: var(--muted);
            transition: color 0.2s ease;
            z-index: 10;
        }

        .toggle-pass:hover {
            color: var(--brand);
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 0.75rem 0 1.25rem;
            font-size: 0.8125rem;
            color: var(--muted);
        }

        .form-row label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            user-select: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .form-row a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .form-row a:hover {
            color: var(--brand);
        }

        .btn-submit {
            width: 100%;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%);
            color: #fff;
            box-shadow: 0 10px 20px rgba(26, 127, 90, 0.15);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(26, 127, 90, 0.22);
        }

        .error-list {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }

        .error-list ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .error-list li {
            color: var(--danger);
            font-size: 0.8125rem;
        }

        .auth-footer {
            margin-top: 1.5rem;
            font-size: 0.8125rem;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 500;
        }

        /* === Kolom Kanan (Ilustrasi) === */
        .auth-art-section {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background:
                radial-gradient(1100px 520px at 90% 10%, rgba(255, 255, 255, 0.15), transparent 60%),
                radial-gradient(800px 500px at 10% 100%, rgba(255, 255, 255, 0.12), transparent 55%),
                linear-gradient(135deg, var(--brand) 0%, #22c39a 35%, var(--brand-light) 70%, #3ce3b8 100%);
        }

        .auth-art-section::before {
            content: "";
            position: absolute;
            inset: 1rem;
            border-radius: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.14);
        }

        .art-svg {
            width: min(560px, 92%);
            height: auto;
        }

        @media (max-width: 1024px) {
            .auth-art-section {
                display: none;
            }
        }
    </style>
</head>
<body>
    <main class="auth-container">
        <section class="auth-form-section">
            <div class="brand-logo">
                <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Logo Kost Lolita">
                <span>Kost Lolita</span>
            </div>

            <div class="header-text">
                <div class="d-flex align-items-center gap-2" style="color:#16c79a;font-weight:600;margin-bottom:6px">
                    <i data-feather="shield"></i> Aman & Nyaman
                </div>
                <h1 class="title">Hallo, Selamat Datang <br> Di kost Lolita</h1>
                <p class="subtitle">Masuk untuk mengelola kontrak, pembayaran, dan notifikasi kost kamu.</p>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success mt-2" role="alert">{{ session('success') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf
                <div class="form-group">
                    <div class="input-field">
                        <input type="email" class="input" id="email" name="email" value="{{ old('email') }}"
                               placeholder="Email" required autocomplete="username">
                        <i data-feather="mail" class="input-icon"></i> </div>
                </div>

                <div class="form-group">
                    <div class="input-field">
                        <input type="password" class="input" id="password" name="password" placeholder="Password"
                               required autocomplete="current-password" minlength="6">
                        <i data-feather="lock" class="input-icon"></i> <button type="button" class="toggle-pass" aria-label="Tampilkan/Sembunyikan Password">
                            <i data-feather="eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <label>
                        <input type="checkbox" name="remember" style="accent-color:#16c79a" checked> Ingat Saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    <i data-feather="log-in"></i> Masuk
                </button>

                @if($errors->any())
                    <div class="error-list">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="auth-footer">Belum punya akun?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Daftar</a>
                    @else
                        <a href="{{ url('/') }}">Kembali ke Beranda</a>
                    @endif
                </p>
            </form>
        </section>

        {{-- RIGHT: ILLUSTRATION --}}
        <section class="auth-art-section" aria-hidden="true">
            <svg class="art-svg" viewBox="0 0 900 700" xmlns="http://www.w3.org/2000/svg" role="img">
                <title>Kost Aman: check-in, kunci & ponsel</title>
                <g opacity=".95" fill="#ffffff">
                    <path d="M120 160c18-40 78-40 96 0 44-12 80 34 50 66H70c-14-24 2-54 50-66z" opacity=".25" />
                    <path d="M740 110c20-30 74-26 88 8 32-6 60 26 40 50H690c-8-18 6-42 50-58z" opacity=".25" />
                    <path d="M710 550c22-36 86-32 102 8 34-8 64 22 44 46H648c-10-16 6-38 62-54z" opacity=".25" />
                </g>
                <g transform="translate(520,120) rotate(-6)">
                    <rect x="0" y="0" rx="36" ry="36" width="260" height="460" fill="#064e3b" opacity=".18" />
                    <rect x="12" y="12" rx="28" ry="28" width="236" height="436" fill="#065f46" />
                    <rect x="18" y="60" rx="24" ry="24" width="224" height="360" fill="url(#gradScreen)" />
                    <circle cx="130" cy="28" r="6" fill="#e5e7eb" />
                    <g stroke="#ffffff" stroke-width="3" fill="none" opacity=".9" transform="translate(70,220)">
                        <circle cx="60" cy="40" r="38" opacity=".25" />
                        <path d="M32,48c8,18 20,28 39,28 22,0 35-12 45-32" />
                        <path d="M28,28c12,-22 34,-30 64,-20 25,8 36,28 36,48" />
                        <path d="M58,10c18,2 36,16 36,42 0,18 -2,24 -8,36" />
                    </g>
                    <rect x="70" y="370" width="140" height="10" rx="5" fill="#34d399" opacity=".35" />
                    <rect x="70" y="370" width="86" height="10" rx="5" fill="#6ee7b7" />
                </g>
                <g transform="translate(680,340)">
                    <rect x="-36" y="36" width="120" height="100" rx="16" fill="#ffffff" opacity=".2" />
                    <rect x="-28" y="44" width="104" height="84" rx="14" fill="#ffffff" opacity=".9" />
                    <rect x="-12" y="86" width="72" height="12" rx="6" fill="#10b981" />
                    <path d="M8,44 a34,34 0 0 1 68,0 v10 h-12 v-10 a22,22 0 0 0 -44,0 v10 H8z" fill="#ffffff" opacity=".9" />
                </g>
                <g transform="translate(160,420)">
                    <rect x="-10" y="-120" width="200" height="160" rx="16" fill="#ffffff" opacity=".2" />
                    <path d="M0,-40 l100,-70 100,70 v100 a16,16 0 0 1 -16,16 h-168 a16,16 0 0 1 -16,-16z" fill="#fff" opacity=".95" />
                    <rect x="80" y="-18" width="40" height="74" rx="6" fill="#16c79a" />
                    <circle cx="115" cy="18" r="4" fill="#fff" />
                </g>
                <defs>
                    <linearGradient id="gradScreen" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#16c79a" />
                        <stop offset="60%" stop-color="#22c39a" />
                        <stop offset="100%" stop-color="#2dd4bf" />
                    </linearGradient>
                </defs>
            </svg>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace({ 'stroke-width': 1.8 });

            const togglePassBtn = document.querySelector('.toggle-pass');
            const passwordInput = document.getElementById('password');

            if (togglePassBtn && passwordInput) {
                togglePassBtn.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    togglePassBtn.innerHTML = feather.icons[type === 'password' ? 'eye' : 'eye-off'].toSvg();
                });
            }
        });
    </script>
</body>
</html>
