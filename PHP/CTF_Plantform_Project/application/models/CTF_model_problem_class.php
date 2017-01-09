<?php
class CTF_model_problem_class extends CI_Model 
{
    function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
	public function get_all_problem_class()
	{
		$sql = "SELECT * FROM CTF_PROBLEM_CLASS";
		$r = $this -> db -> query($sql);
		$r = $r -> result_array();
		return $r;
	}
	public function get_class_byID($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM CTF_PROBLEM_CLASS WHERE ID = '$id'";
		$r = $this -> db -> query($sql);
		$r = $r ->result_array();
		return $r;
	}
}
?>