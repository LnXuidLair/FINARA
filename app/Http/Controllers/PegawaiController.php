<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function verifyStaff(Request $request)
    {
        $pegawai = Pegawai::where('nip', $request->nip)->first();

        if (!$pegawai) {
            return response()->json(['valid' => false]);
        }

        return response()->json([
            'valid' => true,
            'verified' => $pegawai->is_verified
        ]);
    }
}
