<?php
    class M_pages extends CI_Model
    {
        public function get_pages($id=NULL)
        {
            if ( $id ) {
                $this->db->where('pages.id',$id);
            }
            $this->db->select("*,DATE_FORMAT(pages.create_at, '%W,  %d %b %Y') AS create_at_mod, IF(pages.block='0','YES','NO') AS block_mod ");
            return $this->db->get('pages')->result();
        }
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
        public function delete($id)
        {
            $where= [
                'id'=> $id
            ];
            return $this->db->delete('pages',$where);
        }
    }
    