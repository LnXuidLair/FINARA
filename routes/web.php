<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AccessCodeController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanSiswaController;
use App\Http\Controllers\PembayaranSiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrangTuaInfoController;
use App\Http\Controllers\ParentInfoController;
use App\Http\Controllers\ParentTagihanPaymentController;

// ======================
// HALAMAN AWAL
// ======================
Route::get('/', function () {
    return view('welcome');
});

// ======================
// HALAMAN LOGIN (VIEW)
// ======================
Route::get('/login/admin', function () {
    return view('auth.login-admin');
})->name('login.admin');

Route::get('/login/staff', function () {
    return view('auth.login-staff');
})->name('login.staff');

Route::get('/login/parent', function () {
    return view('auth.login-parent');
})->name('login.parent');

// ======================
// PROSES LOGIN (POST)
// ======================
Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/staff', [LoginController::class, 'staffLogin']);
Route::post('/login/parent', [LoginController::class, 'parentLogin']);

// ======================
// REGISTER (KALAU ADA)
// ======================
Route::view('/register/admin', 'auth.register-admin')->name('register.admin');
Route::view('/register/staff', 'auth.register-staff')->name('register.staff');
Route::view('/register/parent', 'auth.register-parent')->name('register.parent');

Route::post('/register/admin', [RegisterController::class, 'registerAdmin'])->name('register.admin.post');
Route::post('/register/staff', [RegisterController::class, 'registerStaff'])->name('register.staff.post');
Route::post('/register/parent', [RegisterController::class, 'registerParent'])->name('register.parent.post');

// ======================
// VERIFIKASI
// ======================
Route::post('/verify-staff', [PegawaiController::class, 'verifyStaff'])->name('verify.staff');
Route::post('/verify-nisn', [OrangTuaController::class, 'verifyNisn'])->name('verify.nisn');
Route::post('/verify-code', [AccessCodeController::class, 'verify'])->name('verify.code');

// ======================
// DASHBOARD
// ======================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard-staff', function () {
    $user = auth()->user();
    if (!$user || $user->role !== 'pegawai') {
        abort(403, 'Anda tidak memiliki akses.');
    }

    return view('dashboard.pegawai');
})->middleware('auth')->name('dashboard.staff');

// ======================
// ADMIN ROUTES
// ======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Master Data
    Route::resource('master-data/orangtua', OrangTuaController::class);
    Route::resource('master-data/siswa', SiswaController::class);
    
    // Tagihan & Pembayaran
    Route::resource('tagihan-siswa', TagihanSiswaController::class);
    Route::resource('pembayaran-siswa', PembayaranSiswaController::class);
});

// ======================
// DASHBOARD ORANGTUA
// ======================
Route::prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParentDashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware(['auth', 'parent', 'force_parent_password', 'force_password_change']);

    Route::get('/password', [\App\Http\Controllers\ParentPasswordController::class, 'edit'])
        ->name('password.edit')
        ->middleware(['auth', 'parent']);

    Route::put('/password', [\App\Http\Controllers\ParentPasswordController::class, 'update'])
        ->name('password.update')
        ->middleware(['auth', 'parent']);

    // Menu Pembayaran
    Route::get('/pembayaran', [\App\Http\Controllers\ParentPembayaranController::class, 'index'])
        ->name('pembayaran.index')
        ->middleware(['auth', 'parent', 'force_parent_password', 'force_password_change']);

    // Menu Informasi Siswa
    Route::get('/informasi-siswa', [\App\Http\Controllers\ParentInformasiSiswaController::class, 'index'])
        ->name('informasi-siswa.index')
        ->middleware(['auth', 'parent', 'force_parent_password', 'force_password_change']);
});

// ======================
// PARENT TAGIHAN PAYMENT
// ======================
Route::middleware(['auth', 'parent', 'force_parent_password'])->prefix('parent/tagihan-payment')->name('parent.tagihan-payment.')->group(function () {
    Route::get('/', [ParentTagihanPaymentController::class, 'index'])->name('index');
    Route::get('/{tagihanId}/create', [ParentTagihanPaymentController::class, 'create'])->name('create');
    Route::post('/{tagihanId}', [ParentTagihanPaymentController::class, 'store'])->name('store');
    Route::get('/{tagihanId}', [ParentTagihanPaymentController::class, 'show'])->name('show');
});

