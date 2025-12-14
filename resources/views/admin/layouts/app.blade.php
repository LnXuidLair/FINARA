<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('admin.presensi.index') }}">Presensi</a>
                <a class="nav-link" href="{{ route('admin.penggajian.index') }}">Penggajian</a>
                <a class="nav-link" href="{{ route('admin.pengeluaran.index') }}">Pengeluaran</a>
            </div>
        </nav>
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>