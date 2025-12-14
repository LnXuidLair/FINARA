<?php

namespace App\Http\Controllers;

use App\Models\Bahanbaku;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBahanbakuRequest;
use App\Http\Requests\UpdateBahanbakuRequest;

class BahanbakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanbaku = Bahanbaku::all();
        return view('bahanbaku.index', compact('bahanbaku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {     
        return view('bahanbaku.create', ['kode_bahanbaku' => Bahanbaku::generateKodeBahanbaku()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBahanbakuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBahanbakuRequest $request)
    {
        // Validasi data
        $validatedData = $request->validate([

            'nama_bahanbaku' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Generate kode bahan baku
        $kodeBahanbaku = Bahanbaku::generateKodeBahanbaku();

        // Simpan ke database
        Bahanbaku::create([
            'kode_bahanbaku' => $kodeBahanbaku,
            'nama_bahanbaku' => $validatedData['nama_bahanbaku'],
            'satuan' => $validatedData['satuan'],
            'jumlah' => $validatedData['jumlah'],
        ]);

        return redirect()->route('bahanbaku.index')->with('success', 'Data bahan baku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bahanbaku  $bahanbaku
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Bahanbaku::find($id); // Mendapatkan data dari database
        return view('bahanbaku.show', compact('item')); // Mengirim $item ke view
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bahanbaku  $bahanbaku
     * @return \Illuminate\Http\Response
     */
    public function edit(Bahanbaku $bahanbaku)
    {
        return view('bahanbaku.edit', compact('bahanbaku'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Requests\UpdateBahanbakuRequest  $request
     * @param  \App\Models\Bahanbaku  $bahanbaku
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBahanbakuRequest $request, Bahanbaku $bahanbaku)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_bahanbaku' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Update data
        $bahanbaku->update($validatedData);

        return redirect()->route('bahanbaku.index')->with('success', 'Data bahan baku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Hapus data bahan baku
        $bahanbaku = Bahanbaku::findOrFail($id);
        $bahanbaku->delete();

        return redirect()->route('bahanbaku.index')->with('success', 'Data bahan baku berhasil dihapus!');
    }
}
