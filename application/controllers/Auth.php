<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        $this->content = array(
            'base_url' => base_url()
        );
    }

    public function index()
    {
        if ($this->isLogin != 0) {
            redirect('dashboard');
        }
        $this->twig->display('login-page.html', $this->content);
    }

    public function action_login()
    {
        $this->load->model('model_akun');
        $email = $this->input->post('email');
        $cek = $this->model_akun->getBy(array('email_akun' => $email));
        $pesan = array();
        if ($cek->num_rows() > 0) {
            $akun = $cek->row();
            $pass = $this->input->post('password');
            if (password_verify($pass, $akun->password_akun)) {
                $session = array(
                    'isLogin' => 1,
                    'id_akun_login' => $akun->id_akun,
                    'id_user_akun_login' => $akun->id_user_akun,
                    'email_akun_login' => $akun->email_akun,
                    'level_akun_login' => $akun->level_akun
                );
                $this->session->set_userdata($session);
                $pesan['isLogin'] = 1;
                $pesan['id_user'] = $akun->id_user_akun;
                $pesan['condition'] = 2;
                $pesan['pesan'] = "Login Berhasil !";
                $pesan['url'] = 'dashboard';
                echo json_encode($pesan);
                // if ($role == 1) {
                //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                //     $pesan['condition'] = 2;
                //     $pesan['pesan'] = "Login Berhasil !";
                //     $pesan['url'] = 'semuaAnggaranKegiatan';
                //     echo json_encode($pesan);
                // }else if($role == 2){
                //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                //     $pesan['condition'] = 2;
                //     $pesan['pesan'] = "Login Berhasil !";
                //     $pesan['url'] = 'semuaAnggaranKegiatan';
                //     echo json_encode($pesan);
                // }else if($role == 3){
                //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                //     $pesan['condition'] = 2;
                //     $pesan['pesan'] = "Login Berhasil !";
                //     $pesan['url'] = 'semuaAnggaranKegiatan';
                //     echo json_encode($pesan);
                // }
            } else {
                // $this->session->set_flashdata('msgLogin','Password tidak cocok !');
                $pesan['condition'] = 1;
                $pesan['pesan'] = "Login Gagal ! Password Tidak Cocok";
                echo json_encode($pesan);
            }
        } else {
            // $this->session->set_flashdata('msgLogin','NIP Tidak terdaftar !');
            $pesan['condition'] = 0;
            $pesan['pesan'] = "Login Gagal ! Email Tidak tersedia !";
            echo json_encode($pesan);
        }
    }

    public function action_login_android()
    {
        $this->load->model('model_akun');
        $email = $this->input->post('email');
        $cek = $this->model_akun->getBy(array('email_akun' => $email));
        $pesan = array();
        if ($cek->num_rows() > 0) {
            $akun = $cek->row();
            $pass = $this->input->post('password');
            if ($akun->level_akun == 1) {
                if (password_verify($pass, $akun->password_akun)) {
                    $session = array(
                        'isLogin' => 1,
                        'id_akun_login' => $akun->id_akun,
                        'id_user_akun_login' => $akun->id_user_akun,
                        'email_akun_login' => $akun->email_akun,
                        'level_akun_login' => $akun->level_akun
                    );
                    $this->session->set_userdata($session);
                    $pesan['isLogin'] = 1;
                    $pesan['id_user'] = $akun->id_user_akun;
                    $pesan['condition'] = 2;
                    $pesan['pesan'] = "Login Berhasil !";
                    $pesan['url'] = 'dashboard';
                    echo json_encode($pesan);
                    // if ($role == 1) {
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }else if($role == 2){
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }else if($role == 3){
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }
                } else {
                    // $this->session->set_flashdata('msgLogin','Password tidak cocok !');
                    $pesan['condition'] = 1;
                    $pesan['pesan'] = "Login Gagal ! Password Tidak Cocok";
                    echo json_encode($pesan);
                }
            } else {
                $pesan['condition'] = 0;
                $pesan['pesan'] = "Hanya diperuntukkan untuk bagian Akademik ";

                echo json_encode($pesan);
            }
        } else {
            // $this->session->set_flashdata('msgLogin','NIP Tidak terdaftar !');
            $pesan['condition'] = 0;
            $pesan['pesan'] = "Login Gagal ! Email Tidak tersedia !";
            echo json_encode($pesan);
        }
    }

    public function logoutAndroid()
    {
        $isLogin = $this->input->post('isLogin');
        if ($isLogin == 1) {
            $this->session->set_userdata('isLogin', 0);
            $this->session->sess_destroy();
            $isLogin = 0;

            $output = array(
                'status' => 200,
                'pesan' => 'berhasil logout ',
                'isLogin' => 0
            );
        } else {
            $output = array(
                'status' => 500,
                'pesan' => 'Anda sudah logout'
            );
        }

        echo json_encode($output);
    }

    public function logout()
    {
        $this->session->set_userdata('isLogin', 0);
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
