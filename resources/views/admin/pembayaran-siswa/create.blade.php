@extends('layouts.app')

@section('title', 'Tambah Pembayaran Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tambah Pembayaran Siswa</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.pembayaran-siswa.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tagihan_siswa_id" class="form-label">Tagihan Siswa <span class="text-danger">*</span></label>
                                    <select name="tagihan_siswa_id" id="tagihan_siswa_id" class="form-select" required>
                                        <option value="">-- Pilih Tagihan --</option>
                                        @foreach($tagihanSiswa as $tagihan)
                                            <option value="{{ $tagihan->id }}" 
                                                    {{ old('tagihan_siswa_id') == $tagihan->id ? 'selected' : '' }}
                                                    data-jumlah="{{ $tagihan->jumlah }}">
                                                {{ $tagihan->siswa->nama }} - {{ $tagihan->jenis_tagihan }} 
                                                (Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }})
                                                @if($tagihan->sisa > 0)
                                                    - Sisa: Rp {{ number_format($tagihan->sisa, 0, ',', '.') }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hanya tagihan yang belum lunas yang ditampilkan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="online" {{ old('metode_pembayaran') == 'online' ? 'selected' : '' }}>Pembayaran Online</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_bayar" class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" 
                                           class="form-control" value="{{ old('tanggal_bayar') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_bayar" class="form-label">Jumlah Bayar (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" 
                                           value="{{ old('jumlah_bayar') }}" required min="1000" step="1000">
                                    <small class="text-muted">Minimal Rp 1.000</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                                           class="form-control" accept="image/*">
                                    <small class="text-muted">Opsional. Format: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                                    <small class="text-muted">Opsional</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.pembayaran-siswa.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagihanSelect = document.getElementById('tagihan_siswa_id');
    const jumlahInput = document.getElementById('jumlah_bayar');
    
    tagihanSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const jumlah = selectedOption.getAttribute('data-jumlah');
        
        if (jumlah) {
            // Hitung sisa pembayaran
            const currentTotal = {{ old('jumlah_bayar', 0) }};
            const sisa = parseFloat(jumlah) - currentTotal;
            
            if (sisa > 0) {
                jumlahInput.value = sisa;
                jumlahInput.max = sisa;
            } else {
                jumlahInput.value = 0;
            }
        }
    });
});
</script>
@endsection
