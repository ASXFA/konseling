<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_akun');
        if ($this->role == 1 || $this->role == 3) {
            $this->load->model('model_guru');
            $data = $this->model_guru->getByInduk($this->induk);
            $this->id = $data->id_guru;
            $this->nama = $data->nama_guru;
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
                'telp_user_login' => $this->telp
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
                'jabatan_user_login' => $this->jabatan,
                'alamat_user_login' => $this->alamat,
                'ortu_user_login' => $this->ortu
            );
        }
	}

	public function listAkun()
	{
		$this->twig->display('main/akun.html',$this->content);
	}

    public function akunLists()
    {
        $akun = $this->model_akun->make_datatables();
        $data = array();
        if (!empty($akun)) {
            $no = 1;
            foreach($akun as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->induk_akun;
                $sub_data[] = $row->username_akun;
                if ($row->role_akun == 1) {
                    $sub_data[] = "<span class='badge badge-secondary'> Guru BK</span>";
                }else if($row->role_akun == 2){
                    $sub_data[] = "<span class='badge badge-secondary'> Guru</span>";
                }else{
                    $sub_data[] = "<span class='badge badge-secondary'> Siswa</span>";
                }
                if ($row->status_akun == 1) {
                    $sub_data[] = "<span class='badge badge-success'> Aktif </span>";
                    $sub_data[] = "<button class='btn btn-danger btn-sm mr-2 setStatus' id='".$row->id_akun."' data-status='0' title='Aktifkan Akun'><i class='fa fa-times'></i></button><button class='btn btn-warning btn-sm mr-2 resetPass' id='".$row->id_akun."' data-induk='".$row->induk_akun."' title='Reset Password'><i class='fa fa-recycle'></i></button>";
                }else {
                    $sub_data[] = "<span class='badge badge-danger'> Nonaktif </span>";
                    $sub_data[] = "<button class='btn btn-success btn-sm mr-2 setStatus' id='".$row->id_akun."' data-status='1' title='Aktifkan Akun'><i class='fa fa-check'></i></button><button class='btn btn-warning btn-sm mr-2 resetPass' id='".$row->id_akun."' data-induk='".$row->induk_akun."' title='Reset Password'><i class='fa fa-recycle'></i></button>";
                }
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_guru->get_all_data(),
            'recordsFiltered' => $this->model_guru->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function setStatus()
    {
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        $pesan = array();
        $process = $this->model_akun->setStatus($id,$status);
        if ($process) {
            $pesan['cond'] = '1';
        }else{
            $pesan['cond'] = '2';
        }
        echo json_encode($pesan);
    }

    public function resetPass()
    {
        $id = $this->input->post('id');
        $pass = password_hash($this->input->post('induk'),PASSWORD_DEFAULT);
        $array = array('password_akun' => $pass);
        $pesan = array();
        $process = $this->model_akun->resetPass($id,$array);
        if ($process) {
            $pesan['cond'] = "1";
        }else{
            $pesan['cond'] = '0';
        }
        echo json_encode($pesan);
    }

}