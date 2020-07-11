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
            $this->load->view('nav',[
                'asal_sekolah' => $this->get_group_by_schools()
            ]);
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
    
    protected function get_group_by_schools()
    {
        # load model untuk mendapatkan data sekolah
        $this->load->model('M_users_detail');

        return $this->M_users_detail->get_group_by_schools();
    }
}