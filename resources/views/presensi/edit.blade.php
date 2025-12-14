<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Beban</h5>
        </div>
        <div class="card-body">
    <form action="{{ route('beban.update', $beban->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_beban" class="form-label">Kode Beban</label>
            <input type="text" name="kode_beban" id="kode_beban" class="form-control" value="{{ $beban->kode_beban }}" readonly>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $beban->tanggal }}" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $beban->keterangan }}" required>
        </div>
        <div class="mb-3">
            <label for="nominal" class="form-label">Nominal</label>
            <input type="int" name="nominal" id="nominal" class="form-control" value="{{ $beban->nominal }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('beban.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</x-app-layout>
