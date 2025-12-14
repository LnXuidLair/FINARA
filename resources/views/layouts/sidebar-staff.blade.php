<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
      <div class="nano-content">
        <div class="logo"><a href="{{ url('/dashboard-staff') }}"><img src="{{ asset('assets/images/logo.png.jpg') }}" alt="" width="150" height="auto" style="display:block; margin:0 auto; mix-blend-mode: multiply;" /></a></div>
        <ul>
          <li class="label">Master Data</li>
          <li><a href="{{ url('siswa') }}"><i class="ti-user"></i> Siswa</a></li>
          <li><a href="{{ url('coa') }}"><i class="ti ti-clipboard"></i> COA </a></li>
          <li><a href="{{ url('pegawai') }}"><i class="ti ti-clipboard"></i> Pegawai</a></li>
          <li><a href="{{ url('orangtua') }}"><i class="ti-user"></i> Orangtua</a></li>
          <li><a href="{{ url('gaji_jabatan') }}"><i class="ti-user"></i> Gaji dan Jabatan</a></li>
          <li class="label">Transaksi</li>
          <li><a href="{{ url('pembayaran_siswa') }}"><i class="ti ti-clipboard"></i> Pembayaran Siswa</a></li>
          <li><a href="{{ url('presensi') }}"><i class="ti ti-clipboard"></i> Presensi </a></li>
          <li><a href="{{ url('penggajian') }}"><i class="ti ti-clipboard"></i> Penggajian </a></li>
          <li><a href="{{ route('admin.pengeluaran.index') }}"><i class="ti ti-clipboard"></i> Pengeluaran </a></li>
          <li class="label">Form</li>
          <li><a href="form-basic.html"><i class="ti-view-list-alt"></i> Basic Form </a></li>
          <li><a href="form-validation.html"><i class="fa fa-list"></i>Form validation</a></li>
          <li class="label">Extra</li>
          <li><a class="sidebar-sub-toggle"><i class="ti-files"></i> Invoice <span class="sidebar-collapse-icon ti-angle-down"></span></a>
            <ul>
              <li><a href="invoice.html">Basic</a></li>
            </ul>
          </li>
          <li><a class="sidebar-sub-toggle"><i class="ti-target"></i> Pages <span class="sidebar-collapse-icon ti-angle-down"></span></a>
            <ul>
              <li><a href="page-login.html">Login</a></li>
              <li><a href="page-register.html">Register</a></li>
              <li><a href="page-reset-password.html">Forgot password</a></li>
               <li><a href="blank.html">blank page</a></li>
            </ul>
          </li>
          <li><a><i class="ti-close"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </div>