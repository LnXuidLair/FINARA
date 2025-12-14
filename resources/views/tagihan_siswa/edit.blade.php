@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Tagihan Siswa</h5>
        <a href="{{ route('tagihan_siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('tagihan_siswa.update', $tagihan->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <select name="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror" required>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id', $tagihan->siswa_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} ({{ $s->kelas }}) - {{ $s->orangtua->nama_ortu ?? 'Belum ada orang tua' }}
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Tagihan</label>
                    <select name="jenis_tagihan" class="form-select @error('jenis_tagihan') is-invalid @enderror" required>
                        @foreach(['SPP','Uang Gedung','Seragam','Kegiatan','Lainnya'] as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_tagihan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Periode (Bulan/Tahun)</label>
                    <input type="text" name="periode" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode', $tagihan->periode) }}" required>
                    @error('periode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nominal</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" value="{{ old('nominal', $tagihan->nominal) }}" min="1" required>
                        @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="unpaid" {{ old('status', $tagihan->status) === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="partial" {{ old('status', $tagihan->status) === 'partial' ? 'selected' : '' }}>Sebagian</option>
                    <option value="paid" {{ old('status', $tagihan->status) === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
