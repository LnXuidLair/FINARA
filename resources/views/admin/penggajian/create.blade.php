@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Penggajian</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.penggajian.store') }}">
                        @csrf

                        {{-- Pegawai --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Pegawai</label>
                            <div class="col-md-6">
                                <select name="id_pegawai" class="form-control" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">
                                            {{ $pegawai->nama_pegawai }} - {{ $pegawai->gajiJabatan->jabatan ?? 'Tidak ada jabatan' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Periode --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Periode</label>
                            <div class="col-md-6">
                                <input type="month" name="periode" class="form-control"
                                       value="{{ now()->format('Y-m') }}" required>
                            </div>
                        </div>

                        {{-- Status Penggajian --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Status Penggajian</label>
                            <div class="col-md-6">
                                <select name="status_penggajian" class="form-control" required>
                                    <option value="belum_dibayar">Belum Dibayar</option>
                                    <option value="sudah_dibayar">Sudah Dibayar</option>
                                </select>
                            </div>
                        </div>

                        {{-- Button --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
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
