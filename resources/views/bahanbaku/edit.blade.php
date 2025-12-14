<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Bahan Baku</h5>
        </div>
        <div class="card-body">
    <form action="{{ route('bahanbaku.update', $bahanbaku->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_bahanbaku" class="form-label">Kode Bahan Baku</label>
            <input type="text" name="kode_bahanbaku" id="kode_bahanbaku" class="form-control" value="{{ $bahanbaku->kode_bahanbaku }}" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_bahanbaku" class="form-label">Nama Bahan Baku</label>
            <input type="text" name="nama_bahanbaku" id="nama_bahanbaku" class="form-control" value="{{ $bahanbaku->nama_bahanbaku }}" required>
        </div>
        <div class="mb-3">
            <label for="satuan" class="form-label">Satuan</label>
            <input type="text" name="satuan" id="satuan" class="form-control" value="{{ $bahanbaku->satuan }}" required>
        </div>
        <div class="mb-3">
            <label for="Jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $bahanbaku->jumlah }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</x-app-layout>
