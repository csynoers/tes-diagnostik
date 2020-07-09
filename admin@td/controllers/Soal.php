<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soal extends MY_Controller {

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
		
		$this->load->model(['M_question','M_question_categories','M_choice']);
	}

	/* ==================== START : DAFTAR SOAL ==================== */
	public function index()
	{
		$this->load->helper('text');
		$data['kategori_soal'] = $this->M_question_categories->get_question_categories();
		$data['rows'] = $this->M_question->get_question();
		$this->render_pages( 'question', $data );
	}
	/* ==================== END : DAFTAR SOAL ==================== */

	/* ==================== START : FORM TAMBAH SOAL ==================== */
	public function add()
	{
		$data['data_action']  	= base_url() .'soal/store';			
		$data['options_kategori_soal'] = $this->options_kategori_soal();

		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='form-group'>
					<label>Pertanyaan :</label>
					<textarea name='question' class='form-control mytextarea'>Masukan Pertanyaan disini ...</textarea>
				</div>
				<div class='form-group'>
					<label>Kategori soal :</label>
					{$data['options_kategori_soal']}
				</div>
				<div id='fieldChoices'></div>
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

		/* for debug only : uncomment text below */
		// $this->debugs( $data );
	}
	/* ==================== END : FORM TAMBAH SOAL ==================== */

	/* ==================== START : FORM EDIT SOAL url{soal/edit}==================== */
	public function edit()
	{
		$data['rows']			= $this->M_question->get_question( $this->uri->segment(3) );
		// $this->debugs($data);
		foreach ($data['rows'] as $key => $value) {
			$data['action']   		= base_url();		
			$data['data_action']  	= base_url().'soal/store/'.$this->uri->segment(3);

			$checked_option = [
				($value->block_mod=='YES' ? 'checked' : NULL ),
				($value->block_mod=='NO' ? 'checked' : NULL ),
			];
			
			$value->title_jawaban = '<hr><label>Silahkan Masukan Jawaban (A - '.($data['rows'][0]->count_of_choices == 4 ? 'D' : 'E' ).')</label>';

			# get rows data choice where question_id
			$choices = $this->M_choice->get( $value->question_id );
			$value->choices = NULL;
			foreach ($choices as $keyChoices => $valueChoices) {
				$value->choices .= "
					<div class='form-check'>
						<label class='form-check-label' for='exampleRadios1'>Jawaban {$valueChoices->question_code}:</label>
						<textarea name='questions[{$valueChoices->question_code}][choice]' class='form-control mytextarea'>{$valueChoices->choice}</textarea>
					</div>
					<input type='hidden' name='questions[{$valueChoices->question_code}][choice_id]' value='{$valueChoices->choice_id}' >
					<hr>
				"; 
			}

			$html= "
				<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
					<div class='form-group'>
						<label>Pertanyaan :</label>
						<textarea name='question' class='form-control mytextarea'>{$value->question}</textarea>
					</div>
					<div class='form-group'>
						<label>Kategori soal :</label>
						<input type='text' class='form-control' value='{$value->title}' readonly=''>
					</div>
					<div id='fieldChoices'>
						{$value->title_jawaban}
						{$value->choices}
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
	/* ==================== END : FORM EDIT SOAL ==================== */

	/**
	 * ==================== START : PROCESS DATA STORE url{soal/store/id}==================== 
	 * id = bersifat optional (jika terdapat id maka process update jika tidak, maka proses insert)
	 * */
	public function store()
	{
		if ( $this->uri->segment(3) ) { # update data
			# create messsage store variable TRUE or FALSE : default is false
			$stats = FALSE;

			# send data to class M_question Model post variable
			$this->M_question->post= $this->input->post();

			// $this->debugs($this->M_question);

			# yang mempunyai bobot lebih dari 0 hanya pilihan yang benar saja
			# update table questions where question_id = $this->uri->segment(3)
			$this->M_question->store();

			# get choices all
			$choices = $this->input->post('questions');
			
			# get selected choice is true
			// $choices_option = $this->input->post('choices_option');

			foreach ($choices as $keyChoices => $valueChoices) {
				$this->M_choice->post= [
					'choice_id' => $valueChoices['choice_id'],
					'question_code' => $keyChoices,
					'weight' => 0,
					'choice' => $valueChoices['choice'],
				];

				# call store method: update to choices table
				$this->M_choice->store();
			}
			$stats = TRUE;

			if ( $stats ) {
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
			# create messsage store variable TRUE or FALSE : default is false
			$stats = FALSE;

			$this->M_question->post= $this->input->post();
			// print_r($this->M_question->post);
			// die();

			# get data kategori soal
			$row_question_categori = $this->M_question_categories->get_question_categories( $this->input->post('question_categori_id') )[0];

			
			# get last insert id table questions kolom question_id
			$last_insert_question_id = $this->M_question->store();

			# get choices all
			$choices = $this->input->post('choices_answer');
			
			# get selected choice is true
			// $choices_option = $this->input->post('choices_option');

			foreach ($choices as $key => $value) {
				// $weight = ( $key==$choices_option ) ? $row_question_categori->true_grade : 0 ;
				$this->M_choice->post= [
					'question_id' => $last_insert_question_id,
					'question_code' => $key,
					'weight' => 0,
					'choice' => $value,
				];

				# call store method: insert to choices table
				$this->M_choice->store();
			}
			$stats = TRUE;

			if ( $stats ) {
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
	/* ==================== END : PROCESS DATA STORE ==================== */

	/* ==================== START : PROCESS DELETE DATA ==================== */
	public function delete()
	{
		# get question_categori_id
		$id= $this->uri->segment(3);
		
		if ( $this->M_question->check_relations( $id ) > 0 ) {
			# relation exist
			$this->msg= [
				'stats'=>0,
				'msg'=>'Maaf Data ini sedang dipakai',
			];
		} else {
			# no relations
			if ( $this->M_question->delete( $id ) ) {
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
	/* ==================== END : PROCESS DELETE DATA ==================== */

	/* ==================== START : SELECT OPTIONS KATEGORI SOAL ==================== */
	protected function options_kategori_soal()
	{
		$data[] = "<option value='' selected disabled> -- Pilih kategori soal -- </option>"; 
		foreach ($this->M_question_categories->get_question_categories() as $key => $value) {
			$data[] = "<option value='{$value->question_categori_id}' data-count-of-choice='{$value->count_of_choices}'>{$value->title}</option>";
		}
		$data = implode('',$data);
		$data = "
			<select name='question_categori_id' class='form-control' id='optionsKategoriSoal' required>{$data}</select>
		";

		return $data;
	}
	/* ==================== END : SELECT OPTIONS KATEGORI SOAL ==================== */




}
