@extends('layouts.parent')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">Daftar Pembayaran</h4>
        <a href="{{ route('parent.payment.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Pembayaran
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title">Total Pembayaran</p>
                        <h3 class="card-amount">Rp {{ number_format($pembayaran->sum('jumlah'), 0, ',', '.') }}</h3>
                    </div>
                    <div class="card-icon card-bill">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title">Pembayaran Lunas</p>
                        <h3 class="card-amount">{{ $pembayaran->where('status_pembayaran', 'lunas')->count() }}</h3>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(135deg, #10B981 0%,#059669 100%);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title">Menunggu Pembayaran</p>
                        <h3 class="card-amount">{{ $pembayaran->where('status_pembayaran', 'pending')->count() }}</h3>
                    </div>
                    <div class="card-icon" style="background: linear-gradient(135deg, #F59E0B 0%,#D97706 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->siswa->nama_siswa ?? 'N/A' }}</td>
                            <td>{{ $item->jenis_pembayaran }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status_pembayaran == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($item->status_pembayaran == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status_pembayaran == 'pending')
                                    <button class="btn btn-sm btn-primary btn-bayar" 
                                            data-id="{{ $item->id }}"
                                            data-snap-token="{{ $item->snap_token }}">
                                        Bayar
                                    </button>
                                @else
                                    <a href="{{ route('pembayaran.invoice', $item->id) }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fas fa-print"></i>
                                    </a>
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

<!-- Midtrans Snap JS -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.btn-bayar');

        buttons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const token = btn.getAttribute('data-snap-token');

                if (!token) {
                    alert('Snap token belum tersedia. Silakan buat pembayaran baru.');
                    return;
                }

                window.snap.pay(token, {
                    onSuccess: function(result) {
                        window.location.reload();
                    },
                    onPending: function(result) {
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal');
                    },
                    onClose: function() {
                        // user closed popup
                    }
                });
            });
        });
    });
</script>

@endsection
