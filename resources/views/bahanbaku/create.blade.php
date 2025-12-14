<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">Tambah Data Bahan Baku</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('bahanbaku.store') }}" method="POST">
                    @csrf

                    <!-- ID Bahan Baku -->  
                    <div class="mb-3">
                        <label for="kode_bahanbaku" class="form-label">ID Bahan Baku</label>
                        <input type="text" name="kode_bahanbaku" id="kode_bahanbaku" class="form-control" value="{{ $kode_bahanbaku }}" readonly>
                    </div>

                    <!-- Nama Bahan Baku -->
                    <div class="mb-3">
                        <label for="nama_bahanbaku" class="form-label">Nama Bahan Baku</label>
                        <input type="text" name="nama_bahanbaku" id="nama_bahanbaku" class="form-control" value="{{ old('nama_bahanbaku') }}" placeholder="Masukkan nama bahan baku" required>
                        @error('nama_bahanbaku')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Satuan -->
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control" value="{{ old('satuan') }}" placeholder="Masukkan satuan (misal: kg, liter)" required>
                        @error('satuan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah') }}" placeholder="Masukkan jumlah bahan baku" min="0" required>
                        @error('jumlah')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
