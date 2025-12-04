<div class="modal fade" id="ubahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelmodalubah">Tambahkan ke dalam keranjang</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="#" class="formpenjualan" method="post">
                    @csrf
                    <input type="hidden" id="idbaranghidden" name="idbaranghidden">
                    <input type="hidden" id="tipeproses" name="tipeproses" value="tambah">

                    <div class="mb-3 row">
                        <label for="nama_barang" class="col-sm-4 col-form-label">Nama Produk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="harga" class="col-sm-4 col-form-label">Harga Produk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="harga" name="harga" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jumlah" class="col-sm-4 col-form-label">Jumlah</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1">
                            <div class="invalid-feedback errorjumlah"></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>
