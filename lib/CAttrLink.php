<?php

class CAttrLink{
	protected $tbl_name = "attr_links";
	protected $datas = array();
	
	function __construct($args=null){
		if($args){
			if(is_numeric($args)){
				Cmwdb::$db->where('l_id', $args);
				$res = Cmwdb::$db->getOne($this->tbl_name);
				if(!empty($res)){
					$this->datas = $res;
					$this->datas['attr_values'] = json_decode($res['attr_values'], true);
//					if($this->datas['template_id'])$this->datas['template'] = new CAttrTemplate($this->datas['template_id']);
//					else $this->datas['template'] = new CAttrTemplate($this->datas['shortkey']);
				}
				
			}
			if(is_array($args)){
				$this->datas = $args;
				//FIXME: Try and verify correction 
				$this->datas['attr_values'] = json_decode($args['attr_values'], true);
			}
			
		}
	}
	
	function CreateLink($args){
		CErrorHandling::RegisterHandle("add_attribute_post");
		if(is_array($args)){
			if($args['template_id']==="")return false;
			$values = $args['attr_values'];
			$args['attr_values'] = json_encode($args['attr_values']);
			if(Cmwdb::$db->insert($this->tbl_name, $args)){
				$this->datas = $args;
				if($values=="")$this->datas['attr_values'] = array();
				else $this->datas['attr_values'] = $values;
				return true;
			}
		}
		return false;
	}
	
	function GetDatas(){
		$ret = array();
		$ret = $this->datas;
		$ret['attr_template'] = new CAttrTemplate($this->datas['template_id']);
		$ret['attr_template'] = $ret['attr_template']->GetDatas();
		return $ret;
	}
}
?>