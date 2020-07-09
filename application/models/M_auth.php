<?php 

class M_auth extends CI_Model{
	protected $table = 'users';
	protected $primaryKey = 'users.id';
	protected $foreignKey = 'users.username';
	protected $username;
	protected $password;
	protected $level;
	protected $block;
	protected $create_at;
	protected $update_at;
	protected $last_login;

	# table relations
	protected $tableRelationUsersDetail = 'users_detail.username=users.username';

	protected $tableUsersDetail = 'users_detail'; 
	protected $primaryKeyUsersDetail = 'users_detail.user_detail_id'; 
	protected $foreignKeyUsersDetail = 'users_detail.username';
	protected $nik; 
	protected $fullname; 
	protected $email; 
	protected $telp; 
	protected $birth_date; 
	protected $schools; 
	
	function check_auth($table,$where){		
		return $this->db->get_where($table,$where);
	}

	public function store()
	{
		$this->username = $this->post['username'];
		$this->password = $this->post['password'];
		$this->level = 'user';
		$this->block = '1';
		$this->create_at = date('Y-m-d H:i:s');

		$data = array(
			'username' => $this->username,
			'password' => $this->password,
			'level' => $this->level,
			'block' => $this->block,
			'create_at' => $this->create_at,
		);
		$this->db->insert( $this->table,$data );

		return $this->store_detail( $this->username );
	}

	public function store_detail( $username )
	{
		$this->username = $username;
		$this->nik = $this->post['nik'];
		$this->fullname = $this->post['fullname'];
		$this->email = $this->post['email'];
		$this->telp = $this->post['telp'];
		$this->birth_date = $this->post['birth_date'];
		$this->schools = $this->post['schools'];

		$data = array(
			'username' => $this->username,
			'nik' => $this->nik,
			'fullname' => $this->fullname,
			'email' => $this->email,
			'telp' => $this->telp,
			'birth_date' => $this->birth_date,
			'schools' => $this->schools,
		);
		return $this->db->insert( $this->tableUsersDetail,$data );
	}
	
	public function check_already_exist()
	{
		$this->username = $this->post['username'];
		$this->nik = $this->post['nik'];
		$this->email = $this->post['email'];
		$this->telp = $this->post['telp'];

		# where condition
		// $this->db->where('users.username','admin');
		$this->db->where( 'users.username', $this->username );
		$this->db->or_where( 'users_detail.nik', $this->nik );
		$this->db->or_where( 'users_detail.email', $this->email );
		$this->db->or_where( 'users_detail.telp', $this->telp );

		$this->db->join( $this->tableUsersDetail, $this->tableRelationUsersDetail, 'left' );

		return $this->db->get( $this->table );
	}
	public function reset_password()
	{
		$this->username = $this->post['username'];
		$this->password = $this->post['password'];
		$data =  array(
			'password' => $this->password,
		);
		$this->db->where($this->foreignKey,$this->username);
		return $this->db->update($this->table, $data);
	}
	public function update_last_login( $username )
	{
		$this->username = $this->post['username'];
		$this->last_login = date('Y-m-d H:i:s');
		$data =  array(
			'last_login' => $this->last_login,
		);
		$this->db->where($this->foreignKey,$this->username);
		return $this->db->update($this->table, $data);
	}
	public function update_session( $username )
	{
		$this->session = json_encode($this->session->userdata('user'));
		$data =  array(
			'session' => $this->session,
		);
		$this->db->where($this->foreignKey,$username);
		return $this->db->update($this->table, $data);
	}
	public function reset_session( $username )
	{
		$this->session = json_encode($this->session->userdata('user'));
		$data =  array(
			'session' => NULL,
		);
		$this->db->where($this->foreignKey,$username);
		return $this->db->update($this->table, $data);
	}
}