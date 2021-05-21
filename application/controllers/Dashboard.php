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
        if ($this->role == 1 || $this->role == 3) {
            $this->load->model('model_guru');
            $data = $this->model_guru->getByInduk($this->induk);
            $this->id = $data->id_guru;
            $this->nama = $data->nama_guru;
            $this->load->model('model_jabatan');
            $jabatan = $this->model_jabatan->getById($data->jabatan_guru);
            $this->jabatan = $jabatan->nama_jabatan;
            $this->telp = $data->telp_guru;
        }else if($this->role == 3){
            // $this->load->model('model_siswa');
            // $data = $this->model_siswa->getByInduk($user->induk_akun);
            // $session = array(
            //     'isLogin' => 1,
            //     'id_user' => $data->id_siswa,
            //     'induk_user' => $data->induk_siswa
            // );
        }
        $this->content = array(
            'base_url'=>base_url(),
            'id_user' => $this->id,
            'induk_user_login' => $this->induk,
            'nama_user_login' => $this->nama,
            'jabatan_user_login' => $this->jabatan,
            'telp_user_login' => $this->telp
        );
	}

	public function index()
	{
		$this->twig->display('main/dashboard.html',$this->content);
	}
}
