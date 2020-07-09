<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

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
        if( ! $this->session->userdata('root') ){
            redirect(base_url());
		}

		$this->load->model(['M_users','M_answers','M_question']);
	}
	public function index()
	{
		$data['siswa_terdaftar'] = count($this->M_users->terdaftar());
		$data['pembayaran_belum_dikonfirmasi'] = count($this->M_users->konfirmasi());
		$data['hasil_try_out'] = count($this->M_answers->get());
		$data['total_soal'] = count($this->M_question->get_question());
		$data['siswa_sedang_mengerjakan'] = count($this->M_users->siswa_sedang_mengerjakan());
		// $this->debugs($data);
        $this->render_pages( 'dashboard', $data );
        
	}
}
