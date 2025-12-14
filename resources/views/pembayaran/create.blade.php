@extends('layouts.parent')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">Tambah Pembayaran</h4>
        <a href="{{ route('parent.payment.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="payment-form" method="POST" action="{{ route('create-payment') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_siswa" class="form-label">Nama Siswa</label>
                                <select class="form-select @error('id_siswa') is-invalid @enderror" id="id_siswa" name="id_siswa" required>
                                    <option value="" selected disabled>Pilih Siswa</option>
                                    @foreach($siswa as $s)
                                        <option value="{{ $s->id }}" {{ old('id_siswa') == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_siswa }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_siswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                                <select class="form-select @error('jenis_pembayaran') is-invalid @enderror" id="jenis_pembayaran" name="jenis_pembayaran" required>
                                    <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    <option value="SPP" {{ old('jenis_pembayaran') == 'SPP' ? 'selected' : '' }}>SPP</option>
                                    <option value="Daftar Ulang" {{ old('jenis_pembayaran') == 'Daftar Ulang' ? 'selected' : '' }}>Daftar Ulang</option>
                                    <option value="Uang Gedung" {{ old('jenis_pembayaran') == 'Uang Gedung' ? 'selected' : '' }}>Uang Gedung</option>
                                    <option value="Lainnya" {{ old('jenis_pembayaran') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                       id="tanggal_bayar" name="tanggal_bayar" 
                                       value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                           id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submit-button">
                                <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_siswa: document.getElementById('id_siswa').value,
                    jenis_pembayaran: document.getElementById('jenis_pembayaran').value,
                    tanggal_bayar: document.getElementById('tanggal_bayar').value,
                    jumlah: document.getElementById('jumlah').value,
                    _token: document.querySelector('input[name="_token"]').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Open Snap payment popup
                    window.snap.pay(data.token, {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil!');
                            window.location.href = '{{ route('parent.payment.index') }}';
                        },
                        onPending: function(result) {
                            alert('Menunggu pembayaran!');
                            window.location.href = '{{ route('parent.payment.index') }}';
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="fas fa-credit-card me-1"></i> Bayar Sekarang';
                        },
                        onClose: function() {
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="fas fa-credit-card me-1"></i> Bayar Sekarang';
                        }
                    });
                } else {
                    throw new Error('Token pembayaran tidak diterima');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-credit-card me-1"></i> Bayar Sekarang';
            });
        });
    });
</script>
@endsection
