<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header Tombol -->
                    <div class="flex justify-between mb-6">
                        <a href="{{ url('penjualan/status') }}" class="btn btn-dark">
                            <i class="ti ti-clock"></i> Lihat Status Pemesanan
                        </a>
                        <a href="{{ url('penjualan/keranjang') }}" class="btn btn-success">
                            <i class="ti ti-shopping-cart"></i> Lihat Keranjang
                        </a>
                    </div>

                    <!-- List Barang -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($barang as $p)
                            <div class="border rounded-lg col-md-6 shadow-md p-4 bg-gray-50">
                                <div class="card h-100 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-700">{{ $p->nama_barang }}</h3>
                                    <div class="mt-2">
                                        <a data-fancybox="gallery" href="{{ url('barang/' . $p->foto) }}">
                                            <img width="150px" height="150px" src="{{ url('barang/' . $p->foto) }}">
                                        </a>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">{{ $p->deskripsi }}</p>
                                    <p class="mt-2 text-sm font-semibold text-gray-800">Stok: <span id="stok-{{ $p->id }}">{{ $p->stok }}</span></p>
                                    <p class="mt-2 text-sm font-semibold text-gray-800">Harga: Rp {{ number_format($p->harga, 0, ',', '.') }}</p>

                                    <!-- Form Tambah ke Keranjang -->
                                    <form action="{{ url('penjualan/tambah-keranjang') }}" method="POST" class="form-keranjang" data-id="{{ $p->id }}">
                                        @csrf
                                        <input type="hidden" name="idbarang" value="{{ $p->id }}">
                                        
                                        <label for="jumlah-{{ $p->id }}" class="block text-sm font-medium text-gray-700 mt-2">Jumlah:</label>
                                        <input type="number" name="jumlah" id="jumlah-{{ $p->id }}" min="1" max="{{ $p->stok }}" value="1" class="form-input w-full p-2 border rounded mb-2">
                                    
                                        <button type="submit" class="w-full btn btn-primary">
                                            <i class="fa fa-shopping-cart"></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- SCRIPT AJAX -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("📦 Script jalan...");
    
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
        if (!csrfToken) {
            console.error("🚨 CSRF token tidak ditemukan!");
            return;
        }
    
        document.querySelectorAll(".form-keranjang").forEach(form => {
            form.addEventListener("submit", function (e) {
                e.preventDefault(); // Cegah reload
    
                const formData = new FormData(this);
                const actionUrl = this.getAttribute('action');
                const barangId = this.dataset.id;
                const jumlahInput = this.querySelector('input[name="jumlah"]');
                const jumlah = parseInt(jumlahInput.value);
                const stokEl = document.getElementById('stok-' + barangId);
                const stok = parseInt(stokEl.innerText);
    
                // Validasi jumlah
                if (jumlah > stok) {
                    alert("❌ Jumlah melebihi stok yang tersedia!");
                    return;
                }
    
                fetch(actionUrl, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log("🟢 Respon dari server:", data);
    
                    if (data.status === 200) {
                        alert("✅ " + data.message);
    
                        // Kurangi stok di UI
                        stokEl.innerText = stok - jumlah;
    
                        // Reset jumlah jadi 1
                        jumlahInput.value = 1;
                    } else if (data.status === 400 && data.errors) {
                        let messages = Object.values(data.errors).flat().join("\n");
                        alert("❌ Error:\n" + messages);
                    } else {
                        alert("❌ " + (data.message || "Terjadi kesalahan."));
                    }
                })
                .catch(err => {
                    console.error("🚨 Error:", err);
                    alert("❌ Gagal mengirim data ke server.");
                });
            });
        });
    });
</script>