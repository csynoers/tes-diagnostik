<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Ujian extends MY_Controller {

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
	public function __construct()
	{
		parent::__construct();
		/**
		 * # Check authentication 
		 * # LOAD MODEL :
		 * 1. 
		 */

		# Check authentication 
        if( ! $this->session->userdata('user') ){
            redirect(base_url());
		}
		
		# load model
		$this->load->model(['M_auth','M_question_categories','M_exam_configs','M_banks','M_exam_user_configs','M_users']);

		$this->load->helper('rupiah_helper');

		# require php mailer
        require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
		require APPPATH.'libraries/phpmailer/src/SMTP.php';
	}

	public function index()
	{
		$data['rows'] = $this->M_question_categories->get_question_categories();
		$this->render_pages( 'try_out',$data );
	}

	public function store()
	{
		if ( $this->uri->segment(3) ) {
			# proses konfirmasi pembayaran
            # code...with upload file
            $config['upload_path']          = './src/proof_payments/';
            $config['allowed_types']        = 'jpg|png';

            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('fupload'))
            {
                $this->msg= [
                    'stats'=>0,
                    'msg'=> $this->upload->display_errors(),
                ];
            }
            else
            {
                $this->M_exam_user_configs->post= $this->input->post();
                $this->M_exam_user_configs->post['gambar']= $this->upload->data()['file_name'];

                /* start image resize */
                $this->load->helper('img');
                $this->load->library('image_lib');
                $sizes = [768,320,128];
                foreach ($sizes as $size) {
                    $this->image_lib->clear();
                    $this->image_lib->initialize( resize($size, $config['upload_path'], $this->M_exam_user_configs->post['gambar']) );
                    $this->image_lib->resize();
                }
                /* end image resize */

                if ( $this->M_exam_user_configs->store( $this->uri->segment(3) ) ) {
					/* send to admin email */
					$admin = $this->M_users->get_admin();
					$user_config = $this->M_exam_user_configs->get( $this->session->userdata('user')->username );
					$user_config->imageSrc = base_url('src/proof_payments/'.$user_config->proof_payment);
					$html = "
						<html>
							<head>
								<title>Try Out CPNS</title>
							</head>
							<body style='background: #eee;'>
								<div style='padding: 50px;'>
									<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
										<h1>Try Out CAT CPNS</h1>
									</div>
									<div style='background: #fff;padding: 30px 30px;'>
										<h2 style='margin-top: 0px'>Hi {$admin->fullname},</h2>
										Silahkan melakukan konfirmasi pembayaran segera, User ini telah melakukan pembayaran untuk mendapatkan token :
										<table style='width: 100%;border-spacing: unset;'>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>NIK </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->nik." </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Nama Lengkap </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->fullname." </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Username </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->username." </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Email </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> <a href='mailto:".$this->session->userdata('user')->email."' target='_blank'>".$this->session->userdata('user')->email."</a> </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Telepon </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->telp." </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Bank Transfer </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'> {$user_config->bank_transfer} </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Nominal Transfer </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'>Rp. ".rupiah($this->session->userdata('user')->nominal_transfer)." </td>
											</tr>
											<tr>
												<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Bukti Transfer </td>
												<td style='padding: 10px 10px;border: 1px solid #ddd;'>
													<img src='{$user_config->imageSrc}' title='Bukti Transfer'>
												</td>
											</tr>
										</table>
									</div>
									<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
										<p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Try Out CAT CPNS Bimbel IC Surabaya © ".date('Y')."</a><br> Pusat Operasional : Jl. Mulyosari Mas C3 No 19 Surabaya</p>
									</div>
								</div>
							</body>
						</html>	
					";
					$this->send_email_smtp('Konfirmasi Pembayaran',$admin->email,$html);

                    $this->msg= [
                        'stats'=>1,
                        'msg'=> 'Bukti pembayaran berhasil dikirim',
                    ];
                    
                } else {
                    $this->msg= [
                        'stats'=>0,
                        'msg'=> 'Bukti pembayaran gagal dikirim',
                    ];
                }
                
            }
			echo json_encode($this->msg);
		
		} else {
			# proses kirim permintaan
			$this->M_exam_user_configs->post = [];
			$this->M_exam_user_configs->post['bank_transfer'] = $this->input->post('bank');
			$this->M_exam_user_configs->post['username'] = $this->session->userdata('user')->username;
			$this->M_exam_user_configs->post['total_payment'] = $this->session->userdata('user')->nominal_transfer;
	
			if ( $this->M_exam_user_configs->store() ) {
				/* send to admin email */
				$user_config = $this->M_exam_user_configs->get( $this->session->userdata('user')->username );
				$html = "
					<html>
						<head>
							<title>Try Out CPNS</title>
						</head>
						<body style='background: #eee;'>
							<div style='padding: 50px;'>
								<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
									<h1>Try Out CAT CPNS</h1>
								</div>
								<div style='background: #fff;padding: 30px 30px;'>
									<h2 style='margin-top: 0px'>Hi {$admin->fullname},</h2>
									Silahkan melakukan pembayaran sesuai jumlah nominal dan bank transfer, dibawah ini untuk mendapatkan token :
									<table style='width: 100%;border-spacing: unset;'>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>NIK </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->nik." </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Nama Lengkap </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->fullname." </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Username </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->username." </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Email </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> <a href='mailto:".$this->session->userdata('user')->email."' target='_blank'>".$this->session->userdata('user')->email."</a> </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Telepon </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->session->userdata('user')->telp." </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Bank Transfer </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'> ".$this->input->post('bank')." </td>
										</tr>
										<tr>
											<td width='25%' style='padding: 10px 10px;border: 1px solid #ddd;'>Nominal Transfer </td>
											<td style='padding: 10px 10px;border: 1px solid #ddd;'>Rp. ".rupiah($this->session->userdata('user')->nominal_transfer)." </td>
										</tr>
									</table>
								</div>
								<div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
									<p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Try Out CAT CPNS Bimbel IC Surabaya © ".date('Y')."</a><br> Pusat Operasional : Jl. Mulyosari Mas C3 No 19 Surabaya</p>
								</div>
							</div>
						</body>
					</html>	
				";
				$this->send_email_smtp('Tagihan pembayaran aktivasi token',$this->session->userdata('user')->email,$html);

				$this->msg= [
					'stats'=> 1,
					'msg'=> 'Permintaan token berhasil dikirim silahkan buka email anda dan lakukan pembayaran sesuai dengan nominal yang tertera, jika tidak masuk di menu Inbox(kotak masuk) silahkan cek di menu Spam, Terimakasih',
				];
			} else {
				$this->msg= [
					'stats'=> 0,
					'msg'=> 'Permintaan token gagal dibuat',
				];
			}
			echo json_encode( $this->msg );
		}
		
		
	}

	public function detail_pembayaran()
	{
		# code...
		$row_user_configs = $this->M_exam_user_configs->get( $this->session->userdata('user')->username );

		$data['action']   		= base_url();		
		$data['data_action']  	= base_url() .'ujian/store/' .$row_user_configs->exam_user_config_id;		
		
		
		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='input-group mb-3'>
					<div class='input-group-prepend'>
						<span class='input-group-text'>Sudah melakukan pembayaran sebesar</span>
					</div>
					<span class='form-control text-right'>Rp. ".rupiah($row_user_configs->total_payment)."</span>
					<div class='input-group-prepend'>
						<span class='input-group-text text-info'>(tidak kurang tidak lebih)</span>
					</div>
				</div>
				<div class='form-group'>
					<label>Bank transfer ke :</label>
					<span class='form-control'>{$row_user_configs->bank_transfer}</span>
				</div>
				<div class='form-group'>
					<label>Bukti pembayaran :</label>
					<img class='img-fluid mx-auto d-block' src='".base_url("src/proof_payments/{$row_user_configs->proof_payment}")."' alt='Bukti Pembayaran'>
				</div>
			</form>
		";
		echo $html;
	}

	/* ==================== START : PROSES UJIAN ==================== */
	public function proses()
	{
		if ( $this->uri->segment( 2 ) ) {
			$this->proses_ujian();

		} else {
			echo 'Maaf Salah';
		}
	}
	protected function proses_ujian()
	{			
		if ( empty($this->session->userdata('user')->examination_process) ) {
			// $data = [];
			$exam_limit_total = 120;
			$count_of_question_total = 0;
			foreach ($this->M_question_categories->get_question_categories() as $key => $value) {
				// $exam_limit_total += (int) $value->exam_limit;
				$count_of_question_total += (int) $value->count_of_question;
				
				$questions 			= $this->M_exam_configs->proses_sql_rand($value->question_categori_id);
				$value->questions 	= [];
				foreach ($questions as $keyQuestion => $valueQuestion) {
					$choices = $this->M_exam_configs->get_choices($valueQuestion->question_id);
					foreach ($choices as $keyChoice => $valueChoice) {
						$valueQuestion->choices[] = $valueChoice;
					}
					/**
					 * if question_status same as:
					 * 0 = default Or unanswered
					 * 1 = answered
					 */
					$valueQuestion->no_soal = $keyQuestion+1;
					// $valueQuestion->rules_answer = ($value->true_question=='same' ? 'Poin 5' : 'Poin 1-5' );
					$valueQuestion->question_status = 0;
					// $valueQuestion->question_weight = 0;
					$valueQuestion->question_answer_key = 0;
	
					$value->questions[] = $valueQuestion;
				}
				if ( count($value->questions) > 0 ) {
					$data['rows'][] = $value;
				}
			}
					
			$_SESSION['user']->examination_process = TRUE;
			$_SESSION['user']->exam_limit_total = $exam_limit_total;
			$_SESSION['user']->count_of_question_total = $count_of_question_total;

			# save $data variable to session
			$_SESSION['user']->rows = $data['rows'];
			$_SESSION['user']->exam_start = date('Y-m-d H:i:s');
			$_SESSION['user']->exam_end = date('Y-m-d H:i:s',strtotime("+{$_SESSION['user']->exam_limit_total} minutes", strtotime($_SESSION['user']->exam_start)));
			$_SESSION['user']->last_do_work = 0;
		}
		// echo '<pre>';		
		// print_r($this->session->userdata('user')->rows);
		// echo '</pre>';
		// die();

		$li = [];
		foreach ($this->session->userdata('user')->rows as $key => $value) {
			# default $value->active is null
			$value->active = NULL;

			# default $value->aria_selected is false
			$value->aria_selected = false;

			if ( $this->session->userdata('user')->last_do_work != '0' ) {
				# get last do work question
				$key_last_do_work = explode('/',$this->session->userdata('user')->last_do_work);

				if ( $key_last_do_work[0]==$key ) {
					$value->active = 'active' ;
					$value->aria_selected = 'true';
				}

			} elseif ( $key==0 ) {
				$value->active = 'active' ;
				$value->aria_selected = 'true';
			}

			# dont remove this for debug activ tab category question
			// echo "nav tab {$key}/{$value->active}<br>";

			$li[] = "
				<li class='nav-item'>
					<a class='nav-link {$value->active}' id='nav{$value->question_categori_id}-tab' data-toggle='tab' href='#nav{$value->question_categori_id}' role='tab' aria-controls='nav{$value->question_categori_id}' aria-selected='{$value->aria_selected}'>{$value->title}</a>
				</li>
			";
		}
		$li= implode('',$li);
		
		$tab = [];
		foreach ($this->session->userdata('user')->rows as $key => $value) {
			# default $value->active is null			
			$value->active = NULL ;

			if ( $this->session->userdata('user')->last_do_work != '0' ) {
				# get last do work question
				$key_last_do_work = explode('/',$this->session->userdata('user')->last_do_work);

				if ( $key_last_do_work[0]==$key ) {
					$value->active = 'show active' ;
				}

			} elseif ( $key==0 ) {
				$value->active = 'show active' ;
			}

			# dont remove this for debug activ tab category question
			// echo "tab-pane {$key}/{$value->active}<br>";

			// $value->active = ($key==0) ? 'show active' : NULL ;
			$value->navQuestion = [];
			$value->tabQuestion = [];
			foreach ($value->questions as $keyQuestion => $valueQuestion) {
				# default $valueQuestion->active is null
				$valueQuestion->active = NULL;
				
				if ( $this->session->userdata('user')->last_do_work != '0' ) {
					# get last do work question
					$key_last_do_work = explode('/',$this->session->userdata('user')->last_do_work);
					if ( ($key==$key_last_do_work[0]) && ($keyQuestion==$key_last_do_work[1]) ) {
						$valueQuestion->active = 'active show';
					} elseif ( $key!=$key_last_do_work[0] ) {
						$valueQuestion->active = ($keyQuestion==0) ? 'active show' : NULL;
					}
					
				} elseif ( $keyQuestion==0 ) {
					$valueQuestion->active = 'active show';
				}

				# dont remove this for debug activ question
				// echo "no {$valueQuestion->no_soal}/{$valueQuestion->active}<br>";

				$valueQuestionAnswered = NULL;
				if ( $valueQuestion->question_status == 1 ) {
					$valueQuestionAnswered = 'border border-info rounded';
				}

				$value->navQuestion[] = "
						<li class='nav-item m-1 {$valueQuestionAnswered}'>
							<a class='nav-questions nav-link {$valueQuestion->active}' href='#nav{$key}{$keyQuestion}tab-pill' data-toggle='pill'>{$valueQuestion->no_soal}</a>
						</li>
				";

				$valueQuestion->choices_html = [];
				foreach ($valueQuestion->choices as $keyChoice => $valueChoice) {
					$valueQuestion->checked = NULL;
					if ( $valueQuestion->question_status == 1 ) {
						if ( $valueQuestion->question_answer_key == $keyChoice ) {
							$valueQuestion->checked = 'checked';
						}
					}
					$valueQuestion->choices_html[] = "
						<tr>
							<td class='row'>
							<div class=''>
								{$valueChoice->question_code}.&nbsp 
							</div>
							<div class=''>
								<div class='form-check'>
									<label class='form-check-label'>
										<input type='radio' class='form-check-input choices' name='choices[{$key}/{$keyQuestion}]' value='{$key}/{$keyQuestion}/{$keyChoice}' {$valueQuestion->checked}>{$valueChoice->choice}
									</label>
								</div>
							</div>
							</td>
						</tr>
					"; 
					
				}
				$valueQuestion->choices_html = implode('',$valueQuestion->choices_html);
				if ( $keyQuestion==0 ) {
					$valueQuestion->submitNavigation = "
					<ul class='d-block nav'>
						<li class='border float-right nav-item text-center w-50 border-primary'>
							<a class='nav-link nav-question' href='#nav{$key}{$valueQuestion->no_soal}tab-pill' data-closest='nav{$value->question_categori_id}'>Lanjut</a>
						</li>
					</ul>
					";
				} elseif ( $keyQuestion==($value->count_of_question-1) ) {
					$valueQuestion->submitNavigation = "
						<ul class='nav row'>
							<li class='nav-item col-6 text-center'>
								<a class='nav-question nav-link border border-warning' href='#nav{$key}".($keyQuestion-1)."tab-pill' data-closest='nav{$value->question_categori_id}'>Kembali</a>
							</li>
					";
				} else {
					$valueQuestion->submitNavigation = "
						<ul class='nav row'>
							<li class='nav-item col-6 text-center'>
								<a class='nav-question nav-link border border-warning' href='#nav{$key}".($keyQuestion-1)."tab-pill' data-closest='nav{$value->question_categori_id}'>Kembali</a>
							</li>
							<li class='nav-item col-6 text-center'>
								<a class='nav-question nav-link border border-primary' href='#nav{$key}{$valueQuestion->no_soal}tab-pill' data-closest='nav{$value->question_categori_id}'>Lanjut</a>
							</li>
						</ul>
					";
				}
				$value->tabQuestion[] = "
						<div id='nav{$key}{$keyQuestion}tab-pill' class='container tab-pane tab-questions {$valueQuestion->active}'>
							<hr>
							<label class='font-weight-normal text-muted'>
								Soal ke <b>{$valueQuestion->no_soal}</b> dari <b>{$value->count_of_question}</b>
							</label><br>
							<label>{$valueQuestion->no_soal}. Soal</label><br>
							<label class='text-black-50 text-muted'>
								Kategori: {$value->title}
							</label><br>
							<div class='text-justify font-weight-normal'>
								{$valueQuestion->question}
								<div class='card mt-3'>
									<div class='card-body'>
										<table class='table table-borderless table-hover'>
											<tbody>
												{$valueQuestion->choices_html}
											</tbody>
										</table>
									</div>
								</div>
							</div>
							{$valueQuestion->submitNavigation}
						</div>
				";
			}
			$value->navQuestion = implode('',$value->navQuestion);
			$value->tabQuestion = implode('',$value->tabQuestion);

			$tab[] = "
				<div class='tab-pane fade {$value->active}' id='nav{$value->question_categori_id}' role='tabpanel' aria-labelledby='nav{$value->question_categori_id}-tab'>
					<!-- Nav pills -->
					<ul class='mt-3 nav nav-pills' role='tablist'>
						{$value->navQuestion}
					</ul>
					<!-- Tab panes -->
					<div class='tab-content'>
						{$value->tabQuestion}
					</div>
				</div>
			";
		}
		$tab= implode('',$tab);
		
		$data['nav']['ul'] = "
			<ul class='nav nav-tabs' id='myTab' role='tablist'>
				{$li}
			</ul>
		";

		$data['nav']['tab'] = "
			<div class='tab-content' id='myTabContent'>
				{$tab}
			</div>
		";

		$data['navtab'] = implode('',$data['nav']); 

		$data['html'] = "
			<table class='table table-bordered font-weight-normal'>
				<tbody>
					<tr>
						<td class='w-50'>Total Soal</td>
						<td>".$this->session->userdata('user')->count_of_question_total." Soal</td>
					</tr>
					<tr>
						<td>Batas Waktu Pengerjaan</td>
						<td>".$this->session->userdata('user')->exam_limit_total." Menit (<span id='examLimit'>0</span>)</td>
					</tr>
					<tr>
						<td>Sisa Waktu</td>
						<td><span id='countDown' data-start='".$this->session->userdata('user')->exam_start."' data-end='".$this->session->userdata('user')->exam_end."'>0</span></td>
					</tr>
					<tr>
						<td colspan='2'>
							<b>!!Catatan Penting:</b><br>
							1. Semua tindakan terkait dengan tes ini, dimulai dan diakhiri berdasarkan instruksi dari Pendamping tes, maka lakukan sesuai yang diinstruksikan.<br>
							2. Jumlah pilihan jawaban untuk soal 1 sd 40 ada 4 pilihan yaitu A, B, C, dan D. Pilihlah jawaban yang dianggap tepat.<br>
							3. Jumlah pilihan jawaban untuk soal 41 sd 120 ada 5 pilihan yaitu A, B, C, D dan E. Pilihlah jawaban yang dianggap sesuai dengan kondisi Anda. Ini tentang sesuai atau tidak dengan kondisi Anda, bukan tentang benar atau salah.<br>
							4. Harap kerjakan semua soal, tanpa ada yang terlewat.<br>
						</td>
					</tr>
				</tbody>
			</table>
			{$data['navtab']}
			<a href='".base_url('ujian/session-store')."' class='btn btn-block btn-primary' style='margin-top:10rem' onclick='return confirm(`Apakah Anda yakin, setelah anda memilih selesai mengerjakan anda akan langsung di keluarkan dari sistem secara otomatis, Harap kerjakan semua soal, tanpa ada yang terlewat.`)'>Selesai Mengerjakan</a>
		";

		echo $data['html'];
		// print_r($data);
		// $this->debugs( $data );
		// $this->debugs( $this->M_exam_configs->proses_sql_rand($token,$kategori) );
	}
	/* ==================== END : PROSES UJIAN ==================== */

	public function update_session(){
		$data['question_status'] = 1;
		$data['question_weight'] = $_SESSION['user']->rows[$this->uri->segment(3)]->questions[$this->uri->segment(4)]->choices[$this->uri->segment(5)]->weight;
		$data['question_answer_key'] = $this->uri->segment(5);

		# update questions sessions = question_status
		$_SESSION['user']->rows[$this->uri->segment(3)]->questions[$this->uri->segment(4)]->question_status = $data['question_status'];

		# update questions sessions = question_weight
		$_SESSION['user']->rows[$this->uri->segment(3)]->questions[$this->uri->segment(4)]->question_weight = $data['question_weight'];

		# update questions sessions = question_answer_key
		$_SESSION['user']->rows[$this->uri->segment(3)]->questions[$this->uri->segment(4)]->question_answer_key = $data['question_answer_key'];

		# update session last do work question
		$_SESSION['user']->last_do_work = $this->uri->segment(3).'/'.$this->uri->segment(4);

		$this->M_auth->update_session($this->session->userdata('user')->username);
		// print_r($data);
		// $encode = json_encode($this->session->userdata('user'));
		// $decode = json_decode();
		// print_r($encode);
		// echo "<br>====<br><br>";
		// print_r($decode);
		// $this->debugs($this->session);
	}
	/* ==================== START : EXAM STORE PROCESS ==================== */
	public function session_store(){
		
		$data['answers'] = array(
			'username' => $this->session->userdata('user')->username,
			'question_title' => $this->session->userdata('user')->count_of_question_total,
			'total_questions' => $this->session->userdata('user')->count_of_question_total,
			'limit_passing_grade' => 0,
			'passing_grade' => 0,
			'correct_answer' => 0,
			'wrong_answer' => 0,
			'not_answered' => 0,
			'exam_limit' => $this->session->userdata('user')->exam_limit_total,
			'create_at' => date('Y-m-d H:i:s'),
			'start_exam' => $this->session->userdata('user')->exam_start,
			'end_exam' => $this->session->userdata('user')->exam_end,
		);
		$answer_id = 0;
		# create variable count questions answered
		$questions_answered = 0;

		# generate data insert batch in table answers_detail
		foreach ($this->session->userdata('user')->rows as $keyCategory => $valueCategory) {			
			foreach ($valueCategory->questions as $keyQuestion => $valueQuestion) {
				if ( $valueQuestion->question_status == 0 ) {
					$questions_answered += 1;
				}
				
				$data['answers_detail'][] = array(
					'answer_id' => $answer_id,
					'category' => $valueCategory->title,
					'correct_answer' => 0,
					'wrong_answer' => 0,
					'not_answered' => 0,
					'total_questions' => 0,
					'limit_passing_grade' => 0,
					'passing_grade' => 0,
					'question_assessment' => $valueQuestion->choices[$valueQuestion->question_answer_key]->question_code,
					'exam_limit' => 0,
				);
			}

		}

		if ( $questions_answered > 0 ) {
			# code... jika ada pertanyaan yang belum terjawab lebih dari 0 jalankan script dibawah ini
			echo ("<script>window.alert('Maaf masih ada {$questions_answered} soal belum anda kerjakan');window.location.href='".base_url()."';</script>");
		} else {
			# code... jika tidak ada pertanyaan yang belum terjawab lebih dari 0 jalankan script dibawah ini
			
			# load model just for this session
			$this->load->model(['M_configs','M_answers','M_answers_detail']);
		
			# get question_title
			$data['answers']['question_title'] = 'Tes Diasnostik-' . ( $this->M_answers->rows_by_username( $username=$this->session->userdata('user')->username)+1 );
			# send data to Model 
			$this->M_answers->post = $data['answers'];
			
			# store process return last insert id table : answers
			$answer_id = $this->M_answers->store();
			
			# set field answer_id in data['answers_detail']
			foreach ($data['answers_detail'] as $key => $value) {
				$data['answers_detail'][$key]['answer_id'] = $answer_id;
			}
			
			# send data to Model 
			$this->M_answers_detail->post = $data['answers_detail'];
			
			# store process table : answers_detail
			$this->M_answers_detail->store();;
	
			# reset session
			$this->M_auth->reset_session($this->session->userdata('user')->username);
			$_SESSION['user']->examination_process = FALSE;
			$_SESSION['user']->rows = NULL;
			redirect(base_url('auth/logout'));
		}
	}
	/* ==================== END : EXAM STORE PROCESS ==================== */

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

        $mail->setFrom('pinsus2017surabaya@gmail.com', 'Try Out CAT CPNS'); // user email
        $mail->addReplyTo('pinsus2017surabaya@gmail.com', ''); //user email

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
            $response = FALSE;
        }else{
            // echo 'Message has been sent';
            $response = TRUE;
        }
        /* ==================== END :: SEND EMAIL ==================== */
        return $response;
    }
}
