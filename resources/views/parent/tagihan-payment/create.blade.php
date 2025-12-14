@extends('parent')

@section('content')
<div class="card mb-4 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Bayar Tagihan</h3>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('parent.tagihan-payment.store', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $tagihan->siswa->nama }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Tagihan</label>
                    <input type="text" class="form-control" value="{{ $tagihan->jenis_tagihan }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Periode</label>
                    <input type="text" class="form-control" value="{{ $tagihan->periode ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Total Tagihan</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sudah Dibayar</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($tagihan->total_pembayaran, 0, ',', '.') }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sisa Pembayaran</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($tagihan->sisa, 0, ',', '.') }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-select" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                    <small class="text-muted">Format: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional"></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" id="payBtn">Kirim Bukti Pembayaran</button>
                    <a href="{{ route('parent.tagihan-payment.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
