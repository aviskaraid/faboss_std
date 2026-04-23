<style>
#accordionSidebar {
    background: linear-gradient(
        180deg,
        #1F4F32 0%,
        #2F6B3F 45%,
        #163824 100%
    );

    /* Right side shadow */
    box-shadow: 6px 0 18px rgba(0, 0, 0, 0.35);
    z-index: 100; /* ensures shadow stays above content */
}

/* Sidebar text & icons */
#accordionSidebar .nav-link,
#accordionSidebar .sidebar-heading,
#accordionSidebar .nav-link i {
    color: rgba(255, 255, 255, 0.9);
}

/* Hover & active states */
#accordionSidebar .nav-link:hover,
#accordionSidebar .nav-item.active > .nav-link {
    background-color: rgba(255, 255, 255, 0.08);
    color: #ffffff;
}

/* Keep brand content inside the allowed height */
.sidebar-brand {
    padding-top: 12px;
    padding-bottom: 12px;
}

/* Wrapper keeps things centered */
.brand-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Logo sizing */
.brand-logo {
    margin-top: 80px;
    width: 100%;
    max-height: 80px;
    object-fit: contain;
}

/* Brand name spacing */
.brand-text {
    margin-top: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.2;
    text-align: center;
    color: rgba(255, 255, 255, 0.95);
}

.sidebar-divider-brand-gap {
    margin-top: 100px !important;
}    

</style>

