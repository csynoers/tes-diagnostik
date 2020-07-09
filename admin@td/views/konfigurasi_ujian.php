  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Informasi Konfigurasi Ujian</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Konfigurasi Ujian</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="border border-primary card">
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <form action='javascript: void(0)' data-action='<?= base_url('ujian/konfigurasi/post/'.$token->id) ?>' class='col-6'>
                  <div class='input-group'>
                    <div class='input-group-prepend'>
                      <span class='input-group-text'>Biaya aktivasi token</span>
                    </div>
                    <div class='input-group-prepend'>
                      <span class='input-group-text'>Rp. </span>
                    </div>
                    <input min='1' value='<?= $token->text ?>' type='number' name='text' class='form-control' placeholder='Masukan biaya disini type number...' required=''>
                    <div class='input-group-prepend'>
                      <input type='hidden' name='store' value='configs'>
                      <button type='submit' class='bg-primary-gradient btn btn-primary input-group-text'>Save</button>
                    </div>
                  </div>
                </form>
                <!-- / form -->
                
                <form action='javascript: void(0)' data-action='<?= base_url('ujian/konfigurasi/post/'.$skd->id) ?>' class='col-6'>
                  <div class='input-group'>
                    <div class='input-group-prepend'>
                      <span class='input-group-text'>Passing Grade SKD</span>
                    </div>
                    <input step='.01' min='1' value='<?= $skd->text ?>' type='number' name='text' class='form-control' placeholder='Masukan biaya disini type number...' required=''>
                    <div class='input-group-prepend'>
                      <span class='input-group-text'>%</span>
                    </div>
                    <div class='input-group-prepend'>
                    <input type='hidden' name='store' value='configs'>
                      <button type='submit' class='bg-primary-gradient btn btn-primary input-group-text'>Save</button>
                    </div>
                  </div>
                </form>
                <!-- / form -->

              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-12">
          <div class="border border-primary card">
            <!-- <div class="card-header">
              <h3>Konfigurasi kategori soal</h3>
            </div> -->
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Title</th>
                  <th>Batas Waktu Pengerjaan(Menit)</th>
                  <th>Jumlah Soal</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->no          = ($key+1);
                    $value->href_lihat_soal = base_url('soal/index/?kategori='.$value->question_categori_id);
                    $value->href_edit   = base_url('ujian/konfigurasi/get/'.$value->question_categori_id);
                    
                    echo "
                      <tr>
                        <td>{$value->no}</td>
                        <td>{$value->title}</td>
                        <td>{$value->exam_limit_mod}</td>
                        <td>{$value->number_of_questions_mod} dari {$value->count_of_question} Soal</td>
                        <td>
                          <div class='btn-group'>
                            <button type='button' class='btn btn-default'>Action</button>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                              <span class='caret'></span>
                              <span class='sr-only'>Toggle Dropdown</span>
                            </button>
                            <div class='dropdown-menu' role='menu' x-placement='top-start' style='position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, -165px, 0px);'>
                            <a class='dropdown-item' href='{$value->href_lihat_soal}' title='Lihat soal {$value->title}'>Lihat Soal</a>
                              <a class='dropdown-item form-edit' href='javscript:void(0)' data-title='Edit Konfigurasi Ujian: {$value->title}' data-href='{$value->href_edit}'>Edit</a>
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
                    <th>Title</th>
                    <th>Batas Waktu Pengerjaan(Menit)</th>
                    <th>Jumlah Soal</th>
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