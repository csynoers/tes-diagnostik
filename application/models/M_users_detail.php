<?php 

class M_users_detail extends CI_Model{
	# table users_detail
	protected $table = 'users_detail'; 
	protected $primaryKey = 'users_detail.user_detail_id'; 
	protected $foreignKey = 'users_detail.username';
	protected $nik; 
	protected $fullname; 
	protected $email; 
    protected $telp;

    public function store( $username )
    {
        $this->email = $this->post['email'];
		$this->telp = $this->post['telp'];

		$data = array(
			'email' => $this->email,
			'telp' => $this->telp,
		);

		$this->db->where( "username", $username );

		return $this->db->update( $this->table, $data);
	}
	public function check_already_exist( $username )
	{
		$this->email = $this->post['email'];
		$this->telp = $this->post['telp'];

		$this->db->where( "users_detail.username !=", $username );
		$this->db->where( "users_detail.email", $this->email );
		$this->db->where( "users_detail.telp", $this->telp );

		return $this->db->get($this->table)->num_rows();
	}
}