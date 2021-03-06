<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 

class Siswa extends MY_Controller{
    /**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct(){
        parent::__construct();
		
		if( !$this->session->userdata('root') ){
			redirect(base_url('admin@td'));
        }

		
		$this->load->model(['M_users','M_relations']);
		
		$this->load->helper('rupiah_helper');

		# require php mailer
        require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
		require APPPATH.'libraries/phpmailer/src/SMTP.php';
    }

    public function mendaftar()
    {
        $data['rows'] = $this->M_users->mendaftar();
		$this->render_pages( 'siswa/mendaftar', $data );
    }

    public function terdaftar()
    {
        $data['rows'] = $this->M_users->terdaftar();
		$this->render_pages( 'siswa/terdaftar', $data );
	}
	
	/* ==================== Page::Siswa Belum Mengerjakan ==================== */
	public function belum_mengerjakan()
	{
		# membuat variabel filter asal sekolah terlebih dahulu
		$title                  = $this->security->xss_clean($this->input->get('schools'));
		$data['title']          = empty($title) ? NULL : "{$title}" ;
		$data['export']         = base_url('siswa/export/belum-mengerjakan'); 
		$data['export_title']   = "Semua Siswa Belum Mengerjakan Tes Diagnostik"; 
		$data['asal_sekolah']   = $this->M_relations->get_siswa_belum_mengerjakan_group_by_schools();

		if ( empty($title) ) { # jika filter sekolahan kosong kosong
			$data['rows']           = $this->M_relations->get_siswa_belum_mengerjakan();
			
		} else { # jika filter asal sekolahan tidak kosong
			$data['rows']           = $this->M_relations->get_siswa_belum_mengerjakan_by_schools($title);
			$data['export']         = base_url("siswa/export/belum-mengerjakan/{$title}");  
			$data['export_title']   = "Semua Siswa Belum Mengerjakan Tes Diagnostik {$title}";
		}

		$this->render_pages( 'siswa/belum_mengerjakan', $data );		
	}
	/* ==================== End Page::Siswa Belum Mengerjakan ==================== */

	/* ==================== Page::Siswa Sedang Mengerjakan ==================== */
	public function sedang_mengerjakan()
	{
		# membuat variabel filter asal sekolah terlebih dahulu
		$title                  = $this->security->xss_clean($this->input->get('schools'));
		$data['title']          = empty($title) ? NULL : "{$title}" ;
		$data['export']         = base_url('siswa/export/sedang-mengerjakan'); 
		$data['export_title']   = "Semua Siswa Sedang Mengerjakan Tes Diagnostik"; 
		$data['asal_sekolah']   = $this->M_relations->get_siswa_sedang_mengerjakan_group_by_schools();

		if ( empty($title) ) { # jika filter sekolahan kosong kosong
			$data['rows']           = $this->M_relations->get_siswa_sedang_mengerjakan();
			
		} else { # jika filter asal sekolahan tidak kosong
			$data['rows']           = $this->M_relations->get_siswa_belum_mengerjakan_by_schools($title);
			$data['export']         = base_url("siswa/export/sedang-mengerjakan/{$title}");  
			$data['export_title']   = "Semua Siswa Sedang Mengerjakan Tes Diagnostik {$title}";
		}

		$this->render_pages( 'siswa/sedang_mengerjakan', $data );		
	}
	/* ==================== End Page::Siswa Sedang Mengerjakan ==================== */
	
	/* ==================== Page::Export Siswa ==================== */
	public function export()
	{
		# filter export by segment ke 3
		switch ($this->uri->segment(3)) {
			case 'belum-mengerjakan':
				$title = $this->security->xss_clean($this->uri->segment(4)); 
				if ( $title ) { # jika filter sekolahan tidak kosong kosong
					$data['rows']	= $this->M_relations->get_siswa_belum_mengerjakan_by_schools($title);
		
				} else { # jika filter sekolahan kosong kosong
					$data['rows']   = $this->M_relations->get_siswa_belum_mengerjakan();
				}
				$this->load->view( 'siswa/belum_mengerjakan_export', $data );
				break;
			case 'sedang-mengerjakan':
				$title = $this->security->xss_clean($this->uri->segment(4)); 
				if ( $title ) { # jika filter sekolahan tidak kosong kosong
					$data['rows']	= $this->M_relations->get_siswa_sedang_mengerjakan_by_schools($title);
		
				} else { # jika filter sekolahan kosong kosong
					$data['rows']   = $this->M_relations->get_siswa_sedang_mengerjakan();
				}
				$this->load->view( 'siswa/sedang_mengerjakan_export', $data );
				break;
			
			default:
				# code...
				break;
		}
	}
	/* ==================== End Page::Export Siswa ==================== */

