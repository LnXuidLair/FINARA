<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index()
    {
        $presensi = Presensi::with('pegawai')->latest()->get();
        return view('admin.presensi.index', compact('presensi'));
    }

    public function create()
    {
        \Log::info('PresensiController@create called');
        $pegawais = Pegawai::all();
        \Log::info('Pegawais count: ' . $pegawais->count());
        // Debug: uncomment to test
        // dd('Controller reached', $pegawais->count());
        return view('admin.presensi.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        try {
            Presensi::create($validated);
            
            return redirect()
                ->route('admin.presensi.index')
                ->with('success', 'Data presensi berhasil disimpan!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data presensi.');
        }
    }

    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        return redirect()->route('admin.presensi.index')
            ->with('success', 'Data presensi berhasil dihapus');
    }
}
