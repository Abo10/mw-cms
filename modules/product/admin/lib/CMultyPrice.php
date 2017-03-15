<?php

class CMultyPrice{
	protected $tbl_name = 'multyprice';
	function __construct(){

	}
	
	function AddLinks($args, $obj_id, $obj_type){
// 		var_dump($_POST);die;
		$reformated = array();
		$attr_groups = $args['attr_multiprice'];
		$prices = $args['price'];
		$attaches = $args['attach_id'];
		$count = $args['count'];
		$stocks = $args['in_stock'];
		$orders = $args['order'];
		foreach ($attr_groups as $index=>$links){
			if($links==""){
				unset($attr_groups[$index]);
				unset($prices[$index]);
				unset($attaches[$index]);
				unset($count[$index]);
				unset($stocks[$index]);
				unset($orders[$index]);
			}
			else{
				$reformated[$index]['attr_links'] = json_decode($links, true);
				$reformated[$index]['price'] = $prices[$index];
				$reformated[$index]['o_count'] = $count[$index];
				$reformated[$index]['o_img'] = $attaches[$index];
				$reformated[$index]['order'] = $orders[$index];
				$reformated[$index]['instock'] = $stocks[$index];
				$reformated[$index]['obj_id'] = $obj_id;
				$reformated[$index]['obj_type'] = $obj_type;
			}
		}
// 		var_dump($reformated);die;
		//Format to insert type reformated array
		$ret = array();
		$atr_l = array();
		foreach ($reformated as $index=>$values){
			$cur_link = 1;
			foreach ($values['attr_links'] as $attr_group=>$val_group){
				$ret[$index]['attr_group'.$cur_link] = $attr_group;
// 				var_dump($val_group);die;
				$ret[$index]['attr_value'.$cur_link] = $val_group;
				$cur_link++;
			}
			$ret[$index]['price'] = $values['price'];
			$ret[$index]['o_count'] = $values['o_count'];
			$ret[$index]['o_img'] = $values['o_img'];
			$ret[$index]['order'] = $values['order'];
			$ret[$index]['instock'] = $values['instock'];
			$ret[$index]['obj_id'] = $obj_id;
			$ret[$index]['obj_type'] = $obj_type;
		}
// 		var_dump($ret);die;
		//Now insert all this to db but in transaction
		Cmwdb::$db->startTransaction();
		foreach ($ret as $values){
			if(!Cmwdb::$db->insert($this->tbl_name, $values)){
				Cmwdb::$db->rollback();
				return false;
			}
		}
// 		var_dump($ret);die;
		Cmwdb::$db->commit();
// 		echo 1;/*  */
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		$res = Cmwdb::$db->get($this->tbl_name, null, ['id']);
		$ret = array();
		foreach ($res as $values){
			$ret[] = $values['id'];
		}
		$mod = CModule::LoadModule('attributika');
// 		echo 1;
		if(is_object($mod)){
 			$mod->AddSubjectsLinks($ret, 'multyprice');
		}
// 		echo Cmwdb::$db->getLastError();
// 		die;
		return true;

	}
	
	function GetLinks($subject_id, $subject_type){
		Cmwdb::$db->where('obj_id', $subject_id);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		$tmp = array();
		$as_multyprice = array();
		if(!empty($res)){
			$attributika = CModule::LoadModule('attributika');
			foreach ($res as $values){
				$ret[$values['id']] = $values;
				$as_multyprice[$values['attr_group1']][] = $values['attr_value1'];
				$as_multyprice[$values['attr_group2']][] = $values['attr_value2'];
				if($values['o_img']){
					$at = new CAttach($values['o_img']);
					$ret[$values['id']]['image_url'] = $at->GetURL();
				}
				
				if(is_object($attributika)){
					$att_d = $attributika->GetValuesOnly($values['attr_group1']);
					if(isset($att_d[$values['attr_value1']])){
						$ret[$values['id']]['attr1_text'] = $att_d[$values['attr_value1']]['unit_value'];
					}
					$att_d = $attributika->GetValuesOnly($values['attr_group2']);
					if(isset($att_d[$values['attr_value2']])){
						$ret[$values['id']]['attr2_text'] = $att_d[$values['attr_value2']]['unit_value'];
					}
						
				}
			}
		}
		return $ret;
	}
	
