  <!-- Control Sidebar -->
  <!-- <aside class="control-sidebar control-sidebar-dark"> -->
    <!-- Control sidebar content goes here -->
  <!-- </aside> -->
  <!-- /.control-sidebar -->
  <footer class="main-footer">
    <strong>Tes Diagnostik &copy; <?php echo date('Y') ?> Homeschooling Prima Edukasi
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <!-- /.modal -->

<!-- jQuery UI 1.11.4 -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/dist/js/demo.js"></script> -->
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Morris.js charts -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/morris/morris.min.js"></script> -->
<!-- Sparkline -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/sparkline/jquery.sparkline.min.js"></script> -->
<!-- jvectormap -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/knob/jquery.knob.js"></script> -->
<!-- daterangepicker -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script> -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/daterangepicker/daterangepicker.js"></script> -->
<!-- datepicker -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- Bootstrap WYSIHTML5 -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->
<!-- Slimscroll -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
<!-- FastClick -->
<!-- <script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/fastclick/fastclick.js"></script> -->
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/dist/js/adminlte.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url()?>../themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
window.setTimeout("waktu()",1000); 
function waktu() { 
  var tanggal = new Date(); 
  setTimeout("waktu()",1000); 
  document.getElementById("jam").innerHTML = tanggal.getHours(); 
  document.getElementById("menit").innerHTML = ': '+tanggal.getMinutes();
  document.getElementById("detik").innerHTML = ': '+tanggal.getSeconds();

  document.getElementById("tglSekarang").innerHTML = getTanggalIndoSekarang();
}

