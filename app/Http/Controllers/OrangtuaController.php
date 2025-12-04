<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class OrangtuaController extends Controller
{
    public function verifyNisn(Request $request)
    {
        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return response()->json(['valid' => false]);
        }

        return response()->json([
            'valid' => true,
            'taken' => $siswa->id_orangtua !== null
        ]);
    }
}
