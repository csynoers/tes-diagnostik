<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends MY_Controller {

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
		
		$this->load->model('M_banks');
	}
	/* ==================== START : DEFAULT PAGE url{pages/index}==================== */
	public function index()
	{
		$data['rows'] = $this->M_banks->get();
		$this->render_pages( 'banks', $data );
        
	}
	/* ==================== END : DEFAULT PAGE ==================== */

	/* ==================== START : FORM ADD PAGES url{pages/add}==================== */
	public function add()
	{
		$data['action']   		= base_url();		
		$data['data_action']  	= base_url() .'bank/store';		
		$data['slug']			= "{$_SERVER['SERVER_NAME']}/bank/";
		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='form-group'>
					<label>Number</label>
					<input type='text' name='bank_number' class='form-control' placeholder='Number' required=''>
				</div>
				<div class='form-group'>
					<label>Type</label>
					<input type='text' name='bank_type' class='form-control' placeholder='Type' required=''>
				</div>
				<div class='form-group'>
					<label>Title</label>
					<input type='text' name='bank_title' class='form-control' placeholder='Title' required=''>
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
		$data['rows']			= $this->M_banks->get( $this->uri->segment(3) );
		foreach ($data['rows'] as $key => $value) {
			$data['action']   		= base_url();		
			$data['data_action']  	= base_url().'bank/store/'.$this->uri->segment(3);		

			$html= "
				<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' id='addNew' method='post' enctype='multipart/form-data'>
					<div class='form-group'>
						<label>Number</label>
						<input value='{$value->bank_number}' type='text' name='bank_number' class='form-control' placeholder='Number' required=''>
					</div>
					<div class='form-group'>
						<label>Type</label>
						<input value='{$value->bank_type}' type='text' name='bank_type' class='form-control' placeholder='Type' required=''>
					</div>
					<div class='form-group'>
						<label>Title</label>
						<input value='{$value->bank_title}' type='text' name='bank_title' class='form-control' placeholder='Title' required=''>
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
			$this->M_banks->post= $this->input->post();
			if ( $this->M_banks->store() ) {
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
			$this->M_banks->post= $this->input->post();
			if ( $this->M_banks->store() ) {
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
		if ( $this->M_banks->delete( $this->uri->segment(3) ) ) {
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
