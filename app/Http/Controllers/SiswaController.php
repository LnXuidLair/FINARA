<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pegawai']);
    }

    public function index()
    {
        $siswa = Siswa::with('orangtua')
            ->orderBy('nama_siswa')
            ->paginate(15);

        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        $orangtuaList = OrangTua::orderBy('nama_ortu')->pluck('nama_ortu', 'id');
        return view('siswa.create', compact('orangtuaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|max:10|unique:siswa,nisn',
            'kelas' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'id_orangtua' => 'required|exists:orangtua,id',
        ]);

        Siswa::create($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::with('orangtua')->findOrFail($id);
        $orangtuaList = OrangTua::orderBy('nama_ortu')->pluck('nama_ortu', 'id');
        return view('siswa.edit', compact('siswa', 'orangtuaList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|max:10|unique:siswa,nisn,' . $siswa->id,
            'kelas' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'id_orangtua' => 'required|exists:orangtua,id',
        ]);

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
