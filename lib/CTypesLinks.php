<?php

class CTypesLinks{
	protected $s_l = null;//meky shatin field_name
	protected $m_l = null;//shaty mekin field_name
	protected $s_l_val = null;//meky shatin value
	protected $m_l_vals = null;//shaty mekin values
	protected $tbl_name = "";
	
	function __construct(){}
	
	function LoadValues($s_link){
		$this->s_l_val = $s_link;
		Cmwdb::$db->where($this->s_l, $this->s_l_val);
		$res = Cmwdb::$db->get($this->tbl_name, null, $this->m_l);
		if(!empty($res)){
			foreach ($res as $key=>$value)$this->m_l_vals[$key] = $value[$this->m_l];
			return $this->m_l_vals;
		}
		return false;
	}
	
	function AddLinks($s_l_val, $m_l_vals){
		$this->s_l_val = $s_l_val;	
		Cmwdb::$db->startTransaction();
		foreach ($m_l_vals as $value){
			if(Cmwdb::$db->insert($this->tbl_name, array($this->m_l=>$value, $this->s_l=>$s_l_val))){
				$this->m_l_vals[] = $value;
			}
			else{
				Cmwdb::$db->rollback();
				return false;
			}
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function GetAsArray(){
		$ret['group_id'] = $this->s_l_val;
		$ret['values'] = $this->m_l_vals;
		return $ret;
	}
	
	function GetAsArrayJSON(){
		return json_encode($this->GetAsArray());
	}
	
	function DeleteThis(){
		Cmwdb::$db->where($this->s_l, $this->s_l_val);
		Cmwdb::$db->delete($this->tbl_name);
		$this->s_l_val = null;
		$this->m_l_vals = array();
	}
	
	function GetCount(){
		return count($this->m_l_vals);
	}
	
	function GetCount_SLink($cid){
		Cmwdb::$db->where($this->m_l, $cid);
		$res = Cmwdb::$db->get($this->tbl_name);
		return count($res);
	}
	
	function GetBySLink($cid){
		Cmwdb::$db->where($this->m_l, $cid);
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		foreach ($res as $values)$ret[] = $values['post_id'];
		return $ret;
	}
	
}
?>