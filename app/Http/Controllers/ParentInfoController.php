<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'parent']);
    }

    public function index(Request $request)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->firstOrFail();

        $siswaList = $orangtua->siswa()->orderBy('nama_siswa')->get();

        $selectedSiswaId = $request->query('siswa_id');
        $siswa = null;

        if ($selectedSiswaId) {
            $siswa = $orangtua->siswa()->where('id', $selectedSiswaId)->first();
        }

        if (!$siswa) {
            $siswa = $siswaList->first();
        }

        $riwayat = collect();
        $totalTagihan = 0;

        if ($siswa) {
            $riwayat = PembayaranSiswa::where('id_siswa', $siswa->id)
                ->orderBy('tanggal_bayar', 'desc')
                ->get();

            $totalTagihan = (int) PembayaranSiswa::where('id_siswa', $siswa->id)
                ->where('status_pembayaran', 'pending')
                ->sum('jumlah');
        }

        $tahunAjaran = now()->month >= 7
            ? now()->format('Y') . '/' . now()->addYear()->format('Y')
            : now()->subYear()->format('Y') . '/' . now()->format('Y');

        return view('parent.informasi-siswa', [
            'orangtua' => $orangtua,
            'siswaList' => $siswaList,
            'siswa' => $siswa,
            'tahunAjaran' => $tahunAjaran,
            'totalTagihan' => $totalTagihan,
            'riwayat' => $riwayat,
        ]);
    }
}
