@extends('layouts.app')

@section('title', 'Detail Tagihan Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Detail Tagihan Siswa</h4>
                    <div>
                        <a href="{{ route('admin.tagihan-siswa.edit', $tagihan->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.tagihan-siswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Siswa</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="150px"><strong>Nama Siswa</strong></td>
                                    <td>{{ $tagihan->siswa->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIS</strong></td>
                                    <td>{{ $tagihan->siswa->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $tagihan->siswa->kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Orang Tua</strong></td>
                                    <td>{{ $tagihan->siswa->orangtua->nama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Tagihan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="150px"><strong>Jenis Tagihan</strong></td>
                                    <td>{{ $tagihan->jenis_tagihan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah</strong></td>
                                    <td>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Jatuh Tempo</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Periode</strong></td>
                                    <td>{{ $tagihan->periode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        @php
                                            $status = $tagihan->status;
                                            $statusClass = $status == 'lunas' ? 'success' : 'warning';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($tagihan->keterangan)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Keterangan</h6>
                                <p>{{ $tagihan->keterangan }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Riwayat Pembayaran -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Riwayat Pembayaran</h6>
                            @if($tagihan->pembayaranSiswa->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Bayar</th>
                                                <th>Jumlah</th>
                                                <th>Metode</th>
                                                <th>Keterangan</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tagihan->pembayaranSiswa as $pembayaran)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}</td>
                                                    <td>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                                                    <td>{{ $pembayaran->metode_pembayaran }}</td>
                                                    <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                                                    <td>
                                                        @if($pembayaran->bukti_pembayaran)
                                                            <a href="{{ asset('uploads/bukti_pembayaran/' . $pembayaran->bukti_pembayaran) }}" 
                                                               target="_blank" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> Lihat
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <th colspan="1">Total Pembayaran</th>
                                                <th>Rp {{ number_format($tagihan->total_pembayaran, 0, ',', '.') }}</th>
                                                <th colspan.
                                                <Pembayaran</.
                                                <;td>
 . 
                                               .
                                                <th colspan="2">
                                                    @if($tagihan->sisa > 0)
                                                        <span class="text-danger">Sisa: Rp {{ number_format($tagihan->sisa, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-success">LUNAS</span>
                                                    @endif
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada pembayaran untuk tagihan ini.
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('admin.pembayaran-siswa.create') }}?tagihan_id={{ $tagihan->id }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
