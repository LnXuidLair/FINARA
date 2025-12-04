<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessCodeController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// === MULTI LOGIN ===
Route::view('/login/admin', 'auth.login-admin')->name('login.admin');
Route::view('/login/staff', 'auth.login-staff')->name('login.staff');
Route::view('/login/parent', 'auth.login-parent')->name('login.parent');

Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/staff', [LoginController::class, 'staffLogin']);
Route::post('/login/parent', [LoginController::class, 'parentLogin']);

Route::view('/register/admin', 'auth.register-admin')->name('register.admin');
Route::view('/register/staff', 'auth.register-staff')->name('register.staff');
Route::view('/register/parent', 'auth.register-parent')->name('register.parent');

Route::post('/register/admin', [RegisterController::class, 'registerAdmin'])->name('register.admin.post');
Route::post('/register/staff', [RegisterController::class, 'registerStaff'])->name('register.staff.post');
Route::post('/register/parent', [RegisterController::class, 'registerParent'])->name('register.parent.post');

// === VERIFICATION === //
Route::post('/verify-staff', [PegawaiController::class, 'verifyStaff'])->name('verify.staff');
Route::post('/verify-nisn', [OrangtuaController::class, 'verifyNisn'])->name('verify.nisn');
Route::post('/verify-code', [AccessCodeController::class, 'verify'])->name('verify.code');

require __DIR__.'/auth.php';