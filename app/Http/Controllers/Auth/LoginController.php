<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin'
        ], $request->remember)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email / password admin salah']);
    }

   public function staffLogin(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'pegawai'
    ], $request->remember)) {

        return redirect()->route('pegawai.dashboard');
    }

    return back()->withErrors(['email' => 'Email / password pegawai salah']);
}


    public function parentLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'orangtua'
        ], $request->remember)) {
            return redirect()->route('parent.dashboard');
        }

        return back()->withErrors(['email' => 'Email / password orang tua salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
