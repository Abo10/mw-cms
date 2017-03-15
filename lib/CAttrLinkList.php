<?php

class CAttrLinkList{
	protected $tbl_name = "attr_link_languaged";
	protected $objects = array();
	
	function __construct($obj_id=null, $obj_type=null){
		if($obj_id && $obj_type){
			Cmwdb::$db->where('obj_id', $obj_id);
			Cmwdb::$db->where('obj_type', $obj_type);
			$res = Cmwdb::$db->get($this->tbl_name);
			if(!empty($res)){
				$tmp_array = array();
			
 				foreach ($res as $values){
 					$tmp_array[$values['l_group']][$values['attr_lang']] = $values;
 				}
 				$tmp = array();
 				foreach ($tmp_array as $key=>$values){
 					$current_template = null;
 					foreach ($values as $lang=>$content){
 						$tmp[$key]['template_id'] = $content['template_id'];
 						$tmp[$key]['l_group'] = $content['l_group'];
 						$tmp[$key]['obj_id'] = $content['obj_id'];
 						$tmp[$key]['obj_type'] = $content['obj_type'];
 						$tmp[$key]['attr_values'][$lang] = $content['attr_values'];
 						$current_template = $content['template_id'];
 						
 					}
 					$tmpl = new CAttrTemplate($current_template);
 					$tmp[$key]['attr_template'] = $tmpl->GetDatas();
 					
 				}
 				$this->objects = $tmp;
 				
			}
			
		}
	}
	
	function GetDatas(){
		$ret = array();

		if(!empty($this->objects)){
			foreach ($this->objects as $l_group=>$values){
				foreach ($values['attr_values'] as $lang=>$title){
					$tmp = array();
					$tmp['t_id'] = $values['template_id'];
					$tmp['l_id'] = $values['l_group'];
					$tmp['t_title'] = isset($values['attr_template']['attr'][$lang])?$values['attr_template']['attr'][$lang]:'ss';
					$tmp['attr_values'] = $title;
					$ret[$lang][] = $tmp;
				}
			}
		}
		return $ret;
	}
	
	function DeleteAssociations($obj_id, $obj_type){
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		return Cmwdb::$db->delete($this->tbl_name);
	}
}
?>