	function GetLinksArray($subject_id, $subject_type){
		if(is_numeric($subject_id))Cmwdb::$db->where('obj_id', $subject_id);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		foreach ($res as $values)$ret[$values['id']]=$values;
		return $ret;
	}
	function EditLinks($args, $obj_id, $obj_type, $in_transaction=false){
		$reformated = array();
		$attr_groups = $args['attr_multiprice'];
		$prices = $args['price'];
		$attaches = $args['attach_id'];
		$count = $args['count'];
		$stocks = $args['in_stock'];
		$orders = $args['order'];
		$edit_ids = $args['edit_id'];
		foreach ($attr_groups as $index=>$links){
			if($links==""){
				unset($attr_groups[$index]);
				unset($prices[$index]);
				unset($attaches[$index]);
				unset($count[$index]);
				unset($stocks[$index]);
				unset($orders[$index]);
				unset($edit_ids[$index]);
			}
			else{
				$reformated[$index]['attr_links'] = json_decode($links, true);
				$reformated[$index]['price'] = $prices[$index];
				$reformated[$index]['o_count'] = $count[$index];
				$reformated[$index]['o_img'] = $attaches[$index];
				$reformated[$index]['order'] = $orders[$index];
				$reformated[$index]['instock'] = $stocks[$index];
				$reformated[$index]['obj_id'] = $obj_id;
				$reformated[$index]['obj_type'] = $obj_type;
				$reformated[$index]['edit_id'] = $edit_ids[$index];
			}
		}
		// 		var_dump($reformated);die;
		//Format to insert/update/delete type reformated array
		$ret = array();
		$atr_l = array();
		foreach ($reformated as $index=>$values){
			$cur_link = 1;
			foreach ($values['attr_links'] as $attr_group=>$val_group){
				$ret[$index]['attr_group'.$cur_link] = $attr_group;
				// 				var_dump($val_group);die;
				$ret[$index]['attr_value'.$cur_link] = $val_group;
				$cur_link++;
			}
			$ret[$index]['price'] = $values['price'];
			$ret[$index]['o_count'] = $values['o_count'];
			$ret[$index]['o_img'] = $values['o_img'];
			$ret[$index]['order'] = $values['order'];
			$ret[$index]['instock'] = $values['instock'];
			$ret[$index]['obj_id'] = $obj_id;
			$ret[$index]['obj_type'] = $obj_type;
			$ret[$index]['edit_id'] = $values['edit_id'];
		}
		$existsInBase = array();
		//Take exists multyprice rows for compaer
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		$exists = Cmwdb::$db->get($this->tbl_name, null, ['id', 'attr_group1', 'attr_group2', 'attr_value1', 'attr_value2']);
		if(!empty($exists)){
			foreach ($exists as $vals)$existsInBase[$vals['id']] = "";
		}
// 		var_dump($ret);die;
		//Starting add/update/remove actions
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		$attr_forAdds = array();
		$attr_forRemove = array();
		$reconvert = array();
		foreach ($exists as $values)$reconvert[$values['id']] = $values;
		$exists = $reconvert;
		foreach ($ret as $multyprices){
			$edit_id = $multyprices['edit_id'];
			unset($multyprices['edit_id']);
			if($edit_id){//Need for update
				unset($exists[$edit_id]);
				Cmwdb::$db->where('id', $edit_id);
				if(!Cmwdb::$db->update($this->tbl_name, $multyprices)){//was error
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
			else{//Add new multyprice row
				if(isset($multyprices['attr_group1']) && isset($multyprices['attr_value1']))
					$attr_forAdds[$multyprices['attr_group1']][$multyprices['attr_value1']] = "";
				
				if(isset($multyprices['attr_group2']) && isset($multyprices['attr_value2']))
					$attr_forAdds[$multyprices['attr_group2']][$multyprices['attr_value2']] = "";
							
// 				$attr_forAdds[$multyprices['attr_group2']][$multyprices['attr_value2']] = "";
				if(!Cmwdb::$db->insert($this->tbl_name, $multyprices)){//was error
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
// 		var_dump($exists);die;

		//Now delete all rows, that miss in new array
		foreach ($exists as $id=>$values){
			$attr_forRemove[$values['attr_group1']][$values['attr_value1']]="";
			$attr_forRemove[$values['attr_group2']][$values['attr_value2']]="";
			Cmwdb::$db->where('id', $id);
			if(!Cmwdb::$db->delete($this->tbl_name)){//was error
				if(!$in_transaction)Cmwdb::$db->rollback();
				return false;
			}
		}
		
		//Now add newly added atributes in attributika and remove removed
// 		$attributika = CModule::LoadModule('attributika');
// 		if(is_object($attributika)){
// 			if(!$attributika->AddSubjects($obj_id, $obj_type, $attr_forAdds, true)){
// 				if(!$in_transaction)Cmwdb::$db->rollback();
// 				return false;
// 			}
// 			if(!$attributika->DeleteSubjects_ByAttributes($obj_id, $obj_type, $attr_forRemove, true)){
// 				if(!$in_transaction)Cmwdb::$db->rollback();
// 				return false;
// 			}
// 		}
		
		Cmwdb::$db->commit();
		return true;
	}
	
// 	function GetLinks($obj_id, $obj_type, $m_group=false){
// 		if(is_array($obj_id))Cmwdb::$db->where('obj_id', $obj_id, "in");
// 		if(is_numeric($obj_id))Cmwdb::$db->where('obj_id', $obj_id);
// 		Cmwdb::$db->where('obj_type', $obj_type);
// 		$res = Cmwdb::$db->get($this->tbl_name);
// 		var_dump($res);die;
// 	}

	function RemoveLinks($obj_id, $obj_type){
		if(is_array($obj_id))Cmwdb::$db->where('obj_id', $obj_id, "in");
		if(is_numeric($oid))Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		return Cmwdb::$db->delete($this->tbl_name);
	}
	
	function GetSubjectsByMLink($mlinks){
		if(is_numeric($mlinks))Cmwdb::$db->where('id', $mlinks);
		if(is_array($mlinks))Cmwdb::$db->where('id', $mlinks, "in");
		$res = Cmwdb::$db->get($this->tbl_name, null, ['id','obj_id']);
// 		var_dump($res);die;
		if(!empty($res)){
			$ret = array();
			foreach ($res as $vals){
				$ret[$vals['id']] = $vals['obj_id'];
			}
			return $ret;
		}
		return array();
	}
	
	function RemoveLinksByAttributes($attr_group, $attr_value){
		$ids = array();
		Cmwdb::$db->where('attr_group1', $attr_group);
		Cmwdb::$db->where('attr_value1', $attr_value);
		$res = Cmwdb::$db->get($this->tbl_name, null,['id']);
		if(!empty($res)){
			foreach ($res as $vals){
				if(in_array($vals['id'], $ids))continue;
				$ids[] = $vals['id'];
			}
		}
		Cmwdb::$db->where('attr_group2', $attr_group);
		Cmwdb::$db->where('attr_value2', $attr_value);
		$res = Cmwdb::$db->get($this->tbl_name, null,['id']);
		if(!empty($res)){
			foreach ($res as $vals){
				if(in_array($vals['id'], $ids))continue;
				$ids[] = $vals['id'];
			}
		}
		if(!empty($ids)){
			Cmwdb::$db->where('id', $ids, "in");
			Cmwdb::$db->delete($this->tbl_name);
			$carts = CModule::LoadModule('cart');
			if(is_object($carts)){
				$carts->DeleteFromCartInDB($ids, 'multiprice');
			}
		}
		return true;
	}
	
	function ApplyDiscount($id, $value, $action="plus", $type="fixed"){
		try {
			Cmwdb::$db->where('obj_id', $id);
			Cmwdb::$db->where('obj_type', 'product');
			$ids = Cmwdb::$db->get($this->tbl_name, null, ['id']);
			$needs = array();
			foreach ($ids as $tmp_id){
				$id = $tmp_id['id'];
				if($value===0 || $value<0)throw new Exception('Invalid argument, the value cant be 0 or minus',5);
				Cmwdb::$db->where('id', $id);
				$current_price = Cmwdb::$db->getValue($this->tbl_name, 'price');
				if($current_price){
					switch ($action){
						case 'plus':{
							$new_price = 0;
							switch ($type){
								case 'fixed':{
									$new_price = $current_price+$value;
									Cmwdb::$db->where('id', $id);
									if(!Cmwdb::$db->update($this->tbl_name, ['price'=>$new_price])){
										throw new Exception('Failed update price, DB error was accoured',3);
									}
									
									break;
								}
								case 'percent':{
									$new_price = $current_price+($current_price/100*$value);
									Cmwdb::$db->where('id', $id);
									if(!Cmwdb::$db->update($this->tbl_name, ['price'=>$new_price])){
										throw new Exception('Failed update price, DB error was accoured',3);
									}
									
									break;
								}
								default:{
									throw new Exception("Undefined action",2);
								}
							}
							break;
						}
						case 'minus':{
							switch ($type){
								case 'fixed':{
									$new_price = $current_price-$value;
									if($new_price<=0)throw new Exception('Invalid area of price, price is null or minus');
									Cmwdb::$db->where('id', $id);
									if(Cmwdb::$db->update($this->tbl_name, ['price'=>$new_price])){
										throw new Exception('Failed update price, DB error was accoured',3);
									}
									
									break;
								}
								case 'percent':{
									$new_price = $current_price-($current_price/100*$value);
									if($new_price<=0)throw new Exception('Invalid area of price, price is null or minus');
									Cmwdb::$db->where('id', $id);
									if(Cmwdb::$db->update($this->tbl_name, ['price'=>$new_price])){
										throw new Exception('Failed update price, DB error was accoured',3);
									}
									
									break;
								}
								default:{
									throw new Exception("Undefined action",2);
								}
							}
							break;
						}
						default:{
							throw new Exception("Undefined action",2);
						}
					}
				}
			}
			
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		return true;
	}
	
	function GetPriceRange($id){
		try {
			Cmwdb::$db->where('obj_id', $id);
			Cmwdb::$db->where('obj_type', 'product');
			$res = Cmwdb::$db->getOne($this->tbl_name, "min(price) min, max(price) max");
			return $res;
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		return true;
	}
	
}
?>