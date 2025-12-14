<x-app-layout>
    <div class="container mt-3">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Nama Pegawai</h5>
                <a href="{{ route('pegawai.create') }}" class="btn btn-primary">Tambah Daftar Pegawai</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Jabatan</th>
                                <th>Email Aktivasi</th>
                                <th>Alamat</th>
                                <th>Status Aktivasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawai as $pgw)
                                <tr>
                                    <td>{{ $pgw->nip}}</td>
                                    <td>{{ $pgw->nama_pegawai}}</td>
                                    <td>{{ $pgw->jabatan}}</td>
                                    <td>{{ $pgw->email}}</td>
                                    <td>{{ $pgw->alamat}}</td>
                                    <td>{{ $pgw->is_verified}}</td>
                                    <td>
                                        <a href="{{ route('pegawai.edit', $pgw->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('pegawai.destroy', $pgw->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pegawai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>