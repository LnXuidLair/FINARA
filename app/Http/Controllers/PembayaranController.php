<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

    public function index()
    {
        $pembayaran = PembayaranSiswa::with('siswa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('pembayaran.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'jenis_pembayaran' => 'required|string|max:100',
            'tanggal_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $pembayaran = new PembayaranSiswa();
        $pembayaran->id_siswa = $request->id_siswa;
        $pembayaran->jenis_pembayaran = $request->jenis_pembayaran;
        $pembayaran->tanggal_bayar = $request->tanggal_bayar;
        $pembayaran->jumlah = $request->jumlah;
        $pembayaran->status_pembayaran = 'pending';
        $pembayaran->save();

        // Prepare for Midtrans payment
        $params = [
            'transaction_details' => [
                'order_id' => 'PYMNT-' . $pembayaran->id . '-' . time(),
                'gross_amount' => $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $pembayaran->siswa->nama_siswa,
                'email' => 'siswa@example.com', // You might want to add email to siswa table
                'phone' => '08123456789',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();
            
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $orderId = explode('-', $request->order_id);
                $pembayaranId = $orderId[1];
                
                $pembayaran = PembayaranSiswa::find($pembayaranId);
                if ($pembayaran) {
                    $pembayaran->status_pembayaran = 'lunas';
                    $pembayaran->save();
                    
                    // Here you can add code to create journal entry (jurnal_umum)
                    // $jurnal = new JurnalUmum();
                    // ... save journal entry
                    // $pembayaran->id_jurnal = $jurnal->id;
                    // $pembayaran->save();
                }
            }
        }
    }

    public function notification()
    {
        // Handle notification from Midtrans
        $notif = new \Midtrans\Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $orderIdParts = explode('-', $orderId);
        $pembayaranId = $orderIdParts[1];
        
        $pembayaran = PembayaranSiswa::find($pembayaranId);
        
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $pembayaran->status_pembayaran = 'pending';
                } else {
                    $pembayaran->status_pembayaran = 'lunas';
                }
            }
        } elseif ($transaction == 'settlement') {
            $pembayaran->status_pembayaran = 'lunas';
        } elseif ($transaction == 'pending') {
            $pembayaran->status_pembayaran = 'pending';
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $pembayaran->status_pembayaran = 'gagal';
        }
        
        $pembayaran->save();
        
        return response()->json(['status' => 'success']);
    }
}
