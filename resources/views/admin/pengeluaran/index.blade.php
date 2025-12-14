@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Pengeluaran</h4>
                        <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-primary btn-sm" title="Tambah Pengeluaran">
                            +
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah</th>
                                    <th>Invoice</th>
                                    <th>Status Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengeluaran as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $item->kategori ?? ($item->jenis === 'gaji' ? 'penggajian' : 'manual') }}</td>
                                        <td>{{ $item->deskripsi ?? $item->keterangan }}</td>
                                        <td>Rp {{ number_format((int)($item->jumlah ?? $item->nominal), 0, ',', '.') }}</td>
                                        <td>
                                            @if(!empty($item->bukti_pembayaran) && $item->bukti_pembayaran !== '-')
                                                <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="btn btn-info btn-sm">Lihat Invoice</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php($status = $item->status_verifikasi ?? 'pending')
                                            @if($status === 'approved')
                                                <span class="badge badge-success">approved</span>
                                            @elseif($status === 'rejected')
                                                <span class="badge badge-danger">rejected</span>
                                            @else
                                                <span class="badge badge-warning">pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
