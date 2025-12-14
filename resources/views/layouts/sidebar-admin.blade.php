<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
      <div class="nano-content">
        <div class="logo"><a href="{{ url('/dashboard') }}"><img src="{{ asset('assets/images/dashboard admin.jpg') }}" alt="" width="150" height="auto" style="display:block; margin:0 auto;" /></a></div>
        <ul>
          <li class="label">Dashboard</li>
          <li><a href="{{ url('/dashboard') }}"><i class="ti-home"></i> Dashboard</a></li>
          <li class="label">Master Data</li>
          <li><a href="{{ url('siswa') }}"><i class="ti-user"></i> Siswa</a></li>
          <li><a href="{{ url('coa') }}"><i class="ti ti-clipboard"></i> COA </a></li>
          <li><a href="{{ url('pegawai') }}"><i class="ti ti-clipboard"></i> Pegawai</a></li>
          <li><a href="{{ url('orangtua') }}"><i class="ti-user"></i> Orangtua</a></li>
          <li><a href="{{ url('gajijabatan') }}"><i class="ti-user"></i> Gaji dan Jabatan</a></li>
          <li class="label">Transaksi</li>
          <li><a href="{{ url('pembayaran_siswa') }}"><i class="ti ti-clipboard"></i> Pembayaran Siswa</a></li>
          <li><a href="{{ route('admin.presensi.index') }}"><i class="ti ti-clipboard"></i> Presensi </a></li>
          <li><a href="{{ route('admin.penggajian.index') }}"><i class="ti ti-clipboard"></i> Penggajian </a></li>
          <li><a href="{{ route('admin.pengeluaran.index') }}"><i class="ti ti-clipboard"></i> Pengeluaran </a></li>
          <li class="label">Laporan</li>
          <li><a class="sidebar-sub-toggle"><i class="ti-file"></i> Laporan <span class="sidebar-collapse-icon ti-angle-down"></span></a>
            <ul>
              <li><a href="{{ url('admin/laporan/jurnal') }}"><i class="ti-book"></i> Jurnal Umum</a></li>
              <li><a href="{{ url('admin/laporan/keuangan') }}"><i class="ti-stats-up"></i> Laporan Arus Kas Pembukuan TK</a></li>
            </ul>
          </li>
          </ul>
      </div>
    </div>
  </div>