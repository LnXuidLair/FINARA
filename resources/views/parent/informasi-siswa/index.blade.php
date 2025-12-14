@extends('parent')

@section('content')
<div class="parent-dashboard">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0 fs-3 fw-semibold text-reset">Informasi Siswa</h3>
    </div>

    @if($siswaList->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada data siswa yang terhubung dengan akun Anda.
        </div>
    @else
        @foreach($siswaList as $siswa)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $siswa->nama_siswa }} ({{ $siswa->kelas }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted">Data Siswa</h6>
                            <p><strong>Nama:</strong> {{ $siswa->nama_siswa }}</p>
                            <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                            <p><strong>Kelas:</strong> {{ $siswa->kelas }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Data Orang Tua</h6>
                            <p><strong>Nama:</strong> {{ $orangtua->nama_ortu }}</p>
                            <p><strong>Email:</strong> {{ $orangtua->email }}</p>
                            <p><strong>Telepon:</strong> {{ $orangtua->no_telp ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Ringkasan Tagihan</h6>
                            <p><strong>Total Tagihan:</strong> Rp {{ number_format($tagihanSummary[$siswa->id]['total_tagihan'], 0, ',', '.') }}</p>
                            <p><strong>Total Dibayar:</strong> Rp {{ number_format($tagihanSummary[$siswa->id]['total_dibayar'], 0, ',', '.') }}</p>
                            <p><strong>Sisa:</strong> Rp {{ number_format($tagihanSummary[$siswa->id]['sisa'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">History Pembayaran</h5>
            </div>
            <div class="card-body">
                @if($paymentHistory->isEmpty())
                    <div class="text-muted">Belum ada riwayat pembayaran.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Siswa</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentHistory as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payment->siswa->nama_siswa }}</td>
                                        <td>{{ $payment->jenis_pembayaran }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