function getTanggalIndoSekarang()
{
  var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];

  var date = new Date();

  var day = date.getDate();

  var month = date.getMonth();

  var thisDay = date.getDay(),

      thisDay = myDays[thisDay];

  var yy = date.getYear();

  var year = (yy < 1000) ? yy + 1900 : yy;

  return (thisDay + ', ' + day + ' ' + months[month] + ' ' + year);

}
</script>
  <!-- tinymce -->
  <script src="<?php echo base_url() ?>assets/tinymce/tinymce.min.js"></script>
  <script type="text/javascript">
  /**
  * function :
  *   <1> this :: class  :: .form-add  :: trigger :: click   :: Load Form Add this element
  *   <2> this :: class  :: .form-edit :: trigger :: click   :: Load Form Edit this element
  *   <3> this :: tags   :: <form>     :: trigger :: submit  :: Store data this element to the Database 
  * */
  /* ==================== START : LOAD FORM ADD DATA ==================== */
  $( document ).on( 'click', '.form-add', function( e ){
    e.preventDefault();
    let data = $( this ).data();
    $.get( data.href, function( d ){
      $( '#myModal .modal-title' ).html( data.title );
      $( '#myModal .modal-body' ).html( d );
      $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );

      /*==================== load texteditor with tyniMCE  ====================*/
      loadTinymce();

      /*==================== load Modal  ====================*/
      $( '#myModal' ).modal( 'show' );

      $( document ).on('change', '#optionsKategoriSoal',function(){
        let countOfChoice = $( this ).find(':selected').data('count-of-choice');
        let jawaban       = ['A','B','C','D','E'];
        let htmls         = [];

        /* loop pilihan A-E */
        for (let index = 0; index < countOfChoice; index++) {
          htmls.push(`
            <div class="form-check">
              <input class="form-check-input hide" type="radio" name="choices_option" id="exampleRadios1" value="${jawaban[index]}" >
              <label class="form-check-label" for="exampleRadios1">
                Jawaban ${jawaban[index]}:
              </label>
              <textarea name='choices_answer[${jawaban[index]}]' class='form-control mytextarea'>Masukan isi jawaban ${jawaban[index]} disini...</textarea>
            </div>
            <hr>
          `);
        }
        htmls = htmls.join('');
        $('#fieldChoices').html(`
          <hr>
          <label>Silahkan Masukan Jawaban (${jawaban[0]} - ${jawaban[countOfChoice-1]})</label>
          ${htmls}
        `);
        loadTinymce();

      });

    },'html');
  });
  /* ==================== END : LOAD FORM ADD DATA ==================== */

  /* ==================== START : LOAD FORM EDIT DATA ==================== */
  $( document ).on( 'click', '.form-edit', function( e ){
    e.preventDefault();
    let data = $( this ).data();
    $.get( data.href, function( d ){
      $( '#myModal .modal-title' ).html( data.title );
      $( '#myModal .modal-body' ).html( d );
      $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );

      /*==================== load texteditor with tyniMCE  ====================*/
      loadTinymce();

      /*==================== load Modal  ====================*/
      $( '#myModal' ).modal( 'show' );

    },'html');
  });
  /* ==================== END : LOAD FORM EDIT DATA ==================== */

  /* ==================== START : PROCESS DATA STORE ==================== */
  $( document ).on( 'submit', 'form', function( e ) {
    e.preventDefault();
    let data = $( this ).data();  
    var formData = new FormData(this);
    $.ajax({
        url: data.action,
        type: 'POST',
        data: formData,
        success: function (data) {
          if ( data.stats==1 ) {
            alert( data.msg )
            location.reload()
          } else {
            alert( data.msg );
          }
          // console.log(data);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json'
    });
  });
  /* ==================== END : PROCESS DATA STORE ==================== */

  /* ==================== START : PROCESS DELETE DATA ==================== */
  $( document ).on('click', '.delete', function( e ){
    e.preventDefault(); 
    $.get( $(this).attr('href'), function(data){
      alert( (data.stats=='1') ? data.msg : data.msg )
      location.reload()
    } ,'json');
  });
  /* ==================== END : PROCESS DELETE DATA ==================== */

  /* ==================== START : RESET TYNIMCE IN MODAL ==================== */
  $( '#myModal' ).on( 'hidden.bs.modal', function () {
    tinyMCE.editors = [];
    console.log( tinyMCE.editors )
  });
  /* ==================== END : RESET TYNIMCE IN MODAL ==================== */

  /* ==================== START : LOAD DATATABLES ==================== */
  $(function () {
    $("#example1").DataTable();
  }); 
  /* ==================== END : LOAD DATATABLES ==================== */

  /* ==================== START : LOAD TYNIMCE ==================== */
  function loadTinymce(){
    if ( $('.mytextarea').length > 0 ) {
      tinymce.init({
        height: '100px',
        width: '100%',
        selector: ".mytextarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality jbimages",
            "emoticons template paste textcolor colorpicker textpattern imagetools"
        ],

          toolbar1: "template | fontselect | undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | media jbimages | link forecolor backcolor emoticons | pagebreak print preview",
        image_advtab: true,
        relative_urls: false,
        /* templates: [
          {title: 'Some title 1', description: 'Some desc 1', content: 'My content'},
          {title: 'Paket Yo Bersih', description: 'tambah paket baru yo bersih', url: '{base_url}../template/?t=paket-yobersih'},
          {title: 'single Column', description: 'Some desc 2', url: '{base_url}../template/?t=single-column'},
          {title: 'single Column 2', description: 'Some desc 2', url: '{base_url}../template/?t=home-page'},
        ],
        valid_elements : '+*[*]',
        content_css: [
          '{base_url}../library/template/css/bootstrap.css',
          '{base_url}../library/template/css/style.css',
          '{base_url}../library/template/fonts/font-awesome/css/font-awesome.min.css',
        ],
        extended_valid_elements: 'table[class=table table-striped]',
        setup: function (editor) {
            editor.on('BeforeSetContent', function(e) {
              if (e.content.indexOf("<table") == 0) {
                        e.content = '<div class="table-responsive">' + e.content + '</div>';
              }
            });
        } */

      });

    }

  }
  /* ==================== END : LOAD TYNIMCE ==================== */
  </script>
  <script>
  (function(j){
    j('.export-excel').on('click',function( e ){
      e.preventDefault()
      let data = j( this ).data();
      j.get(data.href, function(html){
        fnExcelReport(html,data.title)
      },'html')
    })
  })(jQuery)
  function fnExcelReport(html,title)
  {
    var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>'+html+'</html>',
        base64 = function(s) {
          return window.btoa(unescape(encodeURIComponent(s)))
        },
        format = function(s, c) {
          return s.replace(/{(\w+)}/g, function(m, p) {
            return c[p];
          })
        }
      // var toExcel = document.getElementById("export").innerHTML;
      var ctx = {
        worksheet: title || '',
        table: title
        // table: 'toExcel'
      };
      var link = document.createElement("a");
      link.download = `${title}.xls`;
      link.href = uri + base64(format(template, ctx))
      link.click();
  }
  </script>

</body>

</html>