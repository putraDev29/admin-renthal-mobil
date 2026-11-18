<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - AMII TRANS</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-96.png') }}">

    {{-- PWA META TAGS --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e293b">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AmiiTrans">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1100px;
        }

        .login-card {
            border: none;
            border-radius: 28px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .left-side {
            background: linear-gradient(135deg, #020617, #0f172a, #1e3a8a);
            color: white;
            padding: 60px;
            position: relative;
        }

        .left-side h1 {
            font-size: 42px;
            font-weight: 700;
        }

        .left-side p {
            opacity: .9;
            margin-top: 15px;
            line-height: 1.8;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .circle-1 {
            width: 220px;
            height: 220px;
            top: -80px;
            right: -80px;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            bottom: -40px;
            left: -40px;
        }

        .right-side {
            background: white;
            padding: 60px 50px;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
        }

        .login-subtitle {
            color: #64748b;
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 500;
            color: #334155;
        }

        .input-group-text {
            background: #f1f5f9;
            border-right: 0;
        }

        .form-control {
            height: 52px;
            border-left: 0;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #cbd5e1;
        }

        .btn-login {
            height: 52px;
            border-radius: 14px;
            font-weight: 600;
            background: linear-gradient(135deg, #020617, #0f172a, #1e3a8a);
            border: none;
            transition: .3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }

        .alert {
            border-radius: 12px;
        }

        @media(max-width: 991px) {
            .left-side {
                display: none;
            }

            .right-side {
                padding: 40px 30px;
            }
        }

        @media(max-width: 576px) {
            .right-side {
                padding: 35px 20px;
            }

            .login-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="card login-card">
            <div class="row g-0">

                {{-- LEFT SIDE --}}
                <div class="col-lg-6">
                    <div class="left-side h-100 d-flex flex-column justify-content-center align-items-center text-center">

                        <img src="{{ asset('assets/images/logo.png') }}"
                            alt="AMII TRANS"
                            class="img-fluid mb-4"
                            style="max-width: 220px;">

                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-lg-6">
                    <div class="right-side">

                        <div class="text-center mb-4">
                            <h2 class="login-title">Selamat Datang</h2>

                            <p class="login-subtitle">
                                Silahkan login untuk melanjutkan
                            </p>
                        </div>

                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('login.process') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Email</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>

                                    <input type="email" name="email" class="form-control"
                                        placeholder="Masukkan email" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input type="password" name="password" class="form-control"
                                        placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                    .then(function (reg) {
                        console.log('PWA ServiceWorker registered with scope:', reg.scope);
                    })
                    .catch(function (err) {
                        console.warn('PWA ServiceWorker registration failed:', err);
                    });
            });
        }
    </script>
</body>

</html>