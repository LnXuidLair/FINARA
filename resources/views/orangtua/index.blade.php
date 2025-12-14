@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Master Data Orang Tua</h5>
        <a href="{{ route('admin.orangtua.create') }}" class="btn btn-primary">Tambah Orang Tua</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('show_password') && session('orangtua_email') && session('orangtua_password'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <h5><i class="fas fa-info-circle"></i> Akun Login Orang Tua</h5>
                <p><strong>Email:</strong> {{ session('orangtua_email') }}</p>
                <p><strong>Password Awal:</strong> <code>{{ session('orangtua_password') }}</code></p>
                <p class="mb-0"><small>Catatan: Silakan informasikan kepada orang tua untuk login pertama kali dan mengganti password.</small></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIK</th>
                        <th>Pekerjaan</th>
                        <th>No. Telp</th>
                        <th>Alamat</th>
                        <th>Jumlah Anak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orangtua as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_ortu }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->pekerjaan }}</td>
                            <td>{{ $item->no_telp ?: '-' }}</td>
                            <td>{{ $item->alamat ?: '-' }}</td>
                            <td>{{ $item->siswa->count() }}</td>
                            <td>
                                <a href="{{ route('admin.orangtua.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.orangtua.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
