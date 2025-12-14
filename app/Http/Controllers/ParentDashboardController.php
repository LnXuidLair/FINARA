<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'parent']);
    }

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the parent data
        $orangtua = Orangtua::where('id_user', $user->id)->firstOrFail();
        
        // Get all students (children) of the parent
        $siswaList = $orangtua->siswa()->with(['pembayaranSiswa' => function($query) {
            $query->selectRaw('id_siswa, SUM(jumlah) as total_pembayaran')
                  ->groupBy('id_siswa');
        }])->get();

        // Calculate totals
        $totalTagihan = $siswaList->sum('pembayaranSiswa.total_pembayaran');
        $totalDibayar = $siswaList->sum(function($siswa) {
            return $siswa->pembayaranSiswa->sum('total_pembayaran');
        });

        // Prepare data for the chart (example: last 6 months payments)
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $chartData['labels'][] = $monthYear;
            
            // Get total payments for this month
            $total = 0;
            foreach ($siswaList as $siswa) {
                $total += $siswa->pembayaranSiswa()
                    ->whereYear('tanggal_bayar', $date->year)
                    ->whereMonth('tanggal_bayar', $date->month)
                    ->sum('jumlah');
            }
            $chartData['data'][] = $total;
        }

        return view('parent.dashboard', [
            'orangtua' => $orangtua,
            'siswaList' => $siswaList,
            'totalTagihan' => $totalTagihan,
            'totalDibayar' => $totalDibayar,
            'chartData' => $chartData
        ]);
    }
}
