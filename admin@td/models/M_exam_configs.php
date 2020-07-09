<?php
    class M_exam_configs extends CI_Model
    {
        protected $table = 'exam_configs'; 
        protected $primaryKey = 'exam_configs.exam_config_id'; 
        protected $foreignKey = 'exam_configs.question_categori_id'; 
        protected $exam_config_id;
        protected $question_categori_id;
        protected $exam_limit;
        protected $number_of_questions;
        protected $passing_grade;

        protected $tableChoices = 'question_categories';
        protected $tableChoicesRelation = 'question_categories.question_categori_id=exam_configs.question_categori_id';

        public function get($id=NULL)
        {
            if ( $id ) {
                $this->db->where( $this->primaryKey,$id );
            }

            $this->db->select("*,IF(exam_configs.exam_limit=0,'-',CONCAT(exam_configs.exam_limit, ' Menit')) AS exam_limit_mod, IF(exam_configs.number_of_questions=0,'0',exam_configs.number_of_questions) AS number_of_questions_mod, (SELECT COUNT(*) FROM `questions` WHERE `questions`.`question_categori_id`={$this->foreignKey}) AS count_of_question",FALSE);
            $this->db->from($this->table);

            $this->db->join($this->tableChoices, $this->tableChoicesRelation);
            return $this->db->get()->result();
        }

        public function store()
        {
            # update
            $this->exam_config_id = $this->uri->segment(4);
            $this->exam_limit = $this->post['exam_limit'];
            $this->number_of_questions = $this->post['number_of_questions'];
            $this->passing_grade = $this->post['passing_grade'];

            $data= [
                'exam_limit'=> $this->exam_limit,
                'number_of_questions'=> $this->number_of_questions,
                'passing_grade'=> $this->passing_grade,
            ];
            $this->db->where( $this->primaryKey,$this->exam_config_id );

            return $this->db->update( $this->table,$data);
        }
    }
    