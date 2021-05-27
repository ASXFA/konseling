<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_kelas');
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

	public function listKelas()
	{
        $this->load->model('model_guru');
        $this->content['guru'] = $this->model_guru->getAll()->result();
		$this->twig->display('main/kelas.html',$this->content);
	}

    public function kelasLists()
    {
        $this->load->model('model_guru');
        $kelas = $this->model_kelas->make_datatables();
        $data = array();
        if (!empty($kelas)) {
            $no = 1;
            foreach($kelas as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->kode_kelas;
                $sub_data[] = $row->nama_kelas;
                $sub_data[] = $row->keterangan_kelas;
                $guru = $this->model_guru->getById($row->id_guru_kelas);
                if (!empty($guru)) {
                    $sub_data[] = $guru->nama_guru;
                }else{
                    $sub_data[] = "Guru Tidak ada !";
                }
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editKelas' id='".$row->id_kelas."' title='Edit Siswa'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteKelas' id='".$row->id_kelas."' title='Delete Siswa'><i class='fa fa-trash'></i></button>";
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

    public function kelasById()
    {
        $id = $this->input->post('id_kelas');
        $kelas = $this->model_kelas->getById($id);
        $output = array(
            'id_kelas' => $kelas->id_kelas,
            'kode_kelas' => $kelas->kode_kelas,
            'nama_kelas' => $kelas->nama_kelas,
            'keterangan_kelas' => $kelas->keterangan_kelas,
            'id_guru_kelas' => $kelas->id_guru_kelas
        );
        echo json_encode($output);
    }

    public function doKelas()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_kelas');
        if ($operation == 'tambah') {
            $data = array(
                'kode_kelas' => $this->input->post('kode_kelas'),
                'nama_kelas' => $this->input->post('nama_kelas'),
                'keterangan_kelas' => $this->input->post('keterangan_kelas'),
                'id_guru_kelas' => $this->input->post('id_guru_kelas')
            );
            $process = $this->model_kelas->tambahKelas($data);
        }else if($operation == 'edit'){
            $data = array(
                'kode_kelas' => $this->input->post('kode_kelas'),
                'nama_kelas' => $this->input->post('nama_kelas'),
                'keterangan_kelas' => $this->input->post('keterangan_kelas'),
                'id_guru_kelas' => $this->input->post('id_guru_kelas')
            );
            $process = $this->model_kelas->editKelas($data,$id);
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

    public function deletekelas()
    {
        $id = $this->input->post('id_kelas');
        $process = $this->model_kelas->deleteKelas($id);
        echo json_encode($process);
    }
}