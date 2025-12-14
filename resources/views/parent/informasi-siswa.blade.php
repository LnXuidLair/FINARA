@extends('parent')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="section-title mb-0">Informasi Siswa</h4>

    @if($siswaList->count() > 1)
        <form method="GET" action="{{ route('parent.informasi-siswa') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <label for="siswa_id" class="text-muted mb-0">Pilih Anak</label>
            <select class="form-select w-100" id="siswa_id" name="siswa_id" onchange="this.form.submit()" style="min-width: 220px; width: min(100%, 360px);">
                @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ optional($siswa)->id == $s->id ? 'selected' : '' }}>
                        {{ $s->nama_siswa }} - {{ $s->kelas }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif
</div>

@if(!$siswa)
    <div class="card">
        <div class="text-muted">Belum ada data siswa.</div>
    </div>
@else
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <p class="card-title mb-2">Informasi Anak</p>
                        <h5 class="mb-2">{{ $siswa->nama_siswa }}</h5>
                        <div class="text-muted" style="font-size: 14px;">NISN: {{ $siswa->nisn }}</div>
                        <div class="text-muted" style="font-size: 14px;">Kelas: {{ $siswa->kelas }}</div>
                        <div class="text-muted" style="font-size: 14px;">Tahun Ajaran: {{ $tahunAjaran }}</div>
                    </div>
                    <div class="card-icon flex-shrink-0" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                        <i class="fas fa-user-graduate fs-3"></i>
                    </div>
                </div>

                <div class="mt-3 d-flex align-items-center gap-3">
                    <div style="width: 56px; height:56px; border-radius: 12px; background:#E5E7EB;" class="d-flex align-items-center justify-content-center overflow-hidden">
                        <i class="fas fa-image text-muted"></i>
                    </div>
                    <div class="text-muted" style="font-size: 14px;">Foto (opsional)</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <p class="card-title mb-2">Informasi Orang Tua</p>
                        <div class="mb-1"><span class="text-muted">Nama Ayah:</span> <strong>{{ $orangtua->nama_ortu }}</strong></div>
                        <div class="mb-1"><span class="text-muted">Nama Ibu:</span> <strong>-</strong></div>
                        <div class="mb-1"><span class="text-muted">Nomor HP:</span> <strong>{{ $orangtua->no_telp ?? '-' }}</strong></div>
                        <div class="mb-0"><span class="text-muted">Alamat:</span> <strong>{{ $orangtua->alamat ?? '-' }}</strong></div>
                    </div>
                    <div class="card-icon flex-shrink-0" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);">
                        <i class="fas fa-users fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100" style="background: {{ $totalTagihan > 0 ? '#FEF3C7' : '#D1FAE5' }};">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <p class="card-title mb-2">Total Tagihan</p>
                        <h3 class="card-amount mb-1">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h3>
                        <div class="text-muted" style="font-size: 14px;">{{ $totalTagihan > 0 ? 'Ada tagihan berjalan' : 'Lunas' }}</div>
                    </div>
                    <div class="card-icon flex-shrink-0" style="background: {{ $totalTagihan > 0 ? 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)' : 'linear-gradient(135deg, #10B981 0%, #059669 100%)' }};">
                        <i class="fas fa-file-invoice fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="section-title mb-0">Riwayat Pembayaran</h5>
                    <a href="{{ route('parent.payment.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-money-bill-wave me-1"></i> Ke Pembayaran
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Pembayaran</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $p)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                    <td>{{ $p->jenis_pembayaran }}</td>
                                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->status_pembayaran == 'lunas')
                                            <span class="badge bg-success">paid</span>
                                        @elseif($p->status_pembayaran == 'pending')
                                            <span class="badge bg-warning text-dark">pending</span>
                                        @else
                                            <span class="badge bg-danger">failed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada riwayat pembayaran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
