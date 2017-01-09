<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CTF_admin_index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this -> load -> library('session');

		if (isset($_SESSION['admin'])) 
		{
			header("Location: /adminfuckme_controll");
			exit;
		}
	}

	public function index()
	{
		$this ->load->view('public/header');
		$this->load->view('admin/index');
		$this ->load->view('public/footer');
	}
	public function login()
	{
		$data = array(
			'code' => 0,
		);
		if (!isset($_POST['signin'])) {
			echo json_encode($data);
			return;
		}
		$username = $this -> input -> post('adminusername');
		$password = $this -> input ->post('adminpassword');

		if ($username == 'admin' && md5($password) == 'e10adc3949ba59abbe56e057f20f883e') {
			$this->session->set_userdata('admin', '1');
			$data['code'] = 1;
		}
		echo json_encode($data);
	}
}
