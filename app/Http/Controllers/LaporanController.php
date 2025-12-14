<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\PembayaranSiswa;
use App\Models\Coa;

class LaporanController extends Controller
{
    public function jurnal()
    {
        $jurnal = [];
        
        // Ambil data pengeluaran
        $pengeluaran = Pengeluaran::with(['debit', 'kredit'])->get();
        foreach ($pengeluaran as $item) {
            $jurnal[] = [
                'tanggal' => $item->tanggal,
                'no_bukti' => 'EX-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                'keterangan' => $item->keterangan,
                'debit' => $item->nominal,
                'kredit' => 0,
                'akun' => $item->debit ? $item->debit->nama_coa : 'Kas',
                'tipe' => 'pengeluaran'
            ];
        }
        
        // Ambil data pembayaran siswa
        $pembayaran = PembayaranSiswa::with('siswa')->get();
        foreach ($pembayaran as $item) {
            $jurnal[] = [
                'tanggal' => $item->tanggal_bayar,
                'no_bukti' => 'PS-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                'keterangan' => 'Pembayaran ' . $item->jenis_pembayaran . ' - ' . ($item->siswa ? $item->siswa->nama_siswa : 'Siswa'),
                'debit' => 0,
                'kredit' => $item->jumlah,
                'akun' => 'Pendapatan ' . $item->jenis_pembayaran,
                'tipe' => 'pemasukan'
            ];
        }
        
        // Sort berdasarkan tanggal
        usort($jurnal, function($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        return view('admin.laporan.jurnal', compact('jurnal'));
    }

    public function keuangan()
    {
        // Ambil data pembayaran siswa (pendapatan)
        $pembayaran = PembayaranSiswa::with('siswa')
            ->selectRaw('jenis_pembayaran, SUM(jumlah) as total, COUNT(*) as count')
            ->where('status_pembayaran', 'lunas')
            ->groupBy('jenis_pembayaran')
            ->get();
            
        // Ambil data pengeluaran (beban)
        $pengeluaran = Pengeluaran::with(['debit', 'kredit'])
            ->selectRaw('keterangan, SUM(nominal) as total, COUNT(*) as count')
            ->groupBy('keterangan')
            ->get();
        
        // Format data pendapatan
        $pendapatan = [];
        $totalPendapatan = 0;
        foreach ($pembayaran as $item) {
            $pendapatan[] = [
                'akun' => 'Pendapatan ' . $item->jenis_pembayaran,
                'jumlah' => $item->total,
                'persentase' => 0 // akan dihitung nanti
            ];
            $totalPendapatan += $item->total;
        }
        
        // Format data beban
        $beban = [];
        $totalBeban = 0;
        foreach ($pengeluaran as $item) {
            $beban[] = [
                'akun' => $item->keterangan,
                'jumlah' => $item->total,
                'persentase' => 0 // akan dihitung nanti
            ];
            $totalBeban += $item->total;
        }
        
        // Hitung persentase
        foreach ($pendapatan as &$item) {
            $item['persentase'] = $totalPendapatan > 0 ? round(($item['jumlah'] / $totalPendapatan) * 100, 2) : 0;
        }
        foreach ($beban as &$item) {
            $item['persentase'] = $totalBeban > 0 ? round(($item['jumlah'] / $totalBeban) * 100, 2) : 0;
        }
        
        // Hitung laba/rugi dan saldo
        $labaRugi = $totalPendapatan - $totalBeban;
        $saldoKas = $labaRugi; // sederhanakan, bisa dikembangkan dengan data kas real
        
        return view('admin.laporan.keuangan', compact(
            'pendapatan', 
            'beban', 
            'totalPendapatan', 
            'totalBeban', 
            'labaRugi', 
            'saldoKas'
        ));
    }
}
