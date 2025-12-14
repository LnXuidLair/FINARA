<div class="header">
    <div class="container-fluid">
        <div class="row align-items-center">
            {{-- ==== LEFT AREA ==== --}}
            <div class="col-lg-4 col-md-4 col-sm-6 d-flex align-items-center">
                {{-- Sidebar Toggle --}}
                <div class="hamburger sidebar-toggle me-3">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>
            {{-- ==== CENTER SEARCH ==== --}}
            <div class="col-lg-5 col-md-4 d-none d-md-block">
                <div class="search_wrap">
                    <div class="inbox-head top_search">
                        <form class="position inbox_input">
                            <div class="input-append inner-append border_11">
                                <input type="text" class="sr-input" placeholder="Search">
                                <button class="btn sr-btn append-btn btn33" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ==== RIGHT USER DROPDOWN ==== --}}
            <div class="col-lg-3 col-md-4 col-sm-6 text-end">
                <div class="dropdown dib">
                    <div class="header-icon" data-toggle="dropdown">
                        <span class="user-avatar">
                            {{ ucfirst(auth()->user()->role) }}
                            <i class="ti-angle-down f-s-10"></i>
                        </span>
                        <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right">
                            <div class="dropdown-content-body">
                                <ul>
                                    <li class="text-center py-2 fw-bold">
                                        {{ auth()->user()->name }}
                                    </li>
                                    <li><hr></li>
                                    <li>
                                        <a href="{{ route('profile.edit') }}">
                                            <i class="ti-user"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="ti-email"></i> Inbox
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="ti-settings"></i> Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="ti-lock"></i> Lock Screen
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ti-power-off"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ==== MOBILE SEARCH ==== --}}
            <div class="col-12 d-block d-md-none mt-2">
                <div class="search_wrap">
                    <div class="inbox-head top_search">
                        <form class="position inbox_input">
                            <div class="input-append inner-append border_11">
                                <input type="text" class="sr-input" placeholder="Search">
                                <button class="btn sr-btn append-btn btn33" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>