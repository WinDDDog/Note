<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CTF_admin_controll extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		//$this -> load -> model('controll_model', 'controll');
		//$this -> load -> model('platform_model', 'plat');


		$this -> load -> model('CTF_model_user', 'model_user');
		$this -> load -> model('CTF_model_config', 'model_config');
		$this -> load -> model('CTF_model_problem_class', 'model_problem_class');
		$this -> load -> model('CTF_model_problem', 'model_problem');

		$this -> load -> library('session');
		
		if (!isset($_SESSION['admin'])) 
		{
			echo "<script>location.href='/adminfuckme';</script>";
			//header("Location: /adminfuckme.php/ctf_index");
			exit;
		}
	}

	public function index()
	{
		$this ->load->view('public/header');
		$this->load->view('admin/main');
		$this ->load->view('public/footer');
	}
	public function view_add()
	{
		$data = array();
		$data['week'] = $this-> model_config -> get_setting(0);
		$data['class'] = $this -> model_problem_class -> get_all_problem_class();
		$this -> load ->view('admin/add', $data);
	}
	public function view_edit()
	{
		$data = array();
		$data['problem'] = $this -> model_problem -> get_all_problem();
		$this -> load ->view('admin/edit', $data);
	}
	public function view_set()
	{
		$data = array();
		$data['week'] = $this -> model_config -> get_setting(0);
		$data['notice'] = $this -> model_config -> get_setting(1);
		$data['open'] = $this -> model_config -> get_setting(2);
		$data['over'] = $this -> model_config -> get_setting(3);
		$this -> load ->view('admin/setting', $data);
	}
	public function view_user()
	{
		$data = array();
		$id = $this -> input -> get('id');
		if ($id) {
			$data['user'] = $this -> model_user -> get_user_byID($id);
			$this -> load -> view('admin/edit_user', $data);
		}
		else{
			$data['user'] = $this -> model_user -> get_all_user();
			$this -> load -> view('admin/user', $data);
		}
		
	}
	public function view_class()
	{
		$this -> load -> view('admin/class');
	}
	public function con_user()
	{
		$data = array(
			'code' => 0,
		);
		$status = array();
		$id = $this -> input -> post('id');
		$pass = $this -> input -> post('password');
		$open = 1;#$this -> input -> post('IS_LOGIN');
		$level = $this -> input -> post('LEVEL');
		if ($pass) {
			$status = array(
				'IS_LOGIN' => $open,
				'PASSWORD' => md5($pass),
				'LEVEL' => $level,
			);
		}
		else{
			$status = array(
				'IS_LOGIN' => $open,
				'LEVEL' => $level,
			);
		}
		if ($this -> model_user -> update_user_byID($status, $id)) {
			$data['code'] = 1;
		}
		echo json_encode($data);
	}
	public function con_set()
	{
		$data = array(
			'code' => 0, 
		);
		$curWeek = $this -> input -> post('curWeek');
		$publicNotice = $this -> input -> post('publicNotice');
		$isopen = $this -> input -> post('isopen');
		$isover = $this -> input -> post('isover');
		$w = [$curWeek, $publicNotice, $isopen,$isover];
		if ($isopen != 1 && $isopen != 0) {
			$data['code'] = 2;
			echo json_encode($data);
			return;
		}
		if ($curWeek === "" || $curWeek < -1) {
			$data['code'] = 3;
			echo json_encode($data);
			return;
		}
		if ($publicNotice == "") {
			$publicNotice = "暂无公告";
		}
		if($isover != 1 && $isover != 0)
		{
			$data['code'] = 2;
			echo json_encode($data);
			return;
		}
		$data['code'] = 1;
		foreach ($w as $key => $value) {
			if (!$this -> model_config -> update_setting($value, $key)) {
				$data['code'] = 0;
				break;	
			}
		}
		// if (($this -> controll -> update_week($curWeek)) && ($this -> controll -> update_notice($publicNotice))) {
		// 	$data['code'] = 1;
		// }
		echo json_encode($data);
	}
	public function con_add()
	{
		/**
		 * code
		 * 0 -> server error
		 * 1 -> succeed
		 *  2 -> class-error
		 *  3 -> blank data
		 * 4 -> bad point or week
		*/
		$data = array(
			'code' => 0,
		);
		$post = array();
		$post['title'] = $this -> input -> post('title'); 
		$post['describe'] = $this -> input -> post('describe');
		$post['hint'] = $this -> input -> post('hint');
		$post['week'] = $this -> input -> post('week');
		$post['problemclass'] = $this -> input -> post('problemclass');
		$post['point'] = $this -> input -> post('point');
		$post['flag'] = $this -> input -> post('flag');
		$post['level'] = $this -> input -> post('level');

		if ($post['problemclass'] == 'No Class') {
			$data['code'] = 2;
			echo json_encode($data);
			return;
 		}

 		foreach ($post as $key => $value) {
 			if ($key == "hint") {
 				if ($value == "") {
 					$post[$key] = "暂无HINT";
 				}
 			}
 			else{
 				if ($value == "") {
 					$data['code'] = 3;
					echo json_encode($data);
					return;
 				}
 			}
 		}
 		if ($post['point'] < 0 || $post['week'] < 0) {
 			$data['code'] = 4;
			echo json_encode($data);
			return;
 		}
 		if ($this -> model_problem -> insert_problem($post)) {
 			$data['code'] = 1;
 		}
 		echo json_encode($data);
	}
	public function del()
	{
		/**
		* code -->
		* 0 -> del error
		* 1 -> suc
		* 2 -> enter error
		*/
		$data = array(
			'code' => 0, 
		);
		$id = $this -> input -> get('id');
		$id = intval($id);
		if ($id <= 0) {
			$data['code'] = 2;
			echo json_encode($data);
			return;
		}
		if ($this -> model_problem -> del_problem($id)) {
			$data['code'] = 1;
		}
		echo json_encode($data);
	}
	public function con_problem()
	{
		$data = array();
		$id = $this -> input -> get('id');
		$id = intval($id);
		if ($id <= 0) {
			echo '<div class="alert alert-danger" role="alert">Don\'t hack me!</div><script>location.href = "/adminfuckme_controll";</script>';
			return;
		}
		$p = $this -> model_problem -> get_problem_byID($id);
		if ($p['code'] == 0) {
			echo '<div class="alert alert-danger" role="alert">Don\'t hack me!</div><script>location.href = "/adminfuckme_controll";</script>';
			return;
		}
		$data['problem'] = $p['problem'];
		$data['week'] = $this-> model_config -> get_setting(0);
		$data['class'] = $this -> model_problem_class -> get_all_problem_class();
		$this -> load -> view('admin/edit_problem', $data);
	}
	public function con_edit()
	{
		/**
		 * code
		 * 0 -> server error
		 * 1 -> succeed
		 *  3 -> blank data
		*/
		$data = array(
			'code' => 0, 
		);
		$problem = array();
		$problem['title'] = $this -> input -> post('title');
		$problem['describe'] = $this -> input -> post('describe');
		$problem['hint'] = $this -> input -> post('hint');
		$problem['week'] = $this -> input -> post('week');
		$problem['problemclass'] = $this -> input -> post('problemclass');
		$problem['point'] = $this -> input -> post('point');
		$problem['flag'] = $this -> input -> post('flag');
		$id = $this -> input -> post('id');
		$problem['level'] = $this -> input -> post('level');
		foreach ($problem as $key => $value) {
			if ($value == "") {
				$data['code'] = 3;
				echo json_encode($data);
				return;
			}
		}
		if ($id < 0 || $id == "") {
			$data['code'] = 3;
			echo json_encode($data);
			return;
		}
		if ($this -> model_problem -> update_problem($problem, $id)) {
			$data['code'] = 1;
		}
		echo json_encode($data);
	}
}
