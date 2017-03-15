<?php


class CAttrLinkL extends CAttrLink{
	
	function __construct($args=null, $lang = null){
		$this->tbl_name = "attr_link_languaged";
		if(!$lang)$lang = CLanguage::getInstance()->getDefault();
		if($args){
			if(is_numeric($args)){
				Cmwdb::$db->where('l_group', $args);
				$res = Cmwdb::$db->get($this->tbl_name);
				if(!empty($res)){
					foreach ($res as $values){
						$this->datas['template_id'] = $values['template_id'];
						$this->datas['l_group'] = $values['l_group'];
						$this->datas['obj_id'] = $values['obj_id'];
						$this->datas['obj_type'] = $values['obj_type'];
						$this->datas['attr_values'][$values['attr_lang']] = $values['attr_values'];
					}
					$tmp = new CAttrTemplate($this->datas['template_id']);
					$this->datas['attr_template'] = $tmp->GetDatas();
//					var_dump($this->datas);
				}
		
			}
			if(is_array($args)){
				$this->datas = $args;
				//FIXME: Try and verify correction
			}
				
		}
	}
	
	function CreateLink($args){
		if($args['template_id']){
			Cmwdb::$db->startTransaction();
			$l_group = Cmwdb::$db->getOne($this->tbl_name, 'max(l_group) l_group');
			if ($l_group['l_group']) $l_group['l_group']++;
			else $l_group['l_group'] = 1;
			$l_group = $l_group['l_group'];
			
			foreach ($args['attr_values'] as $key=>$value){
				$ins_obj = array();
				$ins_obj['attr_values'] = $value;
				$ins_obj['attr_lang'] = $key;
				$ins_obj['l_group'] = $l_group;
				$ins_obj['obj_id'] = $args['obj_id'];
				$ins_obj['obj_type'] = $args['obj_type'];
				$ins_obj['template_id'] = $args['template_id'];
				if(!Cmwdb::$db->insert($this->tbl_name, $ins_obj)){
					Cmwdb::$db->rollback();
					return false;
				}
			}
			Cmwdb::$db->commit();
			return true;
		}
		return false;
	}
	
	function GetDatas(){
		return $this->datas;
	}
	
}