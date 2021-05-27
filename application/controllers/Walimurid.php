<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walimurid extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_walimurid');
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

	public function listWalimurid()
	{
        $this->load->model('model_siswa');
        $this->content['siswa'] = $this->model_siswa->getAll()->result();
		$this->twig->display('main/walimurid.html',$this->content);
	}

    public function walimuridLists()
    {
        $walimurid = $this->model_walimurid->make_datatables();
        $data = array();
        if (!empty($walimurid)) {
            $no = 1;
            foreach($walimurid as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_walimurid;
                $sub_data[] = $row->induk_siswa_walimurid;
                $sub_data[] = $row->telp_walimurid;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editWalimurid' id='".$row->id_walimurid."' title='Edit Walimurid'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteWalimurid' id='".$row->id_walimurid."' title='Delete Siswa'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_walimurid->get_all_data(),
            'recordsFiltered' => $this->model_walimurid->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function walimuridById()
    {
        $id = $this->input->post('id_walimurid');
        $walimurid = $this->model_walimurid->getById($id);
        $output = array(
            'id_walimurid' => $walimurid->id_walimurid,
            'nama_walimurid' => $walimurid->nama_walimurid,
            'induk_siswa_walimurid' => $walimurid->induk_siswa_walimurid,
            'telp_walimurid' => $walimurid->telp_walimurid
        );
        echo json_encode($output);
    }

    public function doWalimurid()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_walimurid');
        if ($operation == 'tambah') {
            $data = array(
                'nama_walimurid' => $this->input->post('nama_walimurid'),
                'induk_siswa_walimurid' => $this->input->post('induk_siswa_walimurid'),
                'telp_walimurid' => $this->input->post('telp_walimurid')
            );
            $process = $this->model_walimurid->tambahWalimurid($data);
        }else if($operation == 'edit'){
            $data = array(
                'nama_walimurid' => $this->input->post('nama_walimurid'),
                'induk_siswa_walimurid' => $this->input->post('induk_siswa_walimurid'),
                'telp_walimurid' => $this->input->post('telp_walimurid')
            );
            $process = $this->model_walimurid->editWalimurid($data,$id);
        }
        if ($process) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else {
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }

    public function deleteWalimurid()
    {
        $id = $this->input->post('id_walimurid');
        $process = $this->model_walimurid->deleteWalimurid($id);
        echo json_encode($process);
    }
}