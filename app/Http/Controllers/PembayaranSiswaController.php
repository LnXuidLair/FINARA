<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSiswa;
use App\Models\TagihanSiswa;
use App\Models\Siswa;
use App\Models\OrangTua;
use Illuminate\Http\Request;

class PembayaranSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data dari tagihan siswa, bukan dari pembayaran siswa
        $tagihanSiswa = TagihanSiswa::with(['siswa.orangtua', 'pembayaranSiswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pembayaran-siswa.index', compact('tagihanSiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak perlu create form karena data diambil dari tagihan
        return redirect()->route('admin.pembayaran-siswa.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tidak perlu store karena data diambil dari tagihan
        return redirect()->route('admin.pembayaran-siswa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PembayaranSiswa $pembayaranSiswa)
    {
        $pembayaranSiswa->load(['tagihanSiswa.siswa.orangtua']);
        
        // Hitung total pembayaran dan sisa
        $totalPembayaran = PembayaranSiswa::where('tagihan_siswa_id', $pembayaranSiswa->tagihan_siswa_id)
            ->sum('jumlah_bayar');
        $totalTagihan = $pembayaranSiswa->tagihanSiswa->jumlah;
        $sisa = $totalTagihan - $totalPembayaran;
        $status = $sisa <= 0 ? 'lunas' : 'belum_lunas';

        return view('admin.pembayaran-siswa.show', compact('pembayaranSiswa', 'totalPembayaran', 'totalTagihan', 'sisa', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembayaranSiswa $pembayaranSiswa)
    {
        $pembayaranSiswa->load(['tagihanSiswa.siswa.orangtua']);
        
        return view('admin.pembayaran-siswa.edit', compact('pembayaranSiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembayaranSiswa $pembayaranSiswa)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'required|string|in:transfer,tunai,online',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $data = $request->except('bukti_pembayaran');

        // Upload bukti pembayaran baru jika ada
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama jika ada
            if ($pembayaranSiswa->bukti_pembayaran) {
                $oldPath = public_path('uploads/bukti_pembayaran/' . $pembayaranSiswa->bukti_pembayaran);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_pembayaran'), $filename);
            $data['bukti_pembayaran'] = $filename;
        }

        $pembayaranSiswa->update($data);

        return redirect()->route('pembayaran-siswa.index')
            ->with('success', 'Pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembayaranSiswa $pembayaranSiswa)
    {
        // Hapus file bukti pembayaran jika ada
        if ($pembayaranSiswa->bukti_pembayaran) {
            $path = public_path('uploads/bukti_pembayaran/' . $pembayaranSiswa->bukti_pembayaran);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $pembayaranSiswa->delete();

        return redirect()->route('pembayaran-siswa.index')
            ->with('success', 'Pembayaran berhasil dihapus');
    }

    /**
     * Menampilkan laporan pembayaran per siswa
     */
    public function laporanPerSiswa(Request $request)
    {
        $siswaId = $request->input('siswa_id');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = PembayaranSiswa::with(['tagihanSiswa.siswa.orangtua'])
            ->orderBy('tanggal_bayar', 'desc');

        if ($siswaId) {
            $query->whereHas('tagihanSiswa', function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            });
        }

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal_bayar', $bulan)
                  ->whereYear('tanggal_bayar', $tahun);
        }

        $pembayaranSiswa = $query->get();
        $siswaList = Siswa::orderBy('nama')->pluck('nama', 'id');

        return view('admin.pembayaran-siswa.laporan', compact('pembayaranSiswa', 'siswaList', 'siswaId', 'bulan', 'tahun'));
    }

    /**
     * Menampilkan detail pembayaran untuk tagihan tertentu
     */
    public function detailTagihan($tagihanSiswaId)
    {
        $tagihanSiswa = TagihanSiswa::with(['siswa.orangtua', 'pembayaranSiswa'])
            ->findOrFail($tagihanSiswaId);

        $totalPembayaran = $tagihanSiswa->pembayaranSiswa->sum('jumlah_bayar');
        $sisa = $tagihanSiswa->jumlah - $totalPembayaran;
        $status = $sisa <= 0 ? 'lunas' : 'belum_lunas';

        return view('admin.pembayaran-siswa.detail-tagihan', compact('tagihanSiswa', 'totalPembayaran', 'sisa', 'status'));
    }
}
