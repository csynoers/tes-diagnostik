<?php
class MY_Controller extends CI_Controller{
	function __construct(){
        parent::__construct();
    }
    function render_pages( $view='welcome_message', $data=[], $stats=FALSE )
    {
        if ( ! empty( $this->input->get('debugs') ) ) {
            /* for debug only : uncomment text below */
            $this->debugs( $data );
        } else {
            $this->load->view('header');
            $this->load->view('nav');
            $this->load->view( $view, $data, $stats );
            $this->load->view('footer');
        }
        

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