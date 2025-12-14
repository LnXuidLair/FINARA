@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Penggajian</span>
                    <a href="{{ route('admin.penggajian.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Penggajian
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jabatan</th>
                                    <th>Periode</th>
                                    <th>Jumlah Kehadiran</th>
                                    <th>Gaji per Hari</th>
                                    <th>Total Gaji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penggajian as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->pegawai->nama ?? '-' }}</td>
                                        <td>{{ $item->pegawai->gajiJabatan->jabatan ?? ($item->pegawai->jabatan ?? '-') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                                        <td class="text-center">{{ $item->jumlah_kehadiran }} hari</td>
                                        <td class="text-right">@rupiah($item->gaji_perhari)</td>
                                        <td class="text-right">@rupiah($item->total_gaji)</td>
                                        <td>
                                            @if($item->status_penggajian === 'sudah_dibayar')
                                                <span class="badge bg-success">Sudah Dibayar</span>
                                            @else
                                                <span class="badge bg-warning">Belum Dibayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.penggajian.cetak', $item->id) }}" class="btn btn-sm btn-info" target="_blank" title="Cetak Slip Gaji">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <form action="{{ route('admin.penggajian.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data penggajian</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    // Format Rupiah
    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }

    // Format input rupiah
    document.querySelectorAll('.rupiah').forEach(function(element) {
        element.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
</script>
@endpush
@endsection
