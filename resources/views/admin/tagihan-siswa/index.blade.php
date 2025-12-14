@extends('layouts.app')

@section('title', 'Tagihan Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Tagihan Siswa</h4>
                    <a href="{{ route('admin.tagihan-siswa.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Tagihan
                    </a>
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
                                    <th>Siswa</th>
                                    <th>Orang Tua</th>
                                    <th>Jenis Tagihan</th>
                                    <th>Jumlah</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Periode</th>
                                                                        <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tagihan as $index => $item)
                                    <tr>
                                        <td>{{ $tagihan->firstItem() + $index }}</td>
                                        <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ $item->siswa->orangtua->nama_ortu ?? '-' }}</td>
                                        <td>{{ $item->jenis_tagihan }}</td>
                                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                                        <td>{{ $item->periode ?? '-' }}</td>
                                                                                <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.tagihan-siswa.show', $item->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.tagihan-siswa.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tagihan-siswa.destroy', $item->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data tagihan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tagihan->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $tagihan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
