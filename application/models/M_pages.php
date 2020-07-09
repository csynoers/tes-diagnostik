<?php
    class M_pages extends CI_Model
    {
        private $table      = 'pages';
        public $id          = 'pages.id';
        public $sorting     = 'pages.sorting';
        public $slug        = 'pages.slug';
        public $description = 'pages.description';
        public $block       = 'pages.block';
        public $create_at   = 'pages.create_at';
        public $update_at   = 'pages.update_at';
        
        public function get( $slug=NULL )
        {
            if ( $slug ) {
                $this->db->where( $this->slug, $slug );
            }
            $this->db->order_by($this->sorting, 'ASC');
            return $this->db->get( $this->table )->result();
        }
    }
    