@extends('parent')

@section('content')
<div class="parent-dashboard">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <h3 class="section-title mb-0 fs-3 fw-semibold text-reset">Dashboard Overview</h3>
    </div>

    <div class="row g-3 align-items-stretch mb-4">
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
    <div class="card mb-4 w-100">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Rekap Pembayaran</h3>
            </div>
            <div class="chart-wrapper" style="height: clamp(260px, 40vw, 380px);">
                <canvas id="paymentChart" class="w-100 h-100"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-4 w-100">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Tagihan Siswa</h3>
                <div class="d-flex align-items-center gap-2">
                    <div class="text-muted">Total belum dibayar:</div>
                    <div class="fw-semibold">Rp {{ number_format($totalUnpaid ?? 0, 0, ',', '.') }}</div>
                    <button type="button" class="btn btn-primary" id="btnBayarTagihan" {{ ($totalUnpaid ?? 0) > 0 ? '' : 'disabled' }}>
                        Bayar Sekarang
                    </button>
                </div>
            </div>

            @if(($siswaList->count() ?? 0) === 0)
                <div class="text-muted">Belum ada data siswa yang terhubung.</div>
            @else
                @foreach($siswaList as $s)
                    @php
                        $list = $tagihanBySiswa[$s->id] ?? collect();
                    @endphp
                    <div class="mb-4">
                        <div class="fw-semibold mb-2">{{ $s->nama_siswa }} ({{ $s->kelas }})</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Jenis Tagihan</th>
                                        <th>Periode</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $t)
                                        <tr>
                                            <td>{{ $t->jenis_tagihan }}</td>
                                            <td>{{ $t->periode ?? '-' }}</td>
                                            <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                @if($t->status === 'lunas')
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                                    @if($t->sisa > 0)
                                                        <small class="text-muted d-block">Sisa: Rp {{ number_format($t->sisa, 0, ',', '.') }}</small>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada tagihan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btnBayarTagihan');
        if (!btn) return;

        btn.addEventListener('click', function () {
            btn.disabled = true;

            fetch('{{ route('create-tagihan-payment') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(async (r) => {
                const data = await r.json().catch(() => ({}));
                if (!r.ok) {
                    throw new Error(data.error || 'Gagal membuat pembayaran tagihan.');
                }
                return data;
            })
            .then((data) => {
                if (!data.token) throw new Error('Token Midtrans tidak tersedia.');
                window.snap.pay(data.token, {
                    onSuccess: function () { window.location.reload(); },
                    onPending: function () { window.location.reload(); },
                    onError: function () { alert('Pembayaran gagal'); btn.disabled = false; },
                    onClose: function () { btn.disabled = false; }
                });
            })
            .catch((e) => {
                alert(e.message || 'Terjadi kesalahan');
                btn.disabled = false;
            });
        });
    });
</script>
@endpush
