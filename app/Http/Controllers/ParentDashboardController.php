<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;

class ParentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'parent']);
        
        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the parent data by email
        $orangtua = Orangtua::where('email', $user->email)->firstOrFail();
        
        // Get all students (children) of the parent
        $siswaList = $orangtua->siswa()->orderBy('nama_siswa')->get();

        // Get all tagihan for all students of this parent
        $siswaIds = $siswaList->pluck('id');
        
        if ($siswaIds->isEmpty()) {
            return view('parent.dashboard', [
                'orangtua' => $orangtua,
                'siswaList' => $siswaList,
                'totalTagihan' => 0,
                'totalDibayar' => 0,
                'totalUnpaid' => 0,
                'tagihanBySiswa' => collect(),
                'chartData' => ['labels' => [], 'data' => []]
            ]);
        }

        $tagihan = TagihanSiswa::with(['siswa', 'pembayaranSiswa'])
            ->whereIn('siswa_id', $siswaIds)
            ->orderBy('siswa_id')
            ->orderBy('created_at')
            ->get();

        // Check and update pending payments via Midtrans API (fallback for localhost)
        foreach($tagihan as $t) {
            $relatedPayments = $t->pembayaranSiswa()->where('status_pembayaran', 'pending')->get();
            foreach($relatedPayments as $payment) {
                if($payment->order_id) {
                    try {
                        // Check status via Midtrans API
                        $status = $this->checkMidtransStatus($payment->order_id);
                        if($status && $status['transaction_status'] === 'settlement') {
                            // Update payment status
                            $payment->status_pembayaran = 'lunas';
                            $payment->tanggal_bayar = now();
                            $payment->save();
                            
                            Log::info('Payment updated via API check', [
                                'payment_id' => $payment->id,
                                'order_id' => $payment->order_id
                            ]);
                        }
                    } catch(\Exception $e) {
                        Log::error('Failed to check Midtrans status', [
                            'order_id' => $payment->order_id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
        
        // Refresh tagihan data after potential updates
        $tagihan = TagihanSiswa::with(['siswa', 'pembayaranSiswa'])
            ->whereIn('siswa_id', $siswaIds)
            ->orderBy('siswa_id')
            ->orderBy('created_at')
            ->get();

        $tagihanBySiswa = $tagihan->groupBy('siswa_id');

        // Calculate totals using the new status logic
        $totalTagihan = $tagihan->sum('nominal');
        $totalDibayar = 0;
        $totalUnpaid = 0;

        foreach ($tagihan as $item) {
            if ($item->status === 'lunas') {
                $totalDibayar += $item->nominal;
            } else {
                $totalUnpaid += $item->sisa;
            }
        }

        // Prepare data for the chart (last 6 months payments)
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $chartData['labels'][] = $monthYear;
            
            // Get total payments for this month using siswa relationship
            $total = PembayaranSiswa::whereHas('siswa', function($q) use ($siswaIds) {
                $q->whereIn('id', $siswaIds);
            })
            ->whereYear('tanggal_bayar', $date->year)
            ->whereMonth('tanggal_bayar', $date->month)
            ->sum('jumlah');
            
            $chartData['data'][] = $total;
        }

        return view('parent.dashboard', [
            'orangtua' => $orangtua,
            'siswaList' => $siswaList,
            'totalTagihan' => $totalTagihan,
            'totalDibayar' => $totalDibayar,
            'totalUnpaid' => $totalUnpaid,
            'tagihanBySiswa' => $tagihanBySiswa,
            'chartData' => $chartData
        ]);
    }
    
    /**
     * Check payment status via Midtrans API (fallback for localhost)
     */
    private function checkMidtransStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans API error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
