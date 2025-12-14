<x-app-layout>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Pembelian Bahan Baku</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pembelian_bahan_baku.update', $pembelian->$id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="kode_pembelian" class="form-label">Kode Pembelian</label>
                        <input type="text" name="kode_pembelian" id="kode_pembelian" class="form-control" value="{{ $pembelian->kode_pembelian }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="text" name="jumlah" id="jumlah" class="form-control" value="{{ $pembelian->jumlah }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_per_unit" class="form-label">Harga Per Unit</label>
                        <input type="text" name="harga_per_unit" id="harga_per_unit" class="form-control" value="{{ $pembelian->harga_per_unit }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" name="total_harga" id="total_harga" class="form-control" value="{{ $pembelian->total_harga }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
                        <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control">
                        @if($pembelian->bukti_bayar)
                            <img src="{{ asset('storage/' . $pembelian->bukti_bayar) }}" alt="Bukti Bayar" class="img-fluid mt-2" style="max-width: 150px;">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('pembelian_bahan_baku.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Script untuk Menghitung Total Harga -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jumlahInput = document.getElementById('jumlah');
            const hargaPerUnitInput = document.getElementById('harga_per_unit');
            const totalHargaInput = document.getElementById('total_harga');

            function calculateTotal() {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
                totalHargaInput.value = (jumlah * hargaPerUnit).toFixed(2);
            }

            jumlahInput.addEventListener('input', calculateTotal);
            hargaPerUnitInput.addEventListener('input', calculateTotal);
        });
    </script>
</x-app-layout>
