<x-app-layout>
    <div class="container mt-3">
        <div class="card shadow">
            <div class="card-header">
                <h5>Tambah Transaksi Pembelian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pembelian.store') }}" method="POST">
                    @csrf
                    <!-- Kode Pembelian -->
                    <div class="mb-3">
                        <label for="kode_pembelian" class="form-label">Kode Pembelian</label>
                        <input type="text" name="kode_pembelian" id="kode_pembelian" class="form-control" value="{{ $kodePembelian }}" readonly>
                    </div>

                    <!-- Input Tanggal -->
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>

                    <!-- Dropdown Bahan Baku -->
                    <div class="form-group">
                        <label for="bahanbaku_id">Pilih Bahan Baku</label>
                        <select name="bahanbaku_id" id="bahanbaku_id" class="form-control" required>
                            <option value="">Pilih Bahan Baku</option>
                            @foreach ($bahanbaku as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_bahanbaku }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Jumlah -->
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>

                    <!-- Input Harga Satuan -->
                    <div class="form-group">
                        <label for="harga_satuan">Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" min="0" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
