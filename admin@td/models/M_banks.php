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
                $this->db->where($this->primaryKey,$id);
            }
            return $this->db->get($this->table)->result();
        }
        public function store()
        {
            if ( $this->uri->segment(3) ) { # update
                $data= [
                    'bank_number'=> $this->post['bank_number'],
                    'bank_type'=> $this->post['bank_type'],
                    'bank_title'=> $this->post['bank_title'],
                ];
                $where= [
                    'bank_id'=> $this->uri->segment(3)
                ];
                return $this->db->update('banks',$data,$where);

            } else { # insert
                $data= [
                    'bank_number'=> $this->post['bank_number'],
                    'bank_type'=> $this->post['bank_type'],
                    'bank_title'=> $this->post['bank_title'],
                ];
                return $this->db->insert('banks',$data);

            }
        }
        public function delete($id)
        {
            $where= [
                'bank_id'=> $id
            ];
            return $this->db->delete('banks',$where);
        }
    }
    