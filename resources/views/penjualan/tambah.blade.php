<x-app-layout>
    <div class="container">
        <h2>Tambah Penjualan</h2>
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <label for="idbaranghidden">Pilih Barang:</label>
            <select name="idbaranghidden">
                @foreach ($barang as $b)
                    <option value="{{ $b->id }}">{{ $b->nama_barang }} - Rp{{ number_format($b->harga, 0, ',', '.') }}</option>
                @endforeach
            </select>

            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" min="1" required>

            <input type="hidden" name="tipeproses" value="tambah">

            <button type="submit">Tambah</button>
        </form>
    </div>
</x-app-layout>