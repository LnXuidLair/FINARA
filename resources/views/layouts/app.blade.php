<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IDAZCROCHET</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />
    <link href="{{ asset('assets/css/lib/weather-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/lib/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/lib/owl.theme.default.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/lib/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/menubar/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    {{-- ======================================================
        SIDEBAR SESUAI ROLE PENGGUNA  // FIXED HERE
    ======================================================= --}}
    @auth
        @if(Auth::user()->role === 'admin')
            @include('layouts.sidebar-admin')
        @elseif(Auth::user()->role === 'parent')
            @include('layouts.sidebar-parent')
        @elseif(Auth::user()->role === 'staff')
            @include('layouts.sidebar-staff')
        @endif
    @endauth


    {{-- Navigation --}}
    @include('layouts.navigation')

    <div class="content-wrap overflow-hidden">
        <div class="main">
            <div class="container-fluid">

                {{-- ======= HEADER HALAMAN ======= --}}
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 p-r-0 title-margin-right">
                        <div class="page-header">

                            <div class="page-title">
                                {{-- FIXED: nama berubah sesuai role --}}
                                <h1>Hello, {{ Auth::user()->name }} <br>
                                    <span>
                                        @if(Auth::user()->role === 'admin')
                                            Welcome to Admin Dashboard
                                        @elseif(Auth::user()->role === 'parent')
                                            Welcome to Parent Dashboard
                                        @elseif(Auth::user()->role === 'staff')
                                            Welcome to Staff Dashboard
                                        @endif
                                    </span>
                                </h1>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 p-l-0 title-margin-left">
                        <div class="page-header page_header_2">

                            <div class="page-title">
                                <ol class="breadcrumb">
                                    {{-- FIXED: breadcrumb dinamis --}}
                                    <li class="breadcrumb-item">
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ url('/dashboard') }}">Dashboard Admin</a>
                                        @elseif(Auth::user()->role === 'parent')
                                            <a href="{{ url('/dashboard-orangtua') }}">Dashboard Orangtua</a>
                                        @elseif(Auth::user()->role === 'staff')
                                            <a href="{{ url('/dashboard-staff') }}">Dashboard Staff</a>
                                        @endif
                                    </li>

                                    <li class="breadcrumb-item active">Home</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ======== KONTEN DINAMIS ======== --}}
                @yield('content')

            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.nanoscroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/menubar/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/lib/preloader/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <script src="{{ asset('assets/js/lib/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/circle-progress/circle-progress-init.js') }}"></script>
    <script src="{{ asset('assets/js/lib/morris-chart/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/morris-chart/morris.js') }}"></script>
    <script src="{{ asset('assets/js/lib/flot-chart/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/js/lib/flot-chart/jquery.flot.resize.js') }}"></script>

    <script src="{{ asset('assets/js/lib/vector-map/jquery.vmap.js') }}"></script>
    <script src="{{ asset('assets/js/lib/vector-map/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/vector-map/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ asset('assets/js/lib/vector-map/country/jquery.vmap.world.js') }}"></script>

    <script src="{{ asset('assets/js/lib/weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/weather/weather-init.js') }}"></script>
    <script src="{{ asset('assets/js/lib/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/owl-carousel/owl.carousel-init.js') }}"></script>

    <script src="{{ asset('assets/js/dashboard1.js') }}"></script>

    @stack('scripts')

</body>

</html>
