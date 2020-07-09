<?php 

class M_auth extends CI_Model{
	protected $table = 'users';	
	function check_auth($table,$where){		
		return $this->db->get_where($table,$where);
	}	
}