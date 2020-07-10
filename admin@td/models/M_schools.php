<?php
    class M_schools extends CI_Model
    {
        /**
         * CREATE this table TABLE
            CREATE TABLE `schools` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` int(11) NOT NULL,
            `block` enum('0','1') NOT NULL,
            `create_at` datetime NOT NULL,
            `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1
         */

        protected $table = 'schools'; 
        protected $primaryKey = 'schools.id'; 
        protected $id;
        protected $title;
        protected $block;
        protected $create_at;
        protected $update_at;

        /* ==================== START COLLECT DATA ==================== */
        public function get( $id=NULL )
        {
            if ( $id ) { # if $id not null add where condition
                $this->db->where( $this->primaryKey, $id );
            }

            $this->db->select( "*,DATE_FORMAT({$this->table}.create_at, '%W,  %d %b %Y') AS create_at_mod, IF({$this->table}.block='0','YES','NO') AS block_mod " );

            return $this->db->get( $this->table )->result_object();
        }
        /* ==================== END COLLECT DATA ==================== */

        /* ==================== START STORE DATA ==================== */
        public function store()
        {
            if ( $this->uri->segment(3) ) { # update
                $data= [
                    'title'=> $this->post['title'],
                    'slug'=> $this->post['slug'],
                    'description'=> $this->post['description'],
                    'block'=> $this->post['publish'],
                ];
                $where= [
                    'id'=> $this->uri->segment(3)
                ];
                return $this->db->update('pages',$data,$where);

            } else { # insert
                $data= [
                    'title'=> $this->post['title'],
                    'slug'=> $this->post['slug'],
                    'description'=> $this->post['description'],
                    'block'=> $this->post['publish'],
                    'create_at'=> date('Y-m-d H:i:s'),
                ];
                return $this->db->insert('pages',$data);

            }
        }
        /* ==================== END STORE DATA ==================== */

        /* ==================== START DELETE DATA ==================== */
        public function delete( $id )
        {
            $this->id = $id;

            $where= [
                'id' => $this->id
            ];

            return $this->db->delete( $this->table, $where );
        }
        /* ==================== END DELETE DATA ==================== */
    }
    