// Route untuk create-tagihan-payment (Midtrans)
Route::post('/create-tagihan-payment', function (Illuminate\Http\Request $request) {
    // Logic untuk membuat pembayaran Midtrans
    $tagihanId = $request->input('tagihan_id');
    
    // Get tagihan data
    $tagihan = \App\Models\TagihanSiswa::find($tagihanId);
    if (!$tagihan) {
        return response()->json(['error' => 'Tagihan tidak ditemukan'], 404);
    }
    
    // Create pembayaran record
    $pembayaran = new \App\Models\PembayaranSiswa();
    $pembayaran->id_siswa = $tagihan->siswa_id;
    $pembayaran->jenis_pembayaran = $tagihan->jenis_tagihan;
    $pembayaran->tanggal_bayar = now()->toDateString();
    $pembayaran->jumlah = $tagihan->nominal;
    $pembayaran->status_pembayaran = 'pending';
    $pembayaran->order_id = 'ORDER-' . $tagihanId . '-' . time();
    $pembayaran->save();
    
    // Generate valid transaction data with correct amount
    $transactionData = [
        'transaction_details' => [
            'order_id' => $pembayaran->order_id,
            'gross_amount' => $tagihan->nominal,
        ],
        'customer_details' => [
            'first_name' => $tagihan->siswa->nama_siswa ?? 'Test',
            'last_name' => 'User',
            'email' => \Auth::user()->email,
            'phone' => '08123456789',
        ],
        'item_details' => [
            [
                'id' => 'TAGIHAN-' . $tagihanId,
                'price' => $tagihan->nominal,
                'quantity' => 1,
                'name' => $tagihan->jenis_tagihan,
            ],
        ],
    ];

    // Create Snap token using Midtrans
    try {
        // Set Midtrans config
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
        
        $snapToken = \Midtrans\Snap::getSnapToken($transactionData);
        
        // Save snap token
        $pembayaran->snap_token = $snapToken;
        $pembayaran->save();
        
        return response()->json([
            'token' => $snapToken,
            'redirect_url' => null
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal membuat token Midtrans: ' . $e->getMessage()
        ], 500);
    }
})->name('create-tagihan-payment')->middleware(['auth', 'parent']);

// Route untuk create-semua-tagihan-payment (Multiple tagihan)
Route::post('/create-semua-tagihan-payment', function (Illuminate\Http\Request $request) {
    // Logic untuk membuat pembayaran semua tagihan Midtrans
    $user = \Auth::user();
    $orangtua = \App\Models\OrangTua::where('email', $user->email)->first();
    
    if (!$orangtua) {
        return response()->json(['error' => 'Orangtua tidak ditemukan'], 404);
    }
    
    // Get all siswa of this orangtua
    $siswaList = $orangtua->siswa()->get();
    $siswaIds = $siswaList->pluck('id');
    
    // Get all unpaid tagihan
    $tagihanList = \App\Models\TagihanSiswa::with(['siswa', 'pembayaranSiswa'])
        ->whereIn('siswa_id', $siswaIds)
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Calculate total for each tagihan and filter unpaid
    $tagihanBelumLunas = collect();
    $totalSemua = 0;
    
    foreach ($tagihanList as $tagihan) {
        $totalBayar = $tagihan->pembayaranSiswa->sum('jumlah');
        $sisa = $tagihan->nominal - $totalBayar;
        
        if ($sisa > 0) {
            $tagihan->sisa = $sisa;
            $tagihanBelumLunas->push($tagihan);
            $totalSemua += $sisa;
        }
    }
    
    if ($tagihanBelumLunas->isEmpty()) {
        return response()->json(['error' => 'Tidak ada tagihan yang perlu dibayar'], 422);
    }
    
    if ($totalSemua < 1) {
        return response()->json(['error' => 'Total pembayaran terlalu kecil'], 422);
    }
    
    // Create pembayaran record
    $pembayaran = new \App\Models\PembayaranSiswa();
    $pembayaran->id_siswa = $tagihanBelumLunas->first()->siswa_id;
    $pembayaran->jenis_pembayaran = 'Pembayaran Semua Tagihan';
    $pembayaran->tanggal_bayar = now()->toDateString();
    $pembayaran->jumlah = $totalSemua;
    $pembayaran->status_pembayaran = 'pending';
    $pembayaran->order_id = 'SEMUA-' . time();
    $pembayaran->save();
    
    // Generate item details for all tagihan
    $itemDetails = $tagihanBelumLunas->map(function ($tagihan) {
        return [
            'id' => 'TAGIHAN-' . $tagihan->id,
            'price' => (int) $tagihan->sisa,
            'quantity' => 1,
            'name' => $tagihan->jenis_tagihan . ' - ' . ($tagihan->siswa->nama_siswa ?? ''),
        ];
    })->toArray();
    
    // Generate valid transaction data
    $transactionData = [
        'transaction_details' => [
            'order_id' => $pembayaran->order_id,
            'gross_amount' => $totalSemua,
        ],
        'customer_details' => [
            'first_name' => $orangtua->nama_ortu,
            'last_name' => '',
            'email' => $user->email,
            'phone' => $orangtua->no_telp ?? '08123456789',
        ],
        'item_details' => $itemDetails,
    ];

    // Create Snap token using Midtrans
    try {
        // Set Midtrans config
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
        
        $snapToken = \Midtrans\Snap::getSnapToken($transactionData);
        
        // Save snap token
        $pembayaran->snap_token = $snapToken;
        $pembayaran->save();
        
        return response()->json([
            'token' => $snapToken,
            'redirect_url' => null
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal membuat token Midtrans: ' . $e->getMessage()
        ], 500);
    }
})->name('create-semua-tagihan-payment')->middleware(['auth', 'parent']);

// Redirect old parent dashboard URL to new one
Route::get('/orangtua/dashboard', function () {
    return redirect()->route('parent.dashboard');
})->name('dashboard.orangtua');

// ======================
// MIDTRANS NOTIFICATION
// ======================
Route::post('/midtrans/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('midtrans.notification');
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');

require __DIR__.'/auth.php';
