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

                        <div class="form-group row mb-3">
                            <label for="id_pegawai" class="col-md-4 col-form-label text-md-right">Pegawai</label>
                            <div class="col-md-6">
                                <select name="id_pegawai" id="id_pegawai" class="form-control @error('id_pegawai') is-invalid @enderror" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}" data-gaji="{{ $pegawai->gajiJabatan->gaji_perhari ?? 0 }}" {{ old('id_pegawai') == $pegawai->id ? 'selected' : '' }}>
                                            {{ $pegawai->nama }} - {{ $pegawai->jabatan }}
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
                            <label for="periode" class="col-md-4 col-form-label text-md-right">Periode</label>
                            <div class="col-md-6">
                                <input id="periode" type="month" class="form-control @error('periode') is-invalid @enderror" name="periode" value="{{ old('periode', now()->format('Y-m')) }}" required>
                                @error('periode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="status_penggajian" class="col-md-4 col-form-label text-md-right">Status Penggajian</label>
                            <div class="col-md-6">
                                <select name="status_penggajian" id="status_penggajian" class="form-control @error('status_penggajian') is-invalid @enderror" required>
                                    <option value="belum_dibayar" {{ old('status_penggajian') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                    <option value="sudah_dibayar" {{ old('status_penggajian') == 'sudah_dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                                </select>
                                @error('status_penggajian')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Gaji per Hari</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="gaji_perhari" value="Rp 0" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Jumlah Kehadiran</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="jumlah_kehadiran" value="0 hari" readonly>
                                <small class="text-muted">Jumlah kehadiran akan dihitung otomatis berdasarkan periode yang dipilih</small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Total Gaji</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="total_gaji" value="Rp 0" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const idPegawai = document.getElementById('id_pegawai');
        const periode = document.getElementById('periode');
        const gajiPerHari = document.getElementById('gaji_perhari');
        const jumlahKehadiran = document.getElementById('jumlah_kehadiran');
        const totalGaji = document.getElementById('total_gaji');

        // Format Rupiah
        function formatRupiah(angka) {
            const numberString = angka.toString();
            const sisa = numberString.length % 3;
            let rupiah = numberString.substr(0, sisa);
            const ribuan = numberString.substr(sisa).match(/\d{3}/g);
            
            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            return 'Rp ' + rupiah;
        }

        // Update gaji per hari when employee is selected
        idPegawai.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const gaji = selectedOption.getAttribute('data-gaji') || 0;
            gajiPerHari.value = formatRupiah(gaji);
            hitungTotalGaji();
            
            // Fetch kehadiran if periode is already selected
            if (periode.value) {
                fetchKehadiran(this.value, periode.value);
            }
        });

        // Update kehadiran when periode changes
        periode.addEventListener('change', function() {
            if (idPegawai.value) {
                fetchKehadiran(idPegawai.value, this.value);
            }
        });

        // Fetch kehadiran from server
        function fetchKehadiran(pegawaiId, periode) {
            fetch(`/api/kehadiran?pegawai_id=${pegawaiId}&periode=${periode}`)
                .then(response => response.json())
                .then(data => {
                    const jumlah = (data?.jumlah_kehadiran ?? data?.data?.jumlah_kehadiran ?? 0);
                    jumlahKehadiran.value = `${jumlah} hari`;
                    hitungTotalGaji();
                })
                .catch(error => {
                    console.error('Error:', error);
                    jumlahKehadiran.value = '0 hari';
                    hitungTotalGaji();
                });
        }

        // Calculate total gaji
        function hitungTotalGaji() {
            const gaji = idPegawai.selectedOptions[0]?.getAttribute('data-gaji') || 0;
            const kehadiran = parseInt(jumlahKehadiran.value) || 0;
            const total = gaji * kehadiran;
            totalGaji.value = formatRupiah(total);
        }

        // Initial calculation if there's a selected employee
        if (idPegawai.value) {
            const selectedOption = idPegawai.options[idPegawai.selectedIndex];
            const gaji = selectedOption.getAttribute('data-gaji') || 0;
            gajiPerHari.value = formatRupiah(gaji);
            hitungTotalGaji();
        }
    });
</script>
@endpush
@endsection
