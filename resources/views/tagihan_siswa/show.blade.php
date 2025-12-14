@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Tagihan</h5>
        <a href="{{ route('tagihan_siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2"><strong>ID:</strong> {{ $tagihan->id }}</div>
                <div class="mb-2"><strong>Nama Siswa:</strong> {{ $tagihan->siswa->nama_siswa ?? '-' }}</div>
                <div class="mb-2"><strong>Orang Tua:</strong> {{ $tagihan->orangtua->nama_ortu ?? '-' }}</div>
                <div class="mb-2"><strong>Jenis Tagihan:</strong> {{ $tagihan->jenis_tagihan }}</div>
            </div>
            <div class="col-md-6">
                <div class="mb-2"><strong>Periode:</strong> {{ $tagihan->periode }}</div>
                <div class="mb-2"><strong>Nominal:</strong> Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $tagihan->status }}</div>
                <div class="mb-2"><strong>Tanggal Dibuat:</strong> {{ $tagihan->created_at?->format('d M Y H:i') }}</div>
            </div>
        </div>

        @if($tagihan->keterangan)
            <hr>
            <div><strong>Keterangan:</strong></div>
            <div class="text-muted">{{ $tagihan->keterangan }}</div>
        @endif
    </div>
</div>
@endsection
