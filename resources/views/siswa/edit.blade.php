@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Siswa</h5>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="form-control @error('nisn') is-invalid @enderror" required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" class="form-control @error('nama_siswa') is-invalid @enderror" required>
                    @error('nama_siswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" class="form-control @error('kelas') is-invalid @enderror" required>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Alamat (opsional)</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $siswa->alamat) }}" class="form-control @error('alamat') is-invalid @enderror">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. Telp Siswa (opsional)</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}" class="form-control @error('no_telp') is-invalid @enderror">
                    @error('no_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12"><hr></div>

                <div class="col-md-6">
                    <label class="form-label">Nama Orangtua</label>
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $siswa->orangtua->nama_ortu ?? '') }}" class="form-control @error('nama_ortu') is-invalid @enderror" required>
                    @error('nama_ortu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email Orangtua</label>
                    <input type="email" name="email_ortu" value="{{ old('email_ortu', $siswa->orangtua->email ?? '') }}" class="form-control @error('email_ortu') is-invalid @enderror" required>
                    @error('email_ortu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">No. Telp Orangtua</label>
                    <input type="text" name="no_telp_ortu" value="{{ old('no_telp_ortu', $siswa->orangtua->no_telp ?? '') }}" class="form-control @error('no_telp_ortu') is-invalid @enderror" required>
                    @error('no_telp_ortu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
