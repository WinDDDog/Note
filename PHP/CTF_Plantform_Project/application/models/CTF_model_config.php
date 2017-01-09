<?php
class CTF_model_config extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
    public function get_setting($v) //todo 修改使接口更易使用
    {
        $key = ['CURWEEK', 'PUBLIC_NOTICE', 'IS_OPEN', 'IS_OVER'];
        $week_data = array();
        $sql = "SELECT * FROM CTF_CONFIG WHERE `KEY`='$key[$v]'";
        $r = $this -> db -> query($sql);
        $r = $r -> result_array();
        if (empty($r)) {
            $week_data['code'] = 0;
            $week_data['value'] = "";
        }
        else{
            $week_data['code'] = 1;
            $week_data['value'] = $r[0]['VALUE'];
        }
        return $week_data;
    }

    public function update_setting($value, $w) //todo 修改使接口更易使用
    {
        $key = ['CURWEEK', 'PUBLIC_NOTICE', 'IS_OPEN','IS_OVER'];
        $where['KEY'] = $key[$w];
        $data['VALUE'] = $value;
        $table = "CTF_CONFIG";
        $sql = $this -> db -> update_string($table, $data, $where);
        $r = $this -> db -> query($sql);
        return $r;
    }
}


/*
public function get_setting($v)
{
    $key = ['CURWEEK', 'PUBLIC_NOTICE', 'IS_OPEN'];
    $week_data = array();
    $sql = "SELECT * FROM CTF_CONFIG WHERE `KEY`='$key[$v]'";
    $r = $this -> db -> query($sql);
    $r = $r -> result_array();
    if (empty($r)) {
        $week_data['code'] = 0;
        $week_data['value'] = "";
    }
    else{
        $week_data['code'] = 1;
        $week_data['value'] = $r[0]['VALUE'];
    }
    return $week_data;
}

public function update_setting($value, $w)
{
    $key = ['CURWEEK', 'PUBLIC_NOTICE', 'IS_OPEN'];
    $where['KEY'] = $key[$w];
    $data['VALUE'] = $value;
    $table = "CTF_CONFIG";
    $sql = $this -> db -> update_string($table, $data, $where);
    $r = $this -> db -> query($sql);
    return $r;
}

*/
?>