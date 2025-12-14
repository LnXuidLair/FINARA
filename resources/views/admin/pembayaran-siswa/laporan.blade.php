@extends('layouts.app')

@section('title', 'Laporan Pembayaran Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Laporan Pembayaran Siswa</h4>
                    <a href="{{ route('admin.pembayaran-siswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.pembayaran-siswa.laporan') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="siswa_id" class="form-label">Siswa</label>
                                    <select name="siswa_id" id="siswa_id" class="form-select">
                                        <option value="">Semua Siswa</option>
                                        @foreach($siswaList as $id => $nama)
                                            <option value="{{ $id }}" {{ $siswaId == $id ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="">Semua Bulan</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-select">
                                        <option value="">Semua Tahun</option>
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pembayaran</h5>
                                    <h3>Rp {{ number_format($pembayaranSiswa->sum('jumlah_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Jumlah Transaksi</h5>
                                    <h3>{{ $pembayaranSiswa->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Rata-rata</h5>
                                    <h3>Rp {{ number_format($pembayaranSiswa->avg('jumlah_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Siswa Terbayar</h5>
                                    <h3>{{ $pembayaranSiswa->pluck('tagihanSiswa.siswa.id')->unique()->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Orang Tua</th>
                                    <th>Jenis Tagihan</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaranSiswa as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</td>
                                        <td>{{ $item->tagihanSiswa->siswa->nama ?? '-' }}</td>
                                        <td>{{ $item->tagihanSiswa->siswa->orangtua->nama ?? '-' }}</td>
                                        <td>{{ $item->tagihanSiswa->jenis_tagihan ?? '-' }}</td>
                                        <td>Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $item->metode_pembayaran }}</td>
                                        <td>
                                            @php
                                                $tagihan = $item->tagihanSiswa;
                                                $status = $tagihan ? $tagihan->status : 'unknown';
                                                $statusClass = $status == 'lunas' ? 'success' : 'warning';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ strtoupper($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pembayaran yang sesuai filter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Export Options -->
                    <div class="mt-3">
                        <button class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                        <a href="{{ route('admin.pembayaran-siswa.laporan') }}?{{ http_build_query(request()->query()) }}&export=excel" 
                           class="btn btn-excel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
