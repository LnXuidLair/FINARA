<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">Tambah Data Pelanggan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('pelanggan.store') }}" method="POST">
                    @csrf

                    <!-- ID Pelanggan -->  
                    <div class="mb-3">
                        <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
                        <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" value="{{ $id_pelanggan }}" readonly>
                    </div>

                    <!-- Nama Pelanggan -->
                    <div class="mb-3">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" placeholder="Masukkan nama pelanggan" required>
                        @error('nama_pelanggan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- No Telp Pelanggan -->
                    <div class="mb-3">
                        <label for="no_telp_pelanggan" class="form-label">No Telepon Pelanggan</label>
                        <input type="integer" name="no_telp_pelanggan" id="no_telp_pelanggan" class="form-control" value="{{ old('no_telp_pelanggan') }}" placeholder="Masukkan no telp pelanggan" required>
                        @error('no_telp_pelanggan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Masukkan alamat" required>
                        @error('jumlah')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