    public function konfirmasi()
    {
		if ( $this->uri->segment(3) ) {
			# code...
			$row = $this->M_users->mendaftar( $this->uri->segment(3) )[0];

			$data['action']   		= base_url();		
			$data['data_action']  	= base_url() .'siswa/konfirmasi_store/' .$row->id;
			$row->birthDate   = date("d-m-Y", strtotime($row->birth_date));		
			
			$controlBtnKOnfirmasi = NULL;
			if ( $this->uri->segment(4) ) {
				$controlBtnKOnfirmasi = 'hide';
			}
			$html= "
				<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
					<div class='form-group'>
						<label>NISN :</label>
						<span class='form-control'>{$row->nik}</span>
					</div>
					<div class='form-group'>
						<label>Nama Lengkap :</label>
						<span class='form-control'>{$row->fullname}</span>
					</div>
					<div class='form-group'>
						<label>Tgl Lahir :</label>
						<span class='form-control'>{$row->birthDate}</span>
					</div>
					<div class='form-group'>
						<label>Asal SMA :</label>
						<span class='form-control'>{$row->schools}</span>
					</div>
					<div class='form-group'>
						<label>Email :</label>
						<span class='form-control'>{$row->email}</span>
					</div>
					<div class='form-group'>
						<label>Telepon :</label>
						<span class='form-control'>{$row->telp}</span>
					</div>
					<button type='submit' class='btn btn-primary btn-block {$controlBtnKOnfirmasi}'>Konfirmasi Siswa</button>
				</form>
			";
			echo $html;
		} else {
			# code...
			$data['rows'] = $this->M_users->konfirmasi();
			$this->render_pages( 'siswa/konfirmasi', $data );
		}
		
	}
	
	public function konfirmasi_store()
	{
		$this->M_users->post = $this->input->post();
		if ( $this->M_users->konfirmasi_store($this->uri->segment(3)) ) {
			# send notif email to user
			$row = $this->M_users->mendaftar( $this->uri->segment(3) )[0];
			$row->birthDate   = date("d-m-Y", strtotime($row->birth_date));	

			$html = "
				<html>
					<head>
						<title>Tes Diagnostik</title>
					</head>
					<body style='background: #eee;'>
						<div style='padding: 50px;'>
							<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
								<h1>Verifikasi Pendaftaran</h1>
							</div>
							<div style='background: #fff;padding: 30px 30px;'>
								<h2 style='margin-top: 0px'>Hi {$row->fullname},</h2>
								Selamat pendaftaran anda telah diverifikasi, anda bisa masuk melalui link berikut atau langsung ke website <a href='https://td.homeschoolingprimaedukasi.com/'>Tes Diagnostik</a> :<br>
								<a href='".base_url('../auth')."' style='display: block;text-align: center;background-color: #007bff;border-radius: 25px;padding: 1rem;margin-top: 1rem;color: #fff;text-decoration: none;'>SIGN IN NOW</a><br>
								<table style='width: 100%;border-spacing: unset;'>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>NISN </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->nik} </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Nama Lengkap </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->fullname} </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Tanggal Lahir </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->birthDate} </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Asal Sekolah </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->schools} </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Username </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->username} </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Email </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> <a href='mailto:{$row->email}' target='_blank'>{$row->email}</a> </td>
									</tr>
									<tr>
										<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Telepon </td>
										<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$row->telp} </td>
									</tr>
								</table>
							</div>
							<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
								<p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Tes Diasnostik Homeschooling Prima Edukasi © ".date('Y')."</a><br> PKBM Homeschooling Prima Edukasi Jalan Jenderal Sudirman nomor 84 B – Dumai Timur- Kota Dumai – Riau.</p>
							</div>
						</div>
					</body>
				</html>	
			";
			$this->send_email_smtp('Verifikasi Pendaftaran',$row->email,$html);

			$this->msg= [
				'stats'=> 1,
				'msg'=> 'Verifikasi siswa berhasil, notifikasi email berhasil dikirim ke pendaftar',
			];
		} else {
			$this->msg= [
				'stats'=> 0,
				'msg'=> 'Konfirmasi gagal dilakukan',
			];
		}
		echo json_encode( $this->msg );

	}
	protected function send_email_smtpXXX($subject,$to,$html)
	{
	    mail($to,$subject,$html);
        return TRUE;
	}
	protected function send_email_smtp($subject,$to,$html)
    {
        /* ==================== START :: SEND EMAIL ==================== */

        // PHPMailer object
        $response = false;
        $mail = new PHPMailer();                     
            
        // SMTP configuration
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        $mail->Host     = 'smtp.gmail.com'; //sesuaikan sesuai nama domain hosting/server yang digunakan
        $mail->SMTPAuth = true;
        $mail->Username = 'jogjasitesinur@gmail.com'; // user email
        $mail->Password = 'Sinur12345'; // password email
        $mail->SMTPSecure = 'tls';
        $mail->Port     = 587; // GMail - 465/587/995/993

        $mail->setFrom('info@homeschoolingprimaedukasi.com', 'Tes Diasnostik'); // user email
        $mail->addReplyTo('info@homeschoolingprimaedukasi.com', ''); //user email

        // Add a recipient
        $mail->addAddress($to); //email tujuan pengiriman email

        // Email subject
        $mail->Subject = $subject; //subject email

        // Set email format to HTML
        $mail->isHTML(true);

        // Email body content
        $mailContent = $html; // isi email
        $mail->Body = $mailContent;

        // Send email
        if(!$mail->send()){
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            // die();
            $response = FALSE;
        }else{
            // echo 'Message has been sent';
            $response = TRUE;
        }
        /* ==================== END :: SEND EMAIL ==================== */
        return $response;
	}
	
	/* ==================== START : PROCESS DELETE DATA ==================== */
	public function delete()
	{
		# get question_categori_id
		$username= $this->security->xss_clean($this->uri->segment(3));
		
		if ( $this->M_users->delete( $username ) ) {
			$this->msg= [
				'stats'=>1,
				'msg'=>'Data Berhasil Dihapus',
			];
		} else {
			$this->msg= [
			'stats'=>0,
				'msg'=>'Maaf Data Gagal Dihapus',
			];
		}
			
		echo json_encode( $this->msg );
	}
	/* ==================== END : PROCESS DELETE DATA ==================== */
}