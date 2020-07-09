  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Soal</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Soal</li>
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
              <!-- <h3 class="card-title">Daftar Informasi Kelas</h3> -->
              <a href="javascript:void(0)" data-title="Tambah soal baru" data-href="<?php echo base_url('soal/add') ?>" class="btn btn-default float-right form-add"><i class="fa fa-plus"></i> Add New</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Filter by Kategori Soal :</label>
                  <?php
                    foreach ($kategori_soal as $key => $value) {
                      $active = NULL;
                      if ( $this->input->get('kategori') ) {
                        if ( $this->input->get('kategori')==$value->question_categori_id ) {
                          $active = 'active text-info border-info';
                        }
                      }
                      $href_filter = base_url("soal/index/?kategori={$value->question_categori_id}");
                      echo "<a href='{$href_filter}' class='{$active} btn btn-block btn-default text-left text-secondary mb-2' role='button'>{$value->title}</a>";
                    }
                  ?>
                </div>
                <div class="col-sm-9">
                  <label for="">Daftar Informasi Soal :</label>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Pertanyaan</th>
                      <th>Kategori Soal</th>
                      <th>Create At</th>
                      <th>Publish</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      foreach ($rows as $key => $value) {
                        $value->no          = ($key+1);
                        $value->href_edit   = base_url('soal/edit/'.$value->question_id);
                        $value->href_delete = base_url('soal/delete/'.$value->question_id);
                        $value->question    = strip_tags($value->question); 
                        $value->question_mod= character_limiter($value->question, 30);
                        echo "
                          <tr>
                            <td>{$value->no}</td>
                            <td title='{$value->question}'>{$value->question_mod}</td>
                            <td>{$value->kategori_soal}</td>
                            <td>{$value->create_at_mod}</td>
                            <td>{$value->block_mod}</td>
                            <td>
                              <div class='btn-group'>
                                <button type='button' class='btn btn-default'>Action</button>
                                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                                  <span class='caret'></span>
                                  <span class='sr-only'>Toggle Dropdown</span>
                                </button>
                                <div class='dropdown-menu' role='menu' x-placement='top-start' style='position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, -165px, 0px);'>
                                <a class='dropdown-item form-edit' data-title='Edit soal' data-href='{$value->href_edit}' href='javascript:void(0)'>Edit</a>
                                  <a class='dropdown-item delete' href='{$value->href_delete}'>Delete</a>
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
                        <th>Pertanyaan</th>
                        <th>Kategori Soal</th>
                        <th>Create At</th>
                        <th>Publish</th>
                        <th>Actions</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
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