@extends('layouts.parent')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="section-title mb-0">Invoice Pembayaran</h4>
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Cetak
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-2">FINARA</h5>
                    <div>No. Invoice: <strong>{{ $pembayaran->order_id ?? ('PMT-' . $pembayaran->id) }}</strong></div>
                    <div>Tanggal: <strong>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}</strong></div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div>Status: 
                        @if($pembayaran->status_pembayaran == 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($pembayaran->status_pembayaran == 'pending')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </div>
                    <div class="mt-1">Metode: <strong>{{ $trx->payment_type ?? '-' }}</strong></div>
                    <div class="mt-1">Midtrans Status: <strong>{{ $trx->transaction_status ?? '-' }}</strong></div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="text-muted">Siswa</div>
                    <div><strong>{{ $pembayaran->siswa->nama_siswa ?? 'N/A' }}</strong></div>
                    <div>Kelas: {{ $pembayaran->siswa->kelas ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted">Detail Pembayaran</div>
                    <div>Jenis: <strong>{{ $pembayaran->jenis_pembayaran }}</strong></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Deskripsi</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $pembayaran->jenis_pembayaran }}</td>
                            <td class="text-end">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-end">Total</th>
                            <th class="text-end">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .top-bar, .chart-container, .btn { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .card { box-shadow: none !important; }
}
</style>
@endsection
