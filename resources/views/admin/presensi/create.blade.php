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

                        {{-- Pegawai --}}
                        <div class="mb-3">
                            <label>Pegawai</label>
                            <select name="id_pegawai" class="form-control" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}">
                                        {{ $pegawai->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
