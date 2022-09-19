<?php

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\Formats\Keys\PKCS8;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Crypt\RSA\PublicKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Math\BigInteger;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan extends CI_Controller
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
        $this->load->model('model_pengajuan');
        $this->load->model('model_pengajuan_detail');
        $this->load->model('model_log');
    }

    public function listPengajuan()
    {
        $this->content['page'] = 'Manajemen Pengajuan';
        $this->content['pengesah'] = $this->model_akun->getBy(array('level_akun' => 2))->result();
        $this->content['allUser'] = $this->model_user->getAll();
        $this->twig->display('pengajuan.html', $this->content);
    }

    public function pengajuanLists()
    {
        $pengajuan = $this->model_pengajuan->make_datatables();
        $data = array();
        $no = 0;
        $id_pengajuan_skrg = '';
        if (!empty($pengajuan)) {
            foreach ($pengajuan as $row) {
                if ($this->level == 1) {
                    if ($row->deleted_at == NULL) {
                        $sub_data = array();
                        $e = password_hash($row->id_pengajuan, PASSWORD_DEFAULT);
                        $q = '';
                        $sub_data[] = $no + 1;
                        // $sub_data[] = $row->id_pengajuan;
                        // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                        $sub_data[] = $row->nama_user;
                        $sub_data[] = $row->perihal_pengajuan;
                        $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                        $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                        if ($row->status_pengajuan == 0) {
                            $html = '<span class="badge badge-warning p-2"> Sedang ditinjau </span>';
                        } else if ($row->status_pengajuan == 1) {
                            $html = '<span class="badge badge-primary p-2"> Dalam Proses </span>';
                        } else if ($row->status_pengajuan == 2) {
                            $html = '<span class="badge badge-success p-2"> Verified </span>';
                        } else {
                            $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                        }
                        $sub_data[] = $html;
                        // $sub_data[] = date('d F Y', strtotime($row->created_at));
                        // $sub_data[] = $row->created_by;
                        $sub_data[] = '<div class="btn-group dropleft">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . base_url('pengajuan/detailPengajuan/') . urlencode(base64_encode($row->id_pengajuan)) . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            <a class="dropdown-item hapus" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                        </div>';
                        $data[] = $sub_data;
                        $no++;
                    }
                } else if ($this->level == 2) {
                    $checkPengajuan = $this->model_pengajuan_detail->getBy(array('id_pengesah' => $this->id_user))->result();
                    foreach ($checkPengajuan as $cp) {
                        // if ($no == 0) {
                        //     $id_pengajuan_skrg = $cp->id_pengajuan;
                        if ($cp->id_pengajuan == $row->id_pengajuan && $cp->id_pengesah == $this->id_user) {
                            $sub_data = array();
                            $sub_data[] = $no + 1;
                            // $sub_data[] = $row->id_pengajuan;
                            // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                            $sub_data[] = $row->nama_user;
                            $sub_data[] = $row->perihal_pengajuan;
                            $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                            $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                            if ($row->status_pengajuan == 0) {
                                $html = '<span class="badge badge-warning p-2"> Sedang ditinjau </span>';
                            } else if ($row->status_pengajuan == 1) {
                                $html = '<span class="badge badge-primary p-2"> Dalam Proses </span>';
                            } else if ($row->status_pengajuan == 2) {
                                $html = '<span class="badge badge-success p-2"> Verified </span>';
                            } else {
                                $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                            }
                            $sub_data[] = $html;
                            // $sub_data[] = date('d F Y', strtotime($row->created_at));
                            // $sub_data[] = $row->created_by;
                            $sub_data[] = '<div class="btn-group dropleft">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . base_url('pengajuan/detailPengajuan/') . urlencode(base64_encode($row->id_pengajuan)) . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            </div>
                            </div>';
                            $data[] = $sub_data;
                            $no++;
                            //     }
                            // } else {
                            //     $id_pengajuan_skrg = $cp->id_pengajuan;
                            //     if ($id_pengajuan_skrg != $cp->id_pengajuan && $cp-> ) {
                            //         $sub_data = array();
                            //         $sub_data[] = $no + 1;
                            //         // $sub_data[] = $row->id_pengajuan;
                            //         // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                            //         $sub_data[] = $row->nama_mhs;
                            //         $sub_data[] = $row->perihal_pengajuan;
                            //         $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                            //         $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                            //         if ($row->status_pengajuan == 0) {
                            //             $html = '<span class="badge badge-warning p-2"> Dalam Proses </span>';
                            //         } else if ($row->status_pengajuan == 1) {
                            //             $html = '<span class="badge badge-success p-2"> Selesai </span>';
                            //         } else {
                            //             $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                            //         }
                            //         $sub_data[] = $html;
                            //         // $sub_data[] = date('d F Y', strtotime($row->created_at));
                            //         // $sub_data[] = $row->created_by;
                            //         $sub_data[] = '<div class="btn-group dropleft">
                            //     <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            //         <i class="fa fa-ellipsis-h"></i>
                            //     </button>
                            //     <div class="dropdown-menu">
                            //         <a class="dropdown-item" href="' . base_url('pengajuan/detailPengajuan/') . urlencode(base64_encode($row->id_pengajuan)) . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            //         <a class="dropdown-item ds" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-edit"></i> Digital Signature</a>
                            //     </div>
                            //     </div>';
                            //         $data[] = $sub_data;
                            //         $no++;
                            //     }
                        }
                    }
                } else if ($this->level == 3) {
                    if ($row->deleted_at == NULL && $row->id_lkm_pengajuan == $this->id_user) {
                        $sub_data = array();
                        $sub_data[] = $no + 1;
                        // $sub_data[] = $row->id_pengajuan;
                        // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                        $sub_data[] = $row->nama_user;
                        $sub_data[] = $row->perihal_pengajuan;
                        $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                        $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                        if ($row->status_pengajuan == 0) {
                            $html = '<span class="badge badge-warning p-2"> Sedang ditinjau </span>';
                        } else if ($row->status_pengajuan == 1) {
                            $html = '<span class="badge badge-primary p-2"> Dalam Proses </span>';
                        } else if ($row->status_pengajuan == 2) {
                            $html = '<span class="badge badge-success p-2"> Verified </span>';
                        } else {
                            $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                        }
                        $sub_data[] = $html;
                        // $sub_data[] = date('d F Y', strtotime($row->created_at));
                        // $sub_data[] = $row->created_by;
                        if ($row->status_pengajuan == 0) {
                            $sub_data[] = '<div class="btn-group dropleft">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . base_url('pengajuan/detailPengajuan/') . urlencode(base64_encode($row->id_pengajuan)) . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            <a class="dropdown-item edit" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-edit"></i> Edit</a>
                        </div>
                        </div>';
                        } else {
                            $sub_data[] = '<div class="btn-group dropleft">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . base_url('pengajuan/detailPengajuan/') . urlencode(base64_encode($row->id_pengajuan)) . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                        </div>
                        </div>';
                        }
                        $data[] = $sub_data;
                        $no++;
                    }
                }
            }
        }
        // <a class="dropdown-item edit" id="' . $row->id_mhs . '" href="#"><i class="fas fa-edit"></i> Edit</a> fungsi edit
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $no,
            'recordsFiltered' => $no,
            'data' => $data
        );

        echo json_encode($output);
    }

    public function detailPengajuan($id = "")
    {
        // $data = "asd";
        // write_file('./assets/file/key/file.pem', $data);
        $decId = base64_decode(urldecode($id));
        $pengajuan = $this->model_pengajuan->getBy(array('id_pengajuan' => $decId))->row();
        $detail = $this->model_pengajuan_detail->getBy(array('id_pengajuan' => $decId))->result();
        $this->content['page'] = 'Detail Pengajuan';
        $this->content['pengesah'] = $this->model_akun->getBy(array('level_akun' => 2))->result();
        $this->content['allUser'] = $this->model_user->getAll();
        $this->content['pengajuan'] = $pengajuan;
        $this->content['detail'] = $detail;
        $this->twig->display('detailPengajuan.html', $this->content);
    }

    public function pengajuanById()
    {
        $id = $this->input->post('id');
        $pengajuan = $this->model_pengajuan->getBy(array('id_pengajuan' => $id))->row();
        $detail = $this->model_pengajuan_detail->getBy(array('id_pengajuan' => $id))->result();
        $output = array(
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'perihal_pengajuan' => $pengajuan->perihal_pengajuan,
            'deskripsi_pengajuan' => $pengajuan->deskripsi_pengajuan,
            'nama_file_pengajuan' => $pengajuan->nama_file_pengajuan,
            'pengesah' => $detail,
            'nama_user' => $pengajuan->nama_user,
            // 'prodi_mhs' => $pengajuan->prodi_mhs,
            // 'npm_mhs' => $pengajuan->npm_mhs,
            'created_at' => $pengajuan->created_at,
            'created_by' => $pengajuan->created_by,
            'updated_at' => $pengajuan->updated_at,
            'updated_by' => $pengajuan->updated_by,
            'deleted_at' => $pengajuan->deleted_at,
            'deleted_by' => $pengajuan->deleted_by,
        );
        echo json_encode($output);
    }

    public function gantiStatusUsers()
    {
        $id = $this->input->post('id');
        $data = array('status' => $this->input->post('status'));
        $process = $this->model_users->editUsers($id, $data);
        echo json_encode($process);
    }

    public function doPengajuan()
    {
        $this->load->model('model_akun');

        $config['upload_path'] = './assets/file/pengajuan/';
        $config['allowed_types'] = 'pdf';

        $this->load->library('upload', $config);
        $output = array();
        $operation = $this->input->post('operation');
        if ($operation == "Tambah") {
            if (!$this->upload->do_upload('file_pengajuan')) {
                $output['cond'] = '0';
                $output['msg'] = $this->upload->display_errors();
            } else {
                $file_ext = $this->upload->data('file_ext');
                if ($file_ext == '.pdf') {
                    // create Private Key
                    $private = RSA::createKey()->withMGFHash('sha256');
                    $data = $private->toString('PSS');
                    $filename = sha1('private') . '.pem';
                    $this->createFile($filename, $data);

                    // create Public key
                    // $public = $private->getPublicKey()->withMGFHash('sha256');
                    // $data2 = $public->toString('PSS');
                    // $filename2 = sha1('public') . '.pem';
                    // $this->createFile($filename2, $data2);


                    $nama_file = $this->upload->data('file_name');
                    $data = array(
                        'id_lkm_pengajuan'  => $this->id_user,
                        'perihal_pengajuan' => $this->input->post('perihal_pengajuan'),
                        'deskripsi_pengajuan' => $this->input->post('deskripsi_pengajuan'),
                        'nama_file_pengajuan' => $nama_file,
                        'nama_file_verified_pengajuan' => '-',
                        'private_key_pengajuan' => $filename,
                        'status_pengajuan' => 0,
                        'created_by' => $this->content['nama_akun_login']
                    );
                    $insertPengajuan = $this->model_pengajuan->insert($data);
                    $pengesah = $this->input->post('pengesah');
                    for ($i = 0; $i < count($pengesah); $i++) {
                        $data2 = array(
                            'id_pengajuan' => $insertPengajuan,
                            'id_pengesah' => $pengesah[$i]
                        );
                        $insertDetail = $this->model_pengajuan_detail->insert($data2);
                    }

                    // Masukan ke log
                    $log = array(
                        'nama_aktor_log' => $this->content['nama_akun_login'],
                        'aksi_log' => 'Mahasiswa denga ID ' . $this->id_user . ' Mengajukan berkas dengan perihal ' . $this->input->post("perihal_pengajuan")
                    );
                    $this->model_log->insert($log);

                    if ($insertPengajuan) {
                        $output['cond'] = '1';
                        $output['msg'] = 'Pengajuan Berhasil !, Pengajuan anda sedang di proses ';
                    } else {
                        $output['cond'] = '0';
                        $output['msg'] = 'Pengajuan gagal !';
                    }
                } else {
                    $output['cond'] = '0';
                    $output['msg'] = 'Maaf, File extensi anda bukan PDF. Silahkan upload extensi PDF';
                }
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_pengajuan');
            if (!$this->upload->do_upload('file_pengajuan')) {
                $nama_file = $this->input->post('old_nama_file');
                $data = array(
                    'perihal_pengajuan' => $this->input->post('perihal_pengajuan'),
                    'deskripsi_pengajuan' => $this->input->post('deskripsi_pengajuan'),
                    'nama_file_pengajuan' => $nama_file,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->content['nama_akun_login']
                );
                $editPengajuan = $this->model_pengajuan->edit(array('id_pengajuan' => $id), $data);

                $this->model_pengajuan_detail->delete(array('id_pengajuan' => $id));

                $pengesah = $this->input->post('pengesah');
                for ($i = 0; $i < count($pengesah); $i++) {
                    $data2 = array(
                        'id_pengajuan' => $id,
                        'id_pengesah' => $pengesah[$i]
                    );
                    $insertDetail = $this->model_pengajuan_detail->insert($data2);
                }

                // Masukan ke log
                $log = array(
                    'nama_aktor_log' => $this->content['nama_akun_login'],
                    'aksi_log' => 'Mahasiswa dengan ID ' . $this->id_user . ' Edit Pengajuan berkas dengan perihal ' . $this->input->post("perihal_pengajuan")
                );
                $this->model_log->insert($log);

                if ($editPengajuan) {
                    $output['cond'] = '1';
                    $output['msg'] = 'Edit Pengajuan Berhasil !';
                } else {
                    $output['cond'] = '0';
                    $output['msg'] = 'Edit Pengajuan gagal !';
                }
            } else {
                $nama_file = $this->upload->data('file_name');
                $file_ext = $this->upload->data('file_ext');
                if ($file_ext == '.pdf') {
                    $data = array(
                        'perihal_pengajuan' => $this->input->post('perihal_pengajuan'),
                        'deskripsi_pengajuan' => $this->input->post('deskripsi_pengajuan'),
                        'nama_file_pengajuan' => $nama_file,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => $this->content['nama_akun_login']
                    );
                    $editPengajuan = $this->model_pengajuan->edit(array('id_pengajuan' => $id), $data);

                    $this->model_pengajuan_detail->delete(array('id_pengajuan' => $id));

                    $pengesah = $this->input->post('pengesah');
                    for ($i = 0; $i < count($pengesah); $i++) {
                        $data2 = array(
                            'id_pengajuan' => $id,
                            'id_pengesah' => $pengesah[$i]
                        );
                        $insertDetail = $this->model_pengajuan_detail->insert($data2);
                    }

                    // Masukan ke log
                    $log = array(
                        'nama_aktor_log' => $this->content['nama_akun_login'],
                        'aksi_log' => 'Mahasiswa dengan ID ' . $this->id_user . ' Edit Pengajuan berkas dengan perihal ' . $this->input->post("perihal_pengajuan")
                    );
                    $this->model_log->insert($log);

                    if ($editPengajuan) {
                        $output['cond'] = '1';
                        $output['msg'] = 'Edit Pengajuan Berhasil !';
                    } else {
                        $output['cond'] = '0';
                        $output['msg'] = 'Edit Pengajuan gagal !';
                    }
                } else {
                    $output['cond'] = '0';
                    $output['msg'] = 'Silahkan upload dengan extensi PDF';
                }
            }
        }
        echo json_encode($output);
    }

    public function deletePengajuan()
    {
        $id = $this->input->post('id');
        $data = array(
            'deleted_at' => date('Y-m-d h:i:sa'),
            'deleted_by' => $this->content['nama_akun_login']
        );
        $this->model_pengajuan->edit(array('id_pengajuan' => $id), $data);
        $this->model_pengajuan_detail->edit(array('id_pengajuan' => $id), $data);

        $process = $this->model_log->insert(array('nama_aktor_log' => $this->content['nama_akun_login'], 'aksi_log' => 'Hapus Sementara Pengaduan dengan ID ' . $id . ''));
        if ($process) {
            $output['cond'] = '1';
            $output['msg'] = 'Hapus Data Sementara Berhasil !';
        } else {
            $output['cond'] = '0';
            $output['msg'] = 'Hapus Data Sementara Gagal !';
        }
        echo json_encode($output);
    }

    public function viewHapusPengajuan()
    {
        if ($this->level == 1) {
            $this->content['page'] = 'List Data Hapus';
            $this->twig->display('listHapusPengajuan.html', $this->content);
        } else {
        }
    }

    public function listHapusPengajuan()
    {
        $pengajuan = $this->model_pengajuan->make_datatables();
        $data = array();
        $no = 0;
        if (!empty($pengajuan)) {
            foreach ($pengajuan as $row) {
                if ($this->level == 1) {
                    if ($row->deleted_at != NULL) {
                        $sub_data = array();
                        $sub_data[] = $no + 1;
                        // $sub_data[] = $row->id_pengajuan;
                        // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                        $sub_data[] = $row->nama_user;
                        $sub_data[] = $row->perihal_pengajuan;
                        $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                        $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                        if ($row->status_pengajuan == 0) {
                            $html = '<span class="badge badge-warning p-2"> Dalam Proses </span>';
                        } else if ($row->status_pengajuan == 1) {
                            $html = '<span class="badge badge-success p-2"> Selesai </span>';
                        } else {
                            $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                        }
                        $sub_data[] = $html;
                        // $sub_data[] = date('d F Y', strtotime($row->created_at));
                        // $sub_data[] = $row->created_by;
                        $sub_data[] = '<div class="btn-group dropleft">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item detail" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            <a class="dropdown-item restore" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-undo"></i> Restore</a>
                        </div>
                        </div>';
                        $data[] = $sub_data;
                        $no++;
                    }
                } else if ($this->level == 2) {
                    if ($row->id_lkm_pengajuan == $this->id_user) {
                        $sub_data = array();
                        $sub_data[] = $no + 1;
                        // $sub_data[] = $row->id_pengajuan;
                        // $mhs = $this->model_mhs->getBy(array('id_mhs' => $row->id_mhs_pengajuan))->row();
                        $sub_data[] = $row->nama_user;
                        $sub_data[] = $row->perihal_pengajuan;
                        $sub_data[] = "<a class='link-primary' target='_blank' href='" . base_url() . "assets/file/pengajuan/" . $row->nama_file_pengajuan . "'>" . $row->nama_file_pengajuan . "</a>";
                        $sub_data[] = date('d F Y H:i:sa', strtotime($row->tanggal_pengajuan));
                        if ($row->status_pengajuan == 0) {
                            $html = '<span class="badge badge-warning p-2"> Dalam Proses </span>';
                        } else if ($row->status_pengajuan == 1) {
                            $html = '<span class="badge badge-success p-2"> Selesai </span>';
                        } else {
                            $html = '<span class="badge badge-danger p-2"> Di Tolak </span>';
                        }
                        $sub_data[] = $html;
                        // $sub_data[] = date('d F Y', strtotime($row->created_at));
                        // $sub_data[] = $row->created_by;
                        $sub_data[] = '<div class="btn-group dropleft">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item detail" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-eye"></i> Detail</a>
                            <a class="dropdown-item restore" id="' . $row->id_pengajuan . '" href="#"><i class="fas fa-undo"></i> Restore</a>
                        </div>
                        </div>';
                        $data[] = $sub_data;
                        $no++;
                    }
                }
            }
        }
        // <a class="dropdown-item edit" id="' . $row->id_mhs . '" href="#"><i class="fas fa-edit"></i> Edit</a> fungsi edit
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $no,
            'recordsFiltered' => $no,
            'data' => $data
        );

        echo json_encode($output);
    }

    public function restorePengajuan()
    {
        $output = array();
        $id = $this->input->post('id');
        $data = array(
            'deleted_at' => NULL,
            'deleted_by' => NULL
        );
        $this->model_pengajuan->edit(array('id_pengajuan' => $id), $data);
        $this->model_pengajuan_detail->edit(array('id_pengajuan' => $id), $data);

        // Masukan ke log
        $log2 = array(
            'nama_aktor_log' => $this->content['nama_akun_login'],
            'aksi_log' => 'Restore Data Pengajuan dengan ID ' . $id . ''
        );
        $this->model_log->insert($log2);
        if ($log2) {
            $output['cond'] = '1';
            $output['msg'] = 'Restore berhasil !';
        } else {
            $output['cond'] = '0';
            $output['msg'] = 'Restore gagal !';
        }
        echo json_encode($output);
    }

    public function profilMhs()
    {
        $this->load->model('model_akun');
        $mhs = $this->model_mhs->getBy(array('id_mhs' => $this->id_user))->row();
        $akun = $this->model_akun->getBy(array('id_user_akun' => $this->id_user))->row();
        $level = $this->model_level->getBy(array('id_level' => $akun->level_akun))->row();
        $this->content['mhs'] = $mhs;
        $this->content['page'] = 'Profil Saya';
        $this->content['akun'] = $akun;
        $this->content['level'] = $level;
        $this->twig->display('profilMhs.html', $this->content);
    }

    public function gantiStatusPengajuan()
    {
        $id = $this->input->post('id');
        $pesan = $this->input->post('pesan_pengajuan');
        $status = $this->input->post('status');
        $pengajuan = $this->model_pengajuan->getBy(array('id_pengajuan' => $id))->row();
        $data = array(
            'status_pengajuan' => $status,
            'pesan_pengajuan' => $pesan
        );
        $exec = $this->model_pengajuan->edit(array('id_pengajuan' => $id), $data);
        if ($status == 1) {
            $log = array(
                'nama_aktor_log' => $this->content['nama_akun_login'],
                'aksi_log' => 'User dengan ID ' . $this->id_user . ' MENERIMA pengajuan dengan perihal ' . $pengajuan->perihal_pengajuan
            );
        } else if ($status == 3) {
            $log = array(
                'nama_aktor_log' => $this->content['nama_akun_login'],
                'aksi_log' => 'User dengan ID ' . $this->id_user . ' MENOLAK pengajuan dengan perihal ' . $pengajuan->perihal_pengajuan
            );
        }
        $this->model_log->insert($log);
        $output = array();
        if ($exec) {
            $output['cond'] = '1';
            $output['msg'] = 'Berhasil';
        } else {
            $output['cond'] = '0';
            $output['msg'] = 'Gagal';
        }
        echo json_encode($output);
    }

    public function pengajuanDetailId()
    {
        $id = $this->input->post('id');
        $detail = $this->model_pengajuan_detail->getBy(array('id_pengajuan_detail' => $id))->row();
        echo json_encode($detail);
    }

    function createFile($filename, $data)
    {
        write_file('./assets/file/key/' . $filename, $data);
    }
}
