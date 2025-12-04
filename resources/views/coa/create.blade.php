<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0">Tambah Data COA</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('coa.store') }}" method="POST">
                    @csrf

                    <!-- Kode Akun -->
                    <div class="mb-3">
                        <label for="kode_akun" class="form-label">Kode Akun</label>
                        <input 
                            type="text" 
                            name="kode_akun" 
                            id="kode_akun" 
                            class="form-control @error('kode_akun') is-invalid @enderror" 
                            value="{{ old('kode_akun') }}" 
                            placeholder="Masukkan kode akun" 
                            required
                        >
                        @error('kode_akun')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nama Akun -->
                    <div class="mb-3">
                        <label for="nama_akun" class="form-label">Nama Akun</label>
                        <input 
                            type="text" 
                            name="nama_akun" 
                            id="nama_akun" 
                            class="form-control @error('nama_akun') is-invalid @enderror" 
                            value="{{ old('nama_akun') }}" 
                            placeholder="Masukkan nama akun" 
                            required
                        >
                        @error('nama_akun')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('coa.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
