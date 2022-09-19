<?php

// use phpseclib\Crypt\RSA;
// use phpseclib\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\Formats\Keys\PSS;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Crypt\RSA\PublicKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Math\BigInteger;

use \setasign\Fpdf\Fpdf;
use \setasign\Fpdi\Fpdi;

// require_once('fpdf/fpdf.php');
// require_once('fpdi2/src/autoload.php');


defined('BASEPATH') or exit('No direct script access allowed');


class Signature extends CI_Controller
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
        $this->load->model('model_log');
        $this->load->model('model_pengajuan');
        $this->load->model('model_pengajuan_detail');
    }

    public function sign()
    {
        $id_pengajuan = $this->input->post('id');
        $pengajuan = $this->model_pengajuan->getBy(array('id_pengajuan' => $id_pengajuan))->row();
        $detail = $this->model_pengajuan_detail->getBy(array('id_pengajuan' => $id_pengajuan, 'id_pengesah' => $this->id_user))->row();
        $key = PublicKeyLoader::loadPrivateKey(file_get_contents('./assets/file/key/' . $pengajuan->private_key_pengajuan), $password = false);
        // $key2 = PublicKeyLoader::load(file_get_contents('./assets/file/key/' . $pengajuan->public_key_pengajuan), $password = false);
        $sign = base64_encode($key->sign($this->id_user . '_' . $id_pengajuan));
        $this->load->library('ciqrcode');

        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/'; //string, the default is application/cache/
        $config['errorlog']     = './assets/'; //string, the default is application/logs/
        $config['imagedir']     = './assets/file/images/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
        $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        $image_name = sha1($this->id_user) . '.png';

        $params['data'] = $sign;
        $params['level'] = 'M';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $image_name;
        $this->ciqrcode->generate($params);

        $data = array('digital_signature' => $sign, 'qr_code' => $image_name, 'status' => 1);
        $process = $this->model_pengajuan_detail->edit(array('id_pengajuan' => $id_pengajuan, 'id_pengesah' => $this->id_user), $data);
        $log = array(
            'nama_aktor_log' => $this->content['nama_akun_login'],
            'aksi_log' => 'User ' . $this->content['nama_akun_login'] . ' Melakukan Tanda Tangan pada pengajuan ' . $pengajuan->perihal_pengajuan,
        );
        $this->model_log->insert($log);
        $output = array();
        if ($process) {
            $output['cond'] = '1';
            $output['msg'] = "Berhasil menandatangani !";
        } else {
            $output['cond'] = '0';
            $output['msg'] = "Gagal menandatangani !";
        }

        echo json_encode($output);
        // var_dump($key2->verify('zzzz', $key->sign('zzzz')));

        // $this->twig->display('dashboard.html', $this->content);
    }

    // public function verify_signature()
    // {
    //     $signature = $this->input->post('signature');
    //     // $signature = "Nyzrer3R6wZtQfD8+qlB68XAS/AjBaV10+aTeaKW6rE51BvFaRwEVzGmYjV5BMze8fcHLLWB/z2S7Iw6ORAQVuRUAPFQC3wSLSCDxeaFIIhY/0A1Uq81N1tF9S3AvA3dpvKCUXqYT85J7inas2tz7ngF8p2CzdXtbWxJnejXlUuSJw/Dx8jT9fnjUdoIS98ikbTRs0C5F/R77iqk6BUOMmPLyHLK6rnJYa940gEu2tUVF59m9u3ENIsCZ3kIdfZrjqekHXtDNYspAaB6ttqSqZEosArnDTfy33JdL+e863Q2+gqkf7OQoop1eJoB/LDTAWduatwJegzgXUk+6wqV3w==";
    //     $detail = $this->model_pengajuan_detail->getBy(array('digital_signature' => $signature))->row();
    //     $pengajuan = $this->model_pengajuan->getBy(array('id_pengajuan' => $detail->id_pengajuan))->row();
    //     $key = PublicKeyLoader::loadPrivateKey(file_get_contents('./assets/file/key/' . $pengajuan->private_key_pengajuan), $password = false);
    //     $key2 = $key->getPublicKey();

    //     $decodeSign = base64_decode($signature);
    //     $output = array();
    //     $verify = $key2->verify($detail->id_pengesah . '_' . $detail->id_pengajuan, $decodeSign);
    //     if ($verify == 1) {
    //         $log = array(
    //             'nama_aktor_log' => $this->content['nama_akun_login'],
    //             'aksi_log' => 'User ' . $this->content['nama_akun_login'] . ' Melakukan verifikasi pada pengajuan ' . $pengajuan->perihal_pengajuan,
    //         );
    //         $data = array('status' => 2);
    //         $this->model_pengajuan_detail->edit(array('id_pengajuan_detail' => $detail->id_pengajuan_detail), $data);
    //         $this->model_log->insert($log);

    //         $allDetail = $this->model_pengajuan_detail->getBy(array('id_pengajuan' => $detail->id_pengajuan))->result();
    //         $countAll = count($allDetail);
    //         $countVerified = 0;
    //         foreach ($allDetail as $ad) {
    //             if ($ad->status == 2) {
    //                 $countVerified += 1;
    //             }
    //         }

    //         if ($countVerified == $countAll) {
    //             // panggil fungsi edit pdf dan edit tabel pengajuan untuk masukan pdf baru dan ganti status 
    //             $hasil = $this->creatingVerifiedFile($pengajuan->id_pengajuan, $pengajuan->nama_file_pengajuan);
    //             $arr = array('nama_file_verified_pengajuan' => $hasil['filename'], 'qr_pengajuan' => $hasil['name_qr'], 'status_pengajuan' => 2);
    //             $this->model_pengajuan->edit(array('id_pengajuan' => $pengajuan->id_pengajuan), $arr);
    //         }

    //         $output['status'] = 200;
    //         $output['verify'] = 'verified';
    //     } else {
    //         $output['status'] = 500;
    //         $output['verify'] = 'invalid';
    //     }
    //     echo json_encode($output);
    // }

    // public function creatingVerifiedFile($id_pengajuan, $nama_file)
    // {
    //     $crypt = base_url() . 'frontend/cekDokumen/' . urlencode(base64_encode($id_pengajuan));
    //     $this->load->library('ciqrcode');

    //     $config['cacheable']    = true; //boolean, the default is true
    //     $config['cachedir']     = './assets/'; //string, the default is application/cache/
    //     $config['errorlog']     = './assets/'; //string, the default is application/logs/
    //     $config['imagedir']     = './assets/file/qrPengajuan/'; //direktori penyimpanan qr code
    //     $config['quality']      = true; //boolean, the default is true
    //     $config['size']         = '1024'; //interger, the default is 1024
    //     $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
    //     $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
    //     $this->ciqrcode->initialize($config);

    //     $image_name = str_replace('.pdf', '_', $nama_file) . 'verified_' . date('d_m_Y') . '.png';

    //     $params['data'] = $crypt;
    //     $params['level'] = 'H';
    //     $params['size'] = 10;
    //     $params['savename'] = FCPATH . $config['imagedir'] . $image_name;
    //     $this->ciqrcode->generate($params);

    //     // initiate FPDI
    //     $pdf = new Fpdi();
    //     // add a page
    //     $pdf->AddPage();
    //     // set the source file
    //     $pageCount = $pdf->setSourceFile(FCPATH . "./assets/file/pengajuan/" . $nama_file);
    //     for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //         // import a page
    //         $templateId = $pdf->importPage($pageNo);

    //         // use the imported page and adjust the page size
    //         $pdf->useTemplate($templateId, ['adjustPageSize' => true]);
    //         $pdf->AddPage();
    //     }
    //     $pdf->Image(FCPATH . './assets/file/qrPengajuan/' . $image_name, 45, 20, 130);
    //     $pdf->SetFont('Helvetica');
    //     $pdf->SetXY(45, 5);
    //     $pdf->Write(8, 'Scan Bardcode dibawah untuk mengecek keabsahan dokumen anda ');
    //     $file_name = str_replace(".pdf", "_", $nama_file) . 'verified_' . date('d_m_Y') . '.pdf';
    //     $pdf->Output(FCPATH . './assets/file/dokumen_akhir/' . $file_name, 'F');
    //     $return = array(
    //         'filename' => $file_name,
    //         'name_qr' => $image_name
    //     );
    //     return $return;
    // }
}
