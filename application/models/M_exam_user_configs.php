<?php
    class M_exam_user_configs extends CI_Model
    {
        protected $table = 'exam_user_configs'; 
        protected $primaryKey = 'exam_user_configs.exam_user_config_id'; 
        protected $foreignKey = 'exam_user_configs.username'; 
        protected $exam_user_config_id;
        protected $username;
        protected $token;
        protected $total_payment;
        protected $bank_transfer;
        protected $confirm_payment;
        protected $proof_payment;
        protected $create_at;

        public function get($id=NULL)
        {
            if ( $id ) {
                $this->db->where( $this->foreignKey,$id );
                return $this->db->get( $this->table )->row();
            }else {
                return $this->db->get( $this->table )->result_object();
            }
        }

        public function store($id=NULL)
        {
            if ( $id ) {
                # code...
                # proses insert
                $this->exam_user_config_id = $id;
                $this->proof_payment = $this->post['gambar'];
                $data = array(
                    'proof_payment' => $this->proof_payment,
                );
                $this->db->where( $this->primaryKey, $this->exam_user_config_id );

                return $this->db->update( $this->table,$data );
            } else {
                # proses insert
                $this->username = $this->post['username'];
                $this->total_payment = $this->post['total_payment'];
                $this->bank_transfer = $this->post['bank_transfer'];
                $this->confirm_payment = '0';
                $this->create_at = date('Y-m-d H:i:s');

                $data = array(
                    'username' => $this->username,
                    'total_payment' => $this->total_payment,
                    'bank_transfer' => $this->bank_transfer,
                    'confirm_payment' => $this->confirm_payment,
                    'create_at' => $this->create_at,
                );

                return $this->db->insert( $this->table,$data );
            }
            
        }
    }
    