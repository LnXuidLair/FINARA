<?php

namespace App\Http\Controllers;

use App\Models\GajiJabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $pegawai = Pegawai::all();
        return view('dashboard.pegawai', compact('pegawai'));
    }

    public function index()
    {
        $pegawai = Pegawai::all();
        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        // Generate automatic ID
        $lastPegawai = Pegawai::orderBy('id', 'desc')->first();
        $nextID = $lastPegawai ? $lastPegawai->id + 1 : 1;
        
        $gajiJabatans = GajiJabatan::orderBy('jabatan')->get();
        return view('pegawai.create', compact('gajiJabatans', 'nextID'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:255|unique:pegawai,nip',
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string|max:255',
            'id_gaji_jabatan' => 'required|exists:gaji_jabatan,id',
        ]);

        // Generate automatic ID
        $lastPegawai = Pegawai::orderBy('id', 'desc')->first();
        $nextID = $lastPegawai ? $lastPegawai->id + 1 : 1;

        // Generate username and password automatically
        $username = strtolower(str_replace(' ', '', $validated['nama_pegawai'])) . $nextID;
        $password = $validated['nip'];

        $gajiJabatan = GajiJabatan::findOrFail($validated['id_gaji_jabatan']);

        // Create pegawai
        $pegawai = Pegawai::create([
            'nip' => $validated['nip'],
            'nama_pegawai' => $validated['nama_pegawai'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'id_gaji_jabatan' => $gajiJabatan->id,
            'jabatan' => $gajiJabatan->jabatan,
        ]);

        // Create user account for pegawai
        User::create([
            'name' => $validated['nama_pegawai'],
            'email' => $validated['email'],
            'username' => $username,
            'password' => bcrypt($password),
            'role' => 'pegawai',
        ]);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil disimpan.');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $gajiJabatans = GajiJabatan::orderBy('jabatan')->get();
        return view('pegawai.edit', compact('pegawai', 'gajiJabatans'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|string|max:255|unique:pegawai,nip,' . $pegawai->id,
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string|max:255',
            'id_gaji_jabatan' => 'required|exists:gaji_jabatan,id',
        ]);

        $gajiJabatan = GajiJabatan::findOrFail($validated['id_gaji_jabatan']);

        $pegawai->update([
            'nip' => $validated['nip'],
            'nama_pegawai' => $validated['nama_pegawai'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'id_gaji_jabatan' => $gajiJabatan->id,
            'jabatan' => $gajiJabatan->jabatan,
        ]);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

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
