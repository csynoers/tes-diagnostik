<?php
    class M_answers_detail extends CI_Model
    {
        protected $table = 'answers_detail'; 
        protected $primaryKey = 'answers_detail.answer_detail_id'; 
        protected $answer_detail_id;
        protected $answer_id;
        protected $category;
        protected $correct_answer;
        protected $wrong_answer;
        protected $not_answered;
        protected $total_questions;
        protected $limit_passing_grade;
        protected $passing_grade;
        protected $question_assessment;
        protected $exam_limit;

        public function get( $id)
        {
            $this->db->where( 'answers_detail.answer_id', $id );
            return $this->db->get($this->table)->result_object();
        }
        public function store()
        {
            $data = $this->post;
            return $this->db->insert_batch( $this->table, $data);
        }
    }
    