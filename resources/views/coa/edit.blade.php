<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data COA</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('coa.update', $coa->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Pastikan ini ada -->
                <div class="mb-3">
                    <label for="kode_akun" class="form-label">Kode Akun</label>
                    <input type="text" name="kode_akun" id="kode_akun" class="form-control" 
                           value="{{ $coa->kode_akun }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama_akun" class="form-label">Nama Akun</label>
                    <input type="text" name="nama_akun" id="nama_akun" class="form-control" 
                           value="{{ old('nama_akun', $coa->nama_akun) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
</div>
</x-app-layout>
