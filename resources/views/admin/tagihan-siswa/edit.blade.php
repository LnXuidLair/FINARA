@extends('layouts.app')

@section('title', 'Edit Tagihan Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Tagihan Siswa</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.tagihan-siswa.update', $tagihan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                    <select name="siswa_id" id="siswa_id" class="form-select" required>
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach($siswa as $s)
                                            <option value="{{ $s->id }}" 
                                                    {{ old('siswa_id', $tagihan->siswa_id) == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama }} ({{ $s->nis }}) - {{ $s->orangtua->nama ?? 'Tidak ada orang tua' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_tagihan" class="form-label">Jenis Tagihan <span class="text-danger">*</span></label>
                                    <select name="jenis_tagihan" id="jenis_tagihan" class="form-select" required>
                                        <option value="">-- Pilih Jenis Tagihan --</option>
                                        <option value="SPP" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'SPP' ? 'selected' : '' }}>SPP</option>
                                        <option value="Uang Kegiatan" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Uang Kegiatan' ? 'selected' : '' }}>Uang Kegiatan</option>
                                        <option value="Daftar Ulang" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Daftar Ulang' ? 'selected' : '' }}>Daftar Ulang</option>
                                        <option value="Uang Buku" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Uang Buku' ? 'selected' : '' }}>Uang Buku</option>
                                        <option value="Uang Seragam" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Uang Seragam' ? 'selected' : '' }}>Uang Seragam</option>
                                        <option value="Lainnya" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control" 
                                           value="{{ old('jumlah', $tagihan->jumlah) }}" required min="1000" step="1000">
                                    <small class="text-muted">Minimal Rp 1.000</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" 
                                           class="form-control" value="{{ old('tanggal_jatuh_tempo', $tagihan->tanggal_jatuh_tempo) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="periode" class="form-label">Periode</label>
                                    <input type="text" name="periode" id="periode" class="form-control" 
                                           value="{{ old('periode', $tagihan->periode) }}" placeholder="Contoh: Januari 2025">
                                    <small class="text-muted">Opsional</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
                                    <small class="text-muted">Opsional</small>
                                </div>
                            </div>
                        </div>

                        @if($tagihan->pembayaranSiswa->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        <strong>Perhatian:</strong> Tagihan ini sudah memiliki pembayaran. 
                                        Mengubah jumlah tagihan akan mempengaruhi perhitungan status pembayaran.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.tagihan-siswa.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
