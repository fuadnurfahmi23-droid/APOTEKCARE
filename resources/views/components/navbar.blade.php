<nav class="navbar navbar-expand-lg navbar-warung position-fixed w-100">
    <div class="container-fluid" style="padding: 0 7rem">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

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
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link border-0 bg-transparent" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
