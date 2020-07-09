<?php
class MY_Controller extends CI_Controller{
	function __construct(){
        parent::__construct();
        $this->load->model(['M_pages']);
    }

    function render_pages($view_load='welcome_message',$data_load=[])
    {
        if ( $view_load=='welcome_message' ) {
            # code...
            $this->load->view($view_load, $data_load );
        } else {
            # code...
            $this->load->view('header');
            $this->load->view('nav', $this->navbar() );
            $this->load->view($view_load, $data_load );
            $this->load->view('footer');
        }
    }

    public function navbar()
    {
        return [
            'pages' => $this->M_pages->get()
        ];
    }

    /* ==================== START : FOR DEBUG ONLY ==================== */
	public function debugs( $data )
	{
        if ( ENVIRONMENT=='development' ) { # debug works
            echo '
                <pre>
                    '.strip_tags(json_encode($data,JSON_PRETTY_PRINT)).'
                </pre>
                
            ';
        }
                
	}
	/* ==================== END : FOR DEBUG ONLY ==================== */
}