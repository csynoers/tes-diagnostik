  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Siswa Belum Dikonfirmasi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Konfirmasi Siswa</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <p>Cara melakukan konfirmasi verifikasi data siswa :</p>
              <p>
                <ol>
                  <li>Pilih menu "Detail" pada kolom actions.</li>
                  <li>Jika sudah sama silahkan pilih menu "Konfirmasi Siswa", sistem akan otomatis mengirimkan notifikasi verifikasi data pendaftar kepada email Siswa.</li>
                  <li>Dengan melakukan konfirmasi siswa, status siswa akan berubah menjadi <a href="<?= base_url('siswa/terdaftar') ?>" class="text-info">Siswa terdaftar</a></li>
                  <li>Jika tidak sama pilih menu "Close" saja pada kolom kanan bawah.</li>
                </ol>
              </p>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>NISN</th>
                  <th>Nama Lengkap</th>
                  <th>Tgl Lahir</th>
                  <th>Asal SMA</th>
                  <th>Email</th>
                  <th>Telepon</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  // echo '<pre>';
                  // print_r($rows);
                  // echo '</pre>';
                  foreach ($rows as $key => $value) {
                    $value->no          = ($key+1);
                    $value->href_edit   = base_url('siswa/konfirmasi/'.$value->id);
                    $value->birthDate   = date("d-m-Y", strtotime($value->birth_date));
                    echo "
                      <tr>
                        <td>{$value->no}</td>
                        <td>{$value->nik}</td>
                        <td>{$value->fullname}</td>
                        <td>{$value->birthDate}</td>
                        <td>{$value->schools}</td>
                        <td>{$value->email}</td>
                        <td>{$value->telp}</td>
                        <td>
                          <a class='btn btn-primary form-edit' data-title='Detail Siswa' data-href='{$value->href_edit}' href='javascript:void(0)'>Detail</a>
                        </td>
                      </tr>
                    ";
                  }
                ?>
                
                </tbody>
                <tfoot>
                  <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Tgl Lahir</th>
                    <th>Asal SMA</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Actions</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->