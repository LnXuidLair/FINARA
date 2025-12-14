<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pengeluaran;
use App\Models\Penggajian;
use App\Models\PembayaranSiswa;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\Presensi;

class DashboardController extends Controller
{
    // =========================================
    // DASHBOARD ADMIN (SUDAH ADA)
    // =========================================
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // =============================
        // ROW 1 - STATISTIK KEUANGAN
        // =============================

        $totalPemasukan = PembayaranSiswa::whereMonth('tanggal_bayar', $bulanIni)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah');

        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->sum('nominal');

        $totalGaji = Penggajian::whereMonth('tanggal', $bulanIni)
            ->sum('total_gaji');

        $saldo = $totalPemasukan - ($totalPengeluaran + $totalGaji);

        // =============================
        // ROW 2 - GRAFIK TAHUNAN
        // =============================
        $pemasukanBulanan = PembayaranSiswa::selectRaw("MONTH(tanggal_bayar) AS bulan, SUM(jumlah) AS total")
            ->whereYear('tanggal_bayar', $tahunIni)
            ->where('status_pembayaran', 'lunas')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pengeluaranBulanan = Pengeluaran::selectRaw("MONTH(tanggal) AS bulan, SUM(nominal) AS total")
            ->whereYear('tanggal', $tahunIni)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');


        // =============================
        // ROW 3 - DATA MASTER & PRESENSI
        // =============================
        $jumlahPegawai = Pegawai::count();
        $jumlahSiswa = Siswa::count();
        $jumlahOrangtua = Orangtua::count();

        $presensiHariIni = Presensi::whereDate('tanggal', Carbon::today())->get();

        $hadir = $presensiHariIni->where('status', 'hadir')->count();
        $izin = $presensiHariIni->where('status', 'izin')->count();
        $sakit = $presensiHariIni->where('status', 'sakit')->count();
        $tidakHadir = $presensiHariIni->where('status', 'tidak hadir')->count();

        // =============================
        // VIEW
        // =============================
        return view('dashboard.admin', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'totalGaji',
            'saldo',
            'pemasukanBulanan',
            'pengeluaranBulanan',
            'jumlahPegawai',
            'jumlahSiswa',
            'jumlahOrangtua',
            'hadir',
            'izin',
            'sakit',
            'tidakHadir'
        ));
    }

    // =========================================
    // DASHBOARD ORANGTUA (BARU DITAMBAHKAN)
    // =========================================
        public function orangtuaDashboard()
    {
        $user = auth()->user();

        // Hanya role orangtua
        if ($user->role !== 'orangtua') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data orangtua berdasarkan user
        $ortu = \App\Models\Orangtua::where('id_user', $user->id)->first();

        if (!$ortu) {
            return "Data orangtua belum diisi oleh admin.";
        }

        // Ambil semua anak
        $anak = $ortu->siswa;

        // Ambil semua tagihan berdasarkan anak
        $tagihan = \App\Models\PembayaranSiswa::whereIn('id_siswa', $anak->pluck('id'))
                    ->orderBy('tanggal_bayar', 'desc')
                    ->get();

        return view('dashboard.orangtua', compact('anak', 'tagihan'));
    }

}
