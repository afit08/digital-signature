<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun extends CI_Controller
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
        $this->load->model('model_akun');
    }

    public function listAkun()
    {
        $this->content['page'] = 'Daftar Akun';
        $this->twig->display('akun.html', $this->content);
    }

    public function akunLists()
    {
        $this->load->model('model_level');
        $akun = $this->model_akun->make_datatables();
        $data = array();
        $no = 0;
        if (!empty($akun)) {
            foreach ($akun as $row) {
                if ($row->id_akun != $this->id) {
                    $sub_data = array();
                    $sub_data[] = $no + 1;
                    $sub_data[] = $row->email_akun;
                    $getLevel = $this->model_level->getBy(array('id_level' => $row->level_akun))->row();
                    $sub_data[] = $getLevel->nama_level;
                    $sub_data[] = date('d F Y', strtotime($row->created_at));
                    $sub_data[] = $row->created_by;
                    $sub_data[] = '<div class="btn-group dropleft">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item detail" id="' . $row->id_akun . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                </div>
              </div>';
                    $data[] = $sub_data;
                    $no++;
                }
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $no,
            'recordsFiltered' => $no,
            'data' => $data
        );

        echo json_encode($output);
    }

    public function getByEmail()
    {
        $email = $this->input->post('email');
        $cek = $this->model_akun->getBy(array('email_akun' => $email))->num_rows();
        $output = array();
        if ($cek == 0) {
            $output['cond'] = '1';
        } else {
            $output['cond'] = '0';
        }

        echo json_encode($output);
    }

    public function editPassword()
    {
        $cek = $this->model_akun->getBy(array('id_user_akun' => $this->id_user));
        $pesan = array();
        if ($cek->num_rows() > 0) {
            $user = $cek->row();
            $pass_lama = $this->input->post('pass_lama');
            if (password_verify($pass_lama, $user->password_akun)) {
                $pass_baru = $this->input->post('pass_baru');
                $data = array('password_akun' => password_hash($pass_baru, PASSWORD_DEFAULT));
                $process = $this->model_akun->edit(array('id_user_akun' => $this->id_user), $data);
                $pesan['cond'] = 1;
                $pesan['msg'] = 'Password berhasil diganti !';
                $log2 = array(
                    'nama_aktor_log' => $this->content['nama_akun_login'],
                    'aksi_log' => 'ubah password dengan ID ' . $this->id_user
                );
                $this->load->model('model_log');
                $this->model_log->insert($log2);
            } else {
                $pesan['cond'] = 0;
                $pesan['msg'] = 'Password Lama Anda salah !';
            }
        } else {
            $pesan['cond'] = 0;
            $pesan['msg'] = 'Password gagal diganti !';
        }
        echo json_encode($pesan);
    }
}
