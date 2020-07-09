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

        public function get( $username , $id= NULL)
        {
            $this->db->select("*,DATE_FORMAT(answers.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(answers.passing_grade <= answers.limit_passing_grade,'Tidak Lulus','Lulus') AS keterangan ");
            if ( $id ) {
                $this->db->where( $this->primaryKey, $id );
            }
            $this->db->where( 'answers.username', $username );
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
    