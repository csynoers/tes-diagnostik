<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends MY_Controller{
 
    function __construct(){
        parent::__construct();
        /**
         * LOAD MODEL:
         * 1. M_auth (get data from users table)
         */		
        $this->load->model('M_auth');
        $this->load->model('M_configs');

        # load encrypt library
        $this->load->library('encryption');
        $this->encryption->initialize(
            array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
                'key' => '3s0c9m7@gmail.com'
            )
        );

        # require php mailer
        require APPPATH.'libraries/phpmailer/src/Exception.php';
        require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
        require APPPATH.'libraries/phpmailer/src/SMTP.php';

    }

    /* ==================== START : LOGIN PAGE url{auth/index} ==================== */
    public function index(){
        $csrf = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo $this->encryption->decrypt( '3e97f5f1d70a8d01b5c2047bd0eadf649546fac89be5c35dfb15def2603c2282777fa85bb9888d03ee2efed050258852315db7c6cfc3e924c589356e897d3d2b4kzYbHFzjVHi9QWcEDW8xbC4LktWew==' );
        $this->load->view('login',$csrf);
    }
    /* ==================== END : LOGIN PAGE url{auth/index} ==================== */

    /* ==================== START : REGISTER PAGE url{auth/register} ==================== */
    public function register(){
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        # load modal M_schools untuk mendapatkan data asal sekolah
        $this->load->model('M_schools');
        $data['asal_sekolah'] = $this->M_schools->get();

        $this->load->view('register',$data);
    }
    /* ==================== END : REGISTER PAGE url{auth/register} ==================== */

    public function store()
    {
        # filter xss clean before send to model
        $input_false = 0;
        
        $post = array(
            'nik' => $this->security->xss_clean($this->input->post('nik')),
            'fullname' => $this->security->xss_clean($this->input->post('fullname')),
            'birth_date' => $this->security->xss_clean($this->input->post('birth_date')),
            'schools' => $this->security->xss_clean($this->input->post('schools')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'telp' => $this->security->xss_clean($this->input->post('telp')),
            'username' => $this->security->xss_clean($this->input->post('username')),
            'password' => $this->encryption->encrypt($this->input->post('password')),
        );
        if ( empty($post['nik']) ) {
            $input_false = 1;
        } elseif ( empty($post['fullname']) ) {
            $input_false = 1;
        } elseif ( empty($post['birth_date']) ) {
            $input_false = 1;
        } elseif ( empty($post['schools']) ) {
            $input_false = 1;
        } elseif ( empty($post['email']) ) {
            $input_false = 1;
        } elseif ( empty($post['telp']) ) {
            $input_false = 1;
        } elseif ( empty($post['username']) ) {
            $input_false = 1;
        } elseif ( empty($post['password']) ) {
            $input_false = 1;
        }

        if ( $input_false == 1 ) {
            $this->session->set_flashdata('msg', 'Maaf! tidak boleh ada form yang kosong ');
            redirect(base_url('auth/register'));
            die();
        }
        # send $post variable to model
        $this->M_auth->post = $post;

        # cek apakah user sudah dengan nik/username/email/telp sudah ada sebelumnya
        $cek_users  = $this->M_auth->check_already_exist()->num_rows();
        if ( $cek_users > 0 ) {
            $this->session->set_flashdata('msg', 'Maaf! data nisn/username/email/telp sudah pernah digunakan silahkan coba lagi! atau silahkan klik link <a href="'.base_url('forget-password').'">Saya lupa password</a> ');
            redirect(base_url('auth/register'));

        } else {
            # jika belum ada jalankan proses store data
            $this->M_auth->store();
            
            $data['pesan'] = "
                <html>
                    <head>
                        <title>Tes Diagnostik</title>
                    </head>
                    <body style='background: #eee;'>
                        <div style='padding: 50px;'>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
                                <h1>Tes Diagnostik</h1>
                            </div>
                            <div style='background: #fff;padding: 30px 30px;'>
                                <h2 style='margin-top: 0px'>Hi {$post['fullname']},</h2>
                                <p>Username kamu adalah : {$post['nik']} / {$post['username']} / <a href='mailto:{$post['email']}' target='_blank'>{$post['email']}</a> / {$post['telp']} (pilih salah satu saja)</p>
                                <a href='".base_url('email-confirmation?token='. $this->encryption->encrypt($post['username']))."' target='_blank' style='background-color: #39a300;color: #fff;padding: 10px 12px;text-decoration: none;'>Klik disini untuk melakukan konfirmasi email</a><br>
                                <p style='padding:3rem'>jika tombol tidak bekerja silahkan copy link ini ".base_url('email-confirmation?token='. $this->encryption->encrypt($post['username']))."</p>

                            </div>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
                                <p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Tes Diagnostik Homeschooling Prima Edukasi © ".date('Y')."</a><br> PKBM Homeschooling Prima Edukasi Jalan Jenderal Sudirman nomor 84 B – Dumai Timur- Kota Dumai – Riau.</p>
                            </div>
                        </div>
                    </body>
                </html>	
            ";

            $this->send_email_smtp('Konfirmasi email ',$post['email'],$data['pesan']);
            echo ("<script>window.alert('Terimakasih telah mendaftar, data berhasil dikirim. Silahkan buka email anda pada bagian Inbox, Spam atau All Mail dan klik tautan yang kami kirimkan');window.location.href='".base_url()."';</script>");
        }
        
    }

    public function email_confirmation()
    {
        if ( $this->input->get('token') ) {
            # code...
            // echo $this->encryption->encrypt( 'userdua' );
            $post = array(
                'username' => $this->encryption->decrypt( $this->input->get('token') ),
            );
            
            // # send to model
            $this->M_auth->post = $post;
    
            if ( $this->M_auth->update_last_login($post['username']) ) {
                echo ("<script>window.alert('Selamat konfirmasi email berhasil, anda bisa melakukan login setelah pendaftaran anda terverifikasi admin, mohon menunggu notifikasi selanjutnya melalui email pada bagian Inbox, Spam atau All Mail selanjutnya Terimakasih.');window.location.href='".base_url()."';</script>");
            } else {
                echo ("<script>window.alert('Maaf! konfirmasi email gagal dilakukan');window.location.href='".base_url()."';</script>");
            };
        } else {
            redirect( base_url() );
        }
        
    }

    function process(){
        # filter xss clean before send to model
        $post = array(
            'nik' => $this->security->xss_clean($this->input->post('username')),
            'email' => $this->security->xss_clean($this->input->post('username')),
            'telp' => $this->security->xss_clean($this->input->post('username')),
            'username' => $this->security->xss_clean($this->input->post('username')),
            'password' => $this->input->post('password'),
            // 'password' => $this->encryption->encrypt($this->input->post('password')),
        );

        # send $post variable to model
        $this->M_auth->post = $post;
        
        # cek apakah user sudah dengan nik/username/email/telp sudah ada sebelumnya
        $cek_users  = $this->M_auth->check_already_exist();
        
        if ( $cek_users->num_rows() > 0 ) {
            # code...
            $password = $post['password'];
            $row      = $cek_users->row();

            # if decrypt row->password same as $password
            if ( $this->encryption->decrypt( $row->password ) == $password ) {
                # set session user
                if ( $row->level=='root' ) {
                    # code...level ADMIN
                    $this->session->set_userdata([ "{$row->level}" => $row ]);
                    redirect( base_url('admin@td') );
                } else {
                    # code...level USER

                    if ( $row->last_login ) {                                    
                        if ( $row->block == '0' ) { # jika user tidak di blok
                            # load model answers
                            $this->load->model('M_answers');
                            
                            if ( $this->M_answers->rows_by_username($row->username) > 0 ) { # script ini digunakan untuk mengetahui apakah siswa sudah pernah melakukan tes
                                # code... jika user ini sudah pernah melakukan ujian jalankan script di bawah ini
                                $this->session->set_flashdata('msg', 'Maaf anda sudah pernah mengerjakan Tes Diagnostik, jika ada kesalahan silahkan menghubungi panitia terkait tes ini, terimakasih');
                                redirect( base_url('auth') );
                            } else {
                                # code... jika belum pernah melakukan ujian jalankan script di bawah ini
                                # update last login
                                $this->M_auth->update_last_login( $row->username );
                                $row  = $this->M_auth->check_already_exist()->row();
                                
                                $this->session->set_userdata([ "{$row->level}" => $row ]);
    
                                # jika sedang dalam mengerjakan ujian
                                if ( $row->session != '' ) {
                                    # code...
                                    $_SESSION['user'] = json_decode($row->session);
                                }
                                redirect( base_url() );
                            }
                            
                        } else { # jika user belum diverifikasi
                            $this->session->set_flashdata('msg', 'Maaf pendaftaran anda dalam proses verifikasi, mohon menunggu notifikasi email selanjutnya.');
                            redirect( base_url('auth') );
                        }
                    } else {
                        $this->session->set_flashdata('msg', 'Maaf anda belum melakukan konfirmasi email, silahkan buka email kamu terlebih dahulu dan klik tautan konfirmasi email.');
                        redirect( base_url('auth') );
                    }
                    
                }

            }

            # if decrypt row->password different with $password
            else {
                # code...
                $this->session->set_flashdata('msg', 'username or password does not exist.');
                redirect(base_url('auth'));
            }
            
        }
        
        else{
            
            $this->session->set_flashdata('msg', 'username or password does not exist.');
            redirect(base_url('auth'));
        }
    }

    function logout(){
        // session_destroy();
        $this->session->unset_userdata('user');
        $this->session->sess_destroy();
        redirect(base_url());
    }

    /*  */
    public function forget_password()
    {
        $this->load->view('forget_password');
    }
    public function forget_password_xxx()
    {
        $this->load->view('forget_password_xxx');
    }
    /*  */

    /*  */
    public function send_reset_password()
    {
        $post = array(
            'nik' => $this->security->xss_clean($this->input->post('email')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'telp' => $this->security->xss_clean($this->input->post('email')),
            'username' => $this->security->xss_clean($this->input->post('email')),
        );

        # send post to model
        $this->M_auth->post = $post;

        # cek apakah user sudah dengan nik/username/email/telp sudah ada sebelumnya
        $cek_users  = $this->M_auth->check_already_exist(); 

        if ( $cek_users->num_rows() > 0 ) {
            $row      = $cek_users->row();
            // $this->debugs($row);
            $data['pesan'] = "
                <html>
                    <head>
                        <title>Tes Diagnostik</title>
                    </head>
                    <body style='background: #eee;'>
                        <div style='padding: 50px;'>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
                                <h1>Tes Diagnostik</h1>
                            </div>
                            <div style='background: #fff;padding: 30px 30px;'>
                                <h2 style='margin-top: 0px'>Hi {$row->fullname},</h2>
                                <p>Username Tes kamu adalah : <a href='mailto:{$row->email}' target='_blank'>{$row->email}</a></p>
                                <a href='".base_url('reset-password?token='. $row->username)."' target='_blank' style='background-color: #39a300;color: #fff;padding: 10px 12px;text-decoration: none;'>Klik disini untuk mengganti password</a>
                                <p style='margin-bottom: 0px'>Jika Anda mengetahui kata sandi untuk akun ini, Anda dapat masuk dengan nama pengguna di atas.</p>
                            </div>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
                                <p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Tes Diagnostik Homeschooling Prima Edukasi © ".date('Y')."</a><br> PKBM Homeschooling Prima Edukasi Jalan Jenderal Sudirman nomor 84 B – Dumai Timur- Kota Dumai – Riau.</p>
                            </div>
                        </div>
                    </body>
                </html>	
            ";

            // echo $data['pesan'];
            // echo $this->encryption->decrypt( '8a12e43cd69b8b7472c2318fe954e5eabf9f7033082b752b0e67390cac2f01c71fa2b58036bb833926b6de50db255a4e06ebfa88f5589337f1557f3e19fd98222JQGclF5DUB9lEnoMRcUEIpBlcvE' );
            if ( $this->send_email_smtp('Reset Password',$post['email'],$data['pesan']) ) {
                # code...
                echo ("<script>window.alert('Permintaan reset password berhasil dikirim, Silahkan buka email {$post['email']} pada bagian Inbox, Spam atau All Mail untuk mendapatkan link reset password');window.location.href='".base_url()."';</script>");
            } else {
                $this->session->set_flashdata('msg', 'Maaf! link gagal dikirimkan ke email : '.$post['email'] .' mohon coba lagi nanti');
                redirect(base_url('forget-password'));
            };
            /* ==================== END :: SEND EMAIL ==================== */
        } else {
            $this->session->set_flashdata('msg', 'Maaf! User dengan Email: '.$post['email'].' tidak ditemukan silahkan mendaftar terlebih dahulu');
            redirect(base_url('forget-password'));
        }
        
        // $this->debugs();
    }
     public function send_reset_password_xxx()
    {
        $post = array(
            'nik' => $this->security->xss_clean($this->input->post('email')),
            'email' => $this->security->xss_clean($this->input->post('email')),
            'telp' => $this->security->xss_clean($this->input->post('email')),
            'username' => $this->security->xss_clean($this->input->post('email')),
        );

        # send post to model
        $this->M_auth->post = $post;

        # cek apakah user sudah dengan nik/username/email/telp sudah ada sebelumnya
        $cek_users  = $this->M_auth->check_already_exist(); 

        if ( $cek_users->num_rows() > 0 ) {
            $row      = $cek_users->row();
            $data['pesan'] = "
                <html>
                    <head>
                        <title>Tes Diagnostik</title>
                    </head>
                    <body style='background: #eee;'>
                        <div style='padding: 50px;'>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 15px 15px 0px 0px;'>
                                <h1>Tes Diagnostik</h1>
                            </div>
                            <div style='background: #fff;padding: 30px 30px;'>
                                <h2 style='margin-top: 0px'>Hi {$row->fullname},</h2>
                                <p>Username Tes kamu adalah : <a href='mailto:{$row->email}' target='_blank'>{$row->email}</a></p>
                                <a href='".base_url('reset-password?token='. $row->username)."' target='_blank' style='background-color: #39a300;color: #fff;padding: 10px 12px;text-decoration: none;'>Klik disini untuk mengganti password</a>
                                <p style='margin-bottom: 0px'>Jika Anda mengetahui kata sandi untuk akun ini, Anda dapat masuk dengan nama pengguna di atas.</p>
                            </div>
                            <div style='background:#007bff;padding: 1px 0px;text-align: center;color: white;border-radius: 0px 0px 15px 15px;'>
                                <p><a href='".base_url()."' target='_blank' style='color: wheat;font-weight: bold;'>Tes Diagnostik Homeschooling Prima Edukasi © ".date('Y')."</a><br> PKBM Homeschooling Prima Edukasi Jalan Jenderal Sudirman nomor 84 B – Dumai Timur- Kota Dumai – Riau.</p>
                            </div>
                        </div>
                    </body>
                </html>	
            ";

            echo $data['pesan'];
            $this->send_email_smtp('Reset Password',$post['email'],$data['pesan']);
            // echo $this->encryption->decrypt( '8a12e43cd69b8b7472c2318fe954e5eabf9f7033082b752b0e67390cac2f01c71fa2b58036bb833926b6de50db255a4e06ebfa88f5589337f1557f3e19fd98222JQGclF5DUB9lEnoMRcUEIpBlcvE' );
            die();
            if ( $this->send_email_smtp('Reset Password',$post['email'],$data['pesan']) ) {
                # code...
                echo ("<script>window.alert('Permintaan reset password berhasil dikirim, Silahkan buka email {$post['email']} pada bagian Inbox, Spam atau All Mail untuk mendapatkan link reset password');window.location.href='".base_url()."';</script>");
            } else {
                $this->session->set_flashdata('msg', 'Maaf! link gagal dikirimkan ke email : '.$post['email'] .' mohon coba lagi nanti');
                redirect(base_url('forget-password'));
            };
            /* ==================== END :: SEND EMAIL ==================== */
        } else {
            $this->session->set_flashdata('msg', 'Maaf! User dengan Email: '.$post['email'].' tidak ditemukan');
            redirect(base_url('forget-password'));
        }
        
        // $this->debugs();
    }
    /*  */

    protected function send_email_smtpjkj($subject,$to,$html){
            $header .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
			$header .= "Reply-To: New User <info@gmail.com>\r\n"; 
			$header .= "Return-Path: Admin homeschoolingprimaedukasi.com \r\n"; 
			$header .= "From: homeschoolingprimaedukasi.com \r\n";
        mail($to,$subject,$html,$header);
        return TRUE;
    }
    protected function send_email_smtp($subject,$to,$html,$debug=0)
    {
        /* ==================== START :: SEND EMAIL ==================== */

        // PHPMailer object
        $response = false;
        $mail = new PHPMailer();                     
            
        // SMTP configuration
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = $debug;

        $mail->Host     = 'smtp.gmail.com'; //sesuaikan sesuai nama domain hosting/server yang digunakan
        $mail->SMTPAuth = true;
        $mail->Username = 'jogjasitesinur@gmail.com'; // user email
        $mail->Password = 'Sinur12345'; // password email
        $mail->SMTPSecure = 'tls';
        $mail->Port     = 587; // GMail - 465/587/995/993

        $mail->setFrom('info@homeschoolingprimaedukasi.com', 'Tes Diagnostik'); // user email
        $mail->addReplyTo('info@homeschoolingprimaedukasi.com', ''); //user email

        // Add a recipient
        $mail->addAddress($to); //email tujuan pengiriman email

        // Email subject
        $mail->Subject = $subject; //subject email

        // Set email format to HTML
        $mail->isHTML(true);

        // Email body content
        $mailContent = $html; // isi email
        $mail->Body = $mailContent;

        // Send email
        if(!$mail->send()){
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $response = FALSE;
        }else{
            // echo 'Message has been sent';
            $response = TRUE;
        }
        /* ==================== END :: SEND EMAIL ==================== */
        return $response;
    }

    public function reset_password()
    {
        if ( $this->input->get('token') ) {
            $data['token'] = $this->input->get('token');
            $this->load->view('reset_password',$data);
        } else {
            echo ("<script>window.alert('Maaf anda belum melakukan permintaan lupa password');window.location.href='".base_url('forget-password')."';</script>");
        }
    }
    public function reset_password_xxx()
    {
        if ( $this->input->get('token') ) {
            $data['token'] = $this->input->get('token');
            $this->load->view('reset_password',$data);
        } else {
            echo ("<script>window.alert('Maaf anda belum melakukan permintaan lupa password');window.location.href='".base_url('forget-password')."';</script>");
        }
    }
    public function process_reset_password_xxx()
    {
        # send to model
        $this->M_auth->post = [
            'password' => $this->encryption->encrypt($this->input->post('password')),
            'username' => $this->input->post('token'),
        ];
        if ( $this->M_auth->reset_password() ) {
            # code...
            echo ("<script>window.alert('Password baru berhasil dibuat silahkan melakukan login untuk mencobanya');window.location.href='".base_url('auth')."';</script>");
        } else {
            echo ("<script>window.alert('Maaf! password gagal diubah silahkan kirim ulang permintaan lupa password');window.location.href='".base_url('forget-password')."';</script>");
        };

    }
    public function process_reset_password()
    {
        # send to model
        $this->M_auth->post = [
            'password' => $this->encryption->encrypt($this->input->post('password')),
            'username' => $this->input->post('token'),
        ];
        if ( $this->M_auth->reset_password() ) {
            # code...
            echo ("<script>window.alert('Password baru berhasil dibuat silahkan melakukan login untuk mencobanya');window.location.href='".base_url('auth')."';</script>");
        } else {
            echo ("<script>window.alert('Maaf! password gagal diubah silahkan kirim ulang permintaan lupa password');window.location.href='".base_url('forget-password')."';</script>");
        };

    }
}