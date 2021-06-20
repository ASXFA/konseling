<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kmeans extends CI_Controller {

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

    public function dataRealLists()
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
                $sub_data[] = $siswa->poin_siswa;
                $sub_data[] = $row->nilai_absensi;
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

    public function dataKonversiLists()
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
                $sub_data[] = $siswa->id_param_poin_siswa;
                $sub_data[] = $row->id_param_kehadiran_absensi;
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

    public function kmeans()
	{
        $this->load->model('model_siswa');
        $siswa= $this->model_siswa->getAllJoinAbsensi()->result();
        $c1xAwal = 1;
        $c1yAwal = 1;
        $c2xAwal = 2;
        $c2yAwal = 2;
        $c3xAwal = 3;
        $c3yAwal = 3;
        $hasilC1x = 0;
        $hasilC1y = 0;
        $hasilC2x = 0;
        $hasilC2y = 0;
        $hasilC3x = 0;
        $hasilC3y = 0;
        $j=1;
        $semuaCluster1 = array();
        $semuaCluster2 = array();
        $iterasi = 1;
        for($i=0;$i<$j;$i++){
            if($i == 0){
                $c1x = $c1xAwal;
                $c1y = $c1yAwal;
                $c2x = $c2xAwal;
                $c2y = $c2yAwal;
                $c3x = $c3xAwal;
                $c3y = $c3yAwal;
            }else if($i != 0){
                $c1x = $hasilC1x;
                $c1y = $hasilC1y;
                $c2x = $hasilC2x;
                $c2y = $hasilC2y;
                $c3x = $hasilC3x;
                $c3y = $hasilC3y;
            }

            $hasilC1x = 0;
            $hasilC1y = 0;
            $hasilC2x = 0;
            $hasilC2y = 0;
            $hasilC3x = 0;
            $hasilC3y = 0;
            $jmlhdata1 = 0;
            $jmlhdata2 = 0;
            $jmlhdata3 = 0;
            foreach($siswa as $s){
                $hasil1 = sqrt((pow($s->id_param_poin_siswa - $c1x,2)) + pow($s->id_param_kehadiran_absensi - $c1y,2));
                $hasil2 = sqrt((pow($s->id_param_poin_siswa - $c2x,2)) + pow($s->id_param_kehadiran_absensi - $c2y,2));
                $hasil3 = sqrt((pow($s->id_param_poin_siswa - $c3x,2)) + pow($s->id_param_kehadiran_absensi - $c3y,2));

                if (($hasil1 < $hasil2)&&($hasil1 < $hasil3)) {
                    $hasilC1x += $s->id_param_poin_siswa;
                    $hasilC1y += $s->id_param_kehadiran_absensi;
                    $jmlhdata1 += 1;
                    if($i==0){
                        array_push($semuaCluster1,1);
                    }else if($i != 0){
                        array_push($semuaCluster2,1);
                    }
                }else if(($hasil2 < $hasil1)&&($hasil2 < $hasil3)){
                    $hasilC2x += $s->id_param_poin_siswa;
                    $hasilC2y += $s->id_param_kehadiran_absensi;
                    $jmlhdata2 += 1;
                    if($i==0){
                        array_push($semuaCluster1,2);
                    }else if($i != 0){
                        array_push($semuaCluster2,2);
                    }
                }else if(($hasil3 < $hasil2)&&($hasil3 < $hasil1)){
                    $hasilC3x += $s->id_param_poin_siswa;
                    $hasilC3y += $s->id_param_kehadiran_absensi;
                    $jmlhdata3 += 1;
                    if($i==0){
                        array_push($semuaCluster1,3);
                    }else if($i != 0){
                        array_push($semuaCluster2,3);
                    }
                }
            }            
            // hitung centroid berikutnya 
            if ($jmlhdata1 == 0) {
                $hasilC1x = $hasilC1x;
                $hasilC1y = $hasilC1y;
            }else{
                $hasilC1x = $hasilC1x / $jmlhdata1;
                $hasilC1y = $hasilC1y / $jmlhdata1;
            }

            if ($jmlhdata2 == 0) {
                $hasilC2x = $hasilC2x;
                $hasilC2y = $hasilC2y;
            }else{
                $hasilC2x = $hasilC2x / $jmlhdata2;
                $hasilC2y = $hasilC2y / $jmlhdata2;
            }

            if($jmlhdata3 == 0){
                $hasilC3x = $hasilC3x;
                $hasilC3y = $hasilC3y;
            }else{
                $hasilC3x = $hasilC3x / $jmlhdata3;
                $hasilC3y = $hasilC3y / $jmlhdata3;
            }

            $jmlhPerbedaan = 0;
            if(!empty($semuaCluster2)){
                for($k=0; $k < count($semuaCluster1); $k++){
                    if($semuaCluster1[$k] != $semuaCluster2[$k]){
                        $jmlhPerbedaan += 1;
                    }
                }
                if ($jmlhPerbedaan != 0) {
                    $semuaCluster1 = array();
                    for($l=0; $l<count($semuaCluster2); $l++){
                        array_push($semuaCluster1,$semuaCluster2[$l]);
                    }
                    $j = $j+1;
                    $iterasi +=1;
                }else if($jmlhPerbedaan == 0){
                    $jumlahData = $jmlhdata1 + $jmlhdata2 + $jmlhdata3;
                    $hasilJmlhData1 = ($jmlhdata1 / $jumlahData)*100;
                    $hasilJmlhData2 = ($jmlhdata2 / $jumlahData)*100;
                    $hasilJmlhData3 = ($jmlhdata3 / $jumlahData)*100;

                    // echo $hasilJmlhData1."/n";
                    // echo $hasilJmlhData2."/n";
                    // echo $hasilJmlhData3;
                }
                // print_r($semuaCluster1);
                // print_r($semuaCluster2);
            }else{
                $j = $j+1;
                $iterasi +=1;
            }
        }
        $this->content['iterasi'] = $iterasi;
        $this->content['cluster1'] = $semuaCluster1;
        $this->content['cluster2'] = $semuaCluster2;
        $this->content['hasilJmlhData1'] = $hasilJmlhData1;
        $this->content['hasilJmlhData2'] = $hasilJmlhData2;
        $this->content['hasilJmlhData3'] = $hasilJmlhData3;
        $this->content['c1x'] = $c1x;
        $this->content['c2x'] = $c2x;
        $this->content['c3x'] = $c3x;
        $this->content['c1y'] = $c1y;
        $this->content['c2y'] = $c2y;
        $this->content['c3y'] = $c3y;
        $hasil = array();
        $siswa2= $this->model_siswa->getAllJoinAbsensi()->result_array();
        for($i=0; $i<count($siswa2); $i++){
            $data = array(
                'induk_siswa' => $siswa2[$i]['induk_siswa'],
                'nama_siswa' => $siswa2[$i]['nama_siswa'],
                'poin_siswa' => $siswa2[$i]['poin_siswa'],
                'nilai_absensi' => $siswa2[$i]['nilai_absensi'],
                'hasil_kmeans' => $semuaCluster1[$i]
            );
            array_push($hasil,$data);
        }
        $this->content['hasill'] = $hasil;

		$this->twig->display('main/hasilKmeans.html',$this->content);
	}

    public function cekNilaiKehadiran()
    {
        $this->load->model('model_absensi');
        $absensi = $this->model_absensi->getAll();
        echo json_encode($absensi);
    }

	public function edit($id)
	{
		$j1 = $this->input->post('j1');
		$j2 = $this->input->post('j2');
		$data = $this->HasilKmeans_model->edit($id,$j1,$j2);
		echo json_encode($data);
	}
}