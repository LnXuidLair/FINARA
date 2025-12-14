@extends('layouts.app')

@section('title', 'Jurnal Umum')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="page-header">
            <h1 class="page-title">Jurnal Umum</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan</li>
                <li class="breadcrumb-item active">Jurnal Umum</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Jurnal Umum</h4>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="filterData('all')">Semua</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="filterData('pengeluaran')">Pengeluaran</button>
                                <button class="btn btn-sm btn-outline-success" onclick="filterData('pemasukan')">Pemasukan</button>
                            </div>
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="ti-printer"></i> Cetak
                            </button>
                            <a href="{{ route('admin.laporan.jurnal') }}/export" class="btn btn-success btn-sm">
                                <i class="ti-download"></i> Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Bukti</th>
                                        <th>Tipe</th>
                                        <th>Akun</th>
                                        <th>Keterangan</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jurnal as $item)
                                    <tr data-tipe="{{ $item['tipe'] ?? 'other' }}">
                                        <td>{{ date('d/m/Y', strtotime($item['tanggal'])) }}</td>
                                        <td>{{ $item['no_bukti'] }}</td>
                                        <td>
                                            @if(isset($item['tipe']))
                                                @if($item['tipe'] == 'pengeluaran')
                                                    <span class="badge badge-danger">Pengeluaran</span>
                                                @elseif($item['tipe'] == 'pemasukan')
                                                    <span class="badge badge-success">Pemasukan</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $item['akun'] }}</td>
                                        <td>{{ $item['keterangan'] }}</td>
                                        <td class="text-right">
                                            @if($item['debit'] > 0)
                                                Rp {{ number_format($item['debit'], 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($item['kredit'] > 0)
                                                Rp {{ number_format($item['kredit'], 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Debit</p>
                                <h3 class="text-white" id="total-debit">Rp {{ number_format(array_sum(array_column($jurnal, 'debit')), 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-arrow-up text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Kredit</p>
                                <h3 class="text-white" id="total-kredit">Rp {{ number_format(array_sum(array_column($jurnal, 'kredit')), 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-arrow-down text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Pengeluaran</p>
                                <h3 class="text-white">Rp {{ number_format(array_sum(array_column(array_filter($jurnal, function($item) { return ($item['tipe'] ?? '') == 'pengeluaran'; }), 'debit')), 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-money text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Pemasukan</p>
                                <h3 class="text-white">Rp {{ number_format(array_sum(array_column(array_filter($jurnal, function($item) { return ($item['tipe'] ?? '') == 'pemasukan'; }), 'kredit')), 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-wallet text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterData(tipe) {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const rowTipe = row.getAttribute('data-tipe');
        if (tipe === 'all' || rowTipe === tipe) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<style>
@media print {
    .card-tools, .page-header, .breadcrumb {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .content {
        padding: 0 !important;
    }
}
</style>
@endsection
