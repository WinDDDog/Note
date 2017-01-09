<?php

class CTF_model_rank extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> database();
	}

    public function Get_Sum_Point_Byid($id)
    {
        $sql = "select sum(WEBPOINT),sum(REPOINT),sum(PWNPOINT),sum(PENTESTPOINT),sum(CRYPTOPOINT),sum(MISCPOINT) from CTF_POINT WHERE ID = '$id'";
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
        if(empty($re))
        {
            return $re;
        }
        else{
            return $re[0];
        }
        return $re;
    }
    public function Get_Week_Rank_LEVEL($week)
    {
        $sql = "select distinct CTF_POINT.WEBPOINT,CTF_POINT.REPOINT,CTF_POINT.PWNPOINT,CTF_POINT.PENTESTPOINT,CTF_POINT.CRYPTOPOINT,CTF_POINT.MISCPOINT,CTF_POINT.WEEKPOINT,CTF_USER.ID,CTF_USER.USERNAME from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID where CTF_POINT.WEEK='$week' and CTF_USER.LEVEL=1 order by CTF_POINT.WEEKPOINT DESC";
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
		return $re;	
    }

    public function Get_All_Rank_LEVEL()
    {
        $sql = "select distinct CTF_POINT.POINT,CTF_USER.ID,CTF_USER.USERNAME from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID where CTF_USER.LEVEL=1 order by CTF_POINT.POINT DESC";
        # CTF_POINT.WEBPOINT,CTF_POINT.REPOINT,CTF_POINT.PWNPOINT,CTF_POINT.PENTESTPOINT,CTF_POINT.CRYPTOPOINT,CTF_POINT.MISCPOINT,
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
		return $re;	
    }

    public function Get_Real_Rank()
    {
        $sql = "select distinct CTF_POINT.POINT,CTF_USER.ID,CTF_USER.USERNAME from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID order by CTF_POINT.POINT DESC";
        # CTF_POINT.WEBPOINT,CTF_POINT.REPOINT,CTF_POINT.PWNPOINT,CTF_POINT.PENTESTPOINT,CTF_POINT.CRYPTOPOINT,CTF_POINT.MISCPOINT,
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
		return $re;	
    }

    public function Get_Top_5_This_week($week)
    {
        $sql = "select * from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID where CTF_POINT.WEEK='$week' and CTF_USER.LEVEL=1 order by CTF_POINT.WEEKPOINT DESC LIMIT 0, 5";
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
		return $re;	
    }
    public function Get_Top_5_all()
    {
        $sql = "select distinct CTF_POINT.POINT,CTF_USER.ID,CTF_USER.USERNAME from CTF_USER left outer join CTF_POINT on CTF_USER.ID=CTF_POINT.ID where CTF_USER.LEVEL=1 order by CTF_POINT.POINT DESC LIMIT 0, 5";
        $re = $this -> db -> query($sql);
		$re = $re ->result_array();
		return $re;	
    }
    public function Get_Person_Week_PointRank_Byid($id,$week)
    {
        $all = $this->Get_Week_Rank_LEVEL($week);
        $data['rank'] = count($all);
        $data['person_num'] = count($all);
        $data['weekpoint'] = 0;
        $data['webpoint'] = 0;
        $data['repoint'] = 0;
        $data['pwnpoint'] = 0;
        $data['pentestpoint'] = 0;
        $data['miscpoint'] = 0;
        $data['cryptopoint'] = 0;
        
        foreach($all as $key => $value)
        {
            if ($value['ID'] == $id) {
				$data['rank'] =$key + 1;
                $data['weekpoint'] = $value['WEEKPOINT'];
                $data['webpoint'] = $value['WEBPOINT'];
                $data['repoint'] = $value['REPOINT'];
                $data['pwnpoint'] = $value['PWNPOINT'];
                $data['pentestpoint'] = $value['PENTESTPOINT'];
                $data['miscpoint'] = $value['MISCPOINT'];
                $data['cryptopoint'] = $value['CRYPTOPOINT'];
                
				break;
			}
        }
        return $data;
    }
    public function Get_Person_All_PointRank_Byid($id)
    {
        $all = $this->Get_All_Rank_LEVEL();
        $data['rank'] = count($all);
        $data['person_num'] = count($all);
        $data['point'] = 0;
        foreach($all as $key => $value)
        {
            if ($value['ID'] == $id) {
				$data['rank'] =$key + 1;
                $data['point'] = $value['POINT'];
				break;
			}
        }

        return $data;
    }
    public function Get_Person_Real_Rank_Byid($id)
    {
        $all = $this->Get_Real_Rank();
        $data['rank'] = count($all);
        $data['person_num'] = count($all);
        $data['point'] = 0;
        foreach($all as $key => $value)
        {
            if ($value['ID'] == $id) {
				$data['rank'] =$key + 1;
                $data['point'] = $value['POINT'];
				break;
			}
        }

        $re = $this -> Get_Sum_Point_Byid($id);
        if(empty($re))
        {
            $data['webpoint'] = 0;
            $data['repoint'] = 0;
            $data['pwnpoint'] = 0;
            $data['pentestpoint'] = 0;
            $data['miscpoint'] = 0;
            $data['cryptopoint'] = 0;
        }
        else
        {
            $data['webpoint'] = $re["sum(WEBPOINT)"];
            $data['repoint'] = $re["sum(REPOINT)"];
            $data['pwnpoint'] = $re["sum(PWNPOINT)"];
            $data['pentestpoint'] = $re["sum(PENTESTPOINT)"];
            $data['miscpoint'] = $re["sum(MISCPOINT)"];
            $data['cryptopoint'] = $re["sum(CRYPTOPOINT)"];
            
        }

        return $data;
    }
}
?>
