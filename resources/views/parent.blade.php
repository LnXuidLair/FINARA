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
    :root {
        --primary-color: #6D28D9;
        --secondary-color: #EC4899;
        --text-color: #1F2937;
        --text-light: #6B7280;
        --border-color: #E5E7EB;
        --sidebar-width: 250px;
        --topbar-height: 80px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { overflow-x: hidden; }
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #F3F4F6;
        color: var(--text-color);
    }
    /* Sidebar */
    .sidebar {
        background: linear-gradient(180deg, #6D28D9 0%, #4C1D95 100%);
        color: white;
        min-height: 100vh;
        width: var(--sidebar-width);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1100;
    }
    .logo { padding:20px; text-align:center; font-size:24px; font-weight:700; border-bottom:1px solid rgba(255,255,255,0.1);}
    .nav-menu{padding:20px 0;}
    .nav-item{padding:12px 20px; margin:5px 10px; border-radius:8px; transition:all 0.3s; cursor:pointer;}
    .nav-item:hover, .nav-item.active{background-color: rgba(255,255,255,0.1);}
    .nav-item i{margin-right:10px; width:20px; text-align:center;}

    /* Top Bar */
    .top-bar{
        position: fixed;
        top:0;
        left: 0;
        right:0;
        height: var(--topbar-height);
        background:white;
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding: 0 1rem 0 calc(var(--sidebar-width) + 1rem);
        z-index:1000;
        box-shadow:0 2px 4px rgba(0,0,0,0.05);
    }
    .search-bar{position:relative; width:300px;}
    .search-bar input{width:100%; padding:10px 15px 10px 40px; border-radius:20px; border:1px solid var(--border-color); outline:none; font-size:14px;}
    .search-bar i{position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--text-light);}
    .user-profile{display:flex; align-items:center; gap:10px;}
    .user-avatar{width:40px; height:40px; border-radius:50%; background-color:var(--primary-color); display:flex; align-items:center; justify-content:center; color:white; font-weight:600;}

    /* Main Content */
    .main-content{
        margin-left: 0;
        min-width: 0;
        padding: calc(var(--topbar-height) + 1rem) 1rem 1rem calc(var(--sidebar-width) + 1rem);
        background-color:#F3F4F6;
        min-height:100vh;
    }

    .content-wrapper {
        width: 100%;
        overflow-x: hidden;
    }

    /* Cards */
    .card{background:white; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.05); padding:20px; margin-bottom:20px; border:none;}
    .card-title{font-size:14px; color:var(--text-light); margin-bottom:10px;}
    .card-amount{font-size:24px; font-weight:700; color:var(--text-color); margin:0;}
    .card-icon{width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:15px; color:white;}
    .card-icon i{font-size:1.5rem;}
    .card-bill{background:linear-gradient(135deg,#8B5CF6 0%,#6D28D9 100%);}
    .card-payment{background:linear-gradient(135deg,#EC4899 0%,#BE185D 100%);}

    .section-title{font-size:18px; font-weight:600; margin-bottom:20px; color:var(--text-color);}

    @media(max-width:992px){
        .sidebar{margin-left:-250px;}
        .main-content{margin-left:0; padding: calc(var(--topbar-height) + 1rem) 1rem 1rem;}
        .sidebar.active{margin-left:0;}
        .top-bar{left:0; padding: 0 1rem;}
        .search-bar{width:200px;}
    }

    @media(max-width:576px){
        :root{ --topbar-height: 110px; }
        .top-bar h2{ font-size: 16px; }
        .top-bar p{ font-size: 12px; }
        .search-bar{ display:none; }
        .main-content{ padding: calc(var(--topbar-height) + 0.75rem) 0.75rem 0.75rem; }
    }

    .chart-wrapper{
        position: relative;
        width: 100%;
        overflow: visible;
    }
    .chart-wrapper canvas{
        display: block;
        width: 100% !important;
        max-width: 100% !important;
    }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">FINARA</div>
    <div class="nav-menu">
        <a href="{{ route('parent.dashboard') }}" class="text-decoration-none text-white">
            <div class="nav-item {{ request()->is('parent/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </div>
        </a>
        <a href="{{ route('parent.informasi-siswa') }}" class="text-decoration-none text-white">
            <div class="nav-item {{ request()->is('parent/informasi-siswa*') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i> Informasi Siswa
            </div>
        </a>
        <a href="{{ route('parent.payment.index') }}" class="text-decoration-none text-white">
            <div class="nav-item {{ request()->is('pembayaran*') || request()->is('parent/pembayaran*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i> Pembayaran
            </div>
        </a>
    </div>
</div>

<!-- Top Bar -->
<div class="top-bar">
    <div>
        <h2 class="mb-0">HELLO, Mr/Mrs Parents</h2>
        <p class="text-muted mb-0">Have a great day</p>
    </div>
    <div class="d-flex align-items-center flex-wrap">
        <div class="search-bar me-3">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." class="form-control">
        </div>
        <div class="user-profile dropdown">
            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle"
               id="parentUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">P</div>
                <span>Parents</span>
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
</div>

<div class="content-wrapper">
    <div class="main-content">
        <div class="container-fluid px-2 px-md-3 py-3">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
