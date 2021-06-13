<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catatan_kasus extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url('auth'));
        }
        $this->induk = $this->session->userdata('induk_user');
        $this->role = $this->session->userdata('role_user');
        
        $this->load->model('model_catatan_kasus');
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

	public function listCatatan()
	{
        $this->load->model('model_pelanggaran');
        $this->load->model('model_siswa');
        $this->content['siswa'] = $this->model_siswa->getAll()->result();
        $this->content['pelanggaran'] = $this->model_pelanggaran->getAll()->result();
		$this->twig->display('main/catatan.html',$this->content);
	}

    public function catatanLists()
    {
        $this->load->model('model_pelanggaran');
        $catatan = $this->model_catatan_kasus->make_datatables();
        $data = array();
        if (!empty($catatan)) {
            $no = 1;
            foreach($catatan as $row){
                $sub_data = array();
                $sub_data[] = $no;
                $pelanggaran = $this->model_pelanggaran->getById($row->id_pelanggaran_catatan_kasus);
                if (!empty($pelanggaran)) {
                    $sub_data[] = $pelanggaran->kode_pelanggaran;
                }else{
                    $sub_data[] = "Pelanggaran Tidak Ada !";
                }
                $sub_data[] = $row->penyelesaian_catatan_kasus;
                $sub_data[] = $row->evaluasi_catatan_kasus;
                $sub_data[] = $row->tanggal_catatan_kasus;
                $sub_data[] = $row->pihak_catatan_kasus;
                $sub_data[] = "<button class='btn btn-info btn-sm mr-2 detailCatatan' id='".$row->id_catatan_kasus."' title='cek akun'><i class='fa fa-eye'></i></button><button class='btn btn-warning btn-sm mr-2 editCatatan' id='".$row->id_catatan_kasus."' title='Edit Catatan'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteCatatan' id='".$row->id_catatan_kasus."' title='Delete Catatan'><i class='fa fa-trash'></i></button><a href='".base_url()."cetakByKasus/".$row->id_catatan_kasus."' target='_blank' class='btn btn-success btn-sm mr-2 cetakCatatan' id='".$row->id_catatan_kasus."' title='Cetak Catatan Kasus'><i class='fa fa-print'></i></a>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_catatan_kasus->get_all_data(),
            'recordsFiltered' => $this->model_catatan_kasus->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function catatanById()
    {
        $id = $this->input->post('id');
        $catatan = $this->model_catatan_kasus->getById($id);
        $output = array(
            'id_catatan_kasus' => $catatan->id_catatan_kasus,
            'id_pelanggaran_catatan_kasus' => $catatan->id_pelanggaran_catatan_kasus,
            'penyelesaian_catatan_kasus' => $catatan->penyelesaian_catatan_kasus,
            'evaluasi_catatan_kasus' => $catatan->evaluasi_catatan_kasus,
            'tanggal_catatan_kasus' => $catatan->tanggal_catatan_kasus,
            'pihak_catatan_kasus' => $catatan->pihak_catatan_kasus
        );
        echo json_encode($output);
    }

    public function detailCatatan()
    {
        $id = $this->input->post('id');
        $catatan = $this->model_catatan_kasus->getById($id);
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_pelanggaran');
        $this->load->model('model_siswa');
        $pelanggaran = $this->model_pelanggaran->getById($catatan->id_pelanggaran_catatan_kasus);
        $jp = $this->model_jenis_pelanggaran->getByKode($pelanggaran->kode_jenis_pel_pelanggaran);
        $siswa = $this->model_siswa->getByInduk($pelanggaran->induk_siswa_pelanggaran);
        $output = array('pelanggaran'=>$pelanggaran,'jp'=>$jp,'siswa'=>$siswa,'catatan'=>$catatan);
        echo json_encode($output);
    }

    public function doCatatan()
    {
        // $this->load->model('model_akun');
        $operation = $this->input->post('operation');
        $pesan = array();
        $id = $this->input->post('id_catatan_kasus');
        if ($operation == 'tambah') {
            $data = array(
                'id_pelanggaran_catatan_kasus' => $this->input->post('id_pelanggaran_catatan_kasus'),
                'penyelesaian_catatan_kasus' => $this->input->post('penyelesaian_catatan_kasus'),
                'evaluasi_catatan_kasus' => $this->input->post('evaluasi_catatan_kasus'),
                'tanggal_catatan_kasus' => $this->input->post('tanggal_catatan_kasus'),
                'pihak_catatan_kasus' => $this->input->post('pihak_catatan_kasus')
            );
            $data2 = array('status_pelanggaran'=>1);
            $where2 = $this->input->post('id_pelanggaran_catatan_kasus');
            $process1 = $this->model_catatan_kasus->tambahCatatan($data);
            $this->load->model('model_pelanggaran');
            $process2 = $this->model_pelanggaran->editPelanggaran($data2,$where2);
        }else if($operation == 'edit'){
            $data = array(
                'id_pelanggaran_catatan_kasus' => $this->input->post('id_pelanggaran_catatan_kasus'),
                'penyelesaian_catatan_kasus' => $this->input->post('penyelesaian_catatan_kasus'),
                'evaluasi_catatan_kasus' => $this->input->post('evaluasi_catatan_kasus'),
                'tanggal_catatan_kasus' => $this->input->post('tanggal_catatan_kasus'),
                'pihak_catatan_kasus' => $this->input->post('pihak_catatan_kasus')
            );
            $process1 = $this->model_catatan_kasus->editCatatan($data,$id);
        }
        if ($process1) {
            $pesan['cond'] = '1';
            $pesan['msg'] = 'Berhasil !';
        }else {
            $pesan['cond'] = '0';
            $pesan['msg'] = 'Gagal !';
        }
        echo json_encode($pesan);
    }

    public function deleteCatatan()
    {
        $this->load->model('model_pelanggaran');
        $id = $this->input->post('id');
        $catatan = $this->model_catatan_kasus->getById($id);
        $data = array('status_pelanggaran'=>0);
        $this->model_pelanggaran->editPelanggaran($data,$catatan->id_pelanggaran_catatan_kasus);
        $delete=$this->model_catatan_kasus->deleteCatatan($id);
        if ($delete) {
            $output = array('cond'=>'1');
        }else{
            $output = array('cond'=>'0');
        }
        echo json_encode($output);
    }

    public function cetakByKasus($id)
    {
        $this->load->model('model_pelanggaran');
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_siswa');
        $this->load->model('model_sanksi');
        $this->load->model('model_kelas');
        $catatan = $this->model_catatan_kasus->getById($id);
        $pelanggaran = $this->model_pelanggaran->getById($catatan->id_pelanggaran_catatan_kasus);
        $jp = $this->model_jenis_pelanggaran->getByKode($pelanggaran->kode_jenis_pel_pelanggaran);
        $siswa = $this->model_siswa->getByInduk($pelanggaran->induk_siswa_pelanggaran);
        $this->content['induk_siswa'] = $siswa->induk_siswa;
        $this->content['nama_siswa'] = $siswa->nama_siswa;
        $this->content['jk_siswa'] = $siswa->jk_siswa;
        $this->content['alamat_siswa'] = $siswa->alamat_siswa;
        $this->content['poin_siswa'] = $siswa->poin_siswa;
        $this->content['foto_siswa'] = $siswa->foto_siswa;
        $sanksi = $this->model_sanksi->getById($siswa->status_sanksi_siswa);
        if (!empty($sanksi)) {
            $this->content['status_sanksi_siswa'] = $sanksi->nama_sanksi;
        }else{
            $this->content['status_sanksi_siswa'] = "-";
        }
        $kelas = $this->model_kelas->getById($siswa->id_kelas_siswa);
        if (!empty($kelas)) {
            $this->content['kelas_siswa'] = $kelas->nama_kelas;
        }else{
            $this->content['kelas_siswa'] = "-";

        }
        $this->content['kode_jp'] = $jp->kode_jenis_pelanggaran;
        $this->content['nama_jp'] = $jp->nama_jenis_pelanggaran;
        $this->content['kategori_jp'] = $jp->kategori_jenis_pelanggaran;
        $this->content['poin_jp'] = $jp->poin_jenis_pelanggaran;
        
        $this->content['kode_pelanggaran'] = $pelanggaran->kode_pelanggaran;
        $this->content['tanggal_pelanggaran'] = $pelanggaran->tanggal_pelanggaran;
        $this->content['keterangan_pelanggaran'] = $pelanggaran->keterangan_pelanggaran;

        $this->content['penyelesaian_catatan'] = $catatan->penyelesaian_catatan_kasus;
        $this->content['evaluasi_catatan'] = $catatan->evaluasi_catatan_kasus;
        $this->content['tanggal_catatan'] = $catatan->tanggal_catatan_kasus;
        $this->content['pihak_catatan'] = $catatan->pihak_catatan_kasus;
        // $this->content['data'] = array('catatan'=>$catatan,'pelanggaran'=>$pelanggaran,'jp'=>$jp,'siswa'=>$siswa);
        // $data['siswa'] = $this->siswa_model->view_row();
        // $this->load->view('print', $data);
        $filename = "Laporan_Catatan_Kasus_".$siswa->nama_siswa."-".$siswa->induk_siswa.".pdf";
        ob_start();
        $this->twig->display('main/cetakByKasus.html',$this->content);
        $html = ob_get_contents();
        ob_end_clean();
        require_once 'application/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);
    }

    public function cetakByKategori()
    {
        $this->load->model('model_pelanggaran');
        $this->load->model('model_jenis_pelanggaran');
        $this->load->model('model_siswa');
        $this->load->model('model_sanksi');
        $this->load->model('model_kelas');
        $indk = $this->input->post('induk_siswa_print');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        if ($bulan == "01") {
            $nama_bulan = "JANUARI";
        }else if ($bulan == "02") {
            $nama_bulan = "FEBRUARI";
        }else if ($bulan == "03") {
            $nama_bulan = "MARET";
        }else if ($bulan == "04") {
            $nama_bulan = "APRIL";
        }else if ($bulan == "05") {
            $nama_bulan = "MEI";
        }else if ($bulan == "06") {
            $nama_bulan = "JUNI";
        }else if ($bulan == "07") {
            $nama_bulan = "JULI";
        }else if ($bulan == "08") {
            $nama_bulan = "AGUSTUS";
        }else if ($bulan == "09") {
            $nama_bulan = "SEPETEMBER";
        }else if ($bulan == "10") {
            $nama_bulan = "OKTOBER";
        }else if ($bulan == "11") {
            $nama_bulan = "NOVEMBER";
        }else if ($bulan == "12") {
            $nama_bulan = "DESEMBER";
        }else{
            $nama_bulan = "semua";
        }
        if ($indk == "0" && $bulan == "0" && $tahun == "0") {
            $siswa = $this->model_siswa->getAll()->result();
            $pelanggaran = $this->model_pelanggaran->getAll()->result();
            $catatan = $this->model_catatan_kasus->getAll()->result();
            $jp = $this->model_jenis_pelanggaran->getAll()->result();
            $sanksi = $this->model_sanksi->getAll();
            $kelas = $this->model_kelas->getAll()->result();
            $this->content['siswa'] = $siswa;
            $this->content['pelanggaran'] = $pelanggaran;
            $this->content['catatan'] = $catatan;
            $this->content['jp'] = $jp;
            $this->content['sanksi'] = $sanksi;
            $this->content['kelas'] = $kelas;
            $this->content['nama_bulan'] = $nama_bulan;
            $this->content['tahun'] = $tahun;
            $filename = "Laporan_Catatan_Kasus.pdf";
            ob_start();
            $this->twig->display('main/cetakByKategori.html',$this->content);
            $html = ob_get_contents();
            ob_end_clean();
            require_once 'application/vendor/autoload.php'; 
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L']);
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);
        }else if($indk == "0" && (($bulan != "0" && $tahun == "0")||($bulan == "0" && $tahun != "0")||($bulan != "0" && $tahun != "0"))){
            $siswa = $this->model_siswa->getAll()->result();
            $pelanggaran = $this->model_pelanggaran->getAll()->result();
            $catatan = $this->model_catatan_kasus->getByTanggal($bulan,$tahun)->result();
            $jp = $this->model_jenis_pelanggaran->getAll()->result();
            $sanksi = $this->model_sanksi->getAll();
            $kelas = $this->model_kelas->getAll()->result();
            $this->content['siswa'] = $siswa;
            $this->content['pelanggaran'] = $pelanggaran;
            $this->content['catatan'] = $catatan;
            $this->content['jp'] = $jp;
            $this->content['sanksi'] = $sanksi;
            $this->content['kelas'] = $kelas;
            $this->content['nama_bulan'] = $nama_bulan;
            $this->content['tahun'] = $tahun;
            $filename = "Laporan_Catatan_Kasus.pdf";
            ob_start();
            $this->twig->display('main/cetakByKategori.html',$this->content);
            $html = ob_get_contents();
            ob_end_clean();
            require_once 'application/vendor/autoload.php'; 
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L']);
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);
        }else{
            $siswa = $this->model_siswa->getByInduk($indk);
            $this->content['induk_siswa'] = $siswa->induk_siswa;
            $this->content['nama_siswa'] = $siswa->nama_siswa;
            $this->content['jk_siswa'] = $siswa->jk_siswa;
            $this->content['alamat_siswa'] = $siswa->alamat_siswa;
            $this->content['poin_siswa'] = $siswa->poin_siswa;
            $this->content['foto_siswa'] = $siswa->foto_siswa;
            $sanksi = $this->model_sanksi->getById($siswa->status_sanksi_siswa);
            if (!empty($sanksi)) {
                $this->content['status_sanksi_siswa'] = $sanksi->nama_sanksi;
            }else{
                $this->content['status_sanksi_siswa'] = "-";
            }
            $kelas = $this->model_kelas->getById($siswa->id_kelas_siswa);
            if (!empty($kelas)) {
                $this->content['kelas_siswa'] = $kelas->nama_kelas;
            }else{
                $this->content['kelas_siswa'] = "-";
    
            }
            $pelanggaran = $this->model_pelanggaran->getAllByInduk($indk)->result();
            $catatan = $this->model_catatan_kasus->getByTanggal($bulan,$tahun)->result();
            $jp = $this->model_jenis_pelanggaran->getAll()->result();
            $sanksi = $this->model_sanksi->getAll();
            $kelas = $this->model_kelas->getAll()->result();
            $this->content['pelanggaran'] = $pelanggaran;
            $this->content['catatan'] = $catatan;
            $this->content['jp'] = $jp;
            $this->content['sanksi'] = $sanksi;
            $this->content['kelas'] = $kelas;
            $this->content['nama_bulan'] = $nama_bulan;
            $this->content['tahun'] = $tahun;
            $filename = "Laporan_Catatan_Kasus.pdf";
            ob_start();
            $this->twig->display('main/cetakBySiswaKategori.html',$this->content);
            $html = ob_get_contents();
            ob_end_clean();
            require_once 'application/vendor/autoload.php'; 
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L']);
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);
        }
    }
}
