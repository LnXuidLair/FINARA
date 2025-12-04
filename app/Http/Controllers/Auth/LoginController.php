<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function adminLogin(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin'
        ])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Login Admin gagal']);
    }

    public function staffLogin(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'pegawai'
        ])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Login Pegawai gagal']);
    }

    public function parentLogin(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'orangtua'
        ])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Login Orangtua gagal']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
