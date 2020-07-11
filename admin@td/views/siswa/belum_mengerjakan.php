  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Siswa Belum Mengerjakan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Siswa Belum Mengerjakan</li>
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
              <!-- bantuan untuk halaman ini -->
              <div id="help" class="collapse">
                <p>
                  <b>! Catatan</b><br>
                  1. <b>Filter berdasarkan asal sekolah</b> digunakan untuk menyeleksi tampilan data dan export data berdasarkan asal sekolah.<br>
                  2. <b>Export ke Excel</b> digunakan untuk mendapatkan data dalam format (.xls).<br>  
                  3. Informasi dibawah ini dirutkan secara <b>Descending</b> berdasarkan tanggal dan waktu pendaftaran atau registrasi.<br>
                  4. <b>Actions</b>-><b>Delete</b> digunakan untuk menghapus data siswa yang dipilih.
                </p>
              </div>
              <!-- end bantuan untuk halaman ini -->

              <form class="form-inline" action="<?= base_url("siswa/belum-mengerjakan") ?>">
                <div class="input-group mb-3 w-100">
                  <div class="input-group-prepend">
                    <button class="btn btn-success" data-toggle="collapse" data-target="#help"> <i class="fa fa-question-circle"></i> Bantuan</button>
                  </div>
                  <div class="input-group-prepend">
                    <span class="input-group-text">Filter berdasarkan asal sekolah</span>
                  </div>
                  <select class="form-control" name="schools" onchange="this.form.submit()" method="GET">
                    <option value="0">Semua Siswa</option>
                    <?php
                      foreach ($asal_sekolah as $key => $value) {
                        $value->selected = ($value->schools==$title)? 'selected' : NULL ;
                        echo "<option value='{$value->schools}' {$value->selected}>{$value->schools}</option>";
                      }
                    ?>
                  </select>
                  <div class="input-group-prepend">
                    <a href="javascript: void(0)" data-href="<?= $export ?>" data-title="<?= $export_title ?>" class="btn btn-primary export-excel" title="Export <?= $export_title ?>">Export ke Excel</a>
                  </div>
                </div>
              </form>
              <hr>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Tgl Pendaftaran</th>
                  <th>NISN</th>
                  <th>Nama Lengkap</th>
                  <th>Tgl Lahir</th>
                  <th>Asal Sekolah</th>
                  <th>Email</th>
                  <th>Telepon</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->no              = ($key+1);
                    $value->tglPendaftaran  = date("d/m/Y H:i:s", strtotime($value->create_at));
                    $value->birthDate       = date("d/m/Y", strtotime($value->birth_date));
                    $value->href_delete     = base_url('siswa/delete/'.$value->username);

                    echo "
                      <tr>
                        <td>{$value->no}</td>
                        <td>{$value->tglPendaftaran}</td>
                        <td>{$value->nik}</td>
                        <td>{$value->fullname}</td>
                        <td>{$value->birthDate}</td>
                        <td>{$value->schools}</td>
                        <td>{$value->email}</td>
                        <td>{$value->telp}</td>
                        <td>
                          <div class='btn-group'>
                            <button type='button' class='btn btn-default'>Action</button>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                              <span class='caret'></span>
                              <span class='sr-only'>Toggle Dropdown</span>
                            </button>
                            <div class='dropdown-menu' role='menu' x-placement='top-start' style='position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, -165px, 0px);'>
                              <a class='dropdown-item delete-confirm' href='{$value->href_delete}' data-confirm='Apakah anda yakin akan menghapus user ini?'>Delete</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    ";
                  }
                ?>
                
                </tbody>
                <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Tgl Pendaftaran</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Tgl Lahir</th>
                    <th>Asal Sekolah</th>
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