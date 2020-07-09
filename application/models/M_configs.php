<?php
    class M_configs extends CI_Model
    {
        protected $table = 'configs'; 
        protected $primaryKey = 'configs.id'; 
        protected $text = 'configs.text'; 

        public function get($id=NULL)
        {
            if ( $id ) {
                $this->db->where($this->primaryKey,$id);
            }

            $result = $this->db->get( $this->table );
            if ( $id ) {
                $result = $result->row();
            } else {
                $result = $result->result_object();
            }
            return $result;
        }
    }
    