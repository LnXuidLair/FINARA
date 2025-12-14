<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>FINARA - Dashboard Orang Tua</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .parent-layout {
        --primary-color: #6D28D9;
        --secondary-color: #EC4899;
        --text-color: #1F2937;
        --text-light: #6B7280;
        --border-color: #E5E7EB;
        --sidebar-expanded: 260px;
        --sidebar-collapsed: 86px;
        --topbar-height: 80px;

        font-family: 'Poppins', sans-serif;
        background-color: #F3F4F6;
        color: var(--text-color);
        min-height: 100vh;
        overflow-x: hidden;
    }

    .parent-layout *, .parent-layout *::before, .parent-layout *::after { box-sizing: border-box; }

    .parent-layout .parent-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-expanded);
        background: linear-gradient(180deg, #6D28D9 0%, #4C1D95 100%);
        color: #fff;
        z-index: 1100;
        transition: width 200ms ease, transform 200ms ease;
        overflow: hidden;
    }

    .parent-layout .parent-sidebar .parent-brand {
        padding: 18px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.12);
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 64px;
    }

    .parent-layout .parent-sidebar .parent-nav {
        padding: 14px 10px;
    }

    .parent-layout .parent-sidebar .parent-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        margin: 6px 6px;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        transition: background-color 150ms ease;
        white-space: nowrap;
    }

    .parent-layout .parent-sidebar .parent-nav a:hover,
    .parent-layout .parent-sidebar .parent-nav a.active {
        background: rgba(255,255,255,0.14);
    }

    .parent-layout .parent-sidebar .parent-nav i {
        width: 22px;
        text-align: center;
        font-size: 18px;
        flex: 0 0 auto;
    }

    .parent-layout .nav-text { display: inline; }

    .parent-layout.parent-sidebar-collapsed .parent-sidebar { width: var(--sidebar-collapsed); }
    .parent-layout.parent-sidebar-collapsed .nav-text { display: none; }
    .parent-layout.parent-sidebar-collapsed .parent-brand { justify-content: center; }

    .parent-layout .parent-topbar {
        position: fixed;
        top: 0;
        left: var(--sidebar-expanded);
        right: 0;
        height: var(--topbar-height);
        background: #fff;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
        transition: left 200ms ease;
    }

    .parent-layout.parent-sidebar-collapsed .parent-topbar { left: var(--sidebar-collapsed); }

    .parent-layout .parent-sidebar-toggle {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-color);
    }

    .parent-layout .parent-hamburger {
        width: 18px;
        height: 12px;
        position: relative;
        display: inline-block;
    }

    .parent-layout .parent-hamburger span {
        position: absolute;
        left: 0;
        right: 0;
        height: 2px;
        background: #111827;
        border-radius: 999px;
    }

    .parent-layout .parent-hamburger span:nth-child(1) { top: 0; }
    .parent-layout .parent-hamburger span:nth-child(2) { top: 5px; }
    .parent-layout .parent-hamburger span:nth-child(3) { bottom: 0; }

    .parent-layout .parent-content {
        margin-left: var(--sidebar-expanded);
        padding: calc(var(--topbar-height) + 1rem) 1rem 1rem;
        min-width: 0;
        transition: margin-left 200ms ease;
    }

    .parent-layout.parent-sidebar-collapsed .parent-content { margin-left: var(--sidebar-collapsed); }

    .parent-layout .search-bar { position: relative; width: 300px; }
    .parent-layout .search-bar input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 20px; border: 1px solid var(--border-color); outline: none; font-size: 14px; }
    .parent-layout .search-bar i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-light); }

    .parent-layout .user-profile { display: flex; align-items: center; gap: 10px; }
    .parent-layout .user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-color); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }

    .parent-layout .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .parent-layout .card-body { padding: 20px; }

    .parent-layout .card-title { font-size: 0.95rem; color: var(--text-light); margin-bottom: 0.35rem; }
    .parent-layout .card-amount { font-size: 1.85rem; font-weight: 700; color: var(--text-color); margin: 0; }

    .parent-layout .card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; }
    .parent-layout .card-icon i { font-size: 1.5rem; }
    .parent-layout .card-bill { background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); }
    .parent-layout .card-payment { background: linear-gradient(135deg, #EC4899 0%, #BE185D 100%); }

    .parent-layout .section-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 0; color: var(--text-color); }

    .parent-layout .chart-wrapper { position: relative; width: 100%; min-width: 0; }
    .parent-layout canvas { max-width: 100% !important; }

    @media (max-width: 991.98px) {
        .parent-layout .parent-topbar { left: 0; }
        .parent-layout .parent-content { margin-left: 0; padding: calc(var(--topbar-height) + 1rem) 0.75rem 0.75rem; }
        .parent-layout .parent-sidebar { transform: translateX(-100%); }
        .parent-layout.parent-sidebar-mobile-open .parent-sidebar { transform: translateX(0); }
        .parent-layout .search-bar { width: 220px; }
    }

    @media (max-width: 575.98px) {
        .parent-layout { --topbar-height: 110px; }
        .parent-layout .search-bar { display: none; }
    }
</style>
</head>
<body class="parent-layout">

<aside class="parent-sidebar" id="parentSidebar">
    <div class="parent-brand">
        <span>FINARA</span>
    </div>
    <nav class="parent-nav">
        <a href="{{ route('parent.dashboard') }}" class="{{ request()->is('parent/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span class="nav-text">Dashboard</span>
        </a>
        <a href="{{ route('parent.pembayaran.index') }}" class="{{ request()->is('parent/pembayaran*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i>
            <span class="nav-text">Pembayaran</span>
        </a>
        <a href="{{ route('parent.informasi-siswa.index') }}" class="{{ request()->is('parent/informasi-siswa*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i>
            <span class="nav-text">Informasi Siswa</span>
        </a>
    </nav>
</aside>

<header class="parent-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-light parent-sidebar-toggle" id="parentSidebarToggle" aria-label="Toggle sidebar">
            <span class="parent-hamburger" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        <div class="d-none d-sm-block">
            <h2 class="mb-0 h5">HELLO, Mr/Mrs Parents</h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Have a great day</p>
        </div>
    </div>

    <div class="d-flex align-items-center flex-wrap gap-2">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." class="form-control">
        </div>

        <div class="user-profile dropdown">
            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle"
               id="parentUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">P</div>
                <span class="d-none d-sm-inline">Parents</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="parentUserMenu">
                <li>
                    <a class="dropdown-item" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</header>

<main class="parent-content">
    <div class="container-fluid px-2 px-md-3 py-3">
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const body = document.body;
    const toggleBtn = document.getElementById('parentSidebarToggle');
    const sidebar = document.getElementById('parentSidebar');

    if (!toggleBtn || !sidebar) return;

    function isMobile() {
        return window.matchMedia && window.matchMedia('(max-width: 991.98px)').matches;
    }

    toggleBtn.addEventListener('click', function () {
        if (isMobile()) {
            body.classList.toggle('parent-sidebar-mobile-open');
        } else {
            body.classList.toggle('parent-sidebar-collapsed');
        }
    });

    document.addEventListener('click', function (e) {
        if (!isMobile()) return;
        if (!body.classList.contains('parent-sidebar-mobile-open')) return;
        const target = e.target;
        if (target === toggleBtn || toggleBtn.contains(target)) return;
        if (sidebar.contains(target)) return;
        body.classList.remove('parent-sidebar-mobile-open');
    });

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            body.classList.remove('parent-sidebar-mobile-open');
        }
    });
})();
</script>
@stack('scripts')
</body>
</html>
