<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\Orangtua;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /* ===================== ADMIN ===================== */
    public function registerAdmin(Request $request)
    {
        if (User::where('role', 'admin')->exists()) {
            return back()->withErrors(['error' => 'Admin sudah ada']);
        }

        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin'
        ]);

        return redirect()->route('login.admin');
    }

    /* ===================== STAFF ===================== */
    public function registerStaff(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'password' => 'required|confirmed'
        ]);

        $pegawai = Pegawai::where('nip', $request->nip)->first();

        if (!$pegawai) {
            return back()->withErrors(['nip' => 'NIP tidak ditemukan']);
        }

        if ($pegawai->is_verified) {
            return back()->withErrors(['nip' => 'Pegawai sudah aktif']);
        }

        User::create([
            'name' => $request->name,
            'email' => $pegawai->email,
            'password' => bcrypt($request->password),
            'role' => 'pegawai'
        ]);

        $pegawai->update(['is_verified' => true]);

        return redirect()->route('login.staff');
    }

    /* ===================== ORANGTUA ===================== */
    public function registerParent(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return back()->withErrors(['nisn' => 'NISN tidak terdaftar']);
        }

        if ($siswa->id_orangtua !== null) {
            return back()->withErrors(['nisn' => 'Siswa sudah memiliki akun orangtua']);
        }

        $ortu = Orangtua::create([
            'nik' => 'TEMP-' . rand(100000, 999999),
            'nama_ortu' => $request->name,
            'pekerjaan' => '-',
            'gender' => 1
        ]);

        $siswa->update([
            'id_orangtua' => $ortu->id
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'orangtua'
        ]);

        return redirect()->route('login.parent');
    }
}
