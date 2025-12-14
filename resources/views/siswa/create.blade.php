@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Siswa</h5>
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.siswa.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" class="form-control @error('nama_siswa') is-invalid @enderror" required>
                    @error('nama_siswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="form-control @error('nisn') is-invalid @enderror" required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}" class="form-control @error('kelas') is-invalid @enderror" required>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Orang Tua</label>
                    <select name="id_orangtua" class="form-select @error('id_orangtua') is-invalid @enderror" required>
                        <option value="">-- Pilih Orang Tua --</option>
                        @foreach($orangtuaList as $id => $nama)
                            <option value="{{ $id }}" {{ old('id_orangtua') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('id_orangtua')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
