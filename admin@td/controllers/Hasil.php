<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Hasil extends MY_Controller {

        /**
         * Index Page for this controller.
         *
         * Maps to the following URL
         * 		http://example.com/index.php/welcome
         *	- or -
        * 		http://example.com/index.php/welcome/index
        *	- or -
        * Since this controller is set as the default controller in
        * config/routes.php, it's displayed at http://example.com/
        *
        * So any other public methods not prefixed with an underscore will
        * map to /index.php/welcome/<method_name>
        * @see https://codeigniter.com/user_guide/general/urls.html
        */
        public function __construct()
        {
            parent::__construct();
            /**
             * # Check authentication 
             * # LOAD MODEL :
             * 1. 
             */

            # Check authentication 
            if( ! $this->session->userdata('root') ){
                redirect(base_url());
            }

            $this->load->model(['M_answers','M_answers_detail']);
        }

        public function index()
        {
            $title          = $this->security->xss_clean($this->uri->segment(3));
            $data['title']  = empty($title) ? NULL : " : {$title}" ;
            $data['export'] = base_url('hasil/export/semua'); 
            $data['export_title'] = "Semua Hasil Tes Diagnostik"; 
            
            if ( $title ) { 
                $data['rows']   = $this->M_answers->get_by_schools($title);
                // $data['title']  = empty($title) ? NULL : " : {$title}" ;
                $data['export'] = base_url("hasil/export/semua/{$title}");  
                $data['export_title'] = "Semua Hasil Tes Diagnostik {$title}";
                // $this->debugs($data);
                // die();

            } else { # jika $title kosong
                # code...
                $data['rows']   = $this->M_answers->get();
                // $this->debugs($data);
                // die();
            }

            foreach($data['rows'] as $key => $value){
                $data['rows'][$key]->jawaban = '';
                // foreach($this->M_answers_detail->get($value->answer_id) as $keyDetail => $valueDetail){
                //     $data['rows'][$key]->jawaban .= $valueDetail->question_assessment ;
                // }
            }

            $this->render_pages( 'hasil/hasil_try_out', $data );
            
        }
        /* ==================== START : PROCESS DELETE DATA ==================== */
        public function delete()
        {
            # load model relations
            $this->load->model('M_relations');

            if ( $this->M_relations->delete_answers_relation_answers_detail( $this->uri->segment(3) ) ) { # jika berhasil dihapus
                $this->msg= [
                    'stats'=>1,
                    'msg'=>'Data Berhasil Dihapus',
                ];
            } else { # jika tidak berhasil dihapus
                $this->msg= [
                'stats'=>0,
                    'msg'=>'Maaf Data Gagal Dihapus',
                ];
            }
            echo json_encode($this->msg);
        }
        /* ==================== END : PROCESS DELETE DATA ==================== */

        public function export()
        {
            $title          = $this->security->xss_clean($this->uri->segment(4)); 

            if ( $title ) { 
                $data['rows']   = $this->M_answers->get_by_schools($title);

            } else { # jika $title kosong
                $data['rows']   = $this->M_answers->get();
            }

            # semua hasil try out
            foreach($data['rows'] as $key => $value){
                $data['rows'][$key]->jawaban = '';
                foreach($this->M_answers_detail->get($value->answer_id) as $keyDetail => $valueDetail){
                    $data['rows'][$key]->jawaban .= $valueDetail->question_assessment ;
                }
            }
            $this->load->view( 'hasil/hasil_try_out_export', $data );
        }
        public function detail()
        {
            $answer_id = $this->uri->segment(3);
            $data['answers'] = $this->M_answers->get($answer_id);
            $data['answers_detail'] = $this->M_answers_detail->get($answer_id);

            // $this->debugs($data);

            $table_hasil = [];
            $hasil_kategori = [];
            $passing_grade = [];
            foreach ($data['answers_detail'] as $key => $value) {
                $hasil_kategori[] = "
                    <tr>
                        <td class='w-50'>{$value->category}</td>
                        <td>{$value->passing_grade} %</td>
                    </tr>
                ";
                $passing_grade[] = "* {$value->category} {$value->limit_passing_grade} %<br>";

                $table_hasil[] = "
                    <tr>
                        <td>{$value->category}</td>
                        <td>{$value->total_questions}</td>
                        <td>{$value->correct_answer} Soal</td>
                        <td>{$value->wrong_answer} Soal</td>
                        <td>{$value->not_answered} Soal</td>
                    </tr>
                ";
            }
            $hasil_kategori = implode('',$hasil_kategori);
            $passing_grade[] = "* SKD {$data['answers']->limit_passing_grade}%";
            $passing_grade = implode('',$passing_grade);
            $table_hasil = implode('',$table_hasil);

            $keterangan = ($data['answers']->passing_grade <= $data['answers']->limit_passing_grade) ? '<h2 class="text-danger">Sayang sekali, Kamu Belum Lulus Passing Grade SKD di percobaan kali ini, silahkan coba lagi</h2>' : '<h2 class="text-primary">Selamat, Kamu Lulus Passing Grade SKD di percobaan kali ini</h2>' ;
            echo "
                <table class='table table-bordered font-weight-normal'>
                    <tbody>
                        <tr>
                            <td class='w-50'>Title</td>
                            <td>{$data['answers']->question_title}</td>
                        </tr>
                        <tr>
                            <td>Total Soal</td>
                            <td>{$data['answers']->total_questions} Soal</td>
                        </tr>
                        <tr>
                            <td>Batas Waktu Pengerjaan</td>
                            <td>{$data['answers']->exam_limit} Menit</td>
                        </tr>
                        <tr>
                            <td colspan='2' class='font-italic font-weight-bold p-5 text-center'>
                                Anda mendapatkan ".( ($data['answers']->passing_grade*($data['answers']->total_questions*5))/100 )." poin dari total ".($data['answers']->total_questions*5)." poin, ({$data['answers']->passing_grade}%)
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' class='font-weight-bold'>Kategori : </td>
                        </tr>
                        {$hasil_kategori}
                        <tr>
                            <td colspan='2' class='font-weight-bold p-5 text-center'>
                                {$keterangan}
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <b>Passing Grade</b><br>
                                Nilai kelulusan Passing Grade SKD , minimal jika:<br>
                                {$passing_grade}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--<hr>
                <table class='table table-bordered font-weight-normal'>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Soal</th>
                            <th>Jawaban Benar</th>
                            <th>Jawaban Salah</th>
                            <th>Tidak Dikerjakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$table_hasil}
                    </tbody>
                </table>-->
            ";
        }
    }