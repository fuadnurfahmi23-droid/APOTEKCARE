<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - ApotekCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm border-0 p-4 w-100" style="max-width: 440px">
            <h1 class="h4 text-center mb-4">Daftar Akun ApotekCare</h1>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form action="{{ url('/register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" maxlength="100" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" minlength="4" maxlength="30" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Daftar</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Sudah punya akun? <a href="{{ url('/login') }}">Login</a>
            </p>
        </div>
    </main>
</body>
</html>
