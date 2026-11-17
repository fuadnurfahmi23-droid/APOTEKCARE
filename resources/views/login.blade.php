<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Apotek Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
        }
        .card-login {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
        }
        .logo {
            width: 70px;
            height: 70px;
            background: #2e7d32;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 12px;
        }
        .btn-apotek {
            background: #2e7d32;
            color: #fff;
        }
        .btn-apotek:hover {
            background: #1b5e1f;
            color: #fff;
        }
        .form-control:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 .2rem rgba(46, 125, 50, .2);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card card-login shadow p-4">

            <div class="text-center mb-4">
                <div class="logo"><i class="bi bi-capsule"></i></div>
                <h4 class="fw-bold mb-0">Apotek Sehat</h4>
                <small class="text-muted">Hidup sehat dengan obat yang tepat</small>
            </div>

            @if (session('error'))
                <div class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-3">

                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>

                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-apotek w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Belum punya akun? <a href="{{ url('/register') }}" class="text-success fw-semibold">Daftar</a>
            </p>
        </div>
    </div>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> ccb060305702ba952d63501fcb40e1e2334f8ece
