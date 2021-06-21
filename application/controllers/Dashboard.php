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
        if ($this->role == 1 || $this->role == 2) {
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
            if ($this->role == 2) {
                $this->load->model('model_kelas');
                $kelas = $this->model_kelas->getByIdGuru($this->id);
                $this->content['id_kelas'] = $kelas->id_kelas;
            }

        }else if($this->role == 3){
            $this->load->model('model_siswa');
            $data = $this->model_siswa->getByInduk($this->induk);
            $this->id = $data->id_siswa;
            $this->nama = $data->nama_siswa;
            $this->jabatan = 'siswa';
            // $this->telp = $data->telp_guru;
            $this->alamat = $data->alamat_siswa;
            $this->id_kelas = $data->id_kelas_siswa;
            $this->poin = $data->poin_siswa;
            $this->status_sanksi = $data->status_sanksi_siswa;
            $this->id_param_poin_siswa = $data->id_param_poin_siswa;
            $this->foto = $data->foto_siswa;
            $this->content = array(
                'base_url'=>base_url(),
                'id_user' => $this->id,
                'induk_user_login' => $this->induk,
                'nama_user_login' => $this->nama,
                'role_user_login' => $this->role,
                'jabatan_user_login' => $this->jabatan,
                'alamat_user_login' => $this->alamat,
                'id_kelas_login' => $this->id_kelas,
                'poin_user_login' => $this->poin,
                'status_sanksi_login' => $this->status_sanksi,
                'id_param_poin_login' => $this->id_param_poin_siswa,
                'foto_user_login' => $this->foto
            );
        }
	}

	public function index()
	{
        $this->load->model('model_siswa');
        $this->load->model('model_pelanggaran');
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_catatan_kasus');
        if ($this->role == 1) {
            $this->content['siswa'] = $this->model_siswa->get_all_data();
            $this->content['pelanggaran'] = $this->model_pelanggaran->get_all_data();
            $this->content['catatan'] = $this->model_catatan_kasus->get_all_data();
            $this->content['pelanggaran_table'] = $this->model_pelanggaran->getAllByStatus(0)->result();
            $this->content['siswa_table'] = $this->model_siswa->getAll()->result();
            $this->content['jp_table'] = $this->model_jenis_pelanggaran->getAll()->result();
        }else if($this->role == 2){
            $this->content['siswa'] = $this->model_siswa->getByIdKelas($this->content['id_kelas'])->result();
            $this->content['pelanggaran'] = $this->model_pelanggaran->getAll()->result();
            $this->content['catatan'] = $this->model_catatan_kasus->getAll()->result();
            $this->content['jp_table'] = $this->model_jenis_pelanggaran->getAll()->result();
            $siswa = $this->model_siswa->getByIdKelas($this->content['id_kelas'])->result();
            $pelanggaran = $this->model_pelanggaran->getAll()->result();
            $catatan = $this->model_catatan_kasus->getAll()->result();
            $arrP = array();
            foreach($pelanggaran as $p){
                foreach($siswa as $s){
                    if ($s->induk_siswa == $p->induk_siswa_pelanggaran) {
                        array_push($arrP,$p->id_pelanggaran);
                    }
                }
            }
            $arrC = array();
            foreach($catatan as $c){
                for($i=0; $i<count($arrP); $i++){
                    if ($arrP[$i] == $c->id_pelanggaran_catatan_kasus) {
                        array_push($arrC,$c->id_catatan_kasus);
                    }
                }
            }
            $this->content['hasilP'] = $arrP;
            $this->content['hasilC'] = $arrC;
        }else if($this->role == 3){
            $this->load->model('model_sanksi');
            $this->content['jp_table'] = $this->model_jenis_pelanggaran->getAll()->result();
            $this->content['pelanggaran'] = $this->model_pelanggaran->getAllByInduk($this->induk)->result();
            $this->content['sanksi'] = $this->model_sanksi->getAll();
        }
		$this->twig->display('main/dashboard.html',$this->content);
	}

    public function myProfile()
    {
        if ($this->role == 3) {
            $this->load->model('model_siswa');
            $this->load->model('model_jabatan');
            $this->load->model('model_akun');
            $siswa = $this->model_siswa->getById($this->id);
            $this->content['nama_user'] = $siswa->nama_siswa;
            $this->content['induk_user'] = $siswa->induk_siswa;
            $this->content['jk_user'] = $siswa->jk_siswa;
            $this->content['alamat_user'] = $siswa->alamat_siswa;
            $this->content['foto_user'] = $siswa->foto_siswa;
            $this->content['telp_user'] = "-";
            $this->content['jabatan_user'] = "-";
            $akun = $this->model_akun->getByInduk($siswa->induk_siswa);
            $this->content['username_user'] = $akun->username_akun;
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
        }
        $this->twig->display('main/myProfile.html',$this->content);
    }

    public function editProfil()
    {
        $this->load->model('model_akun');
        $induk = $this->input->post('induk_user_edit');
        if ($this->role == 3) {
            $data = array(
                'nama_siswa' => $this->input->post('nama_user_edit'),
                'jk_siswa' => $this->input->post('jk_user_edit'),
                'alamat_siswa' => $this->input->post('alamat_user_edit'),
            );
            $data2 = array('username_akun' => $this->input->post('username_user_edit'));
            $process = $this->model_siswa->editByIndukSiswa($data,$induk);
            $process2 = $this->model_akun->editAkun($data2,$induk);
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
                $process = $this->model_siswa->editByIndukSiswa($data,$induk);
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
