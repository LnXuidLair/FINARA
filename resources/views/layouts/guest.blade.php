<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 p-0 h-screen w-screen overflow-hidden">
    <header class="w-full flex justify-between items-center px-6 py-3 bg-[linear-gradient(to_right,#C8A2C8_0%,#BFA8E6_20%,#A9B7F5_40%,#8EBBFF_60%,#99D0FF_80%,#90E0EF_100%)] shadow">
        <div class="flex items-center gap-3 font-bold text-xl">
            <img src="{{ asset('assets/images/finara.png') }}" class="h-10">
            FINARA -
            <span>Finacial App Nusantara</span>
        </div>
        <h1 class="text-4xl font-extrabold italic" style="color:#3700FF;">
            @yield('header-title')
        </h1>
        <div class="w-32"></div>
    </header>
    <div class="flex w-full h-[calc(102vh-79px)]">
        <div class="w-3/4 h-full">
            <img src="{{ asset('assets/images/bg-sekolah.jpg') }}"
                 class="w-full h-full object-cover">
        </div>
        <div class="w-1/4 h-full bg-[linear-gradient(to_bottom,#F3E3E3_0%,#CF30A2_100%)] flex flex-col items-center pt-8 px-4 overflow-y-auto">
            <img src="{{ asset('assets/images/finara1.png') }}" class="w-40 mb-4">
            @yield('content')
            <a href="{{ url('/') }}" class="mb-3 inline-flex items-center gap-2 text-[#1A237E] font-bold hover:underline">
                Back to Home
            </a>            
        </div>
    </div>
</body>
</html>
