<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">Tambah Data Produk</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('produk.store') }}" method="POST">
                    @csrf

                    <!-- ID Produk -->  
                    <div class="mb-3">
                        <label for="kode_produk" class="form-label">Kode Produk</label>
                        <input type="text" name="kode_produk" id="kode_produk" class="form-control" value="{{ $kode_produk }}" readonly>
                    </div>

                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk" required>
                        @error('nama_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="text" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" placeholder="Masukkan Harga " required>
                        @error('Harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Jumlah Stok -->
                    <div class="mb-3">
                        <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
                        <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" value="{{ old('jumlah_stok') }}" placeholder="Masukkan jumlah stok" min="0" required>
                        @error('jumlah_stok')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
