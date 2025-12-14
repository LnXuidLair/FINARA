@extends('layouts.app')

@section('content')

<div class="max-w-full space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard Orangtua</h1>
        <p class="text-slate-500">Halo, {{ Auth::user()->name }}.</p>
    </div>

    {{-- DATA ANAK --}}
    <h2 class="text-lg font-semibold">Data Anak</h2>

    @if($anak->isEmpty())
        <p class="text-slate-500 text-sm">Belum ada data anak yang terhubung.</p>
    @else
        <div class="space-y-3">
            @foreach($anak as $a)
                <div class="p-4 bg-white shadow rounded">
                    <div class="font-semibold text-slate-800">{{ $a->nama }}</div>
                    <div class="text-sm text-slate-500">Kelas: {{ $a->kelas }}</div>

                    <a href="{{ route('orangtua.tagihan.detail', $a->id) }}"
                       class="block mt-2 text-sky-600 text-sm hover:underline">
                        Lihat Tagihan →
                    </a>
                </div>
            @endforeach
        </div>
    @endif


    {{-- TAGIHAN --}}
    <h2 class="text-lg font-semibold mt-6">Tagihan Siswa</h2>

    @if($tagihan->isEmpty())
        <p class="text-slate-500 text-sm">Belum ada tagihan untuk saat ini.</p>
    @else
        <div class="space-y-3">
            @foreach ($tagihan as $t)
                <div class="bg-white p-4 shadow rounded">
                    
                    {{-- Nominal --}}
                    <div class="font-bold text-slate-800">
                        Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                    </div>

                    {{-- Keterangan --}}
                    <div class="text-sm text-slate-500">
                        {{ $t->keterangan }}
                    </div>

                    {{-- Status --}}
                    <div class="text-sm mt-1">
                        Status:
                        <span class="font-semibold {{ $t->status == 'lunas' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($t->status) }}
                        </span>
                    </div>

                    {{-- Tombol Bayar --}}
                    @if($t->status != 'lunas')
                        <a href="{{ route('orangtua.bayar', $t->id) }}"
                           class="inline-block mt-3 bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-700">
                            Bayar Sekarang
                        </a>
                    @endif

                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection
