@extends('layouts.app')

@section('title', 'Pembayaran Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Pembayaran Siswa</h4>
                                    </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tagihanSiswa as $index => $item)
                                    <tr>
                                        <td>{{ $tagihanSiswa->firstItem() + $index }}</td>
                                        <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ $item->jenis_tagihan }}</td>
                                        <td>
                                            @if($item->pembayaranSiswa->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($item->pembayaranSiswa->first()->tanggal_bayar)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $totalBayar = $item->pembayaranSiswa->sum('jumlah_bayar');
                                                $status = $totalBayar >= $item->nominal ? 'lunas' : 'belum lunas';
                                                $statusClass = $status == 'lunas' ? 'success' : 'warning';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ strtoupper($status) }}
                                            </span>
                                        </td>
                                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tagihanSiswa instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $tagihanSiswa->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
