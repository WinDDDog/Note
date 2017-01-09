<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CTF_controll_page extends CI_Controller {
	public function __construct()
	{
		
		parent::__construct();
		$this -> load -> library('session');
		//$this -> load -> model('controll_model', 'controll');
		//$this -> load -> model('platform_model', 'plat');

		$this -> load -> model('CTF_model_user', 'model_user');
		$this -> load -> model('CTF_model_config', 'model_config');
		$this -> load -> model('CTF_model_log', 'model_log');
		$this -> load -> model('CTF_model_problem_class', 'model_problem_class');
		$this -> load -> model('CTF_model_problem', 'model_problem');
		$this -> load -> model('CTF_model_point', 'model_point');
		$this -> load -> model('CTF_model_rank', 'model_rank');

		if (!isset($_SESSION['user'])) {
			echo "<script>location.href='/login';</script>";
			exit;
		}

	}

	public function findMAX2_TYPE($input, $mod = 0)
	{
		$output = array_slice($input, 0, 6); 
		arsort($output);
		$rel = array_keys($output);
		if(current($output) == 0)
		{
			$rel[0] = "";
		}
		if(next($output) == 0)
		{
			$rel[1] = "";
		}
		if($mod == 1)
		{
			return substr($rel[0],4,strlen($rel[0])-10).' '.substr($rel[1],4,strlen($rel[1])-10);
		}
		return substr($rel[0],0,strlen($rel[0])-5).' '.substr($rel[1],0,strlen($rel[1])-5);
	}

	public function index()
	{
		$data = array();
		$data['class'] = $this -> model_problem_class -> get_all_problem_class();
		$data['week'] = $this -> model_config -> get_setting(0);
		$data['notice'] = $this -> model_config -> get_setting(1);
		$data['user'] = $this -> session -> userdata('user');
		$this ->load->view('public/header');
		$this->load->view('platform/problem', $data);
		$this ->load->view('public/footer');
	}
	public function sub_sub()
	{
		
		$r = $this -> model_log -> get_sublog();
		$n = 0;
		$re = array();
		foreach ($r as $key => $value) {
			$p = $this -> model_problem -> get_proinfo($value['PROBLEMID']);
			if (!empty($p)) {
				$re[$n] = $value;
				$re[$n]['problem'] = $p;
				$n++;
			}			
		}

		$data = array();
		$data['prolog'] = $re;
		$this -> load -> view('platform/sub', $data);
	}
	public function sub_rank()
	{
		$data = array();
		$week = $this -> model_config -> get_setting(0);
		$data['top_week'] = $this -> model_rank -> Get_Top_5_This_week($week['value']);
		$data['top_all'] = $this -> model_rank -> Get_Top_5_all();
		$this -> load -> view('platform/sub_rank', $data);
	}
	public function rank()
	{
		$data = array();
		$week = $this -> model_config -> get_setting(0);
		$data['info'] = $this -> model_rank ->Get_Week_Rank_LEVEL($week['value']);
		foreach ($data['info'] as $key => $value) {
			$tmp = $this->findMAX2_TYPE($value);
			$data['info'][$key]['Problem_type'] = $tmp;

			$t = $this -> model_log -> get_last_time($value['ID']);
			if (empty($t)) {
				$data['info'][$key]['time']['code'] = 0;
				$data['info'][$key]['time']['value'] = 'Never got points';
			}
			else{
				$data['info'][$key]['time']['code'] = 1;
				$data['info'][$key]['time']['value'] = $t[0]['SOLVED_TIME'];
			}
			
		}



		$data['info_school'] = $this -> model_rank ->Get_All_Rank_LEVEL();
		foreach ($data['info_school'] as $key => $value) {
			$res =  $this -> model_rank ->Get_Sum_Point_Byid($value['ID']);
			$tmp = $this->findMAX2_TYPE($res,1);
			$data['info_school'][$key]['Problem_type'] = $tmp;

			$t = $this -> model_log -> get_last_time($value['ID']);
			if (empty($t)) {
				$data['info_school'][$key]['time']['code'] = 0;
				$data['info_school'][$key]['time']['value'] = 'Never got points';
			}
			else{
				$data['info_school'][$key]['time']['code'] = 1;
				$data['info_school'][$key]['time']['value'] = $t[0]['SOLVED_TIME'];
			}
		}





		$data['info_all'] = $this -> model_rank ->Get_Real_Rank();
		foreach ($data['info_all'] as $key => $value) {
			$res =  $this -> model_rank ->Get_Sum_Point_Byid($value['ID']);
			$tmp = $this->findMAX2_TYPE($res,1);
			$data['info_all'][$key]['Problem_type'] = $tmp;

			$t = $this -> model_log -> get_last_time($value['ID']);
			if (empty($t)) {
				$data['info_all'][$key]['time']['code'] = 0;
				$data['info_all'][$key]['time']['value'] = 'Never got points';
			}
			else{
				$data['info_all'][$key]['time']['code'] = 1;
				$data['info_all'][$key]['time']['value'] = $t[0]['SOLVED_TIME'];
			}
			
		}	
		

		$this -> load -> view('platform/rank', $data);
	}
	public function person()
	{
		$data = array();
		#$data['user'] = $this -> model_user -> get_user_byID($_SESSION['user']['ID']);
		# $data['rank'] = $this -> model_user -> get_rank();
		if($_SESSION['user']['LEVEL']>0)
		{
			$week = $this -> model_config -> get_setting(0);
			$data['week_point'] = $this -> model_rank ->Get_Person_Week_PointRank_Byid($_SESSION['user']['ID'],$week['value']);
			$data['all_point']  = $this -> model_rank ->Get_Person_All_PointRank_Byid($_SESSION['user']['ID']);
		}

		$data['real_point'] = $this -> model_rank ->Get_Person_Real_Rank_Byid($_SESSION['user']['ID']);

		$r = $this -> model_log ->  get_mypro();
		foreach ($r as $key => $value) {
			$r[$key]['pinfo'] = $this -> model_problem -> get_proinfo($value['PROBLEMID']); 
		}

		$data['mypro'] = $r;
		$this -> load -> view('platform/person', $data);
	}
	public function sub()
	{
		/**
		* code
		* 0 -> server error
		* 1 -> suc
		* 2 -> done
		* 3 -> error answer
		* 4 -> bad problemid
		*/
		$data = array(
			'code' => 0,
		);
		$add_point = 0;
		$post['flag'] = $this -> input -> post('flag');
		$post['problemid'] = intval($this -> input -> post('problemid'));
		//判断参数正确
		if ($post['flag'] == '' || $post['problemid'] == '' || $post['problemid'] < 0) {
			$data['code'] = 3;
			echo json_encode($data);
			return;
		}
		//是否重复提交
		if ($this -> model_log -> check_repeat($post['problemid'])) {
			$data['code'] = 2;
			echo json_encode($data);
			return;
		}
		//判断周数
		$pro = $this -> model_problem -> get_proinfo($post['problemid']);
		$d['week'] = $this -> model_config -> get_setting(0);
		if (empty($pro) || $pro['WEEK'] > $d['week']['value']) {
			$data['code'] = 4;
			echo json_encode($data);
			return;
		}
		if($_SESSION['user']['LEVEL'] < $pro['LEVEL'])
		{
			$data['code'] = 4;
			echo json_encode($data);
			return;
		}
		//add 从point表中获得本周的用户分数数组
		$Find_update_point = $this -> model_point -> Get_Array_Byid($_SESSION['user']['ID'],$d['week']['value']);
		//fix bugs 解决可能出现的week修改bug
		if(empty($Find_update_point))
		{
			$this -> model_point ->Login_Week_Point($_SESSION['user']['ID'], $d['week']['value']);
		}

		//check flag 并且更新flag表的内容
		if ($pro['FLAG'] != $post['flag']) {
			$l = array(
				'problemid' => $post['problemid'],
				'flag' => $post['flag'],
				'result' => 0,
				'fin_point' => 0,
			);
			$n = array(
				'SUBMITTED' => $pro['SUBMITTED'] + 1,
			);
			$w = array('ID' => $post['problemid'],);
			if ($this -> model_problem -> update_problem_flag($n, $w)) {
				if ($this -> model_log -> insert_log($l)) {
					$data['code'] = 3;
					echo json_encode($data);
					return;	
				}
			}
		}
		//判断前三血加分
		$solved_num = count($this -> model_log -> get_solved($post['problemid']));
		if ($solved_num == 0) {
			$add_point = 5;
		}
		else if ($solved_num == 1) {
			$add_point = 3;
		}
		else if ($solved_num == 2) {
			$add_point = 1;
		}
		//获得这次最终得分
		//以及判断IS_OVER
		$IS_OVER = $this -> model_config ->get_setting(3);

		$fin_point = $pro['BASEPOINT'] + $add_point;
		if ($pro['WEEK'] != $d['week']['value'] || $IS_OVER['value'] == 1) 
		{
			$fin_point =0;
		}
		$Point = $this -> model_point -> Get_Array_Byid($_SESSION['user']['ID']);
		$p['POINT'] = $Point['POINT'] + $fin_point;
		$n = array(
			'SOLVED' => $pro['SOLVED'] + 1,
			'SUBMITTED' => $pro['SUBMITTED'] + 1,
		);
		$w = array('ID' => $post['problemid'],);
		$l = array(
			'problemid' => $post['problemid'],
			'flag' => $post['flag'],
			'result' => 1,
			'fin_point' => $fin_point,
		);

		//更新week分数
		$week_update_point['WEEKPOINT'] = $Find_update_point['WEEKPOINT'] + $fin_point;
		//add?? 更新相应方向分数 与总分数
		$Point_update_type = $pro['CLASS']."POINT";
		$week_update_point[$Point_update_type] = $Find_update_point[$Point_update_type] + $fin_point;

		if($this -> model_point ->Update_Point_Byid($week_update_point,$_SESSION['user']['ID'],$d['week']['value']))
		{
		//!! 容易出错！！
		if ($this -> model_point -> Update_Point_Byid($p,$_SESSION['user']['ID'])) 
		{
			if ($this -> model_problem -> update_problem_flag($n, $w)) 
			{
				if ($this -> model_log -> insert_log($l)) 
				{
					$data['code'] = 1;
				}
			}
		}

		}
		echo json_encode($data);
	}
	public function week()
	{
		$data = array();
		$id = $this -> input -> get('id');
		$week = $this -> model_config -> get_setting(0);
		if (isset($id)) {
			
			$r = $this -> model_problem -> get_new($id,$_SESSION['user']['LEVEL']);
			$data['new'] = $this -> model_log -> is_done($r);
			$data['type'] = 1;
			$data['week'] = intval($id);
		}
		else{
			$data['type'] = 0;
			$r = $this -> model_problem -> get_new($week['value'],$_SESSION['user']['LEVEL']);
			$data['new'] = $this -> model_log -> is_done($r);
		}
		foreach ($data['new'] as $key => $value) {
			$data['new'][$key]['top'] = $this -> model_log -> get_top_three($value['ID']);
		}
		$this -> load -> view('platform/new', $data);
	}
	public function type()
	{
		$data = array('type' => 2,);
		$type = $this -> input -> get('type');
		$c = $this -> model_problem_class -> get_class_byID($type);
		if ($c) {
			$t = $c[0]['CLASS'];
		}
		else{
			$t = 'WEB';
		}
		$week = $this -> model_config -> get_setting(0);
		$data['t'] = $t;
		$r = $this -> model_problem -> get_type($t, $week['value'],$_SESSION['user']['LEVEL']);
		$data['new'] = $this ->model_log -> is_done($r);
		foreach ($data['new'] as $key => $value) {
			$data['new'][$key]['top'] = $this -> model_log -> get_top_three($value['ID']);
		}
		$this -> load -> view('platform/new', $data);
	}
	public function detail()
	{
		$data = array(
			'code' => 0,
		);
		$id = $this -> input -> get('id');
		if ($id > 0) {
			$data['log'] = $this -> model_log -> get_solved($id);
			$data['code'] = 1;
		}
		$this -> load -> view('platform/detail', $data);
	}
	public function logout()
	{
		$this -> session -> sess_destroy();		
		header("Location: /login");
	}
}