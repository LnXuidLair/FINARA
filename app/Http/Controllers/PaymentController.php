<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->first();

        $siswaIds = $orangtua
            ? $orangtua->siswa()->pluck('id')
            : collect();

        $pembayaran = PembayaranSiswa::with('siswa')
            ->when($siswaIds->isNotEmpty(), function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            }, function ($q) {
                $q->whereRaw('1=0');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->first();

        if (!$orangtua) {
            return view('pembayaran.create', [
                'tagihanBySiswa' => collect(),
                'totalUnpaid' => 0,
            ]);
        }

        $tagihan = TagihanSiswa::with('siswa')
            ->where('orangtua_id', $orangtua->id)
            ->where('status', 'unpaid')
            ->orderBy('siswa_id')
            ->orderBy('created_at')
            ->get();

        $tagihanBySiswa = $tagihan->groupBy('siswa_id');
        $totalUnpaid = (int) $tagihan->sum('nominal');

        return view('pembayaran.create', compact('tagihanBySiswa', 'totalUnpaid'));
    }

    public function createPayment(Request $request)
    {
        return response()->json([
            'error' => 'Pembayaran manual dinonaktifkan. Gunakan pembayaran Tagihan Siswa (Bayar Sekarang).'
        ], 422);
    }

    public function createTagihanPayment(Request $request)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->first();
        if (!$orangtua) {
            return response()->json(['error' => 'Orangtua tidak ditemukan'], 403);
        }

        $tagihan = TagihanSiswa::with('siswa')
            ->where('orangtua_id', $orangtua->id)
            ->get();

        // Filter hanya tagihan yang belum lunas
        $unpaidTagihan = $tagihan->filter(function($t) {
            return $t->status !== 'lunas';
        });

        if ($unpaidTagihan->isEmpty()) {
            return response()->json(['error' => 'Tidak ada tagihan yang perlu dibayar.'], 422);
        }

        $total = (int) $unpaidTagihan->sum('nominal');
        if ($total < 1) {
            return response()->json(['error' => 'Total tagihan tidak valid.'], 422);
        }

        $firstSiswaId = $unpaidTagihan->first()->siswa_id;
        $firstSiswa = $unpaidTagihan->first()->siswa;

        $pembayaran = new PembayaranSiswa();
        $pembayaran->id_siswa = $firstSiswaId;
        $pembayaran->jenis_pembayaran = 'Pembayaran Tagihan';
        $pembayaran->tanggal_bayar = now()->toDateString();
        $pembayaran->jumlah = $total;
        $pembayaran->status_pembayaran = 'pending';
        $pembayaran->save();

        $orderId = 'FINARA-TAGIHAN-' . $pembayaran->id . '-' . time();
        $pembayaran->order_id = $orderId;
        $pembayaran->save();

        // Hubungkan pembayaran dengan semua tagihan yang dibayar
        try {
            foreach ($unpaidTagihan as $t) {
                $pembayaran->tagihanSiswa()->attach($t->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to attach tagihan to payment', [
                'payment_id' => $pembayaran->id,
                'error' => $e->getMessage()
            ]);
        }

        $itemDetails = $unpaidTagihan->take(20)->map(function ($t) {
            return [
                'id' => 'TAG-' . $t->id,
                'price' => (int) $t->nominal,
                'quantity' => 1,
                'name' => ($t->jenis_tagihan . ' ' . $t->periode),
            ];
        })->values()->all();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $firstSiswa?->nama_siswa ?? 'Siswa',
                'phone' => $firstSiswa?->no_telp ?? null,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();

            $pembayaran->tagihanSiswa()->sync($tagihan->pluck('id')->all());

            Transaction::create([
                'pembayaran_siswa_id' => $pembayaran->id,
                'order_id' => $orderId,
                'gross_amount' => $total,
                'snap_token' => $snapToken,
                'transaction_status' => 'pending',
                'payload' => $params,
            ]);

            return response()->json([
                'token' => $snapToken,
                'order_id' => $orderId,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap token error (tagihan): ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        // Log semua request yang masuk
        Log::info('=== MIDTRANS NOTIFICATION RECEIVED ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request IP: ' . $request->ip());
        Log::info('Request headers:', $request->headers->all());
        Log::info('Request payload:', $request->all());
        
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans notification missing required fields', ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
        }

        // Verifikasi signature key
        $serverKey = config('services.midtrans.server_key');
        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if (!hash_equals($computed, $signatureKey)) {
            Log::warning('Midtrans notification signature mismatch', [
                'order_id' => $orderId,
                'computed' => $computed,
                'received' => $signatureKey,
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        $transactionStatus = $payload['transaction_status'] ?? 'pending';
        $paymentType = $payload['payment_type'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionTime = $payload['transaction_time'] ?? now();

        Log::info('Processing Midtrans notification', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
            'fraud_status' => $fraudStatus,
        ]);

        // Mulai transaksi database
        DB::beginTransaction();
        
        try {
            // Cari pembayaran berdasarkan order_id
            $pembayaran = PembayaranSiswa::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::warning('PembayaranSiswa not found for Midtrans order_id', ['order_id' => $orderId]);
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            Log::info('Found payment record', [
                'payment_id' => $pembayaran->id,
                'current_status' => $pembayaran->status_pembayaran,
            ]);

            // Update status pembayaran berdasarkan notifikasi Midtrans
            if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
                // Jika pembayaran berhasil
                if ($transactionStatus === 'capture' && $paymentType === 'credit_card' && $fraudStatus === 'challenge') {
                    $pembayaran->status_pembayaran = 'pending';
                } else {
                    $pembayaran->status_pembayaran = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                    
                    // Cari dan hubungkan dengan tagihan
                    // Karena pembayaran baru sudah dihubungkan saat create, kita hanya perlu memastikan
                    $tagihanList = $pembayaran->tagihanSiswa;
                    
                    if ($tagihanList->isNotEmpty()) {
                        foreach ($tagihanList as $tagihan) {
                            // Update timestamp tagihan untuk memastikan cache terupdate
                            $tagihan->touch();
                            
                            Log::info('Tagihan updated', [
                                'payment_id' => $pembayaran->id,
                                'tagihan_id' => $tagihan->id,
                                'tagihan_status' => $tagihan->status,
                            ]);
                        }
                    } else {
                        Log::warning('No tagihan found for payment', [
                            'payment_id' => $pembayaran->id,
                            'order_id' => $orderId
                        ]);
                    }
                }
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
                $pembayaran->status_pembayaran = 'gagal';
            } else {
                $pembayaran->status_pembayaran = 'pending';
            }
            
            // Simpan perubahan status
            $pembayaran->save();
            
            // Commit transaksi
            DB::commit();
            
            Log::info('Payment status updated successfully', [
                'payment_id' => $pembayaran->id,
                'new_status' => $pembayaran->status_pembayaran,
                'order_id' => $orderId,
            ]);
            
            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing Midtrans notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error processing notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function invoice($id)
    {
        $orangtua = Orangtua::where('id_user', Auth::id())->first();
        if (!$orangtua) {
            abort(403);
        }

        $pembayaran = PembayaranSiswa::with('siswa')->findOrFail($id);

        if (!$pembayaran->siswa || $pembayaran->siswa->id_orangtua !== $orangtua->id) {
            abort(403);
        }

        $trx = null;
        if ($pembayaran->order_id) {
            $trx = Transaction::where('order_id', $pembayaran->order_id)->first();
        }

        return view('pembayaran.invoice', compact('pembayaran', 'trx'));
    }
}
