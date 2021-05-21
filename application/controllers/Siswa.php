<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_siswa');
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
                'nama_user_login' => $this->nama,
                'jabatan_user_login' => $this->jabatan,
                'telp_user_login' => $this->telp,
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

	public function listSiswa()
	{
		$this->twig->display('main/siswa.html',$this->content);
	}

    public function siswaLists()
    {
        $siswa = $this->model_siswa->make_datatables();
        $data = array();
        if (!empty($siswa)) {
            $no = 1;
            foreach($siswa as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->induk_siswa;
                $sub_data[] = $row->nama_siswa;
                $sub_data[] = $row->jk_siswa;
                $sub_data[] = $row->alamat_siswa;
                $sub_data[] = $row->ortu_siswa;
                $sub_data[] = "<button class='btn btn-info btn-sm mr-2 cekAkun' id='".$row->induk_siswa."' title='cek akun'><i class='fa fa-eye'></i></button><button class='btn btn-warning btn-sm mr-2 editSiswa' id='".$row->induk_siswa."' title='Edit Siswa'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteSiswa' id='".$row->induk_siswa."' title='Delete Siswa'><i class='fa fa-trash'></i></button>";
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

    public function siswaByInduk()
    {
        $induk = $this->input->post('induk');
        $siswa = $this->model_siswa->getByInduk($induk);
        $output = array(
            'id_siswa' => $siswa->id_siswa,
            'induk_siswa' => $siswa->induk_siswa,
            'nama_siswa' => $siswa->nama_siswa,
            'alamat_siswa' => $siswa->alamat_siswa,
            'jk_siswa' => $siswa->jk_siswa,
            'ortu_siswa' => $siswa->ortu_siswa
        );
        echo json_encode($output);
    }

    public function doSiswa()
    {
        $this->load->model('model_akun');
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_siswa');
        if ($operation == 'tambah') {
            $data = array(
                'induk_siswa' => $this->input->post('induk_siswa'),
                'nama_siswa' => $this->input->post('nama_siswa'),
                'jk_siswa' => $this->input->post('jk_siswa'),
                'alamat_siswa' => $this->input->post('alamat_siswa'),
                'ortu_siswa' => $this->input->post('ortu_siswa')
            );
            $data2 = array(
                'username_akun' => $this->input->post('induk_siswa'),
                'password_akun' => password_hash($this->input->post('induk_siswa'),PASSWORD_DEFAULT),
                'induk_akun' => $this->input->post('induk_siswa'),
                'role_akun' => $this->input->post('jabatan_siswa'),
                'status_akun' => 0
            );
            $process1 = $this->model_siswa->tambahSiswa($data);
            $process2 = $this->model_akun->tambahAkun($data2);
        }else if($operation == 'edit'){
            $data = array(
                'induk_siswa' => $this->input->post('induk_siswa'),
                'nama_siswa' => $this->input->post('nama_siswa'),
                'jk_siswa' => $this->input->post('jk_siswa'),
                'alamat_siswa' => $this->input->post('alamat_siswa'),
                'ortu_siswa' => $this->input->post('ortu_siswa')
            );
            $process1 = $this->model_siswa->editSiswa($data,$id);
            $data2 = array('induk_akun' => $this->input->post('induk_siswa'));
            $process2 = $this->model_akun->editAkun($data2,$this->input->post('old_induk'));
        }
        if ($process1 && $process2) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else {
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }

    public function deleteSiswa()
    {
        $induk = $this->input->post('induk');
        $process1 = $this->model_siswa->deleteSiswa($induk);
        $this->load->model('model_akun');
        $process2 = $this->model_akun->deleteByInduk($induk);
        echo json_encode($process2);
    }
}