<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pegawai</title>
</head>
<body>

<h2>Dashboard Pegawai</h2>

<p>Selamat datang, {{ auth()->user()->name }}</p>

<hr>

<ul>
    <li>
        <a href="{{ route('pegawai.transaksi') }}">
            Input Transaksi
        </a>
    </li>
    <li>
        <a href="{{ route('pegawai.laporan') }}">
            Laporan Keuangan
        </a>
    </li>
</ul>

</body>
</html>
