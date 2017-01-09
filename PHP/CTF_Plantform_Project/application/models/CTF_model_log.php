<?php
class CTF_model_log extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
	public function insert_log($value)
	{
		$table = "CTF_LOG";
		$data = array(
			'USERNAME' => $_SESSION['user']['USERNAME'],
			'USERID' => $_SESSION['user']['ID'],
			'PROBLEMID' => $value['problemid'],
			'SOLVED_TIME' => time(),
			'SUBMIT_CONTENT' => $value['flag'],
			'RESULT' => $value['result'],
			'FINAL_POINT' => $value['fin_point'],
		);
		$sql = $this -> db -> insert_string($table, $data);
		$r = $this -> db -> query($sql);
		return $r;
	}
	public function get_solved($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM CTF_LOG WHERE PROBLEMID = '$id' AND RESULT = 1 ORDER BY ID";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
	public function check_repeat($p)
	{
		$username = $this -> db -> escape($_SESSION['user']['USERNAME']);
		$problemid = intval($p);
		$sql = "SELECT * FROM CTF_LOG WHERE USERNAME=$username AND RESULT=1 AND PROBLEMID='$problemid'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		if (empty($r)) {
			return 0;
		}
		else{
			return 1;
		}
	}
	public function get_top_three($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM CTF_LOG WHERE PROBLEMID='$id' AND RESULT = 1 ORDER BY SOLVED_TIME ASC LIMIT 0, 3";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
//is done
    public function is_done($pro) //todo!! 修改这个调用  只允许查询这一个表
	{
		$username = $this -> db -> escape($_SESSION['user']['USERNAME']);
		foreach ($pro as $key => $value) {
			$sql = "SELECT * FROM CTF_LOG WHERE USERNAME = $username AND RESULT = 1 AND PROBLEMID = $value[ID]";
			$c = $this -> db -> query($sql);
			$c = $c ->result_array();
			if (count($c) == 1) {
				$pro[$key]['done'] = 1;
			}
			else{
				$pro[$key]['done'] = 0;
			}
		}
		return $pro;
	}
	public function get_sublog()
	{
		$sql = "SELECT * FROM CTF_LOG WHERE RESULT=1 ORDER BY SOLVED_TIME DESC LIMIT 0, 5";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
	public function get_last_time($userid)
	{
		$userid = intval($userid);
		$sql = "SELECT * FROM CTF_LOG WHERE USERID='$userid' AND RESULT=1 ORDER BY SOLVED_TIME DESC LIMIT 0, 1";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
	public function get_mypro()
	{
		$username = $this -> db -> escape($_SESSION['user']['USERNAME']);
		$sql = "SELECT * FROM CTF_LOG WHERE USERNAME = $username AND RESULT = 1";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}

}
?>