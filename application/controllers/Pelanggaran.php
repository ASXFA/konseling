<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggaran extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_pelanggaran');
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

	public function listPelanggaran()
	{
        $this->load->model('model_siswa');
        $this->load->model('model_jenis_pelanggaran');
        $this->content['siswa'] = $this->model_siswa->getAll()->result();
        $this->content['jenis_pelanggaran'] = $this->model_jenis_pelanggaran->getAll()->result();
		$this->twig->display('main/pelanggaran.html',$this->content);
	}

    public function getKodePelanggaran()
    {
        $output = array();
        $cek = $this->model_pelanggaran->cekkode();
        if (!empty($cek)) {
            $nourut = substr($cek, 4, 4);
            $berikutnya = $nourut+1;
            if ($berikutnya < 10) {
                $output['kode'] = "PSBK000".strval($berikutnya);
            }else if($berikutnya > 9){
                $output['kode'] = "PSBK00".strval($berikutnya);
            }else if($berikutnya > 99){
                $output['kode'] = "PSBK0".strval($berikutnya);
            }else{
                $output['kode'] = "PSBK".strval($berikutnya);
            }
        }else{
            $output['kode'] = "PSBK0001";
        }
        echo json_encode($output);
    }

    public function pelanggaranLists()
    {
        $this->load->model('model_siswa');
        $this->load->model('model_jenis_pelanggaran');
        $pelanggaran = $this->model_pelanggaran->make_datatables();
        $data = array();
        if (!empty($pelanggaran)) {
            $no = 1;
            foreach($pelanggaran as $row){
                if ($this->role == 1) {
                    $sub_data = array();
                    $sub_data[] = $no;
                    $sub_data[] = $row->kode_pelanggaran;
                    $sub_data[] = $row->induk_siswa_pelanggaran;
                    $siswa = $this->model_siswa->getByInduk($row->induk_siswa_pelanggaran);
                    $sub_data[] = $siswa->nama_siswa;
                    $sub_data[] = date('d - m - Y',strtotime($row->tanggal_pelanggaran));
                    $sub_data[] = "<button class='btn btn-info btn-sm mr-2 detailPelanggaran' id='".$row->id_pelanggaran."' title='Detail Pelanggaran'><i class='fa fa-eye'></i></button><button class='btn btn-warning btn-sm mr-2 editPelanggaran' id='".$row->id_pelanggaran."' title='Edit Pelanggaran'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deletePelanggaran' id='".$row->id_pelanggaran."' title='Delete Pelanggaran'><i class='fa fa-trash'></i></button>";
                    $data[] = $sub_data;
                    $no++;
                }else if($this->role==2){
                    $siswa = $this->model_siswa->getAll()->result();
                    $sub_data = array();
                    foreach($siswa as $s){
                        if ($row->induk_siswa_pelanggaran == $s->induk_siswa) {
                            if($s->id_kelas_siswa == $this->content['id_kelas']){
                                $sub_data[] = $no;
                                $sub_data[] = $row->kode_pelanggaran;
                                $sub_data[] = $row->induk_siswa_pelanggaran;
                                $sub_data[] = $s->nama_siswa;
                                $sub_data[] = date('d - m - Y',strtotime($row->tanggal_pelanggaran));
                                $sub_data[] = "<button class='btn btn-info btn-sm mr-2 detailPelanggaran' id='".$row->id_pelanggaran."' title='Detail Pelanggaran'><i class='fa fa-eye'></i></button>";
                                $data[] = $sub_data;
                                $no++;
                            }
                        }
                    }
                }else if($this->role == 3){
                    $sub_data = array();
                    if ($row->induk_siswa_pelanggaran == $this->induk) {
                        $sub_data[] = $no;
                        $sub_data[] = $row->kode_pelanggaran;
                        $sub_data[] = $row->induk_siswa_pelanggaran;
                        $sub_data[] = $this->nama;
                        $sub_data[] = date('d - m - Y',strtotime($row->tanggal_pelanggaran));
                        $sub_data[] = "<button class='btn btn-info btn-sm mr-2 detailPelanggaran' id='".$row->id_pelanggaran."' title='Detail Pelanggaran'><i class='fa fa-eye'></i></button>";
                        $data[] = $sub_data;
                        $no++;
                    }
                }
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_pelanggaran->get_all_data(),
            'recordsFiltered' => $this->model_pelanggaran->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function pelanggaranById()
    {
        $id = $this->input->post('id');
        $pelanggaran = $this->model_pelanggaran->getById($id);
        $this->load->model('model_jenis_pelanggaran');
        $jp = $this->model_jenis_pelanggaran->getByKode($pelanggaran->kode_jenis_pel_pelanggaran);
        $output = array(
            'id_pelanggaran' => $pelanggaran->id_pelanggaran,
            'kode_pelanggaran' => $pelanggaran->kode_pelanggaran,
            'induk_siswa_pelanggaran' => $pelanggaran->induk_siswa_pelanggaran,
            'kode_jenis_pel_pelanggaran' => $pelanggaran->kode_jenis_pel_pelanggaran,
            'poin_jenis_pelanggaran' => $jp->poin_jenis_pelanggaran,
            'tanggal_pelanggaran' => $pelanggaran->tanggal_pelanggaran,
            'keterangan_pelanggaran' => $pelanggaran->keterangan_pelanggaran
        );
        echo json_encode($output);
    }

    public function detailPelanggaran()
    {
        $id = $this->input->post('id');
        $pelanggaran = $this->model_pelanggaran->getById($id);
        $this->load->model('model_siswa');
        $this->load->model('model_jenis_pelanggaran');
        $siswa = $this->model_siswa->getByInduk($pelanggaran->induk_siswa_pelanggaran);
        $jp = $this->model_jenis_pelanggaran->getByKode($pelanggaran->kode_jenis_pel_pelanggaran);
        $output = array(
            'id_pelanggaran' => $pelanggaran->id_pelanggaran,
            'kode_pelanggaran' => $pelanggaran->kode_pelanggaran,
            'induk_siswa_pelanggaran' => $pelanggaran->induk_siswa_pelanggaran,
            'nama_siswa' => $siswa->nama_siswa,
            'kode_jenis_pel_pelanggaran' => $pelanggaran->kode_jenis_pel_pelanggaran,
            'nama_jenis_pelanggaran' => $jp->nama_jenis_pelanggaran,
            'poin_jenis_pelanggaran' => $jp->poin_jenis_pelanggaran,
            'tanggal_pelanggaran' => date('d - m - Y',strtotime($pelanggaran->tanggal_pelanggaran)),
            'keterangan_pelanggaran' => $pelanggaran->keterangan_pelanggaran
        );
        echo json_encode($output);
    }

    public function doPelanggaran()
    {
        $operation = $this->input->post('operation');
        $pesan = array();
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_siswa');
        $jp = $this->model_jenis_pelanggaran->getByKode($this->input->post('kode_jenis_pel_pelanggaran'));
        $id = $this->input->post('id_pelanggaran');
        if ($operation == 'tambah') {
            $data = array(
                'kode_pelanggaran' => $this->input->post('kode_pelanggaran'),
                'induk_siswa_pelanggaran' => $this->input->post('induk_siswa_pelanggaran'),
                'kode_jenis_pel_pelanggaran' => $this->input->post('kode_jenis_pel_pelanggaran'),
                'tanggal_pelanggaran' => $this->input->post('tanggal_pelanggaran'),
                'keterangan_pelanggaran' => $this->input->post('keterangan_pelanggaran')
            );
            $sis = $this->model_siswa->getByInduk($this->input->post('induk_siswa_pelanggaran'));
            $hitung = $sis->poin_siswa + $jp->poin_jenis_pelanggaran;
            $data2 = array(
                'poin_siswa' => $hitung
            );
            $process = $this->model_pelanggaran->tambahPelanggaran($data);
            $proces2 = $this->model_siswa->editByIndukSiswa($data2,$this->input->post('induk_siswa_pelanggaran'));
        }else if($operation == 'edit'){
            $data = array(
                'kode_pelanggaran' => $this->input->post('kode_pelanggaran'),
                'induk_siswa_pelanggaran' => $this->input->post('induk_siswa_pelanggaran'),
                'kode_jenis_pel_pelanggaran' => $this->input->post('kode_jenis_pel_pelanggaran'),
                'tanggal_pelanggaran' => $this->input->post('tanggal_pelanggaran'),
                'keterangan_pelanggaran' => $this->input->post('keterangan_pelanggaran')
            );
            $jp2 = $this->model_jenis_pelanggaran->getByKode($this->input->post('old_jenis_pelanggaran'));
            $dat = array('poin_siswa'=>$jp2->poin_jenis_pelanggaran);
            $proc = $this->model_siswa->editByIndukSiswa($dat,$this->input->post('induk_siswa_pelanggaran'));
            $data2 = array('poin_siswa'=>$jp->poin_jenis_pelanggaran);
            $process = $this->model_pelanggaran->editPelanggaran($data,$id);
            $proces2 = $this->model_siswa->editByIndukSiswa($data2,$this->input->post('induk_siswa_pelanggaran'));
        }
        $siswa = $this->model_siswa->getByInduk($this->input->post('induk_siswa_pelanggaran'));
        $this->load->model('model_sanksi');
        $sanksi = $this->model_sanksi->getAll();
        $count = 1;
        foreach($sanksi as $s){
            if ($count == 1) {
                if ($siswa->poin_siswa < $s->jumlah_poin_sanksi) {
                    $d = array('status_sanksi_siswa'=>$s->id_sanksi);
                    $p = $this->model_siswa->editByIndukSiswa($d,$this->input->post('induk_siswa_pelanggaran'));
                    break;
                }
                $count +=1;
            }else{
                if ($siswa->poin_siswa >= $s->jumlah_poin_sanksi) {
                    $d = array('status_sanksi_siswa'=>$s->id_sanksi);
                    $p = $this->model_siswa->editByIndukSiswa($d,$this->input->post('induk_siswa_pelanggaran'));
                }
                $count +=1;
            }
        }
        $this->load->model('model_param_poin');
        $param_poin = $this->model_param_poin->getAll();
        $jl = 1;
        foreach($param_poin as $pp){
            if ($jl != 3) {
                if ($siswa->poin_siswa <= $pp->nilai_max_param_poin) {
                    $da = array('id_param_poin_siswa'=>$pp->id_param_poin);
                    $this->model_siswa->editByIndukSiswa($da,$this->input->post('induk_siswa_pelanggaran'));
                    break;
                }
            }else{
                if ($siswa->poin_siswa > $pp->nilai_max_param_poin) {
                    $da = array('id_param_poin_siswa'=>$pp->id_param_poin);
                    $this->model_siswa->editByIndukSiswa($da,$this->input->post('induk_siswa_pelanggaran'));
                }
            }
            $jl += 1;
        }
        if ($process && $proces2) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else {
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }

    public function deletePelanggaran()
    {
        $id = $this->input->post('id');
        $process = $this->model_pelanggaran->deletePelanggaran($id);
        echo json_encode($process);
    }
}