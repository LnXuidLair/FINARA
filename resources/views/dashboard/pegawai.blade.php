@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard Pegawai</h3>
                </div>
                <div class="card-body">
                    <h4>Selamat datang di Dashboard Pegawai!</h4>
                    <p>Ini adalah halaman dashboard untuk pegawai.</p>
                    
                    @if(isset($pegawai) && $pegawai->count() > 0)
                        <div class="mt-4">
                            <h5>Data Pegawai:</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pegawai as $p)
                                        <tr>
                                            <td>{{ $p->nip }}</td>
                                            <td>{{ $p->nama_pegawai }}</td>
                                            <td>{{ $p->jabatan }}</td>
                                            <td>{{ $p->email }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <strong>Informasi:</strong> Tidak ada data pegawai yang tersedia.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
