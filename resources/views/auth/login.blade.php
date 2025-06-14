<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kost Lolita</title>
    <!-- Add Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary: #1a7f5a;
            --primary-dark: #156c4a;
            --secondary: #64748b;
            --danger: #dc2626;
            --success: #059669;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background-color: #ffffff;
            padding: 2rem;  /* Reduced from 2.5rem */
            border-radius: 12px; /* Reduced from 16px */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 360px; /* Reduced from 400px */
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem; /* Reduced from 2rem */
        }

        .login-header img {
            width: 56px; /* Reduced from 64px */
            height: 56px; /* Reduced from 64px */
            margin-bottom: 0.75rem; /* Reduced from 1rem */
        }

        .login-header h1 {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.25rem; /* Reduced from 1.5rem */
        }

        .form-group label {
            display: block;
            color: var(--secondary);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
            width: 1.25rem;
            height: 1.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #1a7f5a; /* Changed to specific color */
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 127, 90, 0.15);
        }

        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .error-message ul {
            list-style: none;
            color: var(--danger);
            font-size: 0.875rem;
        }

        .back-to-home {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--secondary);
            font-size: 0.875rem;
            margin-top: 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-to-home:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('img/Logo.png') }}" alt="Kost Lolita Logo">
            <h1>Selamat Datang!</h1>
            <p>Silakan masuk untuk mengakses akun Anda</p>
        </div>

        <form action="{{ url('admin/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <i data-feather="mail"></i>
                    <input type="email"
                           class="form-control"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           placeholder="masukkan email Anda"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i data-feather="lock"></i>
                    <input type="password"
                           class="form-control"
                           name="password"
                           id="password"
                           placeholder="masukkan kata sandi Anda"
                           required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Sign In
            </button>

            @if($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

        <a href="{{ url('/') }}" class="back-to-home">
            <i data-feather="arrow-left"></i>
            Back to Home
        </a>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
