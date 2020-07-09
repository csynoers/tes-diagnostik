  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Informasi Hasil Try Out CAT CPNS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Informasi Hasil Try Out</li>
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
                  <th>Title</th>
                  <th>Total Soal</th>
                  <th>Poin</th>
                  <th>Passing Grade</th>
                  <th>Keterangan</th>
                  <th>Create At</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->no          = ($key+1);
                    $value->href_edit   = base_url('hasil/detail/'.$value->answer_id);
                    
                    echo "
                      <tr>
                        <td>{$value->no}</td>
                        <td>{$value->question_title}</td>
                        <td>{$value->total_questions} Soal</td>
                        <td>".( ($value->passing_grade*($value->total_questions*5))/100 )." poin dari total ".($value->total_questions*5)." poin</td>
                        <td>{$value->passing_grade}%</td>
                        <td>{$value->keterangan}</td>
                        <td>{$value->create_at_mod}</td>
                        <td>
                            <a class='btn btn-primary form-load' data-title='Detail Hasil Try Out CAT CPNS' data-href='{$value->href_edit}' href='javascript:void(0)'>Detail Hasil Try Out</a>
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
                    <th>Total Soal</th>
                    <th>Poin</th>
                    <th>Passing Grade</th>
                    <th>Keterangan</th>
                    <th>Create At</th>
                    <th>Action</th>
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