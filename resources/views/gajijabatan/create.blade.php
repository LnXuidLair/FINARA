@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Tambah Gaji Jabatan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('gajijabatan.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gaji per Hari</label>
                    <input type="number" name="gaji_perhari" class="form-control @error('gaji_perhari') is-invalid @enderror" value="{{ old('gaji_perhari') }}" min="0" required>
                    @error('gaji_perhari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('gajijabatan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
