<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Setoran Modal</h5>
        </div>
        <div class="card-body">
    <form action="{{ route('setoranmodal.update', $setoranmodal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_modal" class="form-label">Kode Modal</label>
            <input type="text" name="kode_modal" id="kode_modal" class="form-control" value="{{ $setoranmodal->kode_modal }}" readonly>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $setoranmodal->tanggal }}" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $setoranmodal->keerangan }}" required>
        </div>
        <div class="mb-3">
            <label for="nominal" class="form-label">Nominal</label>
            <input type="number" name="nominal" id="nominal" class="form-control" value="{{ $setoranmodal->nominal }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('setoranmodal.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</x-app-layout>
