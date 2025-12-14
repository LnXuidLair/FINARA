<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AccessCodeController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrangTuaInfoController;
use App\Http\Controllers\GajiJabatanController;
use App\Http\Controllers\ParentInfoController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\LaporanController;

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
Route::post('/verify-nisn', [OrangtuaController::class, 'verifyNisn'])->name('verify.nisn');
Route::post('/verify-code', [AccessCodeController::class, 'verify'])->name('verify.code');

// ======================
// DASHBOARD
// ======================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard-staff', [PegawaiController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard.staff');

// ======================
// PEGAWAI ROUTES
// ======================
Route::middleware(['auth'])->prefix('pegawai')->name('pegawai.')->group(function () {

    Route::get('/dashboard', [PegawaiController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/transaksi', [PegawaiController::class, 'transaksi'])
        ->name('transaksi');

    Route::get('/laporan', [PegawaiController::class, 'laporan'])
        ->name('laporan');
});

// DASHBOARD ORANGTUA
Route::prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParentDashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware(['auth', 'parent']);
});

// Redirect old parent dashboard URL to new one
Route::get('/orangtua/dashboard', function () {
    return redirect()->route('parent.dashboard');
})->name('dashboard.orangtua');

Route::post('/midtrans/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('midtrans.notification');
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');

// Test route without middleware
Route::get('/test-presensi-create', function() {
    return '<h1>Test Route Working</h1><p>Ini test route tanpa controller</p>';
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    
    // Presensi Routes
    Route::resource('presensi', PresensiController::class)->names([
        'index' => 'presensi.index',
        'create' => 'presensi.create',
        'store' => 'presensi.store',
        'edit' => 'presensi.edit',
        'update' => 'presensi.update',
        'destroy' => 'presensi.destroy'
    ]);

    Route::resource('penggajian', PenggajianController::class)->names([
        'index' => 'penggajian.index',
        'create' => 'penggajian.create',
        'store' => 'penggajian.store',
        'edit' => 'penggajian.edit',
        'update' => 'penggajian.update',
        'destroy' => 'penggajian.destroy'
    ]);

    Route::get('penggajian/{id}/cetak', [PenggajianController::class, 'cetakSlip'])->name('penggajian.cetak');
});

Route::resource('coa', CoaController::class);
Route::resource('pegawai', PegawaiController::class);

// Gaji Jabatan Routes
Route::get('/test-gaji', function() {
    return "Route test berhasil!";
});
Route::get('/test-gaji-view', function() {
    try {
        return view('gajijabatan.index', ['gajiJabatans' => collect()]);
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::middleware(['auth'])->group(function () {
    Route::get('/gajijabatan', [GajiJabatanController::class, 'index'])->name('gajijabatan.index');
    Route::get('/gajijabatan/create', [GajiJabatanController::class, 'create'])->name('gajijabatan.create');
    Route::post('/gajijabatan', [GajiJabatanController::class, 'store'])->name('gajijabatan.store');
    Route::get('/gajijabatan/{id}/edit', [GajiJabatanController::class, 'edit'])->name('gajijabatan.edit');
    Route::put('/gajijabatan/{id}', [GajiJabatanController::class, 'update'])->name('gajijabatan.update');
    Route::delete('/gajijabatan/{id}', [GajiJabatanController::class, 'destroy'])->name('gajijabatan.destroy');
});

// ======================
// LAPORAN ROUTES
// ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/laporan/jurnal', [LaporanController::class, 'jurnal'])->name('admin.laporan.jurnal');
    Route::get('/admin/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('admin.laporan.keuangan');
});

require __DIR__.'/auth.php';

// ======================
// PEMBAYARAN ROUTES
// ======================
Route::middleware(['auth'])->group(function () {
    // Parent Payment Routes
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [App\Http\Controllers\PembayaranController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\PembayaranController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PembayaranController::class, 'store'])->name('store');
        Route::post('/callback', [App\Http\Controllers\PembayaranController::class, 'callback'])->name('callback');
        Route::post('/notification', [App\Http\Controllers\PembayaranController::class, 'notification'])->name('notification');
    });

    Route::middleware(['parent'])->group(function () {
        Route::get('/parent/pembayaran', [App\Http\Controllers\PaymentController::class, 'index'])->name('parent.payment.index');
        Route::get('/parent/pembayaran/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('parent.payment.create');
        Route::post('/create-payment', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('create-payment');

        Route::get('/pembayaran/{id}/invoice', [App\Http\Controllers\PaymentController::class, 'invoice'])->name('pembayaran.invoice');

        Route::get('/parent/informasi-siswa', [ParentInfoController::class, 'index'])->name('parent.informasi-siswa');
    });
});
