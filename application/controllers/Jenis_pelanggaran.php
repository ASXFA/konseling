<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_pelanggaran extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_jenis_pelanggaran');
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

	public function listJenispelanggaran()
	{
		$this->twig->display('main/jenis_pelanggaran.html',$this->content);
	}

    public function jenispelanggaranLists()
    {
        $jenis_pelanggaran = $this->model_jenis_pelanggaran->make_datatables();
        $data = array();
        if (!empty($jenis_pelanggaran)) {
            $no = 1;
            foreach($jenis_pelanggaran as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->kode_jenis_pelanggaran;
                $sub_data[] = $row->nama_jenis_pelanggaran;
                $sub_data[] = $row->kategori_jenis_pelanggaran;
                $sub_data[] = $row->poin_jenis_pelanggaran;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editJenispelanggaran' id='".$row->id_jenis_pelanggaran."' title='Edit Siswa'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteJenispelanggaran' id='".$row->id_jenis_pelanggaran."' title='Delete Siswa'><i class='fa fa-trash'></i></button>";
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

    public function jenispelanggaranById()
    {
        $id = $this->input->post('id_jenis_pelanggaran');
        $jenis_pelanggaran = $this->model_jenis_pelanggaran->getById($id);
        $output = array(
            'id_jenis_pelanggaran' => $jenis_pelanggaran->id_jenis_pelanggaran,
            'kode_jenis_pelanggaran' => $jenis_pelanggaran->kode_jenis_pelanggaran,
            'nama_jenis_pelanggaran' => $jenis_pelanggaran->nama_jenis_pelanggaran,
            'kategori_jenis_pelanggaran' => $jenis_pelanggaran->kategori_jenis_pelanggaran,
            'poin_jenis_pelanggaran' => $jenis_pelanggaran->poin_jenis_pelanggaran
        );
        echo json_encode($output);
    }

    public function doJenispelanggaran()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_jenis_pelanggaran');
        if ($operation == 'tambah') {
            $data = array(
                'kode_jenis_pelanggaran' => $this->input->post('kode_jenis_pelanggaran'),
                'nama_jenis_pelanggaran' => $this->input->post('nama_jenis_pelanggaran'),
                'kategori_jenis_pelanggaran' => $this->input->post('kategori_jenis_pelanggaran'),
                'poin_jenis_pelanggaran' => $this->input->post('poin_jenis_pelanggaran')
            );
            $process = $this->model_jenis_pelanggaran->tambahJenispelanggaran($data);
        }else if($operation == 'edit'){
            $data = array(
                'kode_jenis_pelanggaran' => $this->input->post('kode_jenis_pelanggaran'),
                'nama_jenis_pelanggaran' => $this->input->post('nama_jenis_pelanggaran'),
                'kategori_jenis_pelanggaran' => $this->input->post('kategori_jenis_pelanggaran'),
                'poin_jenis_pelanggaran' => $this->input->post('poin_jenis_pelanggaran')
            );
            $process = $this->model_jenis_pelanggaran->editJenispelanggaran($data,$id);
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

    public function deleteJenispelanggaran()
    {
        $id = $this->input->post('id_jenis_pelanggaran');
        $process = $this->model_jenis_pelanggaran->deleteJenispelanggaran($id);
        echo json_encode($process);
    }
}