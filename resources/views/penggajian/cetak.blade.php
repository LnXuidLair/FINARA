<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $penggajian->pegawai->nama }} - {{ \Carbon\Carbon::parse($penggajian->periode)->format('F Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .slip-gaji {
            border: 2px solid #000;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 15px;
        }
        .perusahaan {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .alamat {
            font-size: 14px;
            margin-bottom: 15px;
        }
        .judul {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        .info-pegawai {
            margin-bottom: 30px;
        }
        .info-pegawai p {
            margin: 5px 0;
        }
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-detail th, .table-detail td {
            border: 1px solid #000;
            padding: 8px 15px;
            text-align: left;
        }
        .table-detail th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .ttd {
            margin-top: 50px;
            text-align: right;
        }
        .ttd p {
            margin: 50px 0 0;
        }
        @media print {
            .no-print {
                display: none;
            }
            .slip-gaji {
                border: none;
                padding: 0;
            }
            .pagebreak { 
                page-break-before: always; 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="slip-gaji">
            <!-- Header -->
            <div class="header">
                <div class="perusahaan">FINARA</div>
                <div class="alamat">
                    Jl. Contoh No. 123, Kota Bandung<br>
                    Telp: (022) 1234567 | Email: info@finara.com
                </div>
                <div style="font-size: 16px; font-weight: bold;">SLIP GAJI KARYAWAN</div>
                <div>Periode: {{ \Carbon\Carbon::parse($penggajian->periode)->format('F Y') }}</div>
            </div>

            <!-- Informasi Pegawai -->
            <div class="info-pegawai">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Karyawan</strong>: {{ $penggajian->pegawai->nama }}</p>
                        <p><strong>Jabatan</strong>: {{ $penggajian->pegawai->jabatan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>NIK</strong>: {{ $penggajian->pegawai->nik ?? '-' }}</p>
                        <p><strong>Status</strong>: {{ ucfirst(str_replace('_', ' ', $penggajian->status_penggajian)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Gaji -->
            <table class="table-detail">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gaji per Hari</td>
                        <td class="text-right">@rupiah($penggajian->gaji_perhari)</td>
                    </tr>
                    <tr>
                        <td>Jumlah Kehadiran</td>
                        <td class="text-right">{{ $penggajian->jumlah_kehadiran }} Hari</td>
                    </tr>
                    <tr>
                        <td><strong>Total Gaji</strong></td>
                        <td class="text-right"><strong>@rupiah($penggajian->total_gaji)</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Keterangan -->
            <div style="margin-bottom: 30px;">
                <p><strong>Keterangan:</strong></p>
                <p>1. Slip gaji ini adalah bukti pembayaran gaji yang sah.</p>
                <p>2. Mohon diperiksa kebenaran data yang tercantum.</p>
                <p>3. Jika ada kesalahan, harap segera menghubungi bagian keuangan.</p>
            </div>

            <!-- Tanda Tangan -->
            <div class="row">
                <div class="col-md-6">
                    <div class="ttd">
                        <p>Bandung, {{ date('d F Y') }}</p>
                        <p>Hormat Kami,</p>
                        <br><br><br>
                        <p><strong>Admin Keuangan</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ttd">
                        <p>Menyetujui,</p>
                        <br><br><br>
                        <p><strong>Pimpinan</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">Cetak Slip Gaji</button>
            <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto print when page loads (optional)
        window.onload = function() {
            // Uncomment the line below to automatically open print dialog
            // window.print();
        };
    </script>
</body>
</html>
