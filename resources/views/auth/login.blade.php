<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KosManager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #2563eb;
            color: white;
            border-radius: 18px;
            font-size: 30px;
            margin-bottom: 16px;
        }

        .brand h1 {
            font-size: 27px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .brand p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }

        .login-card {
            background: #ffffff;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .login-title {
            font-size: 21px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 26px;
        }

        .form-label {
            color: #334155;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            min-height: 46px;
            border-color: #cbd5e1;
            border-radius: 9px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .form-check-label {
            font-size: 13px;
            color: #475569;
        }

        .btn-login {
            min-height: 46px;
            border-radius: 9px;
            background: #2563eb;
            border-color: #2563eb;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .login-footer {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="brand">
        <div class="brand-icon">
            <i class="bi bi-buildings"></i>
        </div>

        <h1>KosManager</h1>
        <p>Sistem Manajemen Rumah Kos</p>
    </div>

    <div class="login-card">

        <h2 class="login-title">Selamat datang</h2>
        <p class="login-subtitle">
            Masuk untuk mengelola data rumah kos Anda.
        </p>

        @if (session('status'))
            <div class="alert alert-success py-2 small" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
                Email atau password tidak valid. Silakan periksa kembali.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama@email.com"
                    autocomplete="username"
                    required
                    autofocus
                >

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="form-check-input"
                    >
                    <label for="remember" class="form-check-label">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Masuk ke Dashboard
            </button>
        </form>

    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} KosManager. Semua hak dilindungi.
    </div>

</div>

</body>
</html>