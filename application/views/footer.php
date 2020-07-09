  <footer class="main-footer">
    <strong>Tes Diagnostik &copy; <?php echo date('Y') ?> Homeschooling Prima Edukasi
    <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.0-alpha
    </div> -->
  </footer>

  <!-- Control Sidebar -->
  <!-- <aside class="control-sidebar control-sidebar-dark"> -->
    <!-- Control sidebar content goes here -->
  <!-- </aside> -->
  <!-- /.control-sidebar -->
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
<!-- <script src="<?php echo base_url()?>/themes/adminlte/code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Morris.js charts -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/morris/morris.min.js"></script> -->
<!-- Sparkline -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/sparkline/jquery.sparkline.min.js"></script> -->
<!-- jvectormap -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/knob/jquery.knob.js"></script> -->
<!-- daterangepicker -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script> -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/daterangepicker/daterangepicker.js"></script> -->
<!-- datepicker -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- Bootstrap WYSIHTML5 -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->
<!-- Slimscroll -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
<!-- FastClick -->
<!-- <script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/plugins/fastclick/fastclick.js"></script> -->
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>/themes/adminlte/adminlte.io/themes/dev/adminlte/dist/js/adminlte.js"></script>
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

  function getTimeDiff( datetime, start )
  {
    if ( start ) {
      start = new Date(start);
    } else {
      start = new Date();
    }
    var lastTime = new Date(datetime);
    var diffMs = (lastTime - start); // milliseconds between now

    // Time calculations for days, hours, minutes and seconds
    // var days = Math.floor(diffMs / (1000 * 60 * 60 * 24) ); // days
    var hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60) ); // hours
    var minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((diffMs % (1000 * 60)) / 1000);

    return {
      "distance" : diffMs,
      "minutes" : minutes,
      "seconds" : seconds,
      "label" : `${hours} Jam, ${minutes} Menit ${seconds} Detik`
    };
  }

  /* ==================== START : LOAD FORM EDIT DATA ==================== */
  $( document ).on( 'click', '.form-load', function( e ){
    e.preventDefault();
    let data = $( this ).data();
    $.get( data.href, function( d ){
      $( '#myModal .modal-title' ).html( data.title );
      $( '#myModal .modal-body' ).html( d );
      $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );

      /*==================== load texteditor with tyniMCE  ====================*/
      // loadTinymce();

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

  /* ==================== START : CHECK EXAM PROCESS ==================== */
  if ( $('body').attr('data-exam')=='1' ) {
    loadTryOutWithSession();

    /* triger on modal close */
    $("#myModal").on('hidden.bs.modal', function(){
      if ( $('body').attr('data-exam')!= '0' ) {
        loadTryOutWithSession();
      }
    });
  }
  function loadTryOutWithSession() {
    let data = {
      "title" : "Tes Diagnostik",
      "href" : "<?= base_url('ujian/proses') ?>"
    };
    $.get( data.href+`/${data.token}`, function( d ){
      $( '#myModal .modal-title' ).html( data.title );
      $( '#myModal .modal-body' ).html( d );
      $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );
      $('#examLimit').text( getTimeDiff($('#countDown').data('end'),$('#countDown').data('start')).label );
      updateCountDown($('#countDown').data('end'));  
      $( '#myModal' ).modal( 'show' );
      choicesSelected();
      navQuestion();
    },'html');
  }
  function updateCountDown(datetime)
  {
    let x = setInterval(function() {
      $('#countDown').text( getTimeDiff( datetime ).label )

      // If the count down is over, write some text 
      // let limitDown = Math.floor(getTimeDiff( datetime ) );
      let limitDown = getTimeDiff( datetime ).minutes;
      console.log(getTimeDiff( datetime ).label)
      if ( getTimeDiff( datetime ).label=='0 Jam, 5 Menit 0 Detik' ) {
        alert(' Waktu Anda tinggal 5 Menit');
      }
      if ( getTimeDiff( datetime ).label=='0 Jam, 0 Menit 0 Detik' ) {
        alert(' Anda Memasuki waktu Over Time');
      }
      // if ( limitDown < 0 ) {
        // clearInterval(x);
        // alert('Maaf Waktu Anda Habis')

        /* ==================== START : EXAM STORE PROCESS  ==================== */
        // $.get("<?= base_url('ujian/session-store') ?>",function( d ){
        //   $( '#myModal .modal-title' ).html( 'Hasil Try Out' );
        //   $( '#myModal .modal-body' ).html( d );
        //   $( '#myModal .modal-dialog' ).addClass( 'modal-lg' );
        //   $( '#myModal' ).modal( 'show' );
        // },'html');
        /* ==================== END : EXAM STORE PROCESS  ==================== */
        // $('body').attr('data-exam',0).attr('data-token',0);

      // }
      
    }, 1000)
    
  } 
  /* ==================== END : CHECK EXAM PROCESS ==================== */

  /* ==================== START : CHOICES SELECTED ==================== */
  function choicesSelected() {
    $('.choices').on('click',function(){
      let data = {
        "href" : "<?= base_url('ujian/update-session') ?>",
        "value" : $( this ).val()
      };
      $.get(`${data.href}/${data.value}`)
    })
  }
  /* ==================== END : CHOICES SELECTED ==================== */
  function navQuestion() {
    $( document ).on('click','.nav-question',function(e){
      e.preventDefault()
      let data = {
        "href" : $( this ).attr('href'),
        "closest" : $( this ).data('closest')
      };
      console.log(data.href);
      let wrap = $( this ).closest(`#${data.closest}`);
      wrap.find( '.nav-questions' ).removeClass('active show');

      $.each(wrap.find( '.nav-questions' ),function(){
        if ( $( this ).attr( 'href' )==data.href ) {
          $( this ).addClass('active show')
        }
      })
      

      wrap.find( '.tab-questions' ).removeClass('active show');
      wrap.find( data.href ).addClass('active show');
      // $.each(wrap.find( '.tab-questions' ),function(){
      // //   if ( $( this ).attr( 'id' )==data.href ) {
      // //     $( this ).addClass('active show')
      // //   }
      //   console.log(this)
      // })
    })
  }
  function copyToClipboard() {
  // Create a "hidden" input
  var aux = document.createElement("input");
  // Assign it the value of the specified element
  aux.setAttribute("value", "Você não pode mais dar printscreen. Isto faz parte da nova medida de segurança do sistema.");
  // Append it to the body
  document.body.appendChild(aux);
  // Highlight its content
  aux.select();
  // Copy the highlighted text
  document.execCommand("copy");
  // Remove it from the body
  document.body.removeChild(aux);
  alert("Print screen disable.");
}

$(window).keyup(function(e){
  if(e.keyCode == 44){
    copyToClipboard();
  }
}); 
</script>
</body>

</html>