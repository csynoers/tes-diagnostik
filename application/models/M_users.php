<?php 

class M_users extends CI_Model{
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

	# table users_detail relations
	protected $tableUsersDetailRelation = 'users_detail.username=users.username';

	protected $tableUsersDetail = 'users_detail'; 
	protected $primaryKeyUsersDetail = 'users_detail.user_detail_id'; 
	protected $foreignKeyUsersDetail = 'users_detail.username';
	protected $nik; 
	protected $fullname; 
	protected $email; 
    protected $telp;

    public function get( $username )
    {
        $this->db->where( $this->foreignKey, $username );
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation , 'left' );

        return $this->db->get($this->table)->row();
    }
    public function get_admin()
    {
        $this->db->where( 'users.level', 'root' );
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation , 'left' );

        return $this->db->get($this->table)->row();
    }
    public function store( $username )
    {
        $this->password = $this->post['password'];

		$data = array(
			'password' => $this->password,
		);

		$this->db->where( "username", $username );

		return $this->db->update( $this->table, $data);
	}
}