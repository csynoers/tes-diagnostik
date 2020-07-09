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
     
	# table exam_user_configs relations
    protected $tableExamUserConfigsRelation = 'exam_user_configs.username=users.username';

    protected $tableExamUserConfigs = 'exam_user_configs'; 
    protected $primaryKeyExamUserConfigs = 'exam_user_configs.exam_user_config_id'; 
    protected $foreignKeyExamUserConfigs = 'exam_user_configs.username'; 
    protected $exam_user_config_id;
    protected $usernameExamUserConfigs;
    protected $token;
    protected $total_payment;
    protected $bank_transfer;
    protected $confirm_payment;
    protected $proof_payment;
    protected $create_atExamUserConfigs;
    public function get( $username )
    {
        $this->db->where( $this->foreignKey, $username );
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation , 'left' );

        return $this->db->get($this->table)->row();
    }
    public function store_users( $username )
    {
        $this->password = $this->post['password'];

		$data = array(
			'password' => $this->password,
		);

		$this->db->where( "username", $username );

		return $this->db->update( $this->table, $data);
    }
    
    public function siswa_sedang_mengerjakan()
    {
        # where level SISWA
        $this->level = 'users.level';
        $this->session = 'users.session IS NOT NULL';

        $this->db->where( $this->level, 'user' );
        $this->db->where( $this->session );

        # left join table users_detail
        // $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation, 'left' );

        # left join table exam_user_configs
        // $this->db->join( $this->tableExamUserConfigs, $this->tableExamUserConfigsRelation, 'left' );

        return $this->db->get( $this->table )->result_object();
    }
    
    public function mendaftar($id=NULL)
    {
        # where id users = $id
        if ( $id ) {
            $this->db->where( $this->primaryKey, $id );
        }

        # where level SISWA
        $this->level = 'users.level';
        $this->db->where( $this->level, 'user' );

        # left join table users_detail
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation, 'left' );

        # left join table exam_user_configs
        // $this->db->join( $this->tableExamUserConfigs, $this->tableExamUserConfigsRelation, 'left' );

        return $this->db->get( $this->table )->result_object();
    }
    public function users_by_exam_id($id=NULL)
    {
        # where id users = $id
        if ( $id ) {
            $this->db->where($this->primaryKeyExamUserConfigs,$id);
        }

        # where level SISWA
        $this->level = 'users.level';
        $this->db->where( $this->level, 'user' );

        # left join table users_detail
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation, 'left' );

        # left join table exam_user_configs
        $this->db->join( $this->tableExamUserConfigs, $this->tableExamUserConfigsRelation, 'left' );

        return $this->db->get( $this->table )->result_object();
    }

    public function terdaftar()
    {
        # where level SISWA
        $this->level = 'users.level';
        $this->db->where( $this->level, 'user' );
        
        # siswa terdaftar jika kolom token sudah terisi
        $this->block = 'users.block';
        $this->db->where( $this->block, '0' );

        # siswa terdaftar jika kolom token sudah terisi
        // $this->token = 'exam_user_configs.token!=';
        // $this->db->where( $this->token, '' );

        # left join table users_detail
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation, 'left' );

        # left join table exam_user_configs
        // $this->db->join( $this->tableExamUserConfigs, $this->tableExamUserConfigsRelation, 'left' );

        return $this->db->get( $this->table )->result_object();
    }

    public function konfirmasi( $id=NULL )
    {
        # where id users = $id
        if ( $id ) {
            $this->db->where( $this->primaryKey, $id );
        }

        # where level SISWA
        $this->level = 'users.level';
        $this->db->where( $this->level, 'user' );

        # siswa terdaftar jika kolom token sudah terisi
        $this->block = 'users.block';
        $this->db->where( $this->block, '1' );

        # siswa terdaftar jika kolom confirm_payment = 0
        // $this->confirm_payment = 'exam_user_configs.confirm_payment';
        // $this->db->where( $this->confirm_payment, '0' );
        // $this->db->where('exam_user_configs.proof_payment is NOT NULL');

        # left join table users_detail
        $this->db->join( $this->tableUsersDetail, $this->tableUsersDetailRelation, 'left' );

        # left join table exam_user_configs
        // $this->db->join( $this->tableExamUserConfigs, $this->tableExamUserConfigsRelation, 'left' );

        return $this->db->get( $this->table )->result_object();
    }

    public function konfirmasi_store( $id )
    {
        # Proses update
        $this->block = '0';

        $this->db->where($this->primaryKey,$id);
        $data = array(
            'block' => $this->block,
        );

        return $this->db->update( $this->table, $data);
    }

	function check_auth($table,$where){		
		return $this->db->get_where($table,$where);
	}

	public function store()
	{
		$this->username = $this->post['username'];
		$this->password = $this->post['password'];
		$this->level = 'user';
		$this->block = '0';
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

		$data = array(
			'username' => $this->username,
			'nik' => $this->nik,
			'fullname' => $this->fullname,
			'email' => $this->email,
			'telp' => $this->telp,
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
}