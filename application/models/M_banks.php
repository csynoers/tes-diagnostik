<?php
    class M_banks extends CI_Model
    {
        protected $table = 'banks'; 
        protected $primaryKey = 'banks.bank_id'; 
        protected $bank_id;
        protected $bank_number;
        protected $bank_type;
        protected $bank_title;

        public function get($id=NULL)
        {
            if ( $id ) {
                $this->db->where( $this->primaryKey,$id );
                return $this->db->get( $this->table )->row();
            }else {
                return $this->db->get( $this->table )->result_object();
            }
        }
    }
    