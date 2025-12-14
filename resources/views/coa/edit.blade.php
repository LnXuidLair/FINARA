@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data COA</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('coa.update', $coa->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Pastikan ini ada -->
                        <div class="mb-3">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" value="{{ $coa->id }}" readonly>
                        </div>
                <div class="mb-3">
                    <label for="header_akun" class="form-label">Header Akun</label>
                    <input type="text" name="header_akun" id="header_akun" class="form-control @error('header_akun') is-invalid @enderror" 
                           value="{{ old('header_akun', $coa->header_akun) }}" required>
                    @error('header_akun')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kode_akun" class="form-label">Kode Akun</label>
                    <input type="text" name="kode_akun" id="kode_akun" class="form-control @error('kode_akun') is-invalid @enderror" 
                           value="{{ old('kode_akun', $coa->kode_akun) }}" required maxlength="5">
                    @error('kode_akun')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama_akun" class="form-label">Nama Akun</label>
                    <input type="text" name="nama_akun" id="nama_akun" class="form-control @error('nama_akun') is-invalid @enderror" 
                           value="{{ old('nama_akun', $coa->nama_akun) }}" required>
                    @error('nama_akun')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('coa.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
