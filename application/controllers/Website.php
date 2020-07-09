<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/website
	 *	- or -
	 * 		http://example.com/index.php/website/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/website/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['M_pages']);
	}
	public function index()
	{
		if( $this->session->userdata('user') )
		{
			redirect(base_url('ujian'));
			die();
		}

		if ( count($this->M_pages->get()) > 0 ) {
			# get data dashboard
			$data = [
				'row' => $this->M_pages->get()[0]
			];

			# rendering pages :: dashboard
			$this->render_pages('dashboard',$data);
		} else {
			echo json_encode([
				'message' => 'Maaf Halaman Belum Tersedia'
			]);
		}
	}
}
