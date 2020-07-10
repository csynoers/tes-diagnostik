<?php
    class M_relations extends CI_Model
    {
        /**
         * model ini digunakan untuk menghapus data banyak tabel dalam satu perintah query
         *   */ 
        public function delete_answers_relation_answers_detail($answer_id)
        {
            return $this->db->query("DELETE answers,answers_detail FROM answers RIGHT JOIN answers_detail ON answers.answer_id=answers_detail.answer_id WHERE answers.answer_id='{$answer_id}' ");
        }
    }
    