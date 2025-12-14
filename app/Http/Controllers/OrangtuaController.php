<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSiswa;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrangTuaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pegawai']);
    }

    public function index()
    {
        $orangtua = OrangTua::with('siswa')->orderBy('nama_ortu')->get();
        return view('orangtua.index', compact('orangtua'));
    }

    public function create()
    {
        return view('orangtua.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ortu' => 'required|string|max:255',
            'email' => 'required|email|unique:orangtua,email|unique:users,email',
            'nik' => 'required|string|max:16|unique:orangtua,nik',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'gender' => 'required|integer|in:1,2',
        ]);

        $orangtua = OrangTua::create($validated);

        // Auto create user account for orangtua
        \Log::info('About to create user account for orangtua: ' . $orangtua->email);
        $this->createUserAccount($orangtua);

        $password = session('orangtua_password');
        $message = "Data orang tua berhasil ditambahkan. Akun login telah dibuat dengan password: {$password}";

        return redirect()->route('admin.orangtua.index')
            ->with('success', $message)
            ->with('show_password', true)
            ->with('orangtua_email', $orangtua->email)
            ->with('orangtua_password', $password);
    }

    public function edit($id)
    {
        $orangtua = OrangTua::findOrFail($id);
        return view('orangtua.edit', compact('orangtua'));
    }

    public function update(Request $request, $id)
    {
        $orangtua = OrangTua::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:orangtua,nik,' . $orangtua->id,
            'nama_ortu' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'gender' => 'required|integer|in:1,2',
        ]);

        $orangtua->update($validated);

        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $orangtua = OrangTua::findOrFail($id);
        
        // Delete associated user
        if ($orangtua->user) {
            $orangtua->user->delete();
        }
        
        $orangtua->delete();

        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }

    /**
     * Create user account for orangtua
     */
    private function createUserAccount(OrangTua $orangtua)
    {
        // Check if user already exists
        if (User::where('email', $orangtua->email)->exists()) {
            \Log::info('User already exists for email: ' . $orangtua->email);
            return;
        }

        // Generate random password
        $password = Str::random(8);
        \Log::info('Generated password for ' . $orangtua->email . ': ' . $password);

        // Create user account
        $user = User::create([
            'name' => $orangtua->nama_ortu,
            'email' => $orangtua->email,
            'password' => Hash::make($password),
            'role' => 'orangtua',
            'password_changed_at' => null,
            'email_verified_at' => now(),
        ]);

        \Log::info('User created successfully: ' . $user->email . ' with ID: ' . $user->id);

        // Store password in session for display
        session(['orangtua_password' => $password]);
    }
}
