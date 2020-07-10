  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Siswa Mendaftar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Siswa Mendaftar</li>
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
                  <th>Tgl Pendaftaran</th>
                  <th>NISN</th>
                  <th>Nama Lengkap</th>
                  <th>Tgl Lahir</th>
                  <th>Asal SMA</th>
                  <th>Email</th>
                  <th>Telepon</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->tglPendaftaran  = date("d-m-Y H:i:s", strtotime($value->create_at));
                    $value->statusSiswa     = $value->block=='1' ? 'Mendaftar' : 'Terdaftar' ;
                    $value->no              = ($key+1);
                    $value->birthDate       = date("d-m-Y", strtotime($value->birth_date));

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
                        <td>{$value->statusSiswa}</td>
                        <td>
                          <div class='btn-group'>
                            <button type='button' class='btn btn-default'>Action</button>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                              <span class='caret'></span>
                              <span class='sr-only'>Toggle Dropdown</span>
                            </button>
                            <div class='dropdown-menu' role='menu' x-placement='top-start' style='position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, -165px, 0px);'>
                              <a class='dropdown-item delete-confirm' href='{$value->href_delete}' data-confirm='Apakah anda yakin akan menghapus user ini, jika iya data user maupun hasil tes user ini akan dihapus?'>Delete</a>
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
                    <th>Asal SMA</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
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