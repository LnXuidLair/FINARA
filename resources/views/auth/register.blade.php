@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Register</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full px-3 py-2 border rounded shadow-sm">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-3 py-2 border rounded shadow-sm">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full px-3 py-2 border rounded shadow-sm">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full px-3 py-2 border rounded shadow-sm">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Register
        </button>

        <p class="mt-4 text-sm text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login sekarang</a>
        </p>
    </form>
@endsection