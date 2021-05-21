<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // $this->isLogin = $this->session->userdata('isLogin');
        $this->content = array(
            'base_url'=>base_url()
        );
    }

	public function auth()
	{
        if ($this->session->userdata('isLogin') != 0) {
            redirect('dashboard');
        }
		$this->twig->display('login-page.html',$this->content);
    }

    public function action_login()
    {
        $this->load->model('model_akun');
        $uname = $this->input->post('uname');
        $cek = $this->model_akun->getByUname($uname);
        $pesan = array();
        if ($cek->num_rows() > 0) {
            $user = $cek->row();
            $pass = $this->input->post('pass');
            if (password_verify($pass,$user->password_akun)) {
                if ($user->status_akun == 1) {
                    $session = array(
                        'isLogin' => 1,
                        'induk_user' => $user->induk_akun,
                        'role_user' => $user->role_akun
                    );
                    $this->session->set_userdata($session);
                    $pesan['condition'] = 2;
                    $pesan['pesan'] = "Login Berhasil !";
                    $pesan['url'] = 'index';
                    echo json_encode($pesan);
                    // if ($role == 1) {
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }else if($role == 2){
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }else if($role == 3){
                    //     // $this->session->set_flashdata('msgLogin','Halo, Selamat Datang ');
                    //     $pesan['condition'] = 2;
                    //     $pesan['pesan'] = "Login Berhasil !";
                    //     $pesan['url'] = 'semuaAnggaranKegiatan';
                    //     echo json_encode($pesan);
                    // }
                }else{
                    $pesan['condition'] = 1;
                    $pesan['pesan'] = "Login Gagal ! Akun anda Masih dinonaktifkan !";
                    echo json_encode($pesan);
                }
            }else{
                // $this->session->set_flashdata('msgLogin','Password tidak cocok !');
                $pesan['condition'] = 1;
                $pesan['pesan'] = "Login Gagal ! Password Tidak Cocok";
                echo json_encode($pesan);
            }
        }else{
            // $this->session->set_flashdata('msgLogin','NIP Tidak terdaftar !');
            $pesan['condition'] = 0;
            $pesan['pesan'] = "Login Gagal ! USERNAME Tidak tersedia !";
            echo json_encode($pesan);
        }
    }

    public function logout()
    {
        $this->session->set_userdata('isLogin','0');
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
