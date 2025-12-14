@extends('layouts.parent')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title mb-0">Informasi Siswa</h4>
        @if($siswaList->count() > 1)
            <form method="GET" action="{{ route('parent.info.index') }}" class="d-flex align-items-center gap-2">
                <label for="siswa_id" class="text-muted mb-0">Pilih Anak</label>
                <select class="form-select" id="siswa_id" name="siswa_id" onchange="this.form.submit()" style="min-width: 220px;">
                    @foreach($siswaList as $s)
                        <option value="{{ $s->id }}" {{ optional($selectedSiswa)->id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} - {{ $s->kelas }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

    @if(!$selectedSiswa)
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Belum ada data siswa yang terhubung ke akun orang tua ini.</div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-title">Data Anak</p>
                            <h5 class="mb-1">{{ $selectedSiswa->nama_siswa }}</h5>
                            <div class="text-muted" style="font-size: 14px;">NISN: {{ $selectedSiswa->nisn }}</div>
                            <div class="text-muted" style="font-size: 14px;">Kelas: {{ $selectedSiswa->kelas }}</div>
                            <div class="text-muted" style="font-size: 14px;">Tahun Ajaran: {{ $tahunAjaran }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-title">Data Orang Tua</p>
                            <h5 class="mb-1">{{ $orangtua->nama_ortu }}</h5>
                            <div class="text-muted" style="font-size: 14px;">Kontak: {{ $orangtua->no_telp ?? '-' }}</div>
                            <div class="text-muted" style="font-size: 14px;">NIK: {{ $orangtua->nik ?? '-' }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-title">Total Tagihan Berjalan</p>
                            <h3 class="card-amount">Rp {{ number_format($totalTagihanBerjalan, 0, ',', '.') }}</h3>
                            <div class="text-muted" style="font-size: 14px;">Total Dibayar: Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                        </div>
                        <div class="card-icon" style="background: {{ $totalTagihanBerjalan > 0 ? 'linear-gradient(135deg, #F97316 0%, #EA580C 100%)' : 'linear-gradient(135deg, #10B981 0%, #059669 100%)' }};">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title mb-0">Riwayat Pembayaran</h5>
                    <a href="{{ route('parent.payment.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-money-bill-wave me-1"></i> Ke Pembayaran
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Pembayaran</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatPembayaran as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->jenis_pembayaran }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->status_pembayaran == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($p->status_pembayaran == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->status_pembayaran == 'lunas')
                                            <a href="{{ route('pembayaran.invoice', $p->id) }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('parent.payment.index') }}" class="btn btn-sm btn-outline-primary">
                                                Bayar
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada riwayat pembayaran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
