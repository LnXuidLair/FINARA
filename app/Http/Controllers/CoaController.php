<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Http\Requests\StoreCoaRequest;
use App\Http\Requests\UpdateCoaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tabel()
    {
        // Ambil semua data COA tanpa filter 'header_akun'
        $coa = Coa::all();  // Mengambil semua data COA
        return view('coa.index', [
            'coa' => $coa,
            'title' => 'Contoh M2',
            'nama' => 'Farel Prayoga'
        ]);
    }

    public function index()
    {
        // Ambil data COA
        $coa = Coa::orderBy('kode_akun')->get(); // Mengambil semua data COA tanpa filter perusahaan
        return view('coa.index', compact('coa'));
    }

    /**
     * Fetch all COA data for Ajax requests.
     */
    public function fetchAll()
    {
        $coas = Coa::all(); // Ambil semua data COA
        if ($coas->isEmpty()) {
            return '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }

        $output = view('coa.partials.coa_table', ['coas' => $coas])->render();
        return response()->json(['html' => $output]);
    }

    /**
     * Fetch COA data in JSON format for API or Ajax requests.
     */
    public function fetchCoa()
    {
        $coa = Coa::all(); // Ambil semua data COA
        return response()->json(['coa' => $coa]);
    }

    /**
     * Display a specific COA record for API view.
     */
    public function view($id)
    {
        $coa = Coa::findOrFail($id);
        return response()->json($coa);
    }

    /**
     * Show the form for creating a new COA.
     */
    public function create()
    {
        $nextId = null;

        try {
            $row = DB::selectOne(
                "SELECT AUTO_INCREMENT as next_id FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?",
                ['coa']
            );
            $nextId = $row->next_id ?? null;
        } catch (\Throwable $e) {
            $nextId = null;
        }

        if (empty($nextId)) {
            $nextId = ((int) Coa::max('id')) + 1;
        }

        return view('coa.create', compact('nextId'));
    }

    /**
     * Store a newly created COA in storage.
     */
    public function store(StoreCoaRequest $request)
    {
        $validated = $request->validated();

        Coa::create($validated);

        return redirect()->route('coa.index')->with('success', 'COA berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified COA.
     */
    public function edit(Coa $coa)
    {
        return view('coa.edit', compact('coa'));
    }

    /**
     * Update the specified COA in storage.
     */
    public function update(UpdateCoaRequest $request, Coa $coa)
    {
        $validated = $request->validated();
        $coa->update($validated);

        return redirect()->route('coa.index')->with('success', 'Data COA berhasil diperbarui.');
    }

    /**
     * Remove the specified COA from storage.
     */
    public function destroy(Coa $coa)
    {
        $coa->delete();

        return redirect()->route('coa.index')->with('success', 'COA berhasil dihapus.');
    }
}
