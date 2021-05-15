<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abc extends CI_Controller {

	public function sd()
	{
		$this->load->view('welcome_message',array('asdasd'=>'sssssssssdddss'));
	}
}
