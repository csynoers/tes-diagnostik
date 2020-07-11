<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>
    <table>
      <tr style="border-bottom: 1px solid #ddd;">
        <td id="tglSekarang" colspan="4"></td>
      </tr>
      <tr>
        <td>Jam </td>
        <td id="jam"></td>
        <td id="menit"></td>
        <td id="detik"></td>
      </tr>
    </table>

    <!-- Right navbar links -->
    <div class="navbar-nav ml-auto">
      <a href="javascript: void(0)" data-href="<?php echo base_url() ?>user/edit" data-title="Edit Informasi Profil" class="btn btn-default mr-2 form-edit">Profil</a>
      <a href="<?php echo base_url('../auth/logout'); ?>" class="btn btn-default">Logout</a>
      
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
        <i class="fa fa-th-large"></i>
      </a>
    
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link">
      <img src="data:image/png;base64,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" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Tes Diagnostik</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="<?= base_url() ?>" class="nav-link <?php echo ($this->uri->segment(1)=='dashboard') ? 'active' : null ; ?>">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Beranda
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="<?php echo base_url('pages') ?>" class="nav-link <?php echo ($this->uri->segment(1)=='pages') ? 'active' : null ; ?>">
              <i class="nav-icon fa fa-book"></i>
              <p>
                Pages
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview <?php echo ($this->uri->segment(1) == 'kategori' || $this->uri->segment(1) == 'soal') ? 'menu-open' : null ; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa fa-database"></i>
              <p>
                Master Data
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" >
                <a href="<?php echo base_url('kategori/soal') ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'kategori' && $this->uri->segment(2) == 'soal') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Kategori Soal</p>
                </a>
              </li>
              <li class="nav-item" >
                <a href="<?php echo base_url('soal/index') ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'soal' && $this->uri->segment(2) == 'index') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Soal</p>
                </a>
              </li>
              <li class="nav-item" >
                <a href="<?php echo base_url('kategori/asal-sekolah') ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'kategori' && $this->uri->segment(2) == 'asal-sekolah') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Asal Sekolah</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview <?php echo ($this->uri->segment(1) == 'siswa') ? 'menu-open' : null ; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Siswa
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" >
                <a href="<?php echo base_url('siswa/mendaftar') ?>" class="nav-link <?php echo ($this->uri->segment(2) == 'mendaftar') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Siswa Mendaftar</p>
                </a>
              </li>
              <li class="nav-item" >
                <a href="<?php echo base_url('siswa/terdaftar') ?>" class="nav-link <?php echo ($this->uri->segment(2) == 'terdaftar') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Siswa Terdaftar</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('siswa/konfirmasi')?>" class="nav-link <?php echo ($this->uri->segment(2) == 'konfirmasi') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Konfirmasi Siswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('siswa/belum-mengerjakan')?>" class="nav-link <?php echo ($this->uri->segment(2) == 'belum-mengerjakan') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Siswa Belum Mengerjakan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url('siswa/sedang-mengerjakan')?>" class="nav-link <?php echo ($this->uri->segment(2) == 'sedang-mengerjakan') ? 'active' : null ; ?>">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Siswa Sedang Mengerjakan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="<?php echo base_url('hasil/index') ?>" class="nav-link <?php echo ($this->uri->segment(1)=='hasil') ? 'active' : null ; ?>">
              <i class="nav-icon fa fa-calendar-check-o"></i>
              <p>
                Hasil Tes
              </p>
            </a>
          </li>
          <!-- <li class="nav-item ">
            <a href="<?php echo base_url('ujian/konfigurasi') ?>" class="nav-link <?php echo ($this->uri->segment(2)=='konfigurasi') ? 'active' : null ; ?>">
              <i class="nav-icon fa fa-gears"></i>
              <p>
                Konfigurasi Ujian
              </p>
            </a>
          </li> -->
          <!-- <li class="nav-item ">
            <a href="<?php echo base_url('bank') ?>" class="nav-link <?php echo ($this->uri->segment(1)=='bank') ? 'active' : null ; ?>">
              <i class="nav-icon fa fa-credit-card"></i>
              <p>
                Bank Transfer
              </p>
            </a>
          </li> -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>