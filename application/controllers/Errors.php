<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url());
        }
        // set timezone 
        date_default_timezone_set("Asia/Bangkok");
        $this->id = $this->session->userdata('id_akun_login');
        $this->id_user = $this->session->userdata('id_user_akun_login');
        $this->email = $this->session->userdata('email_akun_login');
        $this->level = $this->session->userdata('level_akun_login');
        $this->load->model('model_level');
        $level = $this->model_level->getBy(array('id_level' => $this->level))->row();
        $this->content = array(
            'base_url' => base_url(),
            'id_akun_login' => $this->id,
            'id_user_akun_login' => $this->id_user,
            'email_akun_login' => $this->email,
            'level_akun_login' => $this->level,
            'nama_level_akun_login' => $level->nama_level
        );

        $this->load->model('model_user');
        $this->load->model('model_mhs');

        $aktor = array();

        // ambil data lengkap aktor login
        if ($this->level != 3) {
            $get = $this->model_user->getBy(array('id_user' => $this->id_user))->row();
            $this->content['nama_akun_login'] = $get->nama_user;
            $this->content['jabatan_akun_login'] = $get->jabatan_user;
            $this->content['nomor_induk_akun_login'] = $get->nip_user;
        } else if ($this->level == 3) {
            $get = $this->model_user->getBy(array('id_user' => $this->id_user))->row();
            $this->content['nama_akun_login'] = $get->nama_user;
            $this->content['jabatan_akun_login'] = $get->jabatan_user;
            $this->content['nomor_induk_akun_login'] = $get->nip_user;
        }

        $this->load->model('model_log');
    }

    public function index()
    {
        $this->output->set_status_header('404');
        $this->twig->display('errors/html/error_404.html', $this->content);
    }
}
