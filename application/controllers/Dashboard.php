<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url());
        }
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
        // $this->load->model('model_mhs');

        $aktor = array();

        // ambil data lengkap aktor login
        // if ($this->level != 3) {
        $get = $this->model_user->getBy(array('id_user' => $this->id_user))->row();
        $this->content['nama_akun_login'] = $get->nama_user;
        $this->content['jabatan_akun_login'] = $get->jabatan_user;
        $this->content['nomor_induk_akun_login'] = $get->nip_user;
        // }
        //  else if ($this->level == 3) {
        //     $get = $this->model_mhs->getBy(array('id_mhs' => $this->id_user))->row();
        //     $this->content['nama_akun_login'] = $get->nama_mhs;
        //     $this->content['jabatan_akun_login'] = 'Mahasiswa';
        //     $this->content['nomor_induk_akun_login'] = $get->npm_mhs;
        // }
        $this->load->model('model_pengajuan');
        $this->load->model('model_user');
        // $this->load->model('model_mhs');
    }

    public function dashboard()
    {
        if ($this->level == 1) {
            $pengajuan = count($this->model_pengajuan->getAll());
            $user = count($this->model_user->getAll());
            // $mhs = count($this->model_mhs->getAll());
            $this->content['pengajuan'] = $pengajuan;
            $this->content['user'] = $user;
            // $this->content['mhs'] = $mhs;
        }
        $this->twig->display('dashboard.html', $this->content);
    }
}
