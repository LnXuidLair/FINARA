<?php

namespace App\Http\Controllers;

use App\Models\GajiJabatan;
use Illuminate\Http\Request;

class GajiJabatanController extends Controller
{
    public function index()
    {
        $gajiJabatans = GajiJabatan::latest()->get();
        return view('gajijabatan.index', compact('gajiJabatans'));
    }

    public function create()
    {
        return view('gajijabatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jabatan' => 'required|string|max:255',
            'gaji_perhari' => 'required|integer|min:0',
        ]);

        GajiJabatan::create($validated);

        return redirect()->route('gajijabatan.index')
            ->with('success', 'Data gaji jabatan berhasil disimpan.');
    }

    public function edit($id)
    {
        $gajiJabatan = GajiJabatan::findOrFail($id);
        return view('gajijabatan.edit', compact('gajiJabatan'));
    }

    public function update(Request $request, $id)
    {
        $gajiJabatan = GajiJabatan::findOrFail($id);

        $validated = $request->validate([
            'jabatan' => 'required|string|max:255',
            'gaji_perhari' => 'required|integer|min:0',
        ]);

        $gajiJabatan->update($validated);

        return redirect()->route('gajijabatan.index')
            ->with('success', 'Data gaji jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gajiJabatan = GajiJabatan::findOrFail($id);
        $gajiJabatan->delete();

        return redirect()->route('gajijabatan.index')
            ->with('success', 'Data gaji jabatan berhasil dihapus.');
    }
}
