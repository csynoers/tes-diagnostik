  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Beranda</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin') ?>">Beranda</a></li>
              <!-- <li class="breadcrumb-item active">Dashboard</li> -->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="alert alert-info alert-dismissible">
              <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
              <h5><i class="icon fa fa-check"></i> Info!</h5>
              Selamat Datang Di halaman Admin.
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?= $siswa_terdaftar+$pembayaran_belum_dikonfirmasi ?></h3>
                <p style="height: 45px;">Siswa Terdaftar</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="<?php echo base_url('siswa/mendaftar')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $pembayaran_belum_dikonfirmasi ?></sup></h3>
                <p style="height: 45px;">Siswa Belum Dikonfirmasi</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?php echo base_url('siswa/konfirmasi') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $siswa_terdaftar ?></h3>
                <p style="height: 45px;">Siswa Sudah Dikonfirmasi</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="<?php echo base_url('siswa/terdaftar')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $siswa_terdaftar-$hasil_try_out ?></h3>
                <p style="height: 45px;">Siswa Belum Mengerjakan Tes</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
              </div>
              <a href="javascript:void(0);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <div class="small-box bg-warning" style="background-color: #fd7e14 !important;">
              <div class="inner">
                <h3><?= $siswa_sedang_mengerjakan ?></h3>
                <p style="height: 45px;">Siswa Sedang Mengerjakan Tes</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-minus-o"></i>
              </div>
              <a href="javascript:void(0);" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $hasil_try_out ?></h3>
                <p style="height: 45px;">Siswa Sudah Mengerjakan Tes<br>dan Hasil Tesnya</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o"></i>
              </div>
              <a href="<?php echo base_url('hasil') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $total_soal ?></h3>
                <p style="height: 45px;">Total Soal</p>
              </div>
              <div class="icon">
                <i class="fa fa-clipboard"></i>
              </div>
              <a href="<?php echo base_url('soal') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->