@extends('layouts.app')

@section('title', 'Laporan Arus Kas Pembukuan TK')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="page-header">
            <h1 class="page-title">Laporan Arus Kas Pembukuan TK</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan</li>
                <li class="breadcrumb-item active">Laporan Arus Kas Pembukuan TK</li>
            </ol>
        </div>

        <!-- Ringkasan Keuangan -->
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Pendapatan</p>
                                <h3 class="text-white" style="font-size: 1.5rem; word-wrap: break-word;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-arrow-down text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Total Beban</p>
                                <h3 class="text-white" style="font-size: 1.5rem; word-wrap: break-word;">Rp {{ number_format($totalBeban, 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-arrow-up text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Laba/Rugi Bersih</p>
                                <h3 class="text-white" style="font-size: 1.5rem; word-wrap: break-word;">Rp {{ number_format($labaRugi, 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-stats-up text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white mb-0">Saldo Kas</p>
                                <h3 class="text-white" style="font-size: 1.5rem; word-wrap: break-word;">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="ti-wallet text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Laba Rugi -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Laporan Arus Kas</h4>
                        <div class="card-tools">
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="ti-printer"></i> Cetak
                            </button>
                            <a href="{{ route('admin.laporan.keuangan') }}/export" class="btn btn-success btn-sm">
                                <i class="ti-download"></i> Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Akun</th>
                                        <th>Jumlah</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>PENDAPATAN</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach($pendapatan as $item)
                                    <tr>
                                        <td>{{ $item['akun'] }}</td>
                                        <td class="text-right">Rp {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                        <td class="text-right">{{ $item['persentase'] }}%</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-success">
                                        <td><strong>Total Pendapatan</strong></td>
                                        <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    
                                    <tr class="table-danger">
                                        <td><strong>BEBAN</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach($beban as $item)
                                    <tr>
                                        <td>{{ $item['akun'] }}</td>
                                        <td class="text-right">Rp {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                        <td class="text-right">{{ $item['persentase'] }}%</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-danger">
                                        <td><strong>Total Beban</strong></td>
                                        <td class="text-right"><strong>Rp {{ number_format($totalBeban, 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    
                                    @if($labaRugi >= 0)
                                    <tr class="table-primary">
                                        <td><strong>LABA BERSIH</strong></td>
                                        <td class="text-right"><strong>Rp {{ number_format($labaRugi, 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                    @else
                                    <tr class="table-danger">
                                        <td><strong>RUGI BERSIH</strong></td>
                                        <td class="text-right"><strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Pendapatan vs Beban -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Perbandingan Pendapatan vs Beban</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="chartKeuangan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartKeuangan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($pendapatan, 'akun')) !!},
        datasets: [
            {
                label: 'Pendapatan',
                data: {!! json_encode(array_column($pendapatan, 'jumlah')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Beban',
                data: {!! json_encode(array_column($beban, 'jumlah')) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
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
