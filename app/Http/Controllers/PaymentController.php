<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\PembayaranSiswa;
use App\Models\Siswa;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $siswa = $orangtua
            ? $orangtua->siswa()->get()
            : collect();

        return view('pembayaran.create', compact('siswa'));
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'jenis_pembayaran' => 'required|string|max:100',
            'tanggal_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $orangtua = Orangtua::where('id_user', Auth::id())->first();
        if (!$orangtua) {
            return response()->json(['error' => 'Orangtua tidak ditemukan'], 403);
        }

        $siswa = Siswa::where('id', $request->id_siswa)
            ->where('id_orangtua', $orangtua->id)
            ->first();

        if (!$siswa) {
            return response()->json(['error' => 'Siswa tidak valid untuk orangtua ini'], 403);
        }

        $pembayaran = new PembayaranSiswa();
        $pembayaran->id_siswa = $request->id_siswa;
        $pembayaran->jenis_pembayaran = $request->jenis_pembayaran;
        $pembayaran->tanggal_bayar = $request->tanggal_bayar;
        $pembayaran->jumlah = (int) $request->jumlah;
        $pembayaran->status_pembayaran = 'pending';
        $pembayaran->save();

        $orderId = 'FINARA-PYMNT-' . $pembayaran->id . '-' . time();
        $pembayaran->order_id = $orderId;
        $pembayaran->save();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $siswa->nama_siswa ?? 'Siswa',
                'phone' => $siswa->no_telp ?? null,
            ],
            'item_details' => [
                [
                    'id' => 'PMT-' . $pembayaran->id,
                    'price' => (int) $pembayaran->jumlah,
                    'quantity' => 1,
                    'name' => $pembayaran->jenis_pembayaran,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();

            Transaction::create([
                'pembayaran_siswa_id' => $pembayaran->id,
                'order_id' => $orderId,
                'gross_amount' => (int) $pembayaran->jumlah,
                'snap_token' => $snapToken,
                'transaction_status' => 'pending',
                'payload' => $params,
            ]);

            return response()->json([
                'token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap token error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans notification received', [
            'payload' => $payload,
            'ip' => $request->ip(),
        ]);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans notification missing required fields', ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
        }

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
        $transactionTime = $payload['transaction_time'] ?? null;

        $pembayaran = PembayaranSiswa::where('order_id', $orderId)->first();
        $trx = Transaction::where('order_id', $orderId)->first();

        if (!$trx) {
            $trx = new Transaction();
            $trx->order_id = $orderId;
        }

        $trx->pembayaran_siswa_id = $pembayaran?->id;
        $trx->gross_amount = (int) $grossAmount;
        $trx->transaction_status = $transactionStatus;
        $trx->payment_type = $paymentType;
        $trx->status_code = $statusCode;
        $trx->fraud_status = $fraudStatus;
        $trx->payload = $payload;
        $trx->transaction_time = $transactionTime;
        $trx->save();

        if ($pembayaran) {
            if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
                if ($transactionStatus === 'capture' && $paymentType === 'credit_card' && $fraudStatus === 'challenge') {
                    $pembayaran->status_pembayaran = 'pending';
                } else {
                    $pembayaran->status_pembayaran = 'lunas';
                }
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
                $pembayaran->status_pembayaran = 'gagal';
            } else {
                $pembayaran->status_pembayaran = 'pending';
            }
            $pembayaran->save();
        } else {
            Log::warning('PembayaranSiswa not found for Midtrans order_id', ['order_id' => $orderId]);
        }

        return response()->json(['status' => 'ok']);
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
