<x-app-layout>

    <div class="container mt-4">
         <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Produk</h5>
        </div>
        <div class="card-body">
    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_produk" class="form-label">Kode Produk</label>
            <input type="text" name="kode_produk" id="kode_produk" class="form-control" value="{{ $produk->kode_produk }}" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">harga</label>
            <input type="text" name="harga" id="harga" class="form-control" value="{{ $produk->harga }}" required>
        </div>
        <div class="mb-3">
            <label for="Jumlah_stok" class="form-label">Jumlah Stok</label>
            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" value="{{ $produk->jumlah_stok }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</x-app-layout>
