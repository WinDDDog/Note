<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CTF_index_page extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this -> load -> model('controll_model', 'controll');
		//$this -> load -> model('platform_model', 'plat');


		$this -> load -> model('CTF_model_user', 'model_user');
		$this -> load -> model('CTF_model_config', 'model_config');
		$this -> load -> model('CTF_model_point', 'model_point');
		$this -> load -> library('session');
	}
	public function index()
	{
		$this ->load->view('public/header');
		$this->load->view('platform/index');
		$this ->load->view('public/footer');
	}
	public function login()
	{
		if (isset($_SESSION['user'])) 
		{
			header("Location: /controll");
		}
		else
		{
			$this -> load -> view('public/header');
			$this -> load -> view('platform/login');
			$this -> load -> view('public/footer');
		}
	}
	public function registry()
	{
		$is_open = $this -> model_config -> get_setting(2);
		if ($is_open['code'] && $is_open['value']) 
		{
			$this -> load -> view('public/header');
			$this -> load -> view('platform/registry');
			$this -> load -> view('public/footer');
		}
		else
		{
			echo "<script>alert('注册关了，别闹了！');history.back();</script>";
		}
	}
	public function con_login()
	{
		/**
		* code
		* 0 -> server error
		* 1 -> suc
		* 2 -> blank input
		* 3 -> error-user
		* 4 -> reject login
		*/
		$data = array( 'code' => 0, );

		$post['user'] = $this -> input -> post('username');
		$post['pass'] = $this -> input -> post('password');

		foreach ($post as $value) 
		{
			if ($value == "") 
			{
				$data['code'] = 2;
				echo json_encode($data);
				return;	
			}
		}

		$info = $this -> model_user -> check_login($post['user'], $post['pass']);
		if (empty($info)) 
		{
			$data['code'] = 3;
		}
		else
		{
			$info = $info[0];
		#	if ($info['IS_LOGIN']) 
		#		{
				$week = $this -> model_config -> get_setting(0);
				if($this -> model_point ->Login_Week_Point($info['ID'], $week['value']))
				{
					$this->session->set_userdata('user', $info);
					$dd['IP'] = $this -> input -> ip_address();
					$dd['TIME'] = time();
					$this->model_user->update_user_byID($dd,$_SESSION['user']['ID']);
					//add'value'
					$data['code'] = 1;
				}
		#	}
		#	else
		#	{
		#		$data['code'] = 4;
		#	}
		$data['code'] = 1;
		}
		echo json_encode($data);
	}
	
	public function con_registry()
	{
		/**
		* code
		* 0 -> server error
		* 1 -> suc
		* 2 -> blank input
		* 3 -> repeat-user
		* 4 -> bad email
		* 5 -> bad qq
		*/
		$data = array(
			'code' => 0, 
		);
		$post = array();
		$post['username'] = $this -> input -> post('username');
		$post['password'] = $this -> input -> post('password');
		$post['email'] = $this -> input -> post('email');
		$post['qq'] = $this -> input -> post('qq');

		foreach ($post as $value) 
		{
			if ($value == "") 
			{
				$data['code'] = 2;
				echo json_encode($data);
				return;
			}
		}

		//add{
		$post['schoolid'] =  $this -> input -> post('schoolid');
		$post['realname'] =  $this -> input -> post('realname');
		$post['college'] =  $this -> input -> post('college');
		//add}

		$post['password'] = md5($post['password']);
		if ($this -> model_user -> check_user($post['username'])) 
		{
			$data['code'] = 3;
			echo json_encode($data);
			return;
		}
		if (!is_numeric($post['qq'])) 
		{
			$data['code'] = 5;
			echo json_encode($data);
			return;
		}
		#$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,10}(\\.[a-z]{2})?)$/i";
		if (!preg_match($pattern, $post['email'])) 
		{
			$data['code'] = 4;
			echo json_encode($data);
			return;
		}
		if ($this -> model_user -> insert_user($post)) 
		{
			$data['code'] = 1;
		}
		echo json_encode($data);
	}
}
