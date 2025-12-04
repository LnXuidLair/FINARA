<x-app-layout>
    <div class="container mt-3">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Bahan Baku</h5>
                <a href="{{ route('bahanbaku.create') }}" class="btn btn-primary">Tambah Bahan Baku</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Bahan Baku</th>
                                <th>Nama Bahan Baku</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahanbaku as $item)
                                <tr>
                                    <td>{{ $item->kode_bahanbaku}}</td>
                                    <td>{{ $item->nama_bahanbaku }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>
                                        <a href="{{ route('bahanbaku.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('bahanbaku.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data bahan baku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
