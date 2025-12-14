<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;

class TagihanSiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $tagihan = TagihanSiswa::with(['siswa.orangtua'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.tagihan-siswa.index', compact('tagihan'));
    }

    public function create()
    {
        $siswa = Siswa::with('orangtua')->orderBy('nama_siswa')->get();

        return view('admin.tagihan-siswa.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_tagihan' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal_jatuh_tempo' => 'required|date',
            'periode' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'jenis_tagihan.required' => 'Jenis tagihan wajib dipilih.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal Rp 1.000.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.date' => 'Format tanggal tidak valid.',
        ]);

        $siswa = Siswa::with('orangtua')->findOrFail($validated['siswa_id']);
        
        // Check if siswa has orangtua
        if (!$siswa->orangtua) {
            return back()->withInput()->with('error', 'Siswa belum terhubung ke orang tua.');
        }
        
        // Create tagihan with correct field names
        TagihanSiswa::create([
            'siswa_id' => $siswa->id,
            'orangtua_id' => $siswa->orangtua->id,
            'jenis_tagihan' => $validated['jenis_tagihan'],
            'nominal' => $validated['jumlah'],
            'periode' => $validated['periode'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('admin.tagihan-siswa.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tagihan = TagihanSiswa::with(['siswa.orangtua', 'pembayaranSiswa'])->findOrFail($id);

        return view('admin.tagihan-siswa.show', compact('tagihan'));
    }

    public function edit($id)
    {
        $tagihan = TagihanSiswa::with(['siswa.orangtua'])->findOrFail($id);
        $siswa = Siswa::with('orangtua')->orderBy('nama')->get();

        return view('admin.tagihan-siswa.edit', compact('tagihan', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $tagihan = TagihanSiswa::findOrFail($id);

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_tagihan' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal_jatuh_tempo' => 'required|date',
            'periode' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $siswa = Siswa::with('orangtua')->findOrFail($validated['siswa_id']);
        if (!$siswa->orangtua_id) {
            return back()->withInput()->with('error', 'Siswa belum terhubung ke orang tua.');
        }

        // Update tagihan WITHOUT status
        $tagihan->update([
            'siswa_id' => $siswa->id,
            'jenis_tagihan' => $validated['jenis_tagihan'],
            'jumlah' => $validated['jumlah'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
            'periode' => $validated['periode'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('admin.tagihan-siswa.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tagihan = TagihanSiswa::findOrFail($id);
        $tagihan->delete();

        return redirect()->route('admin.tagihan-siswa.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}
