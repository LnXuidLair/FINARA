<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
        <div class="nano-content">

            <div class="logo text-center py-3">
                <img src="{{ asset('assets/images/finara1.png') }}" width="130">
                <h5 class="text-white mt-2">FINARA</h5>
            </div>

            <ul>
                {{-- ===== Dashboard ===== --}}
                <li class="{{ request()->routeIs(auth()->user()->role . '.dashboard') ? 'active' : '' }}">
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}">
                        <i class="ti-dashboard"></i> Dashboard
                    </a>
                </li>

                {{-- ================= ADMIN ================= --}}
                @if(auth()->user()->role === 'admin')

                    <li class="label">Master Data</li>
                    <li><a href="{{ route('coa.index') }}"><i class="ti-layers"></i> CoA</a></li>
                    <li><a href="{{ route('pegawai.index') }}"><i class="ti-id-badge"></i> Daftar Pegawai</a></li>
                    <li><a href="{{ route('orangtua.index') }}"><i class="ti-user"></i> Daftar Orang Tua</a></li>
                    <li><a href="{{ route('siswa.index') }}"><i class="ti-book"></i> Daftar Siswa</a></li>
                    <li><a href="{{ route('gajijabatan.index') }}"><i class="ti-id-badge"></i> Gaji Jabatan</a></li>

                    <li class="label">Absensi & Penggajian</li>
                    <li><a href="{{ route('presensi.index') }}"><i class="ti-layers"></i> Presensi</a></li>
                    <li><a href="{{ route('penggajian.index') }}"><i class="ti-user"></i> Penggajian</a></li>
                    <li><a href="{{ route('pengeluaran.index') }}"><i class="ti-id-badge"></i> Pengeluaran</a></li>

                    <li class="label">Laporan</li>
                    <li><a href="{{ route('jurnal.index') }}"><i class="ti-file"></i> Jurnal</a></li>

                @endif

                {{-- ================= PEGAWAI ================= --}}
                @if(auth()->user()->role === 'pegawai')

                    <li><a href="{{ route('siswa.index') }}">
                        <i class="ti-book"></i> Data Siswa
                    </a></li>

                @endif

                {{-- ================= ORANGTUA ================= --}}
                @if(auth()->user()->role === 'orangtua')

                    <li><a href="{{ route('orangtua.anak') }}">
                        <i class="ti-user"></i> Anak Saya
                    </a></li>

                @endif

                {{-- ===== Logout ===== --}}
                <li class="mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                           class="text-danger">
                            <i class="ti-power-off"></i> Logout
                        </a>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>