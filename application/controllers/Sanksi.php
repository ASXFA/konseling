<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sanksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_sanksi');
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

	public function listSanksi()
	{
		$this->twig->display('main/sanksi.html',$this->content);
	}

    public function sanksiLists()
    {
        $sanksi = $this->model_sanksi->make_datatables();
        $data = array();
        if (!empty($sanksi)) {
            $no = 1;
            foreach($sanksi as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_sanksi;
                $sub_data[] = $row->jumlah_poin_sanksi;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editSanksi' id='".$row->id_sanksi."' title='Edit Sanksi'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteSanksi' id='".$row->id_sanksi."' title='Delete Sanksi'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_sanksi->get_all_data(),
            'recordsFiltered' => $this->model_sanksi->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function sanksiById()
    {
        $id = $this->input->post('id_sanksi');
        $sanksi = $this->model_sanksi->getById($id);
        $output = array(
            'id_sanksi' => $sanksi->id_sanksi,
            'nama_sanksi' => $sanksi->nama_sanksi,
            'jumlah_poin_sanksi' => $sanksi->jumlah_poin_sanksi
        );
        echo json_encode($output);
    }

    public function doSanksi()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_sanksi');
        if ($operation == 'tambah') {
            $data = array(
                'nama_sanksi' => $this->input->post('nama_sanksi'),
                'jumlah_poin_sanksi' => $this->input->post('jumlah_poin_sanksi')
            );
            $process = $this->model_sanksi->tambahSanksi($data);
        }else if($operation == 'edit'){
            $data = array(
                'nama_sanksi' => $this->input->post('nama_sanksi'),
                'jumlah_poin_sanksi' => $this->input->post('jumlah_poin_sanksi')
            );
            $process = $this->model_sanksi->editSanksi($data,$id);
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

    public function deleteSanksi()
    {
        $id = $this->input->post('id_sanksi');
        $process = $this->model_sanksi->deleteSanksi($id);
        echo json_encode($process);
    }
}