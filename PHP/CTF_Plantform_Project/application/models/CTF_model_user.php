<?php
/*
//Controll_model
	public function get_all_user()
	{
		$sql = "SELECT * FROM CTF_USER ORDER by IS_LOGIN desc, TIME desc";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		return $r;
	}


    public function update_user($data, $id)
	{
		$table = 'CTF_USER';
		$w['ID'] = $id;
		$sql = $this -> db -> update_string($table, $data, $w);
		$r = $this -> db -> query($sql);
		return $r;
	}
    
//Platform_model
    public function update_user($value)
	{
		$table = "CTF_USER";
		$w = array(
			'USERNAME' => $_SESSION['user']['USERNAME'] ,
		);
		$sql = $this -> db -> update_string($table, $value, $w);
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function get_user($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM CTF_USER WHERE ID = '$id'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		if (empty($r)) {
			return $r;
		}
		else{
			return $r[0];
		}
	}
    public function get_rank()
	{
		$data = array();
		$sql = "SELECT * FROM CTF_USER ORDER BY POINT DESC";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		$data['sum'] = count($r);
		foreach ($r as $key => $value) {
			if ($value['USERNAME'] == $_SESSION['user']['USERNAME']) {
				$data['rank'] =$key + 1;
				break;
			}
		}
		return $data;
	}
    public function get_top($i)
	{
		$sql = [
			"SELECT * FROM CTF_USER WHERE `IS_LOGIN` = 1 ORDER BY POINT DESC LIMIT 0, 5",
			"SELECT * FROM CTF_USER WHERE `IS_LOGIN` = 1 ORDER BY POINT DESC",
		];
		$r = $this -> db -> query($sql[$i]);
		$r = $r ->result_array();
		return $r;	
	}
    public function check_login($user, $pass)
	{
		$username = $this -> db -> escape($user);
		$password = md5($pass);
		$sql = "SELECT * FROM CTF_USER WHERE USERNAME = $username AND PASSWORD = '$password'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
	public function check_user($v)
	{
		$username = $this -> db -> escape($v);
		$sql = "SELECT * FROM CTF_USER WHERE USERNAME = $username";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		return $r;
	}
    public function insert_user($value)
	{
		$data = array(
			'TOKEN' => $this -> CreateRandomString(32),
			'USERNAME' => $value['username'],
			'PASSWORD' => $value['password'],
			'EMAIL' => $value['email'],
			'QQ' => $value['qq'],
			'POINT' => 0, 
		);
		if (strlen($data['USERNAME']) > 64 || strlen($data['EMAIL']) > 64 || strlen($data['QQ']) > 64) {
			echo "长度超过限制！";
			return 0;
		}
		$table = 'CTF_USER';
		$sql = $this -> db -> insert_string($table, $data);
		$r = $this -> db -> query($sql);
		return $r;
	}
*/

class CTF_model_user extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
    public function get_all_user()
	{
		$sql = "select distinct CTF_POINT.POINT,CTF_USER.ID,CTF_USER.USERNAME,CTF_USER.EMAIL,CTF_USER.QQ,CTF_USER.IS_LOGIN,CTF_USER.IP,CTF_USER.TIME,CTF_USER.SCHOOLID,CTF_USER.REALNAME,CTF_USER.COLLEGE,CTF_USER.LEVEL from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID ORDER by CTF_USER.IS_LOGIN desc, CTF_USER.TIME desc";
		$r = $this -> db -> query($sql); # SELECT * FROM CTF_USER ORDER by IS_LOGIN desc, TIME desc;
		$r = $r -> result_array();
		return $r;
	}
    public function update_user_byID($data, $id)
	{
		$table = 'CTF_USER';
		$w['ID'] = $id;
		$sql = $this -> db -> update_string($table, $data, $w);
		$r = $this -> db -> query($sql);
		return $r;
	}
    public function get_user_byID($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM CTF_USER WHERE ID = '$id'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		if (empty($r)) 
        {
			return $r;
		}
		else
        {
			return $r[0];
		}
	}
	/*
    public function get_rank() //todo 需要优化
	{
		$data = array();
		$sql = "SELECT * FROM CTF_USER ORDER BY POINT DESC";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		$data['sum'] = count($r);
		foreach ($r as $key => $value) {
			if ($value['USERNAME'] == $_SESSION['user']['USERNAME']) {
				$data['rank'] =$key + 1;
				break;
			}
		}
		return $data;
	}
    public function get_top($i) //todo 需要修改接口使他更直观
	{
		$sql = [
			"SELECT * FROM CTF_USER WHERE `IS_LOGIN` = 1 ORDER BY POINT DESC LIMIT 0, 5",
			"SELECT * FROM CTF_USER WHERE `IS_LOGIN` = 1 ORDER BY POINT DESC",
		];
		$r = $this -> db -> query($sql[$i]);
		$r = $r ->result_array();
		return $r;	
	}
	*/
    public function check_login($user, $pass)
	{
		$username = $this -> db -> escape($user);
		$password = md5($pass);
		$sql = "SELECT * FROM CTF_USER WHERE USERNAME = $username AND PASSWORD = '$password'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
    public function check_user($v)
	{
		$username = $this -> db -> escape($v);
		$sql = "SELECT * FROM CTF_USER WHERE USERNAME = $username";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		return $r;
	}
    public function insert_user($value)
	{
		$data = array(
			'TOKEN' => md5(time()),
			'USERNAME' => $value['username'],
			'PASSWORD' => $value['password'],
			'EMAIL' => $value['email'],
			'QQ' => $value['qq'],
			//'POINT' => 0, 
			//add
			'SCHOOLID' =>   $value['schoolid'],
			'REALNAME' =>  $value['realname'],
			'COLLEGE' =>  $value['college'],

			'IS_LOGIN' => 1,
		);
		if (strlen($data['USERNAME']) > 64 || strlen($data['EMAIL']) > 64 || strlen($data['QQ']) > 64) {
			echo "长度超过限制！";
			return 0;
		}
		//add
		if (strlen($data['SCHOOLID']) > 20 || strlen($data['REALNAME']) > 20 || strlen($data['COLLEGE']) > 20) {
			echo "长度超过限制！->ADD";
			return 0;
		}
		$table = 'CTF_USER';
		$sql = $this -> db -> insert_string($table, $data);
		$r = $this -> db -> query($sql);
		return $r;
	}

}
?>

