<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Login - Kost Lolita</title>

  <!-- Font & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>

  <style>
    :root{
      --brand:#1a7f5a; --brand2:#16c79a;
      --ink:#0f172a; --muted:#64748b; --ring:#e2e8f0;
      --danger:#dc2626; --bg:#f8fafc; --surface:#fff; --radius:20px;
    }
    *{box-sizing:border-box;margin:0}
    html,body{height:100%}
    body{font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;
         background:var(--bg); color:var(--ink); display:grid; place-items:center; padding:16px;}

    /* Grid utama */
    .auth{
      width:100%; max-width:1200px; min-height:520px;
      background:var(--surface); border-radius:var(--radius);
      box-shadow:0 10px 30px rgba(2,6,23,.08);
      display:grid; grid-template-columns:1.05fr 1fr; overflow:hidden;
    }
    @media (max-width:1024px){ .auth{grid-template-columns:1fr} }

    /* Kolom kiri (form) */
    .left{padding:40px 44px; display:flex; flex-direction:column; justify-content:center}
    .brand{display:flex; align-items:center; gap:10px; margin-bottom:28px}
    .brand img{width:40px; height:40px; object-fit:contain}
    .brand span{font-weight:700; letter-spacing:.2px}
    h1{font-size:clamp(26px,3vw,36px); line-height:1.15; margin:8px 0 8px}
    .sub{color:var(--muted); margin-bottom:24px}

    .flash{margin-bottom:12px}
    .flash .alert{border-radius:12px; padding:10px 12px; font-size:14px}

    .form-group{margin-bottom:14px}
    label{display:block; font-size:13px; color:var(--muted); margin-bottom:6px}
    .field{position:relative}
    .field i{position:absolute; left:12px; top:50%; transform:translateY(-50%); width:18px; height:18px; color:var(--muted)}
    .input{
      width:100%; padding:12px 44px; border:2px solid var(--ring); border-radius:12px;
      font-size:14px; outline:0; transition:.2s; background:#fff;
    }
    .input:focus{border-color:var(--brand); box-shadow:0 0 0 4px rgba(26,127,90,.10)}
    .toggle-pass{position:absolute; right:10px; top:70%; transform:translateY(-50%);
      background:transparent; border:0; padding:6px; cursor:pointer; color:var(--muted)}

    .row{display:flex; align-items:center; justify-content:space-between; gap:10px; margin:10px 0 18px;
         font-size:13px; color:var(--muted)}
    .row a{color:var(--muted); text-decoration:none}
    .row a:hover{color:var(--brand)}

    .btn{width:100%; border:none; border-radius:12px; padding:12px 16px; font-weight:600;
         cursor:pointer; transition:.25s; display:inline-flex; align-items:center; justify-content:center; gap:8px}
    .btn-primary{
      background:linear-gradient(135deg,var(--brand) 0%,var(--brand2) 100%); color:#fff;
      box-shadow:0 10px 20px rgba(26,127,90,.15);
    }
    .btn-primary:hover{transform:translateY(-1px); box-shadow:0 14px 24px rgba(26,127,90,.22)}

    .errors{background:#fef2f2; border:1px solid #fee2e2; border-radius:12px; padding:12px 14px; margin-top:12px}
    .errors li{color:var(--danger); font-size:13px; margin-left:18px}
    .foot{margin-top:14px; font-size:13px; color:var(--muted)}
    .foot a{color:var(--brand); text-decoration:none}

    .right{
      position:relative; display:flex; align-items:center; justify-content:center; padding:32px;
      background:
        radial-gradient(1100px 520px at 90% 10%, rgba(255,255,255,.15), transparent 60%),
        radial-gradient(800px 500px at 10% 100%, rgba(255,255,255,.12), transparent 55%),
        linear-gradient(135deg, var(--brand) 0%, #22c39a 35%, var(--brand2) 70%, #3ce3b8 100%);
    }
    .right::before{content:""; position:absolute; inset:16px; border-radius:16px; border:2px solid rgba(255,255,255,.14)}
    .art{width:min(560px,92%); height:auto}
    @media (max-width:1024px){ .right{display:none} }
  </style>
</head>
<body>
  <main class="auth">
    <!-- LEFT: FORM -->
    <section class="left">
      <div class="brand">
        <img src="{{ asset('img/Logo-no-bg.png') }}" alt="Logo Kost Lolita">
        <span>Kost Lolita</span>
      </div>

      <div>
        <div style="display:flex;align-items:center;gap:8px;color:#16c79a;font-weight:600;margin-bottom:6px">
          <i data-feather="shield"></i> Aman & Nyaman
        </div>
        <h1>Hallo, Selamat Datang <br> Di kost Lolita</h1>
        <p class="sub">Masuk untuk mengelola kontrak, pembayaran, dan notifikasi kost kamu.</p>
      </div>

      {{-- Flash sukses (mis. setelah logout) --}}
      <div class="flash">
        @if(session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
      </div>

      <form action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <div class="field">
            <i data-feather="mail"></i>
            <input type="email" class="input" id="email" name="email" value="{{ old('email') }}"
                   placeholder="Masukan Email" required autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="field">
            <i data-feather="lock"></i>
            <input type="password" class="input" id="password" name="password" placeholder="Masukan Password"
                   required autocomplete="current-password" minlength="6">
            <button type="button" class="toggle-pass" aria-label="Tampilkan/Sembunyikan Password">
              <i data-feather="eye"></i>
            </button>
          </div>
        </div>

        <div class="row">
          <label style="display:flex;align-items:center;gap:8px;user-select:none;cursor:pointer">
            <input type="checkbox" name="remember" style="accent-color:#16c79a" checked> Ingat Saya
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Lupa password?</a>
          @endif
        </div>

        <button type="submit" class="btn btn-primary">
          <i data-feather="log-in"></i> Masuk
        </button>

        @if($errors->any())
          <div class="errors">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <p class="foot">Belum punya akun?
          @if (Route::has('register'))
            <a href="{{ route('register') }}">Sign Up</a>
          @else
            <a href="{{ url('/') }}">Kembali ke Beranda</a>
          @endif
        </p>
      </form>
    </section>

    <section class="right" aria-hidden="true">
      <svg class="art" viewBox="0 0 900 700" xmlns="http://www.w3.org/2000/svg" role="img">
        <title>Kost Aman: check-in, kunci & ponsel</title>
        <g opacity=".95" fill="#ffffff">
          <path d="M120 160c18-40 78-40 96 0 44-12 80 34 50 66H70c-14-24 2-54 50-66z" opacity=".25"/>
          <path d="M740 110c20-30 74-26 88 8 32-6 60 26 40 50H690c-8-18 6-42 50-58z" opacity=".25"/>
          <path d="M710 550c22-36 86-32 102 8 34-8 64 22 44 46H648c-10-16 6-38 62-54z" opacity=".25"/>
        </g>

        <!-- ponsel -->
        <g transform="translate(520,120) rotate(-6)">
          <rect x="0" y="0" rx="36" ry="36" width="260" height="460" fill="#064e3b" opacity=".18"/>
          <rect x="12" y="12" rx="28" ry="28" width="236" height="436" fill="#065f46" />
          <rect x="18" y="60" rx="24" ry="24" width="224" height="360" fill="url(#gradScreen)"/>
          <circle cx="130" cy="28" r="6" fill="#e5e7eb"/>
          <!-- fingerprint -->
          <g stroke="#ffffff" stroke-width="3" fill="none" opacity=".9" transform="translate(70,220)">
            <circle cx="60" cy="40" r="38" opacity=".25"/>
            <path d="M32,48c8,18 20,28 39,28 22,0 35-12 45-32"/>
            <path d="M28,28c12,-22 34,-30 64,-20 25,8 36,28 36,48"/>
            <path d="M58,10c18,2 36,16 36,42 0,18 -2,24 -8,36"/>
          </g>
          <!-- progress -->
          <rect x="70" y="370" width="140" height="10" rx="5" fill="#34d399" opacity=".35"/>
          <rect x="70" y="370" width="86" height="10" rx="5" fill="#6ee7b7"/>
        </g>

        <!-- gembok -->
        <g transform="translate(680,340)">
          <rect x="-36" y="36" width="120" height="100" rx="16" fill="#ffffff" opacity=".2"/>
          <rect x="-28" y="44" width="104" height="84" rx="14" fill="#ffffff" opacity=".9"/>
          <rect x="-12" y="86" width="72" height="12" rx="6" fill="#10b981"/>
          <path d="M8,44 a34,34 0 0 1 68,0 v10 h-12 v-10 a22,22 0 0 0 -44,0 v10 H8z" fill="#ffffff" opacity=".9"/>
        </g>

        <!-- rumah/kamar kost -->
        <g transform="translate(160,420)">
          <rect x="-10" y="-120" width="200" height="160" rx="16" fill="#ffffff" opacity=".2"/>
          <path d="M0,-40 l100,-70 100,70 v100 a16,16 0 0 1 -16,16 h-168 a16,16 0 0 1 -16,-16z" fill="#fff" opacity=".95"/>
          <rect x="80" y="-18" width="40" height="74" rx="6" fill="#16c79a"/>
          <circle cx="115" cy="18" r="4" fill="#fff"/>
        </g>

        <defs>
          <linearGradient id="gradScreen" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#16c79a"/>
            <stop offset="60%" stop-color="#22c39a"/>
            <stop offset="100%" stop-color="#2dd4bf"/>
          </linearGradient>
        </defs>
      </svg>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', ()=>{
      feather.replace({ 'stroke-width': 1.8 });

      // toggle password
      const btn = document.querySelector('.toggle-pass');
      const input = document.getElementById('password');
      if(btn && input){
        btn.addEventListener('click', ()=>{
          const to = input.type === 'password' ? 'text' : 'password';
          input.type = to;
          btn.innerHTML = feather.icons[to === 'password' ? 'eye' : 'eye-off'].toSvg();
        });
      }
    });
  </script>
</body>
</html>
