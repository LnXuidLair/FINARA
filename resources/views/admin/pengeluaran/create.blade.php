@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Pengeluaran</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/pengeluaran') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori" id="kategori" class="form-control" required>
                                        <option value="">- Pilih -</option>
                                        <option value="manual" {{ old('kategori') === 'manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="penggajian" {{ old('kategori') === 'penggajian' ? 'selected' : '' }}>Penggajian</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" id="wrapPenggajian" style="display:none;">
                                <div class="form-group">
                                    <label>Penggajian</label>
                                    <select name="penggajian_id" id="penggajian_id" class="form-control">
                                        <option value="">- Pilih Penggajian -</option>
                                        @foreach($penggajianList as $pg)
                                            <option value="{{ $pg->id }}" data-total="{{ (int) $pg->total_gaji }}" {{ (string)old('penggajian_id') === (string)$pg->id ? 'selected' : '' }}>
                                                #{{ $pg->id }} - {{ $pg->pegawai->nama_pegawai ?? 'Pegawai' }} ({{ $pg->periode }}) - Rp {{ number_format((int)$pg->total_gaji, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6"></div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Upload Invoice</label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>COA Debit</label>
                                    <select name="coa_debit_id" class="form-control" required>
                                        <option value="">- Pilih COA -</option>
                                        @foreach($coaList as $coa)
                                            <option value="{{ $coa->id }}" {{ (string)old('coa_debit_id') === (string)$coa->id ? 'selected' : '' }}>
                                                {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>COA Kredit</label>
                                    <select name="coa_kredit_id" class="form-control" required>
                                        <option value="">- Pilih COA -</option>
                                        @foreach($coaList as $coa)
                                            <option value="{{ $coa->id }}" {{ (string)old('coa_kredit_id') === (string)$coa->id ? 'selected' : '' }}>
                                                {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    function togglePenggajian() {
        var kategori = document.getElementById('kategori');
        var wrap = document.getElementById('wrapPenggajian');
        var bukti = document.getElementById('bukti_pembayaran');
        if (!kategori || !wrap) return;

        if (kategori.value === 'penggajian') {
            wrap.style.display = 'block';
            if (bukti) bukti.required = false;
        } else {
            wrap.style.display = 'none';
            if (bukti) bukti.required = (kategori.value === 'manual');
        }
    }

    function syncJumlahFromPenggajian() {
        var kategori = document.getElementById('kategori');
        var pg = document.getElementById('penggajian_id');
        var jumlah = document.getElementById('jumlah');
        if (!kategori || !pg || !jumlah) return;

        if (kategori.value !== 'penggajian') return;
        var opt = pg.options[pg.selectedIndex];
        if (!opt) return;
        var total = opt.getAttribute('data-total');
        if (total) jumlah.value = total;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var kategori = document.getElementById('kategori');
        var pg = document.getElementById('penggajian_id');

        togglePenggajian();

        if (kategori) {
            kategori.addEventListener('change', function () {
                togglePenggajian();
                syncJumlahFromPenggajian();
            });
        }

        if (pg) {
            pg.addEventListener('change', syncJumlahFromPenggajian);
        }

        syncJumlahFromPenggajian();
    });
})();
</script>
@endsection
