<?php
    class M_answers extends CI_Model
    {
        protected $table = 'answers'; 
        protected $primaryKey = 'answers.answer_id'; 
        protected $answer_id;
        protected $username;
        protected $question_title;
        protected $total_questions;
        protected $limit_passing_grade;
        protected $passing_grade;
        protected $correct_answer;
        protected $wrong_answer;
        protected $not_answered;
        protected $exam_limit;
        protected $create_at;

        # table users_detail relations
        protected $tableUsersDetailRelation = 'users_detail.username=answers.username';

        protected $tableUsersDetail = 'users_detail'; 
        protected $primaryKeyUsersDetail = 'users_detail.user_detail_id'; 
        protected $foreignKeyUsersDetail = 'users_detail.username';
        protected $nik; 
        protected $fullname; 
        protected $email; 
        protected $telp;
        protected $schools;

        public function get($id= NULL)
        {
            $this->db->select("*,DATE_FORMAT(answers.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(answers.passing_grade <= answers.limit_passing_grade,'Tidak Lulus','Lulus') AS keterangan,concat(TIMEDIFF(create_at,start_exam),' (',DATE_FORMAT(answers.start_exam, '%d/%m/%Y %T'),'-',DATE_FORMAT(answers.create_at, '%d/%m/%Y %T'),')') AS timeDiff ");
            if ( $id ) {
                $this->db->where( $this->primaryKey, $id );
            }
            $this->db->join($this->tableUsersDetail,$this->tableUsersDetailRelation,'left');
            $this->db->order_by('`answers`.`start_exam`', 'DESC');
            $result = $this->db->get($this->table);

            if ( $id ) {
                $result = $result->row();
            } else {
                $result = $result->result_object();
            }
            return $result;
        }
        public function get_by_schools($title)
        {
            $this->db->select("*,DATE_FORMAT(answers.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(answers.passing_grade <= answers.limit_passing_grade,'Tidak Lulus','Lulus') AS keterangan,concat(TIMEDIFF(create_at,start_exam),' (',DATE_FORMAT(answers.start_exam, '%d/%m/%Y %T'),'-',DATE_FORMAT(answers.create_at, '%d/%m/%Y %T'),')') AS timeDiff ");
            $this->db->join($this->tableUsersDetail,$this->tableUsersDetailRelation,'left');
            $this->db->where('users_detail.schools',$title);

            $result = $this->db->get($this->table);
            $result = $result->result_object();

            return $result;
        }
        public function get_lulus($id= NULL)
        {
            $this->db->select("*,DATE_FORMAT(answers.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(answers.passing_grade <= answers.limit_passing_grade,'Tidak Lulus','Lulus') AS keterangan ");
            if ( $id ) {
                $this->db->where( $this->primaryKey, $id );
            }
            $this->db->join($this->tableUsersDetail,$this->tableUsersDetailRelation,'left');
            $this->db->having('keterangan','Lulus');
            $result = $this->db->get($this->table);

            if ( $id ) {
                $result = $result->row();
            } else {
                $result = $result->result_object();
            }
            return $result;
        }
        public function get_tidak_lulus($id= NULL)
        {
            $this->db->select("*,DATE_FORMAT(answers.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(answers.passing_grade <= answers.limit_passing_grade,'Tidak Lulus','Lulus') AS keterangan ");
            if ( $id ) {
                $this->db->where( $this->primaryKey, $id );
            }
            $this->db->join($this->tableUsersDetail,$this->tableUsersDetailRelation,'left');
            $this->db->having('keterangan','Tidak Lulus');
            $result = $this->db->get($this->table);

            if ( $id ) {
                $result = $result->row();
            } else {
                $result = $result->result_object();
            }
            return $result;
        }
        public function rows_by_username($username)
        {
            $this->db->where('answers.username',$username);
            return $this->db->get( $this->table )->num_rows();
        }
        public function store()
        {
            $data = $this->post;
            $this->db->insert('answers', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
    }
    