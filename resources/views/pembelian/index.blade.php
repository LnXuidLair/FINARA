<x-app-layout>
    <div class="container mt-3">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pembelian</h5>
                <a href="{{ route('pembelian.create') }}" class="btn btn-primary">Tambah Pembelian</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Pembelian</th>
                                <th>Tanggal</th>
                                <th>Bahan Baku</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembelian as $item)
                                <tr>
                                    <td>{{ $item->kode_pembelian }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->bahanbaku->nama_bahanbaku }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->total_harga, 2, ',', '.') }}</td>
                                    <td>
                                        <!-- <a href="{{ route('pembelian.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" class="d-inline"> -->
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pembelian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
