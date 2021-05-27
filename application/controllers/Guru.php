<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_guru');
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

	public function listGuru()
	{
		$this->twig->display('main/guru.html',$this->content);
	}

    public function guruLists()
    {
        $guru = $this->model_guru->make_datatables();
        $data = array();
        if (!empty($guru)) {
            $no = 1;
            foreach($guru as $row){
                if ($this->induk != $row->induk_guru) {
                    $sub_data = array();
                    $sub_data[] = $no;
                    $sub_data[] = $row->induk_guru;
                    $sub_data[] = $row->nama_guru;
                    $sub_data[] = $row->jabatan_guru;
                    $sub_data[] = $row->telp_guru;
                    $sub_data[] = "<button class='btn btn-info btn-sm mr-2 cekAkun' id='".$row->induk_guru."' title='cek akun'><i class='fa fa-eye'></i></button><button class='btn btn-warning btn-sm mr-2 editGuru' id='".$row->induk_guru."' title='Edit Guru'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteGuru' id='".$row->induk_guru."' title='Delete Guru'><i class='fa fa-trash'></i></button>";
                    $data[] = $sub_data;
                    $no++;
                }
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

    public function guruByInduk()
    {
        $induk = $this->input->post('induk');
        $guru = $this->model_guru->getByInduk($induk);
        $output = array(
            'id_guru' => $guru->id_guru,
            'induk_guru' => $guru->induk_guru,
            'nama_guru' => $guru->nama_guru,
            'jabatan_guru' => $guru->jabatan_guru,
            'telp_guru' => $guru->telp_guru
        );
        echo json_encode($output);
    }

    public function doGuru()
    {
        $this->load->model('model_akun');
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_guru');
        if ($operation == 'tambah') {
            $data = array(
                'induk_guru' => $this->input->post('induk_guru'),
                'nama_guru' => $this->input->post('nama_guru'),
                'jabatan_guru' => $this->input->post('jabatan_guru'),
                'telp_guru' => $this->input->post('telp_guru')
            );
            $data2 = array(
                'username_akun' => $this->input->post('induk_guru'),
                'password_akun' => password_hash($this->input->post('induk_guru'),PASSWORD_DEFAULT),
                'induk_akun' => $this->input->post('induk_guru'),
                'role_akun' => $this->input->post('jabatan_guru'),
                'status_akun' => 0
            );
            $process1 = $this->model_guru->tambahguru($data);
            $process2 = $this->model_akun->tambahAkun($data2);
        }else if($operation == 'edit'){
            $data = array(
                'induk_guru' => $this->input->post('induk_guru'),
                'nama_guru' => $this->input->post('nama_guru'),
                'jabatan_guru' => $this->input->post('jabatan_guru'),
                'telp_guru' => $this->input->post('telp_guru')
            );
            $process1 = $this->model_guru->editGuru($data,$id);
            $data2 = array('induk_akun' => $this->input->post('induk_guru'));
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

    public function getDetailObjek()
    {
        $id_objek = $this->input->post('id_objek');
        $objek = $this->model_objek_wisata->getById($id_objek);
        $this->load->model('model_alternatif');
        $alternatif = $this->model_alternatif->getByObjekDetail($id_objek);
        $output = array();
        $output['objek'] = $objek;
        $output['alternatif'] = $alternatif;
        echo json_encode($output);
    }

    public function doObjek()
    {
        $config['upload_path']          = './assets/back/img/objek_wisata/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $config);
        $operation = $this->input->post('operation');
        $pesan = array();
        if ( ! $this->upload->do_upload('inputFile')){
            $error = array('error' => $this->upload->display_errors());
            if ($operation == "Tambah") {
                $data = array(
                    'nama_objek' => $this->input->post('nama_objek'),
                    'link_rute_objek'=> $this->input->post('link_rute_objek'),
                    'harga_objek' => $this->input->post('harga_objek'),
                    'waktu_objek' => $this->input->post('waktu_objek'),
                    'popularitas_objek' => $this->input->post('popularitas_objek'),
                    'pengunjung_objek' => $this->input->post('pengunjung_objek'),
                    'jarak_objek' => $this->input->post('jarak_objek'),
                    'keterangan_objek' => $this->input->post('keterangan_objek'),
                    'foto' => 'noimage.png',
                    'created_by' => $this->nama
                );
                $process = $this->model_objek_wisata->tambahObjek($data);
                $pesan = array('id_objek'=>$process,'cond'=>1);
            }else{
                $id = $this->input->post('id_objek');
                $data = array(
                    'nama_objek' => $this->input->post('nama_objek'),
                    'link_rute_objek'=> $this->input->post('link_rute_objek'),
                    'harga_objek' => $this->input->post('harga_objek'),
                    'waktu_objek' => $this->input->post('waktu_objek'),
                    'popularitas_objek' => $this->input->post('popularitas_objek'),
                    'pengunjung_objek' => $this->input->post('pengunjung_objek'),
                    'jarak_objek' => $this->input->post('jarak_objek'),
                    'keterangan_objek' => $this->input->post('keterangan_objek'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->nama
                );
                $process = $this->model_objek_wisata->editObjek($id,$data);
                $pesan = array('cond'=>2,'asup'=>$error);
            }
        }else{
            $foto = array('upload_data'=> $this->upload->data());
            $image = $foto['upload_data']['file_name'];

            if ($operation == "Tambah") {
                $data = array(
                    'nama_objek' => $this->input->post('nama_objek'),
                    'link_rute_objek'=> $this->input->post('link_rute_objek'),
                    'harga_objek' => $this->input->post('harga_objek'),
                    'waktu_objek' => $this->input->post('waktu_objek'),
                    'popularitas_objek' => $this->input->post('popularitas_objek'),
                    'pengunjung_objek' => $this->input->post('pengunjung_objek'),
                    'jarak_objek' => $this->input->post('jarak_objek'),
                    'keterangan_objek' => $this->input->post('keterangan_objek'),
                    'foto' => $image,
                    'created_by' => $this->nama
                );
                $process = $this->model_objek_wisata->tambahObjek($data);
                $pesan = array('id_objek'=>$process,'cond'=>1);
            }else{
                $id = $this->input->post('id_objek');
                $data = array(
                    'nama_objek' => $this->input->post('nama_objek'),
                    'link_rute_objek'=> $this->input->post('link_rute_objek'),
                    'harga_objek' => $this->input->post('harga_objek'),
                    'waktu_objek' => $this->input->post('waktu_objek'),
                    'popularitas_objek' => $this->input->post('popularitas_objek'),
                    'pengunjung_objek' => $this->input->post('pengunjung_objek'),
                    'jarak_objek' => $this->input->post('jarak_objek'),
                    'keterangan_objek' => $this->input->post('keterangan_objek'),
                    'foto' => $image,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->nama
                );
                $process = $this->model_objek_wisata->editObjek($id,$data);
                $pesan = array('cond'=>2,'asup'=>'bener da');
            }
        }
        echo json_encode($pesan);
    }

    public function deleteGuru()
    {
        $induk = $this->input->post('induk');
        $process1 = $this->model_guru->deleteGuru($induk);
        $this->load->model('model_akun');
        $process2 = $this->model_akun->deleteByInduk($induk);
        echo json_encode($process2);
    }
}
