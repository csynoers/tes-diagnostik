  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Siswa Terdaftar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Siswa Terdaftar</li>
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
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>NIK</th>
                  <th>Nama Lengkap</th>
                  <th>Tgl Lahir</th>
                  <th>Asal SMA</th>
                  <th>Email</th>
                  <th>Telepon</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    // print_r($value);
                    $value->no          = ($key+1);
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
                      </tr>
                    ";
                  }
                ?>
                
                </tbody>
                <tfoot>
                  <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Tgl Lahir</th>
                    <th>Asal SMA</th>
                    <th>Email</th>
                    <th>Telepon</th>
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