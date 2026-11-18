<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ApotekCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3faf7;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        /* Dekorasi lingkaran lembut di sudut seperti prototype PDF */
        .bg-circle-1 {
            position: fixed; top: -80px; left: -80px;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(16, 185, 129, 0.12); z-index: 0;
        }
        .bg-circle-2 {
            position: fixed; bottom: -100px; right: -80px;
            width: 360px; height: 360px; border-radius: 50%;
            background: rgba(16, 185, 129, 0.12); z-index: 0;
        }
        .role-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.12) !important;
            border-color: #10b981;
        }
        .btn-emerald {
            background-color: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-emerald:hover {
            background-color: #059669;
            color: #fff;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 .2rem rgba(16, 185, 129, .2);
        }
    </style>
</head>
<body>
    <div class="bg-circle-1"></div>
    <div class="bg-circle-2"></div>

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 position-relative" style="z-index: 1;">

        <!-- LANGKAH 1: PILIH ROLE (Sesuai Halaman 1 PDF) -->
        <div id="stepPilihRole" class="w-100 text-center {{ ($errors->any() || session('error')) ? 'd-none' : '' }}" style="max-width: 720px;">
            <div class="d-inline-flex align-items-center gap-2 mb-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold" style="width: 36px; height: 36px; background: #10b981;">
                    <i class="bi bi-plus-lg"></i>
                </span>
                <h3 class="fw-bold mb-0 text-dark">ApotekCare</h3>
            </div>
            <p class="text-muted mb-4">Sistem kasir dan inventory apotek yang modern, rapi, dan mudah digunakan.</p>

            @if (session('success'))
                <div class="alert alert-success py-2 mx-auto mb-4" style="max-width: 420px;">{{ session('success') }}</div>
            @endif

            <div class="row g-4 justify-content-center">
                <!-- Kartu Login Kasir -->
                <div class="col-md-6">
                    <div class="role-card p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3" style="width: 52px; height: 52px; background: #eff6ff; color: #3b82f6; font-size: 1.4rem;">
                                <i class="bi bi-cart3"></i>
                            </div>
                            <h5 class="fw-bold">Login sebagai Kasir</h5>
                            <p class="text-muted small">Kelola transaksi penjualan, struk, dan riwayat transaksi.</p>
                        </div>
                        <button type="button" onclick="bukaFormLogin('kasir')" class="btn btn-emerald w-100 py-2 mt-3">
                            <i class="bi bi-cart-check me-1"></i> Masuk Kasir
                        </button>
                    </div>
                </div>

                <!-- Kartu Login Inventory -->
                <div class="col-md-6">
                    <div class="role-card p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3" style="width: 52px; height: 52px; background: #ecfdf5; color: #10b981; font-size: 1.4rem;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h5 class="fw-bold">Login sebagai Inventory</h5>
                            <p class="text-muted small">Kelola data obat, stok, expired, dan prioritas obat.</p>
                        </div>
                        <button type="button" onclick="bukaFormLogin('inventory')" class="btn btn-emerald w-100 py-2 mt-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Inventory
                        </button>
                    </div>
                </div>
            </div>

            <p class="text-center mt-4 mb-0 small text-muted">
                Belum punya akun? <a href="{{ url('/register') }}" class="text-success fw-semibold text-decoration-none">Daftar Sekarang</a>
            </p>
        </div>

        <!-- LANGKAH 2: FORM LOGIN (Tetap terhubung ke POST /login milikmu) -->
        <div id="stepFormLogin" class="card border-0 shadow-sm p-4 w-100 {{ ($errors->any() || session('error')) ? '' : 'd-none' }}" style="max-width: 420px; border-radius: 18px;">
            <div class="text-center mb-4">
                <div id="loginIconBox" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3" style="width: 54px; height: 54px; background: #ecfdf5; color: #10b981; font-size: 1.5rem;">
                    <i id="loginIcon" class="bi bi-box-seam"></i>
                </div>
                <h4 id="loginTitle" class="fw-bold mb-1">Login ApotekCare</h4>
                <small id="loginSubtitle" class="text-muted">Masuk menggunakan akun terdaftar Anda</small>
            </div>

            @if (session('error'))
                <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <!-- Input hidden opsional jika nanti controller kamu ingin membaca role yang dipilih -->
                <input type="hidden" name="selected_role" id="selectedRoleInput" value="{{ old('selected_role', 'inventory') }}">

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold">Email</label>
                    <input type="email" class="form-control py-2" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@apotekcare.com" required autofocus>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold">Password</label>
                    <input type="password" class="form-control py-2" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-emerald w-100 py-2 mb-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </button>
                <button type="button" onclick="kembaliPilihRole()" class="btn btn-outline-secondary w-100 py-2" style="border-radius: 10px;">
                    Kembali ke Beranda
                </button>
            </form>
        </div>

    </div>

    <script>
        function bukaFormLogin(role) {
            document.getElementById('stepPilihRole').classList.add('d-none');
            document.getElementById('stepFormLogin').classList.remove('d-none');
            document.getElementById('selectedRoleInput').value = role;

            const title = document.getElementById('loginTitle');
            const subtitle = document.getElementById('loginSubtitle');
            const iconBox = document.getElementById('loginIconBox');
            const icon = document.getElementById('loginIcon');

            if (role === 'kasir') {
                title.textContent = 'Login Kasir';
                subtitle.textContent = 'Masuk sebagai petugas kasir apotek';
                iconBox.style.background = '#eff6ff';
                iconBox.style.color = '#3b82f6';
                icon.className = 'bi bi-cart3';
            } else {
                title.textContent = 'Login Inventory';
                subtitle.textContent = 'Masuk sebagai petugas inventory apotek';
                iconBox.style.background = '#ecfdf5';
                iconBox.style.color = '#10b981';
                icon.className = 'bi bi-box-seam';
            }
        }

        function kembaliPilihRole() {
            document.getElementById('stepFormLogin').classList.add('d-none');
            document.getElementById('stepPilihRole').classList.remove('d-none');
        }
    </script>
</body>
</html>