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
                        <label for="nip" class="form-label">NIP Pegawai</label>
                        <input type="number" name="nip" id="nip" class="form-control" value="{{ old('nip') }}" required>
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Nama Pegawai -->
                    <div class="mb-3">
                        <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" value="{{ old('nama_pegawai') }}" placeholder="Input Nama Siswa" required>
                        @error('nama_pegawai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Masukkan jabatan" required>
                        @error('jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Emal Pegawai -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pegawai</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Masukkan Nama Email" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Alamat Pegawai -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Pegawai</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Masukkan Alamat Pegawai">
                        @error('Alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
