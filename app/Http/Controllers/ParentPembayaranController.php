<?php

namespace App\Http\Controllers;

use App\Models\TagihanSiswa;
use App\Models\PembayaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPembayaranController extends Controller
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
        
        // Get all tagihan for all students of this parent
        $tagihanList = TagihanSiswa::with(['siswa', 'pembayaranSiswa'])
            ->whereIn('siswa_id', $siswaIds)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate total for each tagihan - ignore all existing payments
        foreach ($tagihanList as $tagihan) {
            $tagihan->sisa = $tagihan->nominal;
            $tagihan->status = 'belum lunas';
        }
        
        return view('parent.pembayaran.index', compact('orangtua', 'siswaList', 'tagihanList'));
    }
}
