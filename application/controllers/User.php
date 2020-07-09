<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

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

		$this->load->model(['M_users','M_users_detail']);

		# load encrypt library
        $this->load->library('encryption');
        $this->encryption->initialize(
            array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
                'key' => '3s0c9m7@gmail.com'
            )
        );
	}
	/* ==================== START : FORM EDIT USERS url{pages/edit}==================== */
	public function edit()
	{
		# get username from session user login
		$username = $this->session->userdata('user')->username;

		# get row user
		$row = $this->M_users->get( $username );

		$data['action']   		= base_url();		
		$data['data_action']  	= base_url().'user/store';		

		$html= "
			<form action='javascript:void(0)' data-action='{$data['data_action']}' role='form' method='post' enctype='multipart/form-data'>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>NIK</span>
					</div>
					<span class='form-control font-weight-normal bg-gray'>{$row->nik}</span>
					<div class='input-group-append'>
						<span class='fa fa-user input-group-text'></span>
					</div>
				</div>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>Nama lengkap</span>
					</div>
					<span class='form-control font-weight-normal bg-gray'>{$row->fullname}</span>
					<div class='input-group-append'>
						<span class='fa fa-user input-group-text'></span>
					</div>
				</div>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>Email</span>
					</div>				
					<input value='{$row->email}' name='email' type='email' class='form-control' placeholder='Email' required='' autocomplete='on'>
					<div class='input-group-append'>
						<span class='fa fa-envelope input-group-text'></span>
					</div>
				</div>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>Nomor telepon</span>
					</div>		
					<input value='{$row->telp}' name='telp' type='telp' class='form-control' placeholder='Nomor telepon' required='' autocomplete='on'>
					<div class='input-group-append'>
						<span class='fa fa-phone input-group-text'></span>
					</div>
				</div>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>Username</span>
					</div>		
					<span class='form-control font-weight-normal bg-gray'>{$row->username}</span>
					<div class='input-group-append'>
						<span class='fa fa-user input-group-text'></span>
					</div>
				</div>
				<div class='input-group mb-3'>
					<div class='input-group-append w-25'>
						<span class='input-group-text w-100'>Password</span>
					</div>		
					<input name='password' type='password' class='form-control' placeholder='Password !!jika tidak diganti kosongkan saja'>
					<div class='input-group-append'>
						<span class='fa fa-lock input-group-text'></span>
					</div>
				</div>
				<button type='submit' class='btn btn-primary'>Save</button>
			</form>
		";

		echo $html;
	}
	/* ==================== END : FORM EDIT USERS ==================== */

	/**
	 * ==================== START : PROCESS DATA STORE url{user/store}==================== 
	 * */
	public function store()
	{
		# update data
		$post = $this->input->post();
		# set default status is 0
		$username = $this->session->userdata('user')->username;
		if ( $this->input->post('password') ) {
			$post['password'] = $this->encryption->encrypt($this->input->post('password'));
			# send post to model
			$this->M_users->post = $post;
			$this->M_users->store( $username );
		}

		$this->M_users_detail->post= $post;
		# check already for phone number or email
		if ( $this->M_users_detail->check_already_exist( $username ) > 0 ) {
			$this->msg= [
				'stats'=> 0,
				'msg'=> 'Pastikan nomor telpon dan email belum pernah dipakai',
			];
			$status = FALSE;
		} else {
			if ( $this->M_users_detail->store( $username ) ) {
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
			
		}
		echo json_encode( $this->msg );
	}
	/* ==================== END : PROCESS DATA STORE ==================== */
}
