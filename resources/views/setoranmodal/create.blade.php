<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">Tambah Data Setoran Modal</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('setoranmodal.store') }}" method="POST">
                    @csrf

                    <!-- Kode Modal -->  
                    <div class="mb-3">
                        <label for="kode_modal" class="form-label">Kode Modal</label>
                        <input type="text" name="kode_modal" id="kode_modal" class="form-control" value="{{ $kode_modal }}" readonly>
                    </div>

                    <!-- Tanggal Setoran Modal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Masukkan keterangan" required>
                        @error('keterangan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nominal -->
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" name="nominal" id="nominal" class="form-control" value="{{ old('nminal') }}" placeholder="Masukkan nominal" min="0" required>
                        @error('nominal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('setoranmodal.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
