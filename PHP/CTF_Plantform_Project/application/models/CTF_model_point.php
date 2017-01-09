<?php

class CTF_model_point extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}
	public function Login_Week_Point($id,$week)
	{
		$return_value = true;

		$table = 'CTF_POINT';

		$sql_1 = "select * from CTF_POINT where ID = '$id' AND WEEK = '$week'";
		$result = $this -> db -> query($sql_1);
		$result = $result->result_array();

		if (empty($result)) 
		{
			//add_this_week
			$sql_2 = "select * from CTF_POINT where ID = '$id' ";
			$re = $this -> db -> query($sql_1);
			$re = $re->result_array();

			if(empty($re))
			{
				//add with zero
				$ALL_POINT = 0;
				$find_All_Point = $this -> Get_Array_Byid($id);
				if(!empty($find_All_Point))
				{
					$ALL_POINT = $find_All_Point['POINT'];
				}
				$data = array(
					'ID' => $id,
					'WEEK' => $week,
					'POINT' => $ALL_POINT,
					'WEEKPOINT' => 0,
					'WEBPOINT' => 0,
					'REPOINT' => 0, 
					'PWNPOINT' => 0,
					'PENTESTPOINT' => 0,
					'CRYPTOPOINT' => 0,
					'MISCPOINT' => 0,
					);
					$return_value = $this->Insert_Week_Point($data);
			}
			else
			{
				$re = re[0];
				$re['WEEK'] = $week;
				$re['WEEKPOINT'] = 0;
				
				$return_value = $this->Insert_Week_Point($re);

			}
		}

		return $return_value;
	}

	public function Insert_Week_Point($data)
	{
		$table = 'CTF_POINT';
		$sql = $this -> db -> insert_string($table, $data);
		$r = $this -> db -> query($sql);
		return $r;
	}

	public function Get_Array_Byid($id,$week = 0)
	{
		if($week == 0)
		{
			$sql = "SELECT * FROM CTF_POINT WHERE ID = '$id'";
		}
		else
		{
			$sql = "SELECT * FROM CTF_POINT WHERE ID = '$id' AND WEEK = '$week'";
		}

		$re = $this -> db -> query($sql);
		$re = $re ->result_array();

		if (empty($re)) 
        {
			return $re;
		}

		return $re[0];
	}

	public function Update_Point_Byid($data, $id, $week = 0)
	{
		$table = 'CTF_POINT';
		$where['ID'] = $id;

		if($week != 0)
		{
			$where['WEEK'] = $week;
		}

		$sql = $this -> db -> update_string($table, $data, $where);
		$re = $this -> db -> query($sql);
		return $re;
	}

}
?>

