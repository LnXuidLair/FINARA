{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
.content-wrap, .main, .container-fluid {
    overflow-x: hidden !important;
    width: 100% !important;
    max-width: 100% !important;
}
#chartTren {
    max-width: 100% !important;
}
</style>

<div class="w-full overflow-hidden space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Dashboard Admin</h1>
            <p class="text-sm text-slate-500">Halo, {{ Auth::user()->name }} — ringkasan keuangan & sekolah.</p>
        </div>
    </div>

    <!-- ========================== -->
    <!-- ROW 1: Ringkasan Keuangan -->
    <!-- ========================== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Pemasukan -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-sm font-medium text-green-600">Pemasukan Bulan Ini</h3>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Hanya pembayaran siswa yang sudah lunas</p>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-sm font-medium text-red-600">Pengeluaran Bulan Ini</h3>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Biaya operasional sekolah</p>
        </div>

        <!-- Saldo -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-sm font-medium text-sky-600">Saldo / Kas</h3>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Pemasukan - (Pengeluaran + Gaji)</p>
        </div>

    </div>

    <!-- ========================== -->
    <!-- ROW 2: Tren Keuangan -->
    <!-- ========================== -->
    <div class="w-full overflow-x-hidden">
        <div class="h-64 w-full">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    <!-- ========================== -->
    <!-- ROW 3: Data Master & Presensi -->
    <!-- ========================== -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-slate-500">Jumlah Siswa</div>
            <div class="mt-2 text-2xl font-semibold">{{ $jumlahSiswa }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-slate-500">Jumlah Pegawai</div>
            <div class="mt-2 text-2xl font-semibold">{{ $jumlahPegawai }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-slate-500">Jumlah Orangtua</div>
            <div class="mt-2 text-2xl font-semibold">{{ $jumlahOrangtua }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-xs text-slate-500">Presensi Hari Ini</div>
            <div class="mt-2 text-xl font-semibold">
                {{ $hadir }} hadir • {{ $izin }} izin
            </div>
            <div class="text-xs text-slate-400">
                sakit: {{ $sakit }} • tidak hadir: {{ $tidakHadir }}
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function fillMonths(obj){
        const arr=[];
        for(let m=1;m<=12;m++){
            arr.push(Number(obj[m] ?? 0));
        }
        return arr;
    }

    const pemasukanData = fillMonths({!! json_encode($pemasukanBulanan) !!});
    const pengeluaranData = fillMonths({!! json_encode($pengeluaranBulanan) !!});
    const monthLabels = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

    // Ambil angka tertinggi supaya axis lebih pas
    const maxValue = Math.max(...pemasukanData, ...pengeluaranData) || 100;

    const ctxTren = document.getElementById('chartTren').getContext('2d');
    new Chart(ctxTren, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderWidth: 2,
                    tension: 0.35,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.06)',
                    fill: true,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderWidth: 2,
                    tension: 0.35,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239,68,68,0.06)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10
            },
            plugins: { 
                legend: { position: 'top' } 
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    suggestedMax: maxValue * 1.2, 
                    ticks: { 
                        callback: function(val){
                            return 'Rp ' + val.toLocaleString('id-ID');
                        } 
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                }
            }
        }
    });
</script>
@endpush
