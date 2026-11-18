<aside class="sidebar-apotek p-3">
    <!-- Logo ApotekCare -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2 py-2">
        <a href="{{ url('/dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-success fw-bold" style="width: 32px; height: 32px;">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="fw-bold fs-5">ApotekCare</span>
        </a>
        <!-- Ikon X untuk mobile (opsional) -->
        <i class="bi bi-x-lg text-white d-md-none" style="cursor: pointer;"></i>
    </div>

    <!-- Daftar Menu -->
    <nav class="grow">
        <!-- Dashboard -->
        <a href="{{ url('/dashboard') }}" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Dashboard
        </a>

        <!-- Tampilkan Semua Obat -->
        <a href="{{ url('/semua-obat') }}" class="sidebar-link {{ request()->is('semua-obat') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i> Tampilkan Semua Obat
        </a>

        <!-- Daftarkan Obat -->
        <a href="{{ url('/daftar-obat') }}" class="sidebar-link {{ request()->is('daftar-obat') ? 'active' : '' }}">
            <i class="bi bi-plus-square"></i> Daftarkan Obat
        </a>

        <!-- Tambah Stok -->
        <a href="{{ url('/tambah-stok') }}" class="sidebar-link {{ request()->is('tambah-stok') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-dotted"></i> Tambah Stok
        </a>

        <!-- Hapus Obat -->
        <a href="{{ url('/hapus-obat') }}" class="sidebar-link {{ request()->is('hapus-obat') ? 'active' : '' }}">
            <i class="bi bi-trash"></i> Hapus Obat
        </a>

        <!-- Prioritas Obat -->
        <a href="{{ url('/prioritas-obat') }}" class="sidebar-link {{ request()->is('prioritas-obat') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Prioritas Obat
        </a>

        <hr class="border-light opacity-25 my-3">

        <!-- Logout -->
        <button type="button" class="sidebar-link w-100 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#modalLogout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </nav>
</aside>