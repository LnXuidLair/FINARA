<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaInfoController extends Controller
{
    public function index(Request $request)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->firstOrFail();

        $siswaList = $orangtua->siswa()->orderBy('nama_siswa')->get();

        $selectedSiswaId = $request->query('siswa_id');
        $selectedSiswa = null;

        if ($selectedSiswaId) {
            $selectedSiswa = $orangtua->siswa()->where('id', $selectedSiswaId)->first();
        }

        if (!$selectedSiswa) {
            $selectedSiswa = $siswaList->first();
        }

        $riwayatPembayaran = collect();
        $totalTagihanBerjalan = 0;
        $totalDibayar = 0;

        if ($selectedSiswa) {
            $riwayatPembayaran = PembayaranSiswa::where('id_siswa', $selectedSiswa->id)
                ->orderBy('tanggal_bayar', 'desc')
                ->get();

            $totalTagihanBerjalan = (int) PembayaranSiswa::where('id_siswa', $selectedSiswa->id)
                ->where('status_pembayaran', 'pending')
                ->sum('jumlah');

            $totalDibayar = (int) PembayaranSiswa::where('id_siswa', $selectedSiswa->id)
                ->where('status_pembayaran', 'lunas')
                ->sum('jumlah');
        }

        $tahunAjaran = now()->month >= 7
            ? now()->format('Y') . '/' . now()->addYear()->format('Y')
            : now()->subYear()->format('Y') . '/' . now()->format('Y');

        return view('parent.informasi-siswa.index', [
            'orangtua' => $orangtua,
            'siswaList' => $siswaList,
            'selectedSiswa' => $selectedSiswa,
            'riwayatPembayaran' => $riwayatPembayaran,
            'totalTagihanBerjalan' => $totalTagihanBerjalan,
            'totalDibayar' => $totalDibayar,
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    public function detail($id)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->firstOrFail();

        $siswa = $orangtua->siswa()->where('id', $id)->firstOrFail();

        $riwayatPembayaran = PembayaranSiswa::where('id_siswa', $siswa->id)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $totalTagihanBerjalan = (int) PembayaranSiswa::where('id_siswa', $siswa->id)
            ->where('status_pembayaran', 'pending')
            ->sum('jumlah');

        $totalDibayar = (int) PembayaranSiswa::where('id_siswa', $siswa->id)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah');

        $tahunAjaran = now()->month >= 7
            ? now()->format('Y') . '/' . now()->addYear()->format('Y')
            : now()->subYear()->format('Y') . '/' . now()->format('Y');

        return view('parent.informasi-siswa.index', [
            'orangtua' => $orangtua,
            'siswaList' => $orangtua->siswa()->orderBy('nama_siswa')->get(),
            'selectedSiswa' => $siswa,
            'riwayatPembayaran' => $riwayatPembayaran,
            'totalTagihanBerjalan' => $totalTagihanBerjalan,
            'totalDibayar' => $totalDibayar,
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    public function fetchRiwayat($id)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->firstOrFail();
        $siswa = $orangtua->siswa()->where('id', $id)->firstOrFail();

        $riwayatPembayaran = PembayaranSiswa::where('id_siswa', $siswa->id)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        return response()->json([
            'siswa_id' => $siswa->id,
            'riwayat' => $riwayatPembayaran,
        ]);
    }

    public function fetchTagihan($id)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->firstOrFail();
        $siswa = $orangtua->siswa()->where('id', $id)->firstOrFail();

        $totalTagihanBerjalan = (int) PembayaranSiswa::where('id_siswa', $siswa->id)
            ->where('status_pembayaran', 'pending')
            ->sum('jumlah');

        return response()->json([
            'siswa_id' => $siswa->id,
            'total_tagihan' => $totalTagihanBerjalan,
        ]);
    }
}
