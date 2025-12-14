@extends('parent')

@section('content')
<div class="parent-dashboard">
    <div class="container-fluid py-3 px-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <h3 class="section-title mb-0 fs-3 fw-semibold text-reset">Dashboard Overview</h3>
        </div>

        <div class="row g-4 align-items-stretch mb-4">
            <!-- Total Tagihan Card -->
            <div class="col-12 col-md-6 col-lg-3 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-body d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <p class="card-title text-muted mb-1 fs-6">Total Tagihan</p>
                            <h2 class="card-amount mb-0 fs-2 fw-semibold text-reset">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h2>
                        </div>
                        <div class="card-icon card-bill flex-shrink-0 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-invoice fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran Card -->
            <div class="col-12 col-md-6 col-lg-3 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-body d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <p class="card-title text-muted mb-1 fs-6">Total Pembayaran</p>
                            <h2 class="card-amount mb-0 fs-2 fw-semibold text-reset">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h2>
                        </div>
                        <div class="card-icon card-payment flex-shrink-0 d-flex align-items-center justify-content-center">
                            <i class="fas fa-wallet fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisa Tagihan Card -->
            <div class="col-12 col-md-6 col-lg-3 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-body d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <p class="card-title text-muted mb-1 fs-6">Sisa Tagihan</p>
                            @php
                                $sisaTagihan = $totalTagihan - $totalDibayar;
                                $sisaTagihan = max(0, $sisaTagihan);
                            @endphp
                            <h2 class="card-amount mb-0 fs-2 fw-semibold text-reset">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h2>
                        </div>
                        <div class="card-icon flex-shrink-0 d-flex align-items-center justify-content-center" style="background: #10B981;">
                            <i class="fas fa-receipt fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jumlah Anak Card -->
            <div class="col-12 col-md-6 col-lg-3 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-body d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <p class="card-title text-muted mb-1 fs-6">Jumlah Anak</p>
                            <h2 class="card-amount mb-0 fs-2 fw-semibold text-reset">{{ $siswaList->count() }}</h2>
                        </div>
                        <div class="card-icon flex-shrink-0 d-flex align-items-center justify-content-center" style="background: #3B82F6;">
                            <i class="fas fa-user-graduate fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Pembayaran Chart -->
        <div class="card mb-4 w-100 overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Rekap Pembayaran</h3>
                </div>
                <div class="position-relative w-100" style="height: clamp(260px, 40vw, 380px);">
                    <canvas id="paymentChart" class="w-100 h-100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Pembayaran',
                    data: chartData.data,
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)'
                    ],
                    borderColor: [
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
