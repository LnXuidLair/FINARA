@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Cetak Akun Orangtua</h5>
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <div class="mb-3">Silakan simpan informasi akun berikut.</div>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 220px;">Nama Orangtua</th>
                    <td>{{ $credentials['nama_ortu'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $credentials['email'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Password Sementara</th>
                    <td>{{ $credentials['password'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak</button>
            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Selesai</a>
        </div>
    </div>
</div>
@endsection
