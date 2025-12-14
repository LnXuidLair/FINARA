@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Edit Pegawai</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $pegawai->nip) }}" required>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="form-control @error('nama_pegawai') is-invalid @enderror" value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" required>
                    @error('nama_pegawai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $pegawai->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat', $pegawai->alamat) }}">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jabatan (Wajib)</label>
                    <select name="id_gaji_jabatan" class="form-select @error('id_gaji_jabatan') is-invalid @enderror" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($gajiJabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('id_gaji_jabatan', $pegawai->id_gaji_jabatan) == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->jabatan }} - Rp {{ number_format($jabatan->gaji_perhari, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_gaji_jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
