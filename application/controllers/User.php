<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        //$this->load->model('model_mhs');

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

        $this->load->model('model_log');
    }

    public function listUser()
    {
        $this->load->model('model_level');
        $dariDB = $this->model_user->cekKode();
        $nourut = substr($dariDB, 3, 4);
        $kode = $nourut + 1;
        if ($kode <= 9) {
            $id_user = 'USR000' . strval($kode);
        } else if ($kode > 9 && $kode <= 99) {
            $id_user = 'USR00' . strval($kode);
        } else if ($kode > 99 && $kode <= 999) {
            $id_user = 'USR0' . strval($kode);
        } else {
            $id_user = 'USR' . strval($kode);
        }
        $this->content['page'] = 'Manajemen User';
        $this->content['level'] = $this->model_level->getAll();
        $this->content['kode'] = $id_user;
        $this->twig->display('user.html', $this->content);
    }

    public function userLists()
    {
        $user = $this->model_user->make_datatables();
        $data = array();
        $no = 0;
        if (!empty($user)) {
            foreach ($user as $row) {
                if ($this->id_user != $row->id_user && $row->deleted_at == NULL) {
                    $sub_data = array();
                    $sub_data[] = $no + 1;
                    $sub_data[] = $row->id_user;
                    $sub_data[] = $row->nama_user;
                    $sub_data[] = $row->nip_user;
                    $sub_data[] = $row->jabatan_user;
                    $sub_data[] = $row->no_hp_user;
                    $sub_data[] = date('d F Y', strtotime($row->created_at));
                    $sub_data[] = $row->created_by;
                    $sub_data[] = '<div class="btn-group dropleft">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item detail" id="' . $row->id_user . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                    <a class="dropdown-item hapus" id="' . $row->id_user . '" href="#"><i class="fas fa-trash"></i> Hapus</a>
                </div>
              </div>';
                    $data[] = $sub_data;
                    $no++;
                }
            }
        }
        // <a class="dropdown-item edit" id="' . $row->id_user . '" href="#"><i class="fas fa-edit"></i> Edit</a> fungsi edit
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $no,
            'recordsFiltered' => $no,
            'data' => $data
        );

        echo json_encode($output);
    }

    public function userById()
    {
        $id = $this->input->post('id');
        $this->load->model('model_akun');
        $akun = $this->model_akun->getBy(array('id_user_akun' => $id))->row();
        $user = $this->model_user->getBy(array('id_user' => $id))->row();
        $level = $this->model_level->getBy(array('id_level' => $akun->level_akun))->row();
        $output = array(
            'id_user' => $user->id_user,
            'nama_user' => $user->nama_user,
            'jabatan_user' => $user->jabatan_user,
            'nip_user' => $user->nip_user,
            'prodi_user' => $user->prodi_user,
            'no_hp_user' => $user->no_hp_user,
            'created_at' => $user->created_at,
            'created_by' => $user->created_by,
            'updated_at' => $user->updated_at,
            'updated_by' => $user->updated_by,
            'deleted_at' => $user->deleted_at,
            'deleted_by' => $user->deleted_by,
            'email_akun' => $akun->email_akun,
            'level_akun' => $level->nama_level,
        );
        echo json_encode($output);
    }

    // public function gantiStatusUsers()
    // {
    //     $id = $this->input->post('id');
    //     $data = array('status' => $this->input->post('status'));
    //     $process = $this->model_users->editUsers($id, $data);
    //     echo json_encode($process);
    // }

    public function doUser()
    {
        $this->load->model('model_akun');
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'id_user' => $this->input->post('id_user'),
                'nama_user' => $this->input->post('nama_user'),
                'jabatan_user' => $this->input->post('jabatan_user'),
                'prodi_user' => $this->input->post('prodi_user'),
                'nip_user' => $this->input->post('nip_user'),
                'no_hp_user' => $this->input->post('no_hp_user'),
                'created_by' => $this->content['nama_akun_login']
            );
            $insertUser = $this->model_user->insert($data);

            $data2 = array(
                'id_user_akun' => $this->input->post('id_user'),
                'email_akun' => $this->input->post('email_akun'),
                'password_akun' => password_hash($this->input->post('email_akun'), PASSWORD_DEFAULT),
                'level_akun' => $this->input->post('level_akun'),
                'created_by' => $this->content['nama_akun_login']
            );
            $process = $this->model_akun->insert($data2);

            // Masukan ke log
            $log2 = array(
                'nama_aktor_log' => $this->content['nama_akun_login'],
                'aksi_log' => 'Menambahkan User dengan ID ' . $this->input->post('id_user') . 'dan Akunnya '
            );
            $this->model_log->insert($log2);

            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_user');
            $data = array(
                'nama_user' => $this->input->post('nama_user'),
                'jabatan_user' => $this->input->post('jabatan_user'),
                'nip_user' => $this->input->post('nip_user'),
                'prodi_user' => $this->input->post('prodi_user'),
                'no_hp_user' => $this->input->post('no_hp_user'),
                'updated_at' => date('Y-m-d h:i:sa'),
                'updated_by' => $this->content['nama_akun_login']
            );
            $editUser = $this->model_user->edit(array('id_user' => $id), $data);
            // Masukan ke log
            $log1 = array(
                'nama_aktor_log' => $this->content['nama_akun_login'],
                'aksi_log' => 'Edit User dengan ID ' . $id . ' beserta akunnya'
            );
            $this->model_log->insert($log1);

            $data2 = array(
                'email_akun' => $this->input->post('email_akun'),
                'level_akun' => $this->input->post('level_akun'),
                'updated_at' => date('Y-m-d h:i:sa'),
                'updated_by' => $this->content['nama_akun_login']
            );
            $process = $this->model_akun->edit(array('id_user_akun' => $id), $data2);

            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Edit Data Berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Edit Data Gagal !';
            }
        }
        echo json_encode($output);
    }

    public function deleteUser()
    {
        $this->load->model('model_akun');
        $id = $this->input->post('id');
        $data = array(
            'deleted_at' => date('Y-m-d h:i:sa'),
            'deleted_by' => $this->content['nama_akun_login']
        );
        $this->model_akun->edit(array('id_user_akun' => $id), $data);
        $this->model_log->insert(array('nama_aktor_log' => $this->content['nama_akun_login'], 'aksi_log' => 'Hapus Sementara User dengan ID' . $id . ' dan Akunnya'));
        $process = $this->model_user->edit(array('id_user' => $id), $data);
        if ($process) {
            $output['cond'] = '1';
            $output['msg'] = 'Hapus Data Sementara Berhasil !';
        } else {
            $output['cond'] = '0';
            $output['msg'] = 'Hapus Data Sementara Gagal !';
        }
        echo json_encode($output);
    }

    public function viewHapus()
    {
        $this->content['page'] = 'List Data Hapus';
        $this->twig->display('listHapus.html', $this->content);
    }

    public function listHapus()
    {
        $user = $this->model_user->make_datatables();
        $data = array();
        $no = 0;
        if (!empty($user)) {
            foreach ($user as $row) {
                if ($this->id != $row->id_user && $row->deleted_at != NULL) {
                    $sub_data = array();
                    $sub_data[] = $no + 1;
                    $sub_data[] = $row->nama_user;
                    $sub_data[] = $row->nip_user;
                    $sub_data[] = $row->jabatan_user;
                    $sub_data[] = $row->no_hp_user;
                    $sub_data[] = date('d F Y', strtotime($row->created_at));
                    $sub_data[] = $row->created_by;
                    $sub_data[] = '<div class="btn-group dropleft">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item detail" id="' . $row->id_user . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                    <a class="dropdown-item restore" id="' . $row->id_user . '" href="#"><i class="fas fa-undo"></i> Restore</a>
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

    public function restore()
    {
        $this->load->model('model_akun');
        $output = array();
        $id = $this->input->post('id');
        $data = array(
            'deleted_at' => NULL,
            'deleted_by' => NULL
        );
        $this->model_user->edit(array('id_user' => $id), $data);
        $process = $this->model_akun->edit(array('id_user_akun' => $id), $data);

        // Masukan ke log
        $log2 = array(
            'nama_aktor_log' => $this->content['nama_akun_login'],
            'aksi_log' => 'Restore Data User dengan ID ' . $id . ' dan Akun'
        );
        $this->model_log->insert($log2);
        if ($process) {
            $output['cond'] = '1';
            $output['msg'] = 'Restore berhasil !';
        } else {
            $output['cond'] = '0';
            $output['msg'] = 'Restore gagal !';
        }
        echo json_encode($output);
    }

    public function profilUser()
    {
        $this->load->model('model_akun');
        $user = $this->model_user->getBy(array('id_user' => $this->id_user))->row();
        $akun = $this->model_akun->getBy(array('id_user_akun' => $this->id_user))->row();
        $level = $this->model_level->getBy(array('id_level' => $akun->level_akun))->row();
        $this->content['user'] = $user;
        $this->content['page'] = 'Profil Saya';
        $this->content['akun'] = $akun;
        $this->content['level'] = $level;
        $this->twig->display('profilUser.html', $this->content);
    }

    public function editPassword()
    {
        $this->load->model('model_akun');
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
