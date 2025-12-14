@extends('parent')

@section('content')
<div class="card mb-4 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Pembayaran Tagihan</h3>
        </div>

        @if($unpaidTagihan->isEmpty())
            <div class="alert alert-info">Tidak ada tagihan yang perlu dibayar.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Jenis Tagihan</th>
                            <th>Periode</th>
                            <th>Total Tagihan</th>
                            <th>Sudah Dibayar</th>
                            <th>Sisa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unpaidTagihan as $item)
                            <tr>
                                <td>{{ $item->siswa->nama }}</td>
                                <td>{{ $item->jenis_tagihan }}</td>
                                <td>{{ $item->periode ?? '-' }}</td>
                                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->sisa > 0)
                                        <span class="text-danger">Rp {{ number_format($item->sisa, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->sisa > 0)
                                        <a href="{{ route('parent.tagihan-payment.create', $item->id) }}" class="btn btn-sm btn-primary">Bayar</a>
                                    @else
                                        <a href="{{ route('parent.tagihan-payment.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
