  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Hasil Tes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Hasil Tes</li>
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
              <a href="javascript: void(0)" data-href="<?= base_url('hasil/export/semua') ?>" data-title="Semua Hasil Tes Diagnostik" class="btn btn-primary export-excel">Export ke Excel</a>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>NISN</th>
                  <th>Nama Lengkap</th>
                  <th>Asal Sekolah</th>
                  <th>Waktu Pengerjaan</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->no          = ($key+1);
                    $value->href_edit   = base_url('hasil/detail/'.$value->answer_id);
                    // $value->birthDate   = date("d-m-Y", strtotime($value->birth_date));
                    $value->jwbn        = substr($value->jawaban, 0, 20);
                    echo "
                      <tr data-id='{$value->answer_id}'>
                        <td>{$value->no}</td>
                        <td>{$value->nik}</td>
                        <td>{$value->fullname}</td>
                        <td>{$value->schools}</td>
                        <td>{$value->timeDiff}</td>
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
                    <th>Asal Sekolah</th>
                    <th>Waktu Pengerjaan</th>
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