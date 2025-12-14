<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures parent-sidebar">
    <div class="nano">
        <div class="nano-content">

            <!-- Logo -->
            <div class="logo text-center py-3">
                <a href="{{ url('/dashboard-orangtua') }}">
                    <img src="{{ asset('assets/images/logo.png.jpg') }}" alt="logo" class="sidebar-logo" style="display:block; margin:0 auto; mix-blend-mode: multiply;">
                </a>
            </div>

            <!-- Menu -->
            <ul class="sidebar-menu">
                <li class="menu-label">Dashboard</li>
                <li class="menu-item">
                    <a href="{{ url('/dashboard-orangtua') }}">
                        <i class="ti-home"></i>
                        <span>Beranda</span>
                    </a>
                </li>

                <li class="menu-label">Data Anak</li>
                <li class="menu-item">
                    <a href="{{ url('/orangtua/anak') }}">
                        <i class="ti-user"></i>
                        <span>Data Anak</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('/orangtua/tagihan') }}">
                        <i class="ti-money"></i>
                        <span>Tagihan Siswa</span>
                    </a>
                </li>

                <li class="menu-label">Akun</li>
                <li class="menu-item logout">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti-close"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>
    </div>
</div>

/* ===== SIDEBAR ORANG TUA ===== */
.parent-sidebar {
    width: 260px;
    background: linear-gradient(180deg, #6D28D9, #7C3AED);
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 1000;
}

/* Logo */
.sidebar-logo {
    max-width: 150px;
}

/* Menu */
.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-label {
    padding: 12px 24px;
    font-size: 12px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
    letter-spacing: 0.05em;
}

.menu-item a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 24px;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    margin: 4px 12px;
    transition: all 0.2s ease;
}

.menu-item a i {
    font-size: 16px;
    min-width: 20px;
}

/* Hover */
.menu-item a:hover {
    background: rgba(255,255,255,0.15);
}

/* Active (optional kalau mau pakai) */
.menu-item.active a {
    background: rgba(255,255,255,0.25);
    font-weight: 600;
}

/* Logout beda warna dikit */
.menu-item.logout a {
    color: #FEE2E2;
}

.menu-item.logout a:hover {
    background: rgba(239,68,68,0.25);
}

/* Mobile */
@media (max-width: 991.98px) {
    .parent-sidebar {
        left: -260px;
    }
}
