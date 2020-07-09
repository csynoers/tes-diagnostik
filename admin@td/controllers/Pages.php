<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller {

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
		
		$this->load->model('M_pages');
	}
	/* ==================== START : DEFAULT PAGE url{pages/index}==================== */
	public function index()
	{
		$data['rows'] = $this->M_pages->get_pages();
		$this->render_pages( 'pages', $data );
        
	}
	/* ==================== END : DEFAULT PAGE ==================== */

	/* ==================== START : FORM ADD PAGES url{pages/add}==================== */
	public function add()
	{
		$data['action']   		= base_url();		
		$data['data_action']  	= base_url() .'pages/store';		
		$data['slug']			= "{$_SERVER['SERVER_NAME']}/pages/";
		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='form-group'>
					<label>Title</label>
					<input type='text' name='title' class='form-control' placeholder='Type the title page here ...' required=''>
				</div>
				<div class='form-group'>
					<label>Slug <small class='text-info'>*)digunakan untuk seo format penulisan huruf kecil semua gunakan tanda '-' untuk memisahkan kata </small></label>
					<div class='input-group mb-3'>
						<div class='input-group-prepend'>
							<span class='input-group-text'><i class='fa fa-link'></i></span>
						</div>
						<div class='input-group-prepend'>
							<span class='input-group-text'>{$data['slug']}</span>
						</div>
						<input type='text' name='slug' class='form-control' placeholder='ex: title-page' required=''>
					</div>
				</div>
				<div class='form-group'>
					<label>Deskripsi</label>
					<textarea name='description' class='form-control mytextarea'></textarea>
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
	/* ==================== END : FORM ADD PAGES ==================== */

	/* ==================== START : FORM EDIT PAGES url{pages/edit}==================== */
	public function edit()
	{
		$data['rows']			= $this->M_pages->get_pages( $this->uri->segment(3) );
		foreach ($data['rows'] as $key => $value) {
			$data['action']   		= base_url();		
			$data['data_action']  	= base_url().'pages/store/'.$this->uri->segment(3);		
			$data['slug']			= "{$_SERVER['SERVER_NAME']}/pages/";

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
						<label>Slug <small class='text-info'>*)digunakan untuk seo format penulisan huruf kecil semua gunakan tanda '-' untuk memisahkan kata </small></label>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fa fa-link'></i></span>
							</div>
							<div class='input-group-prepend'>
								<span class='input-group-text'>{$data['slug']}</span>
							</div>
							<input value='{$value->slug}' type='text' name='slug' class='form-control' placeholder='ex: title-page' required=''>
						</div>
					</div>
					<div class='form-group'>
						<label>Deskripsi</label>
						<textarea name='description' class='form-control mytextarea'>{$value->description}</textarea>
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
	/* ==================== END : FORM EDIT PAGES ==================== */

	/**
	 * ==================== START : PROCESS DATA STORE url{pages/store/id}==================== 
	 * id = bersifat optional (jika terdapat id maka process update jika tidak, maka proses insert)
	 * */
	public function store()
	{
		if ( $this->uri->segment(3) ) { # update data
			$this->M_pages->post= $this->input->post();
			if ( $this->M_pages->store() ) {
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
			$this->M_pages->post= $this->input->post();
			if ( $this->M_pages->store() ) {
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
		if ( $this->M_pages->delete( $this->uri->segment(3) ) ) {
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
		echo json_encode($this->msg);
	}
	/* ==================== END : PROCESS DELETE DATA ==================== */
}
