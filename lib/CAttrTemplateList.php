<?php

class CAttrTemplateList{
	protected $tbl_name = "attr_templates";
	protected $templates = array();
	
	function __construct(){
		$res = Cmwdb::$db->get($this->tbl_name);
		if(!empty($res)){
			foreach ($res as $value){
				$this->templates[$value['attr_id']] = new CAttrTemplate($value);
			}
		}
	}
	
	function GetDatas(){
		$ret = array();
		foreach ($this->templates as $key=>$values){
			$ret[$key] = $this->templates[$key]->GetDatas();
		}
		return $ret;
	}
	
	function AddAttribute($shortkey, $args){
		$atr = new CAttrTemplate();
		if($atr->CreateTemplate($shortkey, $args)){
			$this->templates[$atr->GetID()] = $atr;
			return true;
		}
		return false;
	}
}
?>