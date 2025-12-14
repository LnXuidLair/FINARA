<x-app-layout>
    @if(session('status_hapus'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: 'Barang berhasil dihapus dari keranjang.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <div class="py-5">
        <div class="container">
            <h3 class="mb-4 fw-bold">🛒 Keranjang Anda</h3>

            @if(count($keranjang) > 0)
                <h5 class="text-muted mb-2">Jenis Item: <span class="text-dark fw-bold">{{ $jenisBarang }} macam</span></h5>
                <h5 class="text-muted mb-2">Jumlah Barang: <span class="text-dark fw-bold">{{ $totalBarang }} pcs</span></h5>
                <h5 class="text-muted mb-4">Total (IDR): <span class="text-dark fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span></h5>
            @endif

            @if(count($keranjang) > 0)
                <div class="row g-4">
                    @foreach($keranjang as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ url('barang/' . ($item->foto ?? 'default.png')) }}"
                                     class="card-img-top" alt="{{ $item->nama_barang }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->nama_barang }}</h5>
                                    <p class="card-text mb-1">Harga: <strong>Rp {{ number_format($item->harga, 0, ',', '.') }}</strong></p>
                                    <p class="card-text mb-1">Jumlah: {{ $item->jml_barang }}</p>
                                    <p class="card-text">Total: <strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></p>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ $item->no_transaksi }}</span>
                                    <a href="#" onclick="deleteConfirm(this); return false;" data-id="{{ $item->id_penjualan_detail }}" class="btn btn-sm btn-outline-danger">
                                        <i class="ti ti-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 text-end">
                    <a href="{{ url('penjualan/checkout') }}" class="btn btn-lg btn-primary">
                        <i class="ti ti-check"></i> Lanjut ke Checkout
                    </a>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    Keranjang kosong!
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center" id="xid">
                    <!-- Isi akan ditambahkan oleh JS -->
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a id="btn-delete" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Konfirmasi Hapus -->
    <script>
        function deleteConfirm(e) {
            const id = e.getAttribute('data-id');
            const url = `{{ url('penjualan/destroypenjualandetail') }}/${id}`;
            document.getElementById('btn-delete').setAttribute('href', url);
            document.getElementById('xid').innerHTML = `Apakah kamu yakin ingin menghapus item dengan ID <strong>${id}</strong> dari keranjang?`;
            $('#deleteModal').modal('show');
        }
    </script>
</x-app-layout>