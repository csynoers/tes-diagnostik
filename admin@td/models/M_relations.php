<?php
    class M_relations extends CI_Model
    {
        /**
         * model ini digunakan untuk menghapus data banyak tabel dalam satu perintah query
         *   */
        
        # mendapatkan data siswa yang belum mengerjakan, tabel: users, users_detail & answers
        public function get_siswa_belum_mengerjakan()
        {
            $result = $this->db->query("
                SELECT
                    users.username,
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,
                    users_detail.nik,
                    users_detail.fullname,
                    users_detail.email,
                    users_detail.telp,
                    users_detail.birth_date,
                    users_detail.schools,
                    (SELECT COUNT(*) FROM answers WHERE answers.username=users.username) AS jumlah_tes
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NULL
                    HAVING jumlah_tes=0
                    ORDER BY users.create_at DESC
            ");

            return $result->result_object();
        }
        public function get_siswa_belum_mengerjakan_by_schools($schools)
        {
            $result = $this->db->query("
                SELECT
                    users.username,
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,
                    users_detail.nik,
                    users_detail.fullname,
                    users_detail.email,
                    users_detail.telp,
                    users_detail.birth_date,
                    users_detail.schools,
                    (SELECT COUNT(*) FROM answers WHERE answers.username=users.username) AS jumlah_tes
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NULL AND users_detail.schools='{$schools}'
                    HAVING jumlah_tes=0
                    ORDER BY users.create_at DESC
            ");

            return $result->result_object();
        }
        public function get_siswa_belum_mengerjakan_group_by_schools()
        {
            $result = $this->db->query("
                SELECT
                    users.username,
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,                
                    users_detail.schools,
                    (SELECT COUNT(*) FROM answers WHERE answers.username=users.username) AS jumlah_tes
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NULL
                    GROUP BY users_detail.schools
                    HAVING jumlah_tes=0
            ");

            return $result->result_object();
        }
        # mendapatkan data siswa yang sedang mengerjakan, tabel: users & users_detail
        public function get_siswa_sedang_mengerjakan()
        {
            $result = $this->db->query("
                SELECT
                    users.username,
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,
                    users_detail.nik,
                    users_detail.fullname,
                    users_detail.email,
                    users_detail.telp,
                    users_detail.birth_date,
                    users_detail.schools
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NOT NULL
                    ORDER BY users.create_at DESC
            ");

            return $result->result_object();
        }
        public function get_siswa_sedang_mengerjakan_by_schools($schools)
        {
            $result = $this->db->query("
                SELECT
                    users.username,
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,
                    users_detail.nik,
                    users_detail.fullname,
                    users_detail.email,
                    users_detail.telp,
                    users_detail.birth_date,
                    users_detail.schools
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NOT NULL AND users_detail.schools='{$schools}'
                    ORDER BY users.create_at DESC
            ");

            return $result->result_object();
        }
        public function get_siswa_sedang_mengerjakan_group_by_schools()
        {
            $result = $this->db->query("
                SELECT
                    users.level,
                    users.block,
                    users.create_at,
                    users.session,                
                    users_detail.schools
                FROM `users` 
                    LEFT JOIN users_detail
                        ON users_detail.username=users.username      
                WHERE users.level='user' AND users.block='0' AND users.session IS NOT NULL
                    GROUP BY users_detail.schools
            ");

            return $result->result_object();
        }

        # menghapus dua tabel : answers & answers_detail
        public function delete_answers_relation_answers_detail($answer_id)
        {
            return $this->db->query("DELETE answers,answers_detail FROM answers RIGHT JOIN answers_detail ON answers.answer_id=answers_detail.answer_id WHERE answers.answer_id='{$answer_id}' ");
        }

    }
    