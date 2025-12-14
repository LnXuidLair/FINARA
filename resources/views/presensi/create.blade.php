@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Presensi</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.presensi.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="id_pegawai" class="col-md-4 col-form-label text-md-right">Pegawai</label>
                            <div class="col-md-6">
                                <select name="id_pegawai" id="id_pegawai" class="form-control @error('id_pegawai') is-invalid @enderror" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($pegawai as $pegawai)
                                        <option value="{{ $pegawai->id }}" {{ old('id_pegawai') == $pegawai->id ? 'selected' : '' }}>
                                            {{ $pegawai->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_pegawai')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="tanggal" class="col-md-4 col-form-label text-md-right">Tanggal</label>
                            <div class="col-md-6">
                                <input id="tanggal" type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                                <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
