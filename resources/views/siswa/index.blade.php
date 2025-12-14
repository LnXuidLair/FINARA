@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Master Data Siswa</h5>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Orang Tua</th>
                        <th>No. Telp Orang Tua</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->nisn }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>{{ $item->orangtua->nama_ortu ?? '-' }}</td>
                            <td>{{ $item->orangtua->no_telp ?? '-' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus siswa ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $siswa->links() }}
        </div>
    </div>
</div>
@endsection
