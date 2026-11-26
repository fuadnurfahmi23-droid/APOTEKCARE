<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ApotekCare')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar Hijau Tua Prototype */
        .sidebar-apotek {
            width: 250px;
            background-color: #0d5c3e;
            color: #ffffff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .sidebar-apotek::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }
        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .sidebar-link.active {
            background-color: #10b981;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .topbar-apotek {
            background: #ffffff;
            border-bottom: 1px solid #edf2f0;
            padding: 14px 32px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .card-clean {
            background: #ffffff;
            border: 1px solid #edf2f0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        @media (max-width: 768px) {
            .app-wrapper { flex-direction: column; }
            .sidebar-apotek { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Kiri (Menggunakan file components/navbar.blade.php milikmu) -->
        @include('components.navbar')

        <!-- Area Kanan (Topbar + Isi Konten) -->
        <div class="main-content">
            <header class="topbar-apotek">
                <a href="{{ url('/profil') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                    <span class="small fw-semibold text-muted">
                        {{ Auth::user()->name ?? 'Admin Inventory' }}
                    </span>
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="bi bi-person text-secondary"></i>
                    </div>
                </a>
            </header>

            <main class="p-4 grow">
                @yield('isi')
            </main>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout (Sesuai Prototype PDF) -->
    <div class="modal fade" id="modalLogout" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow text-center p-4" style="border-radius: 18px;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: #fef2f2; color: #ef4444; font-size: 1.3rem;">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h6 class="fw-bold mb-1">Konfirmasi Logout</h6>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari sistem ApotekCare?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 border" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ url('/logout') }}" method="POST" class="w-50">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
