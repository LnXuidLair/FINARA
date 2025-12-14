@extends('layouts.app')

@section('title', 'Detail Pembayaran Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Detail Pembayaran Siswa</h4>
                    <div>
                        <a href="{{ route('admin.pembayaran-siswa.edit', $pembayaranSiswa->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.pembayaran-siswa.index') }}" class="btn btn-secondary">
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
                                    <td>{{ $pembayaranSiswa->tagihanSiswa->siswa->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIS</strong></td>
                                    <td>{{ $pembayaranSiswa->tagihanSiswa->siswa->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>{{ $pembayaranSiswa->tagihanSiswa->siswa->kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Orang Tua</strong></td>
                                    <td>{{ $pembayaranSiswa->tagihanSiswa->siswa->orangtua->nama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Pembayaran</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="150px"><strong>Jenis Tagihan</strong></td>
                                    <td>{{ $pembayaranSiswa->tagihanSiswa->jenis_tagihan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Bayar</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($pembayaranSiswa->tanggal_bayar)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Bayar</strong></td>
                                    <td>Rp {{ number_format($pembayaranSiswa->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran</strong></td>
                                    <td>{{ $pembayaranSiswa->metode_pembayaran }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Tagihan</strong></td>
                                    <td>
                                        @php
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

                    @if($pembayaranSiswa->keterangan)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Keterangan Pembayaran</h6>
                                <p>{{ $pembayaranSiswa->keterangan }}</p>
                            </div>
                        </div>
                    @endif

                    @if($pembayaranSiswa->bukti_pembayaran)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Bukti Pembayaran</h6>
                                <div class="text-center">
                                    <img src="{{ asset('uploads/bukti_pembayaran/' . $pembayaranSiswa->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" class="img-fluid" style="max-width: 400px;">
                                    <br>
                                    <a href="{{ asset('uploads/bukti_pembayaran/' . $pembayaranSiswa->bukti_pembayaran) }}" 
                                       target="_blank" class="btn btn-info mt-2">
                                        <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Ringkasan Tagihan -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Ringkasan Tagihan</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr class="table-primary">
                                        <th>Total Tagihan</th>
                                        <td class="text-end">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <th>Total Pembayaran</th>
                                        <td class="text-end">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-{{ $sisa <= 0 ? 'success' : 'warning' }}">
                                        <th>Sisa Pembayaran</th>
                                        <td class="text-end">
                                            @if($sisa > 0)
                                                <span class="text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-success">LUNAS</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td class="text-end">
                                            <span class="badge bg-{{ $status == 'lunas' ? 'success' : 'warning' }} fs-6">
                                                {{ strtoupper($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            @if($sisa > 0)
                                <div class="mt-3">
                                    <a href="{{ route('admin.pembayaran-siswa.create') }}?tagihan_id={{ $pembayaranSiswa->tagihan_siswa_id }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Pembayaran Lagi
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
