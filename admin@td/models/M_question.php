<?php
    class M_question extends CI_Model
    {
        protected $table = 'questions'; 
        protected $primaryKey = 'questions.question_id'; 
        protected $question_categori_id;
        protected $question;
        protected $block;
        protected $create_at;

        protected $tableChoices = 'choices';
        protected $tableChoicesRelation = 'choices.question_id=questions.question_id';

        public function get_question( $id=NULL )
        {
            if ( $id ) {
                $this->db->where('questions.question_id',$id);
            }
            if ( $this->input->get('kategori') ) {
                $this->db->where('question_categories.question_categori_id',$this->input->get('kategori'));
            }
            $this->db->select("
                *,
                DATE_FORMAT(questions.create_at, '%W,  %d %b %Y') AS create_at_mod,
                IF(questions.block='0','YES','NO') AS block_mod,
                question_categories.title AS kategori_soal
            ");
            $this->db->join('question_categories','questions.question_categori_id=question_categories.question_categori_id','left');
            return $this->db->get('questions')->result();
        }

        public function store()
        {
            if ( $this->uri->segment(3) ) { # update
                $this->question             = $this->post['question'];
                $this->block                = '0';

                $data= [
                    'question'=> $this->question,
                    'block'=> $this->block,
                ];
                $where= [
                    'question_id'=> $this->uri->segment(3)
                ];
                return $this->db->update( $this->table,$data,$where);

            } else { # insert
                $this->question_categori_id = $this->post['question_categori_id'];
                $this->question             = $this->post['question'];
                $this->block                = $this->post['publish'];
                $this->create_at            = date('Y-m-d H:i:s');

                $data= [
                    'question_categori_id'=> $this->question_categori_id,
                    'question'=> $this->question,
                    'block'=> $this->block,
                    'create_at'=> $this->create_at,
                ];
                $this->db->insert( $this->table, $data );

                // # get last insert id
                return $this->db->insert_id();

            }
        }
        public function check_relations($id=NULL)
        {
            if ( $id ) {
                $this->db->where($this->primaryKey,$id);
            }

            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->join($this->tableChoices, $this->tableChoicesRelation);
            $query = $this->db->get();

            return $query->num_rows();
        }
    }
    