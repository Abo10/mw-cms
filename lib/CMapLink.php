<?php

class CMapLink{
	protected $tbl_name = "map_link";
	protected $datas = array();
	
	function __construct($args=null){
		if(is_array($args)){
			if(isset($args['obj_id']) && $args['obj_type']){
				Cmwdb::$db->where('obj_id', $args['obj_id']);
				Cmwdb::$db->where('obj_type', $args['obj_type']);
				$res = Cmwdb::$db->get($this->tbl_name);
				if(!empty($res)){
					foreach ($res as $value){
						$this->datas[$value['id']] = $value;
					}
				}
			}
		}
		if(is_numeric($args)){
			Cmwdb::$db->where('id', $args);
			$res = Cmwdb::$db->getOne($this->tbl_name);
			$this->datas = $res;
		}
	}
	
	function CreateMapLinks($args){
		$obj_id = $args['obj_id'];
		$obj_type = $args['obj_type'];
		$args = $this->ConvertArray($args);
		
		$queryData = array();
		$queryData['obj_id'] = $obj_id;
		$queryData['obj_type'] = $obj_type;
		if(isset($args['maps'])){
			foreach ($args['maps'] as $values){
				$queryData['lat'] = $values['lat'];
				$queryData['lng'] = $values['lng'];
				$queryData['img_id'] = $values['img_id'];
				$queryData['map_title'] = $values['map_title'];
				$queryData['args'] = $values['args'];
				if(!Cmwdb::$db->insert($this->tbl_name, $queryData)){
					Cmwdb::$db->rollback();
					return false;
				}
			}
		}
		return true;
	}
	
	protected function ConvertArray($args){
		if(isset($args['lat']) && isset($args['lng']) && isset($args['title'])){
			$lat = $args['lat'];
			$lng = $args['lng'];
			$titles = $args['title'];
			$ret = array();
			$ret['obj_id'] = $args['obj_id'];
			$ret['obj_type'] = $args['obj_type'];
			
			$retp = array();
			$temp2 = array();
			$langs = CLanguage::getInstance()->get_langsUser();
			foreach ($titles as $lang=>$values){
				foreach ($values as $index=>$title){
					$retp[$index][$lang] = $title;
				}
			}
			foreach ($retp as $index=>$values){
				$temp2[$index]['lat'] = $lat[$index];
				$temp2[$index]['lng'] = $lng[$index];
				$temp2[$index]['map_title'] = json_encode($retp[$index]);
				$temp2[$index]['args'] = "";
				if(isset($args['img_id']))
					$temp2[$index]['img_id'] = $args['img_id'];
				else $temp2[$index]['img_id'] = null;
			}
			$ret['maps'] = $temp2;
			return $ret;
		}
		return array();
		
	}
	
	function GetDatas(){return $this->datas;}
	
	function DeleteLinks($pid, $type){
		Cmwdb::$db->where('obj_id', $pid);
		Cmwdb::$db->where('obj_type', $type);
		return Cmwdb::$db->delete($this->tbl_name);
	}
}