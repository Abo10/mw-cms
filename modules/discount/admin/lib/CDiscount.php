<?php
class CDiscount{
	protected $tbl_name = "module_discount";
	
	function __construct(){
// 		echo "Hello from discount module";
	}
	
	function GetDatas($oid=null, $assoc=null){
		
	}
	
	function CreateDiscount($obj_id, $obj_type, $args, $in_transaction=false){
// 		var_dump($args);die;
		if(isset($args['has_discount']) && $args['has_discount']){
// 			var_dump($args);die;
			$counts = $args['count'];
			$percent = $args['percent'];
			$queryData = array();
			$queryData['obj_id'] = $obj_id;
			$queryData['obj_type'] = $obj_type;
			$queryData['discount_type'] = "percent";
			if(!$in_transaction)Cmwdb::$db->startTransaction();
			$this->DeleteDiscount($obj_id, $obj_type);
			foreach ($counts as $index=>$value){
				if(!$percent[$index] || !$value)continue;
				$queryData['discount_value'] = $percent[$index];
				$queryData['discount_argument2'] = $value;
				if(!Cmwdb::$db->insert($this->tbl_name, $queryData)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
				
			}
			
			
			if(!$in_transaction)Cmwdb::$db->commit();
			
			return true;
		}
		else $this->DeleteDiscount($obj_id, $obj_type);
		return false;
	}
	
	function DeleteDiscount($obj_id, $obj_type){
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		return Cmwdb::$db->delete($this->tbl_name);
	}
	
	function AddLinks($obj_id, $obj_type, $args, $in_transaction=false){
		return $this->CreateDiscount($obj_id, $obj_type, $args, $in_transaction);
	}
	
	function GetLinks($obj_id, $obj_type){
// 		echo $obj_id.'-'.$obj_type;
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		Cmwdb::$db->orderBy('discount_argument2', "asc");
		$res = Cmwdb::$db->get($this->tbl_name);
// 		echo Cmwdb::$db->getLastQuery();
		$ret = array();
		$ret['count'] = array();
		$ret['percent'] = array();
		if(!empty($res)){
			$ret['has_discount'] = 1;
			foreach ($res as $values){
				$ret['count'][$values['id']] = $values['discount_argument2'];
				$ret['percent'][$values['id']] = $values['discount_value'];
			}
		}
		else $ret['has_discount'] = 0;
		return $ret;
	}
	
}

?>