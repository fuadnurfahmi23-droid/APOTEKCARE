<aside class="sidebar-apotek p-3">
    <!-- Logo ApotekCare -->
    <a href="{{ url('/dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none px-2 py-2 mb-4">
        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-success fw-bold" style="width: 32px; height: 32px;">
            <i class="bi bi-plus-lg"></i>
        </span>
        <span class="fw-bold fs-5">ApotekCare</span>
    </a>

    <!-- Daftar Menu -->
    <nav class="grow">
        <a href="{{ url('/dashboard') }}" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>

        <a href="{{ url('/mengelola') }}" class="sidebar-link {{ request()->is('mengelola') ? 'active' : '' }}">
            <i class="bi bi-capsule"></i> Kelola Data Obat
        </a>

        <a href="{{ url('/kasir') }}" class="sidebar-link {{ request()->is('kasir') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i> Transaksi Kasir
        </a>

        <a href="{{ url('/riwayat') }}" class="sidebar-link {{ request()->is('riwayat') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>

        <a href="{{ url('/profil') }}" class="sidebar-link {{ request()->is('profil') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profil Akun
        </a>

        <hr class="border-light opacity-25 my-3">

        <!-- Tombol Trigger Modal Logout -->
        <button type="button" class="sidebar-link w-100 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#modalLogout">
            <i class="bi bi-box-arrow-left"></i> Logout
        </button>
    </nav>
</aside>
        <div class="collapse navbar-collapse" id="menuNavbar">
            <ul class="navbar-nav ms-auto align-items-center gap-5">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">dasboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mengelola">mengelola</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profil">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/riwayat">riwayat</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
