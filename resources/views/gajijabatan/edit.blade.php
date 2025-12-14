<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Pelanggan</h5>
        </div>
        <div class="card-body">
    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
            <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" value="{{ $pelanggan->id_pelanggan }}" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="{{ $pelanggan->nama_pelanggan }}" required>
        </div>
        <div class="mb-3">
            <label for="no_telp_pelanggan" class="form-label">No Telepon Pelanggan</label>
            <input type="integer" name="no_telp_pelanggan" id="no_telp_pelanggan" class="form-control" value="{{ $pelanggan->no_telp_pelanggan }}" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" value="{{ $pelanggan->alamat }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</x-app-layout>
