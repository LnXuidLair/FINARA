<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Pegawai;
use App\Models\GajiJabatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index()
    {
        $penggajian = Penggajian::with('pegawai.gajiJabatan')->latest()->get();
        return view('admin.penggajian.index', compact('penggajian'));
    }

    public function create()
    {
        $pegawais = Pegawai::with('gajiJabatan')->get();
        return view('admin.penggajian.create', compact('pegawais'));
    }

    public function edit($id)
    {
        $penggajian = Penggajian::with('pegawai.gajiJabatan')->findOrFail($id);
        $pegawais = Pegawai::with('gajiJabatan')->get();
        return view('admin.penggajian.edit', compact('penggajian', 'pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'periode' => 'required|date_format:Y-m',
            'status_penggajian' => 'required|in:belum_dibayar,sudah_dibayar',
        ]);

        // Hitung jumlah kehadiran
        $pegawai = Pegawai::findOrFail($validated['id_pegawai']);
        $jumlah_kehadiran = $pegawai->hitungKehadiran($validated['periode']);

        $jumlah_hari = Carbon::createFromFormat('Y-m', $validated['periode'])->startOfMonth()->daysInMonth;

        // Buat data penggajian
        $penggajian = new Penggajian([
            'id_pegawai' => $validated['id_pegawai'],
            'periode' => $validated['periode'],
            'jumlah_hari' => $jumlah_hari,
            'status_penggajian' => $validated['status_penggajian'],
            'tanggal' => now(),
            'jumlah_kehadiran' => $jumlah_kehadiran,
            // gaji_perhari dan total_gaji akan dihitung otomatis oleh model
        ]);

        $penggajian->save();

        return redirect()->route('admin.penggajian.index')
            ->with('success', 'Data penggajian berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'periode' => 'required|date_format:Y-m',
            'status_penggajian' => 'required|in:belum_dibayar,sudah_dibayar',
        ]);

        $penggajian = Penggajian::findOrFail($id);

        $pegawai = Pegawai::with('gajiJabatan')->findOrFail($validated['id_pegawai']);
        $jumlah_kehadiran = $pegawai->hitungKehadiran($validated['periode']);
        $jumlah_hari = Carbon::createFromFormat('Y-m', $validated['periode'])->startOfMonth()->daysInMonth;
        $gaji_perhari = $pegawai->gajiJabatan->gaji_perhari ?? 0;
        $total_gaji = $gaji_perhari * $jumlah_kehadiran;

        $penggajian->update([
            'id_pegawai' => $validated['id_pegawai'],
            'periode' => $validated['periode'],
            'jumlah_hari' => $jumlah_hari,
            'gaji_perhari' => $gaji_perhari,
            'total_gaji' => $total_gaji,
            'status_penggajian' => $validated['status_penggajian'],
            'tanggal' => now(),
            'jumlah_kehadiran' => $jumlah_kehadiran,
        ]);

        return redirect()->route('admin.penggajian.index')
            ->with('success', 'Data penggajian berhasil diperbarui');
    }

    public function destroy($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->delete();

        return redirect()->route('admin.penggajian.index')
            ->with('success', 'Data penggajian berhasil dihapus');
    }

    public function cetakSlip($id)
    {
        $penggajian = Penggajian::with('pegawai.gajiJabatan')->findOrFail($id);
        return view('admin.penggajian.cetak', compact('penggajian'));
    }
}
