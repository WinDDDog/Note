<?php
class CTF_model_problem extends CI_Model 
{
    function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
    public function get_all_problem()
	{
		$sql = "SELECT * FROM CTF_PROBLEM ORDER BY ID";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		return $r;
	}
    public function get_problem_byID($id) // get_problem
	{
		$pro_data = array(
			'code' => 0,
			'problem' => null,
		);
		$id = intval($id);
		$sql = "SELECT * FROM CTF_PROBLEM WHERE ID = '$id'";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		if (!empty($r)) {
			$pro_data['code'] = 1;
			$pro_data['problem'] = $r[0];
		}
		return $pro_data;
	}
    public function update_problem($d, $w)
	{
		$table = "CTF_PROBLEM";
		$where['ID'] = $w;
		$data = array(
			'PROBLEM_TITLE' => $d['title'],
			'PROBLEM_DESCRIBLE' => $d['describe'],
			'PROBLEM_HINT' => $d['hint'],
			'WEEK' => $d['week'],
			'CLASS' => $d['problemclass'],
			'BASEPOINT' => $d['point'],
			'FLAG' => trim($d['flag']),
			'LEVEL' => $d['level'],
		);
		if (strlen($data['PROBLEM_TITLE']) > 64 || strlen($data['FLAG']) > 100) {
			echo "长度超过限制！";
			return 0;
		}
		$sql = $this -> db -> update_string($table, $data, $where);
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function insert_problem($d)
	{
		$data = array(
			'ID' => null,
			'PROBLEM_TITLE' => $d['title'],
			'PROBLEM_DESCRIBLE' => $d['describe'],
			'PROBLEM_HINT' => $d['hint'],
			'WEEK' => $d['week'],
			'CLASS' => $d['problemclass'],
			'BASEPOINT' => $d['point'],
			'FLAG' => trim($d['flag']),
			'SOLVED' => 0,
			'SUBMITTED' => 0,
			'LEVEL' => $d['level'],
		);
		if (strlen($data['PROBLEM_TITLE']) > 64 || strlen($data['FLAG']) > 100) {
			echo "长度超过限制！";
			return 0;
		}
		$table = "CTF_PROBLEM";
		$sql = $this -> db -> insert_string($table, $data);
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function del_problem($id)
	{
		$id = intval($id);
		$sql = "DELETE FROM CTF_PROBLEM WHERE ID = '$id'";
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function update_problem_flag($value, $w) //todo! 需要修改这个代码使之可以被 insert_problem重用
	{
		$table = "CTF_PROBLEM";
		$sql = $this -> db -> update_string($table, $value, $w);
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function get_proinfo($id) //todo! 需要修改这个代码使之可以被 get_problem_byID 重用 
	{
		//需要改
		$id = intval($id);
		$sql = "SELECT * FROM CTF_PROBLEM WHERE ID='$id'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		if (!empty($r)) {
			return $r[0];
		}
		else{
			return $r;
		}
		
	}
    public function get_type($class, $week,$level)//todo! 需要修改来进行重用
	{
		$week = intval($week);
		$class = $this -> db -> escape($class);
		$sql = "SELECT * FROM CTF_PROBLEM WHERE CLASS = $class AND WEEK <= '$week' AND LEVEL <= '$level'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
    public function get_new($week,$level)
	{
		$week = intval($week);
		$sql = "SELECT * FROM CTF_PROBLEM WHERE WEEK = '$week' AND LEVEL <= '$level' ORDER BY ID DESC";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		
		return $r;
	}
}
?>