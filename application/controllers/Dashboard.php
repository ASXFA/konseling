<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        // $this->load->model('model_walimurid');
        if ($this->role == 1 || $this->role == 3) {
            $this->load->model('model_guru');
            $data = $this->model_guru->getByInduk($this->induk);
            $this->id = $data->id_guru;
            $this->nama = $data->nama_guru;
            $this->foto = $data->foto_guru;
            $this->load->model('model_jabatan');
            $jabatan = $this->model_jabatan->getById($data->jabatan_guru);
            $this->jabatan = $jabatan->nama_jabatan;
            $this->telp = $data->telp_guru;
            $this->content = array(
                'base_url'=>base_url(),
                'id_user' => $this->id,
                'induk_user_login' => $this->induk,
                'role_user_login' => $this->role,
                'nama_user_login' => $this->nama,
                'jabatan_user_login' => $this->jabatan,
                'telp_user_login' => $this->telp,
                'foto_user_login' => $this->foto
            );
        }else if($this->role == 3){
            $this->load->model('model_siswa');
            $data = $this->model_siswa->getByInduk($this->induk);
            $this->id = $data->id_siswa;
            $this->nama = $data->nama_siswa;
            $this->jabatan = 'siswa';
            // $this->telp = $data->telp_guru;
            $this->alamat = $data->alamat_siswa;
            $this->ortu = $data->ortu_siswa;
            $this->content = array(
                'base_url'=>base_url(),
                'id_user' => $this->id,
                'induk_user_login' => $this->induk,
                'nama_user_login' => $this->nama,
                'role_user_login' => $this->role,
                'jabatan_user_login' => $this->jabatan,
                'alamat_user_login' => $this->alamat,
                'ortu_user_login' => $this->ortu
            );
        }
	}

	public function index()
	{
        $this->load->model('model_siswa');
        $this->load->model('model_pelanggaran');
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_catatan_kasus');
        $this->content['siswa'] = $this->model_siswa->get_all_data();
        $this->content['pelanggaran'] = $this->model_pelanggaran->get_all_data();
        $this->content['catatan'] = $this->model_catatan_kasus->get_all_data();
        $this->content['pelanggaran_table'] = $this->model_pelanggaran->getAllByStatus(0)->result();
        $this->content['siswa_table'] = $this->model_siswa->getAll()->result();
        $this->content['jp_table'] = $this->model_jenis_pelanggaran->getAll()->result();
		$this->twig->display('main/dashboard.html',$this->content);
	}

    public function myProfile()
    {
        if ($this->role == 3) {
            $this->load->model('model_siswa');
        }else{
            $this->load->model('model_guru');
            $this->load->model('model_jabatan');
            $this->load->model('model_akun');
            $guru = $this->model_guru->getById($this->id);
            $this->content['nama_user'] = $guru->nama_guru;
            $this->content['induk_user'] = $guru->induk_guru;
            $this->content['telp_user'] = $guru->telp_guru;
            $jabatan = $this->model_jabatan->getById($guru->jabatan_guru);
            $this->content['jabatan_user'] = $jabatan->nama_jabatan;
            $akun = $this->model_akun->getByInduk($guru->induk_guru);
            $this->content['username_user'] = $akun->username_akun;
            $this->twig->display('main/myProfile.html',$this->content);
        }
    }

    public function editProfil()
    {
        $this->load->model('model_akun');
        $induk = $this->input->post('induk_user_edit');
        if ($this->role == 3) {
            # code...
        }else{
            $data = array(
                'nama_guru' => $this->input->post('nama_user_edit'),
                'telp_guru' => $this->input->post('telp_user_edit')
            );
            $data2 = array('username_akun' => $this->input->post('username_user_edit'));
            $process = $this->model_guru->editGuruByInduk($data,$induk);
            $process2 = $this->model_akun->editAkun($data2,$induk);
        }
        if ($process && $process2) {
            $output=array('cond'=>'1');
        }else{
            $output=array('cond'=>'0');
        }
        echo json_encode($output);
    }

    public function editFoto()
    {
        $induk = $this->input->post('induk_user_foto');
        if ($this->role != 3) {
            $config['upload_path']          = './assets/img-profil/guru/';
        }else{
            $config['upload_path']          = './assets/img-profil/siswa/';
        }
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $config);
        $operation = $this->input->post('operation');
        $pesan = array();
        if ( ! $this->upload->do_upload('inputFile')){
            $error = array('error' => $this->upload->display_errors());
            $pesan['cond'] = '0';
            $pesan['msg'] = $error;
        }else{
            $foto = array('upload_data'=> $this->upload->data());
            $image = $foto['upload_data']['file_name'];
            if($this->role != 3){
                $this->load->model('model_guru');
                $data = array('foto_guru'=>$image);
                $process = $this->model_guru->editGuruByInduk($data,$induk);
            }else{
                $this->load->model('model_siswa');
                $data = array('foto_siswa'=>$image);
                $process = $this->model_guru->editSiswaByInduk($data,$induk);
            }
        }
        if ($process) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else{
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }
}
