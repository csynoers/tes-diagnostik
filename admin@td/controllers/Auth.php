<?php
class Auth extends MY_Controller{
 
    function __construct(){
        parent::__construct();
        
        # load encrypt library
        $this->load->library('encryption');
        $this->encryption->initialize(
            array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
                'key' => '3s0c9m7@gmail.com'
            )
        );

        # load users Model
        $this->load->model('M_auth');

    }

    function index(){
        # Check authentication 
        if( $this->session->userdata('root') ){
            redirect(base_url('dashboard'));
		}
        $csrf = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        $this->load->view('login',$csrf);
    }

    function process(){
        /**
         * initialize where condition in $where_users
         */
        $where_users = array(
            'username'  => $this->security->xss_clean($this->input->post('username')),
            'level'     => 'root',
            'block'     => '0'
        );
        
        $cek_users  = $this->M_auth->check_auth("users",$where_users)->num_rows();
        
        if ( $cek_users > 0 ) {
            # code...
            $password = $this->security->xss_clean($this->input->post('password'));
            $row      = $this->M_auth->check_auth("users",$where_users)->row();
            
            # if decrypt row->password same as $password
            if ( $this->encryption->decrypt( $row->password ) == $password ) {
                # set session user
                $this->session->set_userdata([ 'root' => $row ]);
                redirect( base_url('dashboard') );

            }

            # if decrypt row->password different with $password
            else {
                # code...
                $this->session->set_flashdata('msg', 'username or password does not exist.');
                redirect(base_url('auth'));
            }
            
        }
        
        else{
            
            $this->session->set_flashdata('msg', 'username or password does not exist.');
            redirect(base_url('auth'));
        }
    }

    function logout(){
        $this->session->sess_destroy();
        redirect(base_url('auth'));
    }
}