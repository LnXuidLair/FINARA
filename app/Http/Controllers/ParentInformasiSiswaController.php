<?php

namespace App\Http\Controllers;

use App\Models\TagihanSiswa;
use App\Models\PembayaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentInformasiSiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'parent']);
    }

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the parent data by email
        $orangtua = \App\Models\OrangTua::where('email', $user->email)->firstOrFail();
        
        // Get all students (children) of the parent
        $siswaList = $orangtua->siswa()->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id');
        
        // Get payment history for all students
        $paymentHistory = PembayaranSiswa::with(['siswa'])
            ->whereHas('siswa', function($query) use ($siswaIds) {
                $query->whereIn('id', $siswaIds);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
        
        // Get tagihan summary for each student
        $tagihanSummary = [];
        foreach ($siswaList as $siswa) {
            $totalTagihan = TagihanSiswa::where('siswa_id', $siswa->id)->sum('nominal');
            $totalDibayar = PembayaranSiswa::whereHas('siswa', function($query) use ($siswa) {
                $query->where('id', $siswa->id);
            })->sum('jumlah');
            
            $tagihanSummary[$siswa->id] = [
                'total_tagihan' => $totalTagihan,
                'total_dibayar' => $totalDibayar,
                'sisa' => $totalTagihan - $totalDibayar
            ];
        }
        
        return view('parent.informasi-siswa.index', compact('orangtua', 'siswaList', 'paymentHistory', 'tagihanSummary'));
    }
}
