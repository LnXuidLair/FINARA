@extends('layouts.guest')
@section('title', 'FINARA | Finacial App Nusantara')
@section('header-title')
    Welcome Staff
@endsection
@section('content')
<form method="POST" action="{{ route('login.staff') }}">
    @csrf
    <h3 class="text-3xl font-bold text-center mb-6 text-[#3700FF]">Login</h3>
    <div class="mb-4">
        <input type="text" name="username" placeholder="Username" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E] required">
    </div>
    <div class="mb-4">
        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E] required">
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
    <button  type="submit" class="w-full py-2 rounded-xl text-[#DCBCBC] font-bold text-xl bg-[linear-gradient(to_bottom,_#916FF5_0%,_#6637E8_20%,_#3B099F_40%,_#4F33B1_60%,_#5A33A9_80%,_#8467DB_100%)]">
        Login
    </button>
    <p class="text-center mt-4 text-[#1A237E] font-semibold">
        Belum Punya Akses?
        <a href="{{ route('register.staff') }}" class="hover:underline">Aktifkan Sekarang</a>
    </p>
</form>
@endsection