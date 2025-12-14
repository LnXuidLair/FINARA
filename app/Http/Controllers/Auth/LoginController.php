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
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email / password pegawai salah']);
    }

    public function parentLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Debug: Log input
        \Log::info('Login attempt for email: ' . $request->email);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'orangtua'
        ], $request->remember)) {
            $user = Auth::user();
            
            // Debug: Log success
            \Log::info('Login successful for user: ' . $user->email . ', role: ' . $user->role . ', password_changed: ' . $user->password_changed);
            
            if (empty($user->password_changed_at)) {
                return redirect()->route('parent.password.edit');
            }

            return redirect()->route('parent.dashboard');
        }

        // Debug: Log failed attempt
        \Log::warning('Login failed for email: ' . $request->email);

        return back()->withErrors(['email' => 'Email / password orang tua salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
