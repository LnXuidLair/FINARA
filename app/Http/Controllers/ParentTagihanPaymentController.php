<?php

namespace App\Http\Controllers;

use App\Models\TagihanSiswa;
use App\Models\PembayaranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class ParentTagihanPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'parent']);
        
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $user = Auth::user();
        $orangtua = Orangtua::where('user_id', $user->id)->firstOrFail();

        // Get all students of this parent
        $siswaIds = $orangtua->siswa()->pluck('id');
        
        if ($siswaIds->isEmpty()) {
            return view('parent.tagihan-payment.index', ['unpaidTagihan' => collect()]);
        }

        // Get all tagihan for all students of this parent
        $tagihan = TagihanSiswa::with(['siswa', 'pembayaranSiswa'])
            ->whereIn('siswa_id', $siswaIds)
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        // Filter out fully paid tagihan
        $unpaidTagihan = $tagihan->filter(function($item) {
            return $item->status !== 'lunas';
        });

        return view('parent.tagihan-payment.index', compact('unpaidTagihan'));
    }

    public function create($tagihanId)
    {
        $user = Auth::user();
        $orangtua = Orangtua::where('user_id', $user->id)->firstOrFail();

        // Get all students of this parent
        $siswaIds = $orangtua->siswa()->pluck('id');

        $tagihan = TagihanSiswa::where('id', $tagihanId)
            ->whereIn('siswa_id', $siswaIds)
            ->with(['siswa', 'pembayaranSiswa'])
            ->firstOrFail();

        // Check if already fully paid
        if ($tagihan->status === 'lunas') {
            return redirect()->route('parent.tagihan-payment.show', $tagihanId)
                ->with('info', 'Tagihan ini sudah lunas.');
        }

        return view('parent.tagihan-payment.create', compact('tagihan'));
    }

    public function store(Request $request, $tagihanId)
    {
        $user = Auth::user();
        $orangtua = Orangtua::where('user_id', $user->id)->firstOrFail();

        // Get all students of this parent
        $siswaIds = $orangtua->siswa()->pluck('id');

        $tagihan = TagihanSiswa::where('id', $tagihanId)
            ->whereIn('siswa_id', $siswaIds)
            ->with(['siswa', 'pembayaranSiswa'])
            ->firstOrFail();

        // Check if already fully paid
        if ($tagihan->status === 'lunas') {
            abort(403, 'Tagihan ini sudah lunas.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:transfer,e-wallet,qris',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Calculate remaining amount
        $sisa = $tagihan->sisa;
        
        // Upload bukti pembayaran
        $buktiFileName = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $buktiFileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_pembayaran'), $buktiFileName);
        }

        // Create payment record
        PembayaranSiswa::create([
            'tagihan_siswa_id' => $tagihan->id,
            'tanggal_bayar' => now(),
            'jumlah_bayar' => $sisa,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $buktiFileName,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('parent.tagihan-payment.show', $tagihanId)
            ->with('success', 'Pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }

    public function show($tagihanId)
    {
        $user = Auth::user();
        $orangtua = Orangtua::where('user_id', $user->id)->firstOrFail();

        // Get all students of this parent
        $siswaIds = $orangtua->siswa()->pluck('id');

        $tagihan = TagihanSiswa::where('id', $tagihanId)
            ->whereIn('siswa_id', $siswaIds)
            ->with(['siswa', 'pembayaranSiswa'])
            ->firstOrFail();

        return view('parent.tagihan-payment.show', compact('tagihan'));
    }
}
