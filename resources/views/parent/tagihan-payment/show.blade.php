@extends('parent')

@section('content')
<div class="card mb-4 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h3 class="section-title mb-0 fs-4 fw-semibold text-reset">Detail Pembayaran Tagihan</h3>
        </div>

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
                <label class="form-label">Nominal</label>
                <input type="text" class="form-control" value="Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status Pembayaran</label>
                <input type="text" class="form-control" value="{{ ucfirst($tagihan->status) }}" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Metode Pembayaran</label>
                <input type="text" class="form-control" value="{{ $tagihan->pembayaranSiswa->first()?->metode_pembayaran ?? '-' }}" readonly>
            </div>

            <div class="col-12">
                <a href="{{ route('parent.tagihan-payment.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
