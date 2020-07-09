<?php
    class M_choice extends CI_Model
    {
        public $post;

        protected $table = 'choices';
        protected $choice_id;
        protected $question_id;
        protected $question_code;
        protected $weight;
        protected $choice;

        public function get( $id=NULL )
        {
            if ( $id ) {
                $this->db->where('question_id',$id);
            }
            return $this->db->get( $this->table )->result();
        }

        public function store()
        {
            if ( $this->uri->segment(3) ) { # update
                $this->choice_id = $this->post['choice_id'];
                $this->question_code = $this->post['question_code'];
                $this->weight = $this->post['weight'];
                $this->choice = $this->post['choice'];

                $data= [
                    'question_code'=> $this->question_code,
                    'weight'=> $this->weight,
                    'choice'=> $this->choice,
                ];
                $where= [
                    'choice_id'=> $this->choice_id
                ];
                return $this->db->update( $this->table,$data,$where);

            } else { # insert
                $this->question_id = $this->post['question_id'];
                $this->question_code = $this->post['question_code'];
                $this->weight = $this->post['weight'];
                $this->choice = $this->post['choice'];

                $data= [
                    'question_id'=> $this->question_id,
                    'question_code'=> $this->question_code,
                    'weight'=> $this->weight,
                    'choice'=> $this->choice,
                ];

                # return TRUE or FALSE
                return $this->db->insert( $this->table, $data );

            }
        }
    }
    