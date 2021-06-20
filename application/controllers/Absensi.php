<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_absensi');
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

	public function listAbsensi()
	{
		$this->twig->display('main/absensi.html',$this->content);
	}

    public function absensiLists()
    {
        $this->load->model('model_param_kehadiran');
        $this->load->model('model_siswa');
        $absesi = $this->model_absensi->make_datatables();
        $data = array();
        if (!empty($absesi)) {
            $no = 1;
            foreach($absesi as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $siswa = $this->model_siswa->getById($row->id_siswa_absensi);
                $sub_data[] = $siswa->induk_siswa;
                $sub_data[] = $siswa->nama_siswa;
                if (!empty($row->nilai_absensi)) {
                    $sub_data[] = $row->nilai_absensi;
                    $param = $this->model_param_kehadiran->getById($row->id_param_kehadiran_absensi);
                    $sub_data[] = $param->keterangan_param_kehadiran;
                }else{
                    $sub_data[] = "-";
                    $sub_data[] = "-";
                }
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 beriNilai' id='".$row->id_absensi."' title='Beri Nilai'><i class='fa fa-edit'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_absensi->get_all_data(),
            'recordsFiltered' => $this->model_absensi->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function absensiById()
    {
        $id = $this->input->post('id_absensi');
        $absesi = $this->model_absensi->getById($id);
        $output = array(
            'id_absensi' => $absesi->id_absensi,
            'nilai_absensi' => $absesi->nilai_absensi,
        );
        echo json_encode($output);
    }

    public function doAbsensi()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_absensi');
        $nilai = $this->input->post('nilai_absensi');
        if($operation == 'edit'){
            $this->load->model('model_param_kehadiran');
            $param = $this->model_param_kehadiran->getAll();
            $no = 1;
            foreach($param as $p)
            {
                if($no != 3){
                    if ($nilai >= $p->nilai_max_param_kehadiran) {
                        $data = array(
                            'nilai_absensi' => $this->input->post('nilai_absensi'),
                            'id_param_kehadiran_absensi' => $p->id_param_kehadiran
                        );
                        break;
                    }
                }else{
                    if ($nilai <= $p->nilai_max_param_kehadiran) {
                        $data = array(
                            'nilai_absensi' => $this->input->post('nilai_absensi'),
                            'id_param_kehadiran_absensi' => $p->id_param_kehadiran
                        );
                    }
                }
                $no += 1;
            }
            $proc = $this->model_absensi->editAbsensi($data,$id);
        }
        if ($proc) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else {
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }
}