<ul class="navbar-nav sidebar sidebar-dark accordion"
    id="accordionSidebar">

  <!-- Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center"
   href="<?= base_url('dashboard'); ?>">

    <div class="brand-wrapper text-center">
        <img src="<?= base_url('assets/img/logo.png'); ?>" class="brand-logo">
        <div class="brand-text">
            Financial Accounting System
        </div>
    </div>

  </a>
  
  <?php if(is_admin() || is_user()) { ?>

  
  <hr class="sidebar-divider my-0 sidebar-divider-brand-gap">


  <!-- Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('dashboard'); ?>">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Monitoring -->
  <div class="sidebar-heading">Monitoring</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('masterdata/realtime'); ?>">
      <i class="fas fa-fw fa-bolt"></i>
      <span>Realtime Akun</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Master Data -->
  <div class="sidebar-heading">Master Data</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('masterdata'); ?>">
      <i class="fas fa-fw fa-list"></i>
      <span>Data Akun</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('kelompokakun'); ?>">
      <i class="fa-solid fa-sitemap"></i>
      <span>Kelompok Akun</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('kas'); ?>">
      <i class="fas fa-fw fa-university"></i>
      <span>Kas Tunai & Bank</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('customer'); ?>">
      <i class="fa-solid fa-cart-shopping"></i>
      <span>Customer</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('supplier'); ?>">
      <i class="fa-solid fa-truck-fast"></i>
      <span>Supplier</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('biaya'); ?>">
      <i class="fas fa-fw fa-file-invoice-dollar"></i>
      <span>Daftar Biaya</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('pendapatan'); ?>">
      <i class="fas fa-fw fa-file-invoice-dollar"></i>
      <span>Daftar Pendapatan</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Jurnal -->
  <div class="sidebar-heading">Jurnal</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('journal'); ?>">
      <i class="fas fa-fw fa-book-open"></i>
      <span>Jurnal Umum</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Proses -->
  <div class="sidebar-heading">Proses Periodikal</div>

  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa-regular fa-folder"></i>
      <span>Posting</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="#">
      <i class="fa-regular fa-folder-open"></i>
      <span>Unposting</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('periode'); ?>">
      <i class="fas fa-fw fa-calendar-alt"></i>
      <span>Periode Akuntansi</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Transaksi -->
  <div class="sidebar-heading">Transaksi Kas/Bank</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('transaksibiaya'); ?>">
      <i class="fa-solid fa-folder-minus"></i>
      <span>Transaksi Biaya</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('transaksipendapatan'); ?>">
      <i class="fa-solid fa-folder-plus"></i>
      <span>Transaksi Pendapatan</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Piutang & Utang -->
  <div class="sidebar-heading">Piutang & Utang</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('piutang'); ?>">
      <i class="fas fa-fw fa-hand-holding-usd"></i>
      <span>Piutang</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('utang'); ?>">
      <i class="fa-solid fa-file-invoice-dollar"></i>
      <span>Hutang</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Aset -->
  <div class="sidebar-heading">Aset</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('asset'); ?>">
      <i class="fas fa-fw fa-building"></i>
      <span>Daftar Aset</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('asset/nonaktif'); ?>">
      <i class="fa-regular fa-file-zipper"></i>
      <span>Aset Nonaktif</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Laporan -->
  <div class="sidebar-heading">Laporan</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('report'); ?>">
      <i class="fas fa-fw fa-book"></i>
      <span>Buku Besar</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('neracasaldo/index'); ?>">
      <i class="fas fa-fw fa-balance-scale"></i>
      <span>Neraca Saldo</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0 collapsed" href="#" data-toggle="collapse"
       data-target="#collapseLapKeu" aria-expanded="false">
      <i class="fas fa-fw fa-file-alt"></i>
      <span>Laporan Keuangan</span>
    </a>
    <div id="collapseLapKeu" class="collapse" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?= base_url('labarugi/index'); ?>">
          <i class="fas fa-chart-line mr-2"></i> Laba Rugi
        </a>
        <a class="collapse-item" href="<?= base_url('neraca/index'); ?>">
          <i class="fas fa-landmark mr-2"></i> Neraca
        </a>
        <a class="collapse-item" href="<?= base_url('report/cashflowstatement'); ?>">
          <i class="fas fa-stream mr-2"></i> Arus Kas
        </a>
      </div>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('laporankas'); ?>">
      <i class="fa-regular fa-money-bill-1"></i>
      <span>Kas / Bank</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('umurpiutang'); ?>">
      <i class="fa-solid fa-file-circle-plus"></i>
      <span>Umur Piutang</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('umurutang'); ?>">
      <i class="fa-solid fa-file-circle-minus"></i>
      <span>Umur Hutang</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Setting -->
  <div class="sidebar-heading">Pengaturan</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('import'); ?>">
      <i class="fas fa-fw fa-file-import"></i>
      <span>Import Data</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('setting'); ?>">
      <i class="fas fa-fw fa-cogs"></i>
      <span>Pengaturan</span>
    </a>
  </li>
  

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('profilecompany'); ?>">
      <i class="fas fa-fw fa-id-card"></i>
      <span>Profile Perusahaan</span>
    </a>
  </li>

  <?php } ?>

  <?php if(is_admin()) { ?>
    
  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('admin/user_management'); ?>">
      <i class="fas fa-fw fa-users-cog"></i>
      <span>User Management</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('menu'); ?>">
      <i class="fas fa-fw fa-list"></i>
      <span>Menu Management</span>
    </a>
  </li>

<?php } ?>

<?php if(is_keuangan()) { ?>

  <hr class="sidebar-divider">

  <!-- Transaksi -->
  <div class="sidebar-heading">Transaksi Biaya</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('transaksikas'); ?>">
      <i class="fas fa-fw fa-exchange-alt"></i>
      <span>Transaksi Kas / Bank</span>
    </a>
  </li>

  <hr class="sidebar-divider">

  <!-- Piutang & Utang -->
  <div class="sidebar-heading">Piutang & Utang</div>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('piutang'); ?>">
      <i class="fas fa-fw fa-hand-holding-usd"></i>
      <span>Piutang</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link pt-0" href="<?= base_url('utang'); ?>">
      <i class="fas fa-fw fa-file-contract"></i>
      <span>Utang</span>
    </a>
  </li>

<?php } ?>

  <hr class="sidebar-divider my-0">

  <!-- Logout -->
  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('auth/logout'); ?>">
      <i class="fas fa-fw fa-sign-out-alt"></i>
      <span>Logout</span>
    </a>
  </li>

  <hr class="sidebar-divider d-none d-md-block">

  <!-- Toggle -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
