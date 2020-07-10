<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends MY_Controller {

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

		# Check authentication 
        if( ! $this->session->userdata('root') ){
            redirect(base_url());
		}
		
    }
    
    /* ==================== START : KATEGORI SOAL  ==================== */
	public function soal()
	{
        # load question_categories Model
        $this->load->model('M_question_categories');

        switch ( empty($this->uri->segment(3)) ? NULL : $this->uri->segment(3) ) {
            case 'add':
				# code...
				$this->soal_add();
				break;
				
            case 'edit':
				# code...
				$this->soal_edit();
				break;
				
            case 'store':
				$this->soal_store();
				break;
				
            case 'delete':
				# code...
				$this->soal_delete();
                break;
            
            default:
                # code...
				$data['rows'] = $this->M_question_categories->get_question_categories();
				// $this->debugs($data);
                $this->render_pages( 'question_categories', $data );
                break;
        }
        
	}
	public function soal_add()
	{
		$data['data_action']  = base_url() .'kategori/soal/store';
		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='form-group'>
					<label>Nama kategori soal</label>
					<input type='text' name='title' class='form-control' placeholder='Ketikan nama kategori soal disini ...' required=''>
				</div>
				<div class='form-group'>
					<label>Pilih Jumlah Jawaban</label>
					<div class='form-check'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='count_of_choices' value='4' required=''> 4 (A, B, C, & D)
						</label>
					</div>
					<div class='form-check'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='count_of_choices' value='5' required=''> 5 (A, B, C, D, & E)
						</label>
					</div>
				</div>
				<div class='form-group'>
					<label class='d-block'>Publish</label>
					<div class='form-check-inline'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='publish' value='0' required='' checked=''>YES
						</label>
						</div>
						<div class='form-check-inline'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='publish' value='1' required=''>NO
						</label>
					</div>
				</div>
				<button type='submit' class='btn btn-primary'>Save</button>
			</form>
        ";
		echo $html;
	}
	/* ==================== START : FORM EDIT KATEGORI SOAL url{kategori/soal/edit/id} ==================== */
	public function soal_edit()
	{
		$data['rows']			= $this->M_question_categories->get_question_categories( $this->uri->segment(4) );
		foreach ($data['rows'] as $key => $value) {		
			$data['data_action']  = base_url().'kategori/soal/store/'.$this->uri->segment(4);		
			$jumlah_jawaban= ($value->count_of_choices=='4'? '4 (A, B, C, & D)' : '5 (A, B, C, D, & E)' ) ;
			$checked_option = [
				($value->block=='0' ? 'checked' : NULL ),
				($value->block=='1' ? 'checked' : NULL ),
			];
			$html= "
				<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' id='addNew' method='post' enctype='multipart/form-data'>
					<div class='form-group'>
						<label>Title</label>
						<input value='{$value->title}' type='text' name='title' class='form-control' placeholder='Type the title page here ...' required=''>
					</div>
					<div class='form-group'>
						<label>Jumlah jawaban <small><span class='text-info'>*)Kolom ini tidak bisa diubah</span></small></label>
						<span class='form-control'>{$jumlah_jawaban}</span>
					</div>
					<div class='form-group'>
						<label class='d-block'>Publish</label>
						<div class='form-check-inline'>
							<label class='form-check-label'>
								<input type='radio' class='form-check-input' name='publish' value='0' required='' {$checked_option[0]}>YES
							</label>
							</div>
							<div class='form-check-inline'>
							<label class='form-check-label'>
								<input type='radio' class='form-check-input' name='publish' value='1' required='' {$checked_option[1]}>NO
							</label>
						</div>
					</div>
					<button type='submit' class='btn btn-primary'>Save</button>
				</form>
			";
		}
		echo $html;
	}
	/* ==================== END : FORM EDIT KATEGORI SOAL ==================== */

	/**
	 * ==================== START : PROCESS DATA KATEGORI SOAL STORE url{kategori/soal/store/id}==================== 
	 * id = bersifat optional (jika terdapat id maka process update jika tidak, maka proses insert)
	 * */
	public function soal_store()
	{
		if ( $this->uri->segment(4) ) { # update data
			$this->M_question_categories->post= $this->input->post();
			if ( $this->M_question_categories->store() ) {
				$this->msg= [
					'stats'=> 1,
					'msg'=> 'Data Berhasil Diubah',
				];
			} else {
				$this->msg= [
					'stats'=> 0,
					'msg'=> 'Data Gagal Diubah',
				];
			}
			echo json_encode( $this->msg );
		} else { # insert data
			$this->M_question_categories->post= $this->input->post();
			if ( $this->M_question_categories->store() ) {
				$this->msg= [
					'stats'=> 1,
					'msg'=> 'Data Berhasil Ditambahkan',
				];
			} else {
				$this->msg= [
					'stats'=> 0,
					'msg'=> 'Data Gagal Ditambahkan',
				];
			}
			echo json_encode( $this->msg );
		}
	}
	/* ==================== END : PROCESS DATA KATEGORI SOAL STORE ==================== */

	public function soal_delete()
	{
		# get question_categori_id
		$id= $this->uri->segment(4);
		if ( $this->M_question_categories->check_relations( $id ) > 0 ) {
			# relation exist
			$this->msg= [
				'stats'=>0,
				'msg'=>'Maaf Data ini sedang dipakai',
			];
		} else {
			# no relations
			if ( $this->M_question_categories->delete( $id ) ) {
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
		}
			
		echo json_encode( $this->msg );
	}
	/* ==================== END : KATEGORI SOAL  ==================== */

	/* ==================== START : MASTER DATA :: ASAL SEKOLAH  ==================== */
	public function asal_sekolah()
	{
		# load question_categories Model
		$this->load->model('M_schools');

		switch ( empty($this->uri->segment(3)) ? NULL : $this->uri->segment(3) ) {
			case 'add':
				# code...
				$this->asal_sekolah_add();
				break;
				
			case 'edit':
				# code...
				$this->asal_sekolah_edit();
				break;
				
			case 'store':
				$this->asal_sekolah_store();
				break;
				
			case 'delete':
				# code...
				$this->asal_sekolah_delete();
				break;
			
			default:
				# code...
				$data['rows'] = $this->M_schools->get();
				// $this->debugs($data);
				$this->render_pages( 'asal_sekolah', $data );
				break;
		}
		
	}
	public function asal_sekolah_add()
	{
		$data['data_action']  = base_url() .'kategori/asal-sekolah/store';
		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='form-group'>
					<label>Nama sekolah</label>
					<input type='text' name='title' class='form-control' placeholder='Ketikan nama sekolah disini ...' required=''>
				</div>
				<div class='form-group'>
					<label class='d-block'>Publish</label>
					<div class='form-check-inline'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='publish' value='0' required='' checked=''>YES
						</label>
						</div>
						<div class='form-check-inline'>
						<label class='form-check-label'>
							<input type='radio' class='form-check-input' name='publish' value='1' required=''>NO
						</label>
					</div>
				</div>
				<button type='submit' class='btn btn-primary'>Save</button>
			</form>
        ";
		echo $html;
	}

	/* ==================== START : FORM EDIT MASTER DATA ASAL SEKOLAH url{kategori/asal-sekolah/edit/id} ==================== */
	public function asal_sekolah_edit()
	{
		$id				= $this->uri->segment(4);
		$data['rows']	= $this->M_schools->get( $id );

		foreach ($data['rows'] as $key => $value) {		
			$data['data_action']  = base_url().'kategori/asal-sekolah/store/'.$id;		
			$checked_option = [
				($value->block=='0' ? 'checked' : NULL ),
				($value->block=='1' ? 'checked' : NULL ),
			];
			$html= "
				<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' id='addNew' method='post' enctype='multipart/form-data'>
					<div class='form-group'>
						<label>Nama sekolah</label>
						<input value='{$value->title}' type='text' name='title' class='form-control' placeholder='Type the title page here ...' required=''>
					</div>
					<div class='form-group'>
						<label class='d-block'>Publish</label>
						<div class='form-check-inline'>
							<label class='form-check-label'>
								<input type='radio' class='form-check-input' name='publish' value='0' required='' {$checked_option[0]}>YES
							</label>
							</div>
							<div class='form-check-inline'>
							<label class='form-check-label'>
								<input type='radio' class='form-check-input' name='publish' value='1' required='' {$checked_option[1]}>NO
							</label>
						</div>
					</div>
					<button type='submit' class='btn btn-primary'>Save</button>
				</form>
			";
		}
		echo $html;
	}
	/* ==================== END : FORM EDIT MASTER DATA ASAL SEKOLAH ==================== */

	/**
	 * ==================== START : PROCESS DATA MASTER DATA ASAL SEKOLAH STORE url{kategori/asal-sekolah/store/id}==================== 
	 * id = bersifat optional (jika terdapat id maka process update jika tidak, maka proses insert)
	 * */
	public function asal_sekolah_store()
	{
		$id = $this->uri->segment(4);
		if ( $id ) { # update data
			$this->M_schools->post= $this->input->post();
			if ( $this->M_schools->store( $id ) ) {
				$this->msg= [
					'stats'=> 1,
					'msg'=> 'Data Berhasil Diubah',
				];
			} else {
				$this->msg= [
					'stats'=> 0,
					'msg'=> 'Data Gagal Diubah',
				];
			}
			echo json_encode( $this->msg );
		} else { # insert data
			$this->M_schools->post= $this->input->post();
			if ( $this->M_schools->store() ) {
				$this->msg= [
					'stats'=> 1,
					'msg'=> 'Data Berhasil Ditambahkan',
				];
			} else {
				$this->msg= [
					'stats'=> 0,
					'msg'=> 'Data Gagal Ditambahkan',
				];
			}
			echo json_encode( $this->msg );
		}
	}
	/* ==================== END : PROCESS DATA MASTER DATA ASAL SEKOLAH STORE ==================== */

	/* ==================== START : PROCESS DATA MASTER DATA ASAL SEKOLAH DELETE ==================== */
	public function asal_sekolah_delete()
	{
		# get question_categori_id
		$id= $this->uri->segment(4);
		if ( $this->M_schools->delete( $id ) ) {
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
	/* ==================== END : PROCESS DATA MASTER DATA ASAL SEKOLAH DELETE ==================== */

	/* ==================== END : MASTER DATA :: ASAL SEKOLAH  ==================== */
}
