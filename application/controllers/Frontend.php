<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        // $this->isLogin = $this->session->userdata('isLogin');
        // if ($this->isLogin == 0) {
        //     redirect(base_url());
        // }
        // $this->id = $this->session->userdata('id');
        // $this->nama = $this->session->userdata('nama');
        // $this->uname = $this->session->userdata('uname');
        $this->content = array(
            'base_url'=>base_url(),
            // 'id_user_login' => $this->id,
            // 'nama_user_login' => $this->nama,
            // 'uname_user_login' => $this->uname
        );
	}

	public function index()
	{
		$this->twig->display('home.html',$this->content);
	}
}
