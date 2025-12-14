@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tagihan Siswa</h5>
        <a href="{{ route('tagihan_siswa.create') }}" class="btn btn-primary">Tambah Tagihan</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Siswa</th>
                        <th>Orang Tua</th>
                        <th>Jenis Tagihan</th>
                        <th>Bulan / Periode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihan as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $item->orangtua->nama_ortu ?? '-' }}</td>
                            <td>{{ $item->jenis_tagihan }}</td>
                            <td>{{ $item->periode }}</td>
                            <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($item->status === 'partial')
                                    <span class="badge bg-warning text-dark">Sebagian</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('tagihan_siswa.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('tagihan_siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('tagihan_siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus tagihan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tagihan->links() }}
        </div>
    </div>
</div>
@endsection
