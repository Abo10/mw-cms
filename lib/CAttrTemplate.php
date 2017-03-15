<?php

class CAttrTemplate{
	protected $tbl_name = "attr_templates";
	protected $datas = array();
	
	function __construct($args=null){
		if($args){
			if(is_array($args)){
				if(isset($args['attr_id']) &&  isset($args['attr'])){
					$this->datas = $args;
					$this->datas['attr'] = json_decode($args['attr'], true);
				}
			}
			if(is_numeric($args)){
				Cmwdb::$db->where('attr_id');
				$res = Cmwdb::$db->getOne($this->tbl_name);
				if(!empty($res)){
					$this->datas = $res;
					$this->datas['attr'] = json_decode($res['attr'], true);
	//				var_dump($this->datas);
				}
			}
			else{
				if(is_string($args)){
					Cmwdb::$db->where('shortkey');
					$res = Cmwdb::$db->getOne($this->tbl_name);
					if(!empty($res)){
						$this->datas = $res;
						$this->datas['attr'] = json_decode($res['attr'], true);
						
					}
				}
			}
		}
	}
	
	function CreateTemplate($shortkey, $attr){
		if(Cmwdb::$db->insert($this->tbl_name, array("shortkey"=>$shortkey, "attr"=>json_encode($attr)))){
			$this->datas['attr_id'] = Cmwdb::$db->getInsertId();
			$this->datas['shortkey'] = $shortkey;
			$this->datas['attr'] = $attr;
			return true;
		}
		return false;
	}
	
	function GetDatas(){return $this->datas;}
	
	function GetID(){return $this->datas['attr_id'];}
	
	function EditDatas($shortkey, $args){
		$args = json_encode($args);
		Cmwdb::$db->where('shortkey', $shortkey);
		if(Cmwdb::$db->update($this->tbl_name, array("attr"=>$args)))
				return true;
		return false;
	}
	

}
?>