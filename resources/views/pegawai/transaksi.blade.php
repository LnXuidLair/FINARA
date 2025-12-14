<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Pegawai</title>
</head>
<body>

<h2>Input Transaksi</h2>

<form method="POST" action="#">
    @csrf

    <label>Tanggal</label><br>
    <input type="date" name="tanggal"><br><br>

    <label>Jenis Transaksi</label><br>
    <select name="jenis">
        <option value="penjualan">Penjualan</option>
        <option value="pembelian">Pembelian</option>
    </select><br><br>

    <label>Total</label><br>
    <input type="number" name="total"><br><br>

    <button type="submit">Simpan</button>
</form>

<br>
<a href="{{ route('pegawai.dashboard') }}">Kembali ke Dashboard</a>

</body>
</html>
