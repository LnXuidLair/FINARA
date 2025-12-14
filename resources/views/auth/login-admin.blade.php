@extends('layouts.guest')
@section('title', 'FINARA | Finacial App Nusantara')
@section('header-title')
    Welcome Admin
@endsection
@section('content')
<form method="POST" action="{{ route('login.admin') }}">
    @csrf
    <h3 class="text-3xl font-bold text-center mb-6 text-[#3700FF]">Login</h3>
    <div class="mb-4">
        <input type="email" name="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E] required" required>
    </div>
    <div class="mb-4">
        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="flex justify-between items-center text-[#1A237E] font-semibold text-sm mb-3">
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="remember" class="w-6 h-6 rounded border-[#1A237E] bg-[#4CC0FF]">
            <span>Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="hover:underline">
            Forgot Password?
        </a>
    </div>
    <button type="submit" class="w-full py-2 rounded-xl text-[#DCBCBC] font-bold text-xl bg-[linear-gradient(to_bottom,_#6B7AD2_0%,_#3340FF_20%,_#1337A2_40%,_#170480_60%,_#3E4DD1_80%,_#688DD6_100%)]">
        Login
    </button>
    <p class="text-center mt-4 text-[#1A237E] font-semibold">
        Belum Punya Akun? 
        <a href="{{ route('register.admin') }}" class="hover:underline">Buat Sekarang</a>
    </p>
</form>
@endsection