  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mulai Tes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Beranda</a></li>
              <li class="breadcrumb-item active">Mulai Tes</li>
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
            <!-- /.card-header -->
            <div class="card-header">
              <p>
                <b>Catatan Penting :</b><br>
                1. Semua tindakan terkait dengan tes ini, dimulai dan diakhiri berdasarkan instruksi dari Pendamping tes, maka lakukan sesuai yang diinstruksikan.<br>
                2. Jumlah pilihan jawaban untuk soal 1 sd 40 ada 4 pilihan yaitu A, B, C, dan D. Pilihlah jawaban yang dianggap tepat.<br>
                3. Jumlah pilihan jawaban untuk soal 41 sd 120 ada 5 pilihan yaitu A, B, C, D dan E. Pilihlah jawaban yang dianggap sesuai dengan kondisi Anda. Ini tentang sesuai atau tidak dengan kondisi Anda, bukan tentang benar atau salah.<br>
                4. Harap kerjakan semua soal, tanpa ada yang terlewat.<br>
              </p>
            </div>
            <div class="card-body">
              <?php
                $time_limit = 120;
                $total_soal = 0;
                foreach ($rows as $key => $value) {
                  // $time_limit += $value->exam_limit;
                  $total_soal += $value->count_of_question;
                }
              ?>
              <div class="rowX">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="bg-info-gradient border-warning input-group-text">Batas Waktu Pengerjaan</span>
                  </div>
                  <p class="form-control border-warning text-right"><?= $time_limit ?></p>
                  <div class="input-group-prepend">
                    <span class="bg-info-gradient border-warning input-group-text">Menit</span>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="bg-info-gradient border-warning input-group-text">Total Soal</span>
                  </div>
                  <p class="form-control border-warning text-right"><?= $total_soal ?></p>
                  <div class="input-group-prepend">
                    <span class="bg-info-gradient border-warning input-group-text">Soal</span>
                  </div>
                </div>
                <a id="tryOut" href="javascript: void(0)" onclick="tryOut()" data-href="<?= base_url('ujian/proses') ?>" data-title="Tes Diasnostik Homeschooling Prima Edukasi" class="btn btn-block btn-primary mb-3 mt-3 p-3" style="font-size:20px;border-radius:25px" >Mulai Sekarang</a>
              </div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kategori Soal</th>
                    <th>Jawaban</th>
                    <th>Jumlah Soal</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($rows as $key => $value) {
                    $value->no = ($key+1);
                    $value->jawaban = ($value->count_of_choices=='4'? 'Pilihan jawaban (A, B, C, & D)' : 'Pilihan jawaban (A, B, C, & D)' );
                    
                    echo "
                      <tr>
                        <td>{$value->no}</td>
                        <td>{$value->title}</td>
                        <td>{$value->jawaban}</td>
                        <td>{$value->count_of_question}</td>
                      </tr>
                    ";
                  }
                ?>
                
                </tbody>
                <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Kategori Soal</th>
                    <th>Jawaban</th>
                    <th>Jumlah Soal</th>
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
<script>
  function tryOut() {
    loadTryOut();
    /* triger on modal close */
    $("#myModal").on('hidden.bs.modal', function(){
      loadTryOut();
    });
  }

  function loadTryOut()
  {
    let data = $( '#tryOut' ).data();
    $.get( data.href, function( d ){
      $( '#myModal .modal-title' ).html( data.title );
      $( '#myModal .modal-body' ).html( d );
      $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );
      updateCountDown($('#countDown').data('end'));  
      $( '#myModal' ).modal( 'show' );
      choicesSelected();
      navQuestion();
    },'html');
  }
</script>