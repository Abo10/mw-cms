<?php

class CLinksExt{
	protected $tbl_name = "";
	protected $obj_ids = null;
	protected $obj_type = null;
	protected $s_link = null;
	
	function __construct(){
		
	}
	
	function GetLinks($obj_id=null, $obj_type=null, $s_link=null, $group_by_mlink = false){
		if($obj_type){
			Cmwdb::$db->where('obj_type', $obj_type);
		}
		if($s_link){
			if(is_array($s_link))Cmwdb::$db->where('s_link', $s_link, "in");
			else Cmwdb::$db->where('s_link', $s_link);
		}
		if($obj_id){
			if(is_array($obj_id))Cmwdb::$db->where('obj_id', $obj_id, "in");
			else Cmwdb::$db->where('obj_id', $obj_id);
		}
// 		var_dump($s_link);
// 		echo $this->tbl_name;
// 		var_dump(Cmwdb::$db);
		$res = Cmwdb::$db->get($this->tbl_name);
// 		return $res;
		$ret = array();
		if($group_by_mlink){
			foreach ($res as $values){
				$ret[$values['obj_id']][] = $values['s_link'];
			}
		}
		else{
			foreach ($res as $values){
				$ret[$values['s_link']][] = $values['obj_id'];
			}
				
		}
		
		return $ret;
	}
	
	function AddLinks($obj_id, $obj_type, $s_link, $remove_exists=true){
		if(is_array($obj_id)){
			if(is_numeric($s_link)){
				if($remove_exists){
					Cmwdb::$db->where('obj_id', $obj_id, "in");
					Cmwdb::$db->where('obj_type', $obj_type);
					Cmwdb::$db->delete($this->tbl_name);
				}
				foreach ($obj_id as $ids){
					$queryData['obj_id'] = $ids;
					$queryData['obj_type'] = $obj_type;
					$queryData['s_link'] = $s_link;
					if(!Cmwdb::$db->insert($this->tbl_name, $queryData))return false;
				}
				return true;
			}
			if(is_array($s_link)){
				if($remove_exists){
					Cmwdb::$db->where('obj_id', $obj_id, "in");
					Cmwdb::$db->where('obj_type', $obj_type);
					Cmwdb::$db->delete($this->tbl_name);
				}
				
				foreach ($s_link as $s){
					foreach ($obj_id as $obj){
						$queryData['obj_id'] = $obj;
						$queryData['obj_type'] = $obj_type;
						$queryData['s_link'] = $s;
						if(!Cmwdb::$db->insert($this->tbl_name, $queryData))return false;
						
					}
				}
				return true;
			}
			return false;
		}
		if(is_numeric($obj_id)){
			if(is_numeric($s_link)){
				if($remove_exists){
					Cmwdb::$db->where('obj_id', $obj_id);
					Cmwdb::$db->where('obj_type', $obj_type);
					Cmwdb::$db->delete($this->tbl_name);
				}
				
				$queryData['obj_id'] = $obj_id;
				$queryData['obj_type'] = $obj_type;
				$queryData['s_link'] = $s_link;
				return Cmwdb::$db->insert($this->tbl_name, $queryData);
			}
			if(is_array($s_link)){
				if($remove_exists){
					Cmwdb::$db->where('obj_id', $obj_id);
					Cmwdb::$db->where('obj_type', $obj_type);
					Cmwdb::$db->delete($this->tbl_name);
				}

				foreach ($s_link as $s){
					$queryData['obj_id'] = $obj_id;
					$queryData['obj_type'] = $obj_type;
					$queryData['s_link'] = $s;
					if(!Cmwdb::$db->insert($this->tbl_name, $queryData))return false;
				}
				return true;
			}
		}
		return false;
	}
	
	function RemoveMultyLinks($m_link, $obj_type){
		if(is_array($m_link)){
			Cmwdb::$db->where('obj_id', $m_link, "in");
			Cmwdb::$db->where('obj_type', $obj_type);
		}
		if(is_numeric($m_link)){
			Cmwdb::$db->where('obj_id', $m_link);
			Cmwdb::$db->where('obj_type', $obj_type);
		}
		return Cmwdb::$db->delete($this->tbl_name);
	}
	
	function GetLinksCompaq($obj_id=null, $obj_type=null, $s_link=null, $group_by_mlink = false){
		if($obj_type){
			Cmwdb::$db->where('obj_type', $obj_type);
		}
		if($s_link){
			if(is_array($s_link))Cmwdb::$db->where('s_link', $s_link, "in");
			else Cmwdb::$db->where('s_link', $s_link);
		}
		if($obj_id){
			if(is_array($obj_id))Cmwdb::$db->where('obj_id', $obj_id, "in");
			else Cmwdb::$db->where('obj_id', $obj_id);
		}
		// 		var_dump($s_link);
		// 		echo $this->tbl_name;
		// 		var_dump(Cmwdb::$db);
		$res = Cmwdb::$db->get($this->tbl_name);
		// 		return $res;
		$ret = array();
		if($group_by_mlink){
			foreach ($res as $values){
				$ret[] = $values['obj_id'];
			}
		}
		else{
			foreach ($res as $values){
				$ret[] = $values['s_link'];
			}
	
		}
		return $ret;
	}
	

}
?>