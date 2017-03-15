<?php

class CAttributika{
	protected $tbl_attr_templates = "attributika_templates";
	protected $tbl_attr_units = "attributika_units";
	protected $tbl_attr_links_values = "attributika_links";
	protected $tbl_attr_links_subject = "attributika_subject_links";
	protected $tbl_subjects = "attributika_subjects";
	protected $datas = array();
	protected $configs = array();
	function __construct($attr_id=null){

		$this->configs = CConfig::GetBlock();
		if($attr_id){
			Cmwdb::$db->where('attr_group', $attr_id);
			$res = Cmwdb::$db->get($this->tbl_attr_templates);
			if(!empty($res)){
				foreach ($res as $values)
					$this->datas[$values['lang']] = $values;
			}
		}
	}
	
	function AddAttribute($args, $units){
		$objects = null;
		$objects_type = null;
		$all_objects = null;
		if(isset($args['obj_ids'])){
			$objects = $args['obj_ids'];
			unset($args['obj_ids']);
		}
		if(isset($args['obj_all'])){
			$all_objects = $args['obj_all'];
			unset($args['obj_all']);
		}
		if(isset($args['obj_type'])){
			$objects_type = $args['obj_type'];
			unset($args['obj_type']);
		}
		$order = 0;
		if(isset($args['order'])){
			$order = $args['order'];
			unset($args['order']);
		}
		Cmwdb::$db->startTransaction();
		$attr_group = Cmwdb::$db->getOne($this->tbl_attr_templates, 'max(attr_group) attr_group');
		if ($attr_group['attr_group']) $attr_group['attr_group']++;
		else $attr_group['attr_group'] = 1;
		$attr_group = $attr_group['attr_group'];
		$queryData = array();
		foreach ($args as $lang=>$values){
			$queryData['attr_group'] = $attr_group;
			if($values['attr_name']==""){
				Cmwdb::$db->rollback();
				return false;
			}
			$queryData['attr_name'] = $values['attr_name'];
			if(isset($values['attr_image'])){
				$tmp_img = new CImageExt($this->configs['work_dir']);
				$res = $tmp_img->CreateImage($values['attr_image']); 
				if(!$res){
					Cmwdb::$db->rollback();
					return false;
				}
				$queryData['attr_image'] = $res;
			}
			
			$queryData['template_order'] = $order;
			if(isset($values['is_active']))$queryData['is_active'] = 1;
			$queryData['lang'] = $lang;
			if(!Cmwdb::$db->insert($this->tbl_attr_templates, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
		}
		if(!$this->AddUnits($units, $attr_group)){
			Cmwdb::$db->rollback();
			return false;
		}
		if($all_objects){
			$tmp_module = CModule::LoadModule($objects_type);
			if(is_object($tmp_module)){
				$ids = $tmp_module->GetAllGroups();
				if(is_array($ids)){
					$queryData = array();
					foreach ($ids as $values){
						$queryData['obj_id'] = $values;
						$queryData['obj_type'] = $objects_type;
						$queryData['attr_group'] = $attr_group;
						if(!Cmwdb::$db->insert($this->tbl_attr_links_subject, $queryData)){
							Cmwdb::$db->rollback();
							return false;
						}
					}
						
				}
			}
		}
		else{
			if(is_array($objects)){
				$queryData = array();
				foreach ($objects as $values){
					$queryData['obj_id'] = $values;
					$queryData['obj_type'] = $objects_type;
					$queryData['attr_group'] = $attr_group;
					if(!Cmwdb::$db->insert($this->tbl_attr_links_subject, $queryData)){
						Cmwdb::$db->rollback();
						return false;
					}
				}
			}
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function AddUnits($units, $attr_id){
		Cmwdb::$db->where('t_link', $attr_id);
		$unit_group = Cmwdb::$db->getOne($this->tbl_attr_units, 'max(unit_group) unit_group');
		if ($unit_group['unit_group']) $unit_group['unit_group']++;
		else $unit_group['unit_group'] = 1;
		$unit_group = $unit_group['unit_group'];
		$unit_group++;
		$temp_array = array();
		$queryData = array();
		foreach ($units as $lang=>$values){
			foreach ($values['name'] as $index=>$val){
				$temp_array[$index][$lang]['unit'] = $val;
				
			}
			foreach ($values['order'] as $index=>$val){
				$temp_array[$index][$lang]['order'] = $val;
			
			}
		}
		$is_non = false;
		foreach($temp_array as $index=>$values){
			foreach ($values as $vals){
				if($vals['unit']=="")$is_non = true;
			}
			if($is_non){
				unset($temp_array[$index]);
				$is_non = false;
			}
		}
		foreach ($temp_array as $index=>$values){
			foreach ($values as $key=>$vals){
				$queryData['unit_group'] = $unit_group;
				$queryData['t_link'] = $attr_id;
				$queryData['unit'] = $vals['unit'];
				$queryData['unit_lang'] = $key;
				$queryData['u_order'] = $vals['order'];
				if(isset($vals['unit_img'])){
					$tmp_img = new CImageExt($this->configs['work_dir']);
					$res = $tmp_img->CreateImage($vals['unit_img']);
					if(!$res){
						return false;
					}
					$queryData['unit_img'] = $res;
				}
				if(!Cmwdb::$db->insert($this->tbl_attr_units, $queryData)){
					return false;
				}
			}
			$unit_group++;
		}
		return true;
	}
	
	function GetAttribute($lang = null, $attribute_id=null, $modules=null){
		if(!$lang)$lang = CLanguage::getDefaultUser();
		if(is_array($attribute_id)){
			Cmwdb::$db->where($this->tbl_attr_templates.'.attr_group',$attribute_id, "in");
		}
		if(is_numeric($attribute_id)){
			Cmwdb::$db->where($this->tbl_attr_templates.'.attr_group',$attribute_id);
		}
		Cmwdb::$db->where($this->tbl_attr_templates.'.lang', $lang);
 //		Cmwdb::$db->where($this->tbl_attr_units.'.unit_lang', $lang);
		Cmwdb::$db->join($this->tbl_attr_units,$this->tbl_attr_templates.'.attr_group='.$this->tbl_attr_units.'.t_link','left');
		$res = Cmwdb::$db->get($this->tbl_attr_templates);
		$ret = array();
		if(!empty($res)){
			foreach ($res as $values){
				$ret[$values['attr_group']]['attr_name'] = $values['attr_name'];
				$ret[$values['attr_group']]['template_order'] = $values['template_order'];
				$ret[$values['attr_group']]['attr_image'] = $values['attr_image'];
				$ret[$values['attr_group']]['is_active'] = $values['is_active'];
				$ret[$values['attr_group']]['units'][$values['unit_group']]['unit'] = $values['unit'];
				if($values['unit_img']){
					$at = new CAttach($values['unit_img']);
					$ret[$values['attr_group']]['units'][$values['unit_group']]['unit_image'] = $at->GetURL();
					$ret[$values['attr_group']]['units'][$values['unit_group']]['unit_image_id'] = $values['unit_img'];
				}
				else{
					$ret[$values['attr_group']]['units'][$values['unit_group']]['unit_image'] = null;
					$ret[$values['attr_group']]['units'][$values['unit_group']]['unit_image_id'] = null;
				}

				
			}
			foreach ($ret as $attr_group=>$unneed){
				$ret[$attr_group]['values'] = $this->GetValuesOnly($attr_group, $lang);
			}
			if(is_array($modules)){
				foreach ($modules as $mod_name){
					$mod = CModule::LoadModule($mod_name);
					if(!is_object($mod))continue;
					foreach ($ret as $attr_group=>$unneed){
						$links = $this->GetSubjectsLinks($attr_group, $mod_name);
						if(!empty($links)){
							$ret[$attr_group][$mod_name] = $mod->GetDatas($links, $lang);
						}
						else $ret[$attr_group][$mod_name] = array();
					}
				}
			}
		}
		return $ret;
	}

	function GetAttributeOne($lang = null, $attribute_id=null, $modules=null){
		if(!$lang)$lang = CLanguage::getDefaultUser();
		if(is_numeric($attribute_id)){
			Cmwdb::$db->where($this->tbl_attr_templates.'.attr_group',$attribute_id);
		}
		Cmwdb::$db->where($this->tbl_attr_templates.'.lang', $lang);
		//		Cmwdb::$db->where($this->tbl_attr_units.'.unit_lang', $lang);
		Cmwdb::$db->join($this->tbl_attr_units,$this->tbl_attr_templates.'.attr_group='.$this->tbl_attr_units.'.t_link','left');
		$res = Cmwdb::$db->get($this->tbl_attr_templates);
		$ret = array();
		if(!empty($res)){
			foreach ($res as $values){
				$ret['attr_name'] = $values['attr_name'];
				$ret['template_order'] = $values['template_order'];
				$ret['attr_image'] = $values['attr_image'];
				$ret['is_active'] = $values['is_active'];
				$ret['units'][$values['unit_group']]['unit'] = $values['unit'];
				if($values['unit_img']){
					$at = new CAttach($values['unit_img']);
					$ret['units'][$values['unit_group']]['unit_image'] = $at->GetURL();
					$ret['units'][$values['unit_group']]['unit_image_id'] = $values['unit_img'];
				}
				else{
					$ret['units'][$values['unit_group']]['unit_image'] = null;
					$ret['units'][$values['unit_group']]['unit_image_id'] = null;
				}
	
	
			}
			$ret['values'] = $this->GetValuesOnly($attribute_id, $lang);
		
			if(is_array($modules)){
				foreach ($modules as $mod_name){
					$mod = CModule::LoadModule($mod_name);
					if(!is_object($mod))continue;
					foreach ($ret as $attr_group=>$unneed){
						$links = $this->GetSubjectsLinks($attr_group, $mod_name);
						if(!empty($links)){
							$ret[$mod_name] = $mod->GetDatas($links, $lang);
						}
						else $ret[$mod_name] = array();
					}
				}
			}
		}
		return $ret;
	}
	
	function GetAttributeAllLangs($attribute_id=null, $modules=null){
		if(is_array($attribute_id)){
			Cmwdb::$db->where($this->tbl_attr_templates.'.attr_group',$attribute_id, "in");
		}
		if(is_numeric($attribute_id)){
			Cmwdb::$db->where($this->tbl_attr_templates.'.attr_group',$attribute_id);
		}
		Cmwdb::$db->join($this->tbl_attr_units,$this->tbl_attr_templates.'.attr_group='.$this->tbl_attr_units.'.t_link','left');
		$res = Cmwdb::$db->get($this->tbl_attr_templates);
		$ret = array();
		if(!empty($res)){
			foreach ($res as $values){
				if((is_null($values['unit_lang'])) || $values['lang']===$values['unit_lang']){
					$ret[$values['lang']][$values['attr_group']]['attr_name'] = $values['attr_name'];
					$ret[$values['lang']][$values['attr_group']]['template_order'] = $values['template_order'];
					$ret[$values['lang']][$values['attr_group']]['attr_image'] = $values['attr_image'];
					$ret[$values['lang']][$values['attr_group']]['is_active'] = $values['is_active'];
					if($values['unit'])$ret[$values['lang']][$values['attr_group']]['units'][$values['unit_group']]['unit'] = $values['unit'];
					if($values['unit_img']){
						$at = new CAttach($values['unit_img']);
						$ret[$values['lang']][$values['attr_group']]['units'][$values['unit_group']]['unit_image'] = $at->GetURL();
						$ret[$values['lang']][$values['attr_group']]['units'][$values['unit_group']]['unit_image_id'] = $values['unit_img'];
					}
				}
			}
			if(is_array($modules)){
				foreach ($modules as $mod_name){
					foreach ($ret as $lang=>$values){
						foreach ($values as $attr_group=>$unned){
							$ret[$lang][$attr_group][$mod_name] = $this->GetSubjectsLinks($attr_group, $mod_name);
						}
					}
				}
			}
		}
		return $ret;
	}
	
	function GetSubjectsLinks($attr_group, $subject_type){
		if(is_array($attr_group))Cmwdb::$db->where('attr_group', $attr_group, "in");
		if(is_numeric($attr_group))Cmwdb::$db->where('attr_group', $attr_group);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_attr_links_subject);
		$ret = array();
		foreach ($res as $values)$ret[] = $values['obj_id'];
		return $ret;
	}
	
	function GetLinks($obj_id, $obj_type){
		
	}
	
	function GetLinksBySubjects($subject_ids, $subject_type){
// 		var_dump($subject_ids);
		if(is_array($subject_ids)){
			Cmwdb::$db->where('obj_id', $subject_ids, "in");
		}
		if(is_numeric($subject_ids)){
			Cmwdb::$db->where('obj_type', $subject_type);
		}
		Cmwdb::$db->orderBy('obj_id');
		$res = Cmwdb::$db->get($this->tbl_attr_links_subject);
		$ret = array();
		foreach ($res as $values){
			$ret[$values['obj_id']][] = $values['attr_group'];
		}
		return $ret;
	}
	
	function GetAttributesBySubjects($subject_ids, $subject_type, $lang = null){
// 		var_dump($subject_ids);die;
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		$subject_links = $this->GetLinksBySubjects($subject_ids, $subject_type);
// 		var_dump($subject_links);die;
		$ret = array();
		foreach ($subject_links as $subject_id=>$links){
			$ret[$subject_id] = $this->GetAttribute($lang, $links);
		}
		$ret2 = array();
		foreach ($ret as $values){
			foreach ($values as $attr_group=>$vals)$ret2[$attr_group] = $vals;
		}
		return $ret2;
	}
	
	function GetUnits($t_link, $lang=null){
// 		echo $lang;die;
// 		if(!$lang) $lang=CLanguage::getDefaultUser();
		$ret = array();
		$ret_val = array();
		$res = array();
		if(is_numeric($t_link)){
			Cmwdb::$db->where('t_link', $t_link);
			if($lang)Cmwdb::$db->where('unit_lang', $lang);
			Cmwdb::$db->orderBy('u_order');
			$res = Cmwdb::$db->get($this->tbl_attr_units);
			foreach ($res as $values){
				$ret[$values['unit_lang']][$values['unit_group']]['unit'] = $values['unit'];
				$ret[$values['unit_lang']][$values['unit_group']]['order'] = $values['u_order'];
				$ret[$values['unit_lang']][$values['unit_group']]['unit_img'] = $values['unit_img'];
			}
			Cmwdb::$db->where('attr_group', $t_link);
			if($lang)Cmwdb::$db->where('lang', $lang);
			$res = Cmwdb::$db->get($this->tbl_attr_links_values);
			foreach ($res as $values){
				$ret_val[$values['lang']][$values['val_group']]['unit_value'] = $values['unit_value'];
				$ret_val[$values['lang']][$values['val_group']]['unit_group'] = $values['unit_group'];
				if(isset($values['unit_image'])){
					$at = new CAttach($values['unit_image']);
					$ret_val[$values['lang']][$values['val_group']]['unit_image'] = $at->GetURL();
					$ret_val[$values['lang']][$values['val_group']]['unit_image_id'] = $values['unit_image'];
				}
				else {
					$ret_val[$values['lang']][$values['val_group']]['unit_image'] = ""; 
					$ret_val[$values['lang']][$values['val_group']]['unit_image_id'] = null;
				}
			}
		}
// 		var_dump($ret);
// 		echo '<hr>';
// 		var_dump($ret_val);die;
		return array('units'=>$ret, 'values'=>$ret_val);
	}
	
	function GetUnitsOnly($attr_group, $lang=null){
		$ret = array();
		$res = array();
		if(is_numeric($attr_group)){
			Cmwdb::$db->where('t_link', $attr_group);
			if($lang)Cmwdb::$db->where('unit_lang', $lang);
			Cmwdb::$db->orderBy('u_order');
			$res = Cmwdb::$db->get($this->tbl_attr_units);
			foreach ($res as $values){
				$ret[$values['unit_group']]['unit'] = $values['unit'];
				$ret[$values['unit_group']]['order'] = $values['u_order'];
				$ret[$values['unit_group']]['unit_img'] = $values['unit_img'];
			}
		}
		return $ret;
	}
	
	function GetValuesOnly($attr_group, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		$ret_val = array();
		$res = array();
		if(is_numeric($attr_group)){
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->where('lang', $lang);
			$res = Cmwdb::$db->get($this->tbl_attr_links_values);
			foreach ($res as $values){
				$ret_val[$values['val_group']]['unit_value'] = $values['unit_value'];
				$ret_val[$values['val_group']]['unit_group'] = $values['unit_group'];
				if(isset($values['unit_image'])){
					$at = new CAttach($values['unit_image']);
					$ret_val[$values['val_group']]['unit_image'] = $at->GetURL();
					$ret_val[$values['val_group']]['unit_image_id'] = $values['unit_image'];
				}
				else{
					$ret_val[$values['val_group']]['unit_image'] = ""; 
					$ret_val[$values['val_group']]['unit_image_id'] = null;
				}
			}
				
		}
		return $ret_val;
		
	}
	
	function GetUnitByLang($attr_group, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		$ret = array();
		$ret_val = array();
		$res = array();
		if(is_numeric($attr_group)){
			Cmwdb::$db->where('t_link', $attr_group);
			if($lang)Cmwdb::$db->where('unit_lang', $lang);
			Cmwdb::$db->orderBy('u_order');
			$res = Cmwdb::$db->get($this->tbl_attr_units);
			foreach ($res as $values){
				$ret[$values['unit_group']]['unit'] = $values['unit'];
				$ret[$values['unit_group']]['order'] = $values['u_order'];
				$ret[$values['unit_group']]['unit_img'] = $values['unit_img'];
			}
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->where('lang', $lang);
			$res = Cmwdb::$db->get($this->tbl_attr_links_values);
			foreach ($res as $values){
				$ret_val[$values['val_group']]['unit_value'] = $values['unit_value'];
				$ret_val[$values['val_group']]['unit_group'] = $values['unit_group'];
				if(isset($values['unit_image'])){
					$at = new CAttach($values['unit_image']);
					$ret_val[$values['val_group']]['unit_image'] = $at->GetURL();
				}
				else $ret_val[$values['val_group']]['unit_image'] = ""; 
			}
				
		}
		return array('units'=>$ret, 'values'=>$ret_val);
	}
	
	function GetUnitsJSON($t_link){
		return json_encode($this->GetUnits($t_link));
	}
	
	function AddValues($args, $attr_group, $in_transaction=false){
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		Cmwdb::$db->where('attr_group', $attr_group);
		$val_group = Cmwdb::$db->getOne($this->tbl_attr_links_values, 'max(val_group) val_group');
		if ($val_group['val_group']) $val_group['val_group']++;
		else $val_group['val_group'] = 1;
		$val_group = $val_group['val_group'];
		$queryData = array();
		$start_group = $val_group;
		$converted_array = array();
		foreach ($args as $lang=>$values){
			foreach ($values as $index=>$vals)
				$converted_array[$index][$lang] = $vals;
		}
		$is_passed = false;
		foreach ($converted_array as $index=>$values){
			foreach ($values as $vals){
				if($vals['value']==="")$is_passed = true;
			}
			if($is_passed)unset($converted_array[$index]);
			$is_passed = false;
		}
		if(empty($converted_array)){
			if(!$in_transaction)
				Cmwdb::$db->rollback();
			return false;
		}
		foreach ($converted_array as $index=>$vals){
			$val_group = $start_group;
			foreach ($vals as $lang=>$values){
				$cur_image = null;
				if($lang==CLanguage::getInstance()->getDefaultUser()){
					if($values['attach_id'])$cur_image=$values['attach_id'];
				}
				$queryData['attr_group'] = $attr_group;
				if(isset($values['unit']))
					$queryData['unit_group'] = $values['unit'];
				$queryData['lang'] = $lang;
				$queryData['unit_value'] = $values['value'];
				if($values['attach_id'])
					$queryData['unit_image'] = $values['attach_id'];
				else $queryData['unit_image'] = $cur_image;
				$queryData['val_group'] = $val_group;
				if(isset($vals['val_order']))$queryData['order'] = $values['val_order'];
				if(!Cmwdb::$db->insert($this->tbl_attr_links_values, $queryData)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
			$start_group++;
		}
		if(!$in_transaction)Cmwdb::$db->commit();
// 		die;
		return true;
	}
	
	function EditValues($args, $attr_group){
		echo "starting edit<br>";
// 		var_dump($args);die;
		Cmwdb::$db->startTransaction();
		$res = $this->GetUnits($attr_group);
		$compaer = array();
		$next_val_group = 0;
		if(!empty($res)){
			foreach ($res['values'] as $lang=>$values){
				foreach ($values as $val_group=>$unned){
					$compaer[$val_group] = "";
					$next_val_group = $val_group;
				}
			}
		}
		$passed_links = array();
		foreach ($args as $values){
			foreach ($values as $index=>$vals){
				if(is_numeric($index) && $vals['value']=="")$passed_links[$index] = "";
			}
		}
		$next_val_group++;
		//edit all exists links
		$queryData = array();
		$new_values = array();
		foreach ($args as $lang=>$values){
			foreach ($values as $val_group=>$vals){
				if(!in_array($vals['value'], $passed_links)){
					$queryData = array();
					if(is_numeric($val_group)){
						$queryData['unit_image'] = $vals['attach_id'];
						$queryData['unit_value'] = $vals['value'];
						if(isset($vals['unit']))
							$queryData['unit_group'] = $vals['unit'];
						Cmwdb::$db->where('attr_group', $attr_group);
						Cmwdb::$db->where('val_group', $val_group);
						Cmwdb::$db->where('lang', $lang);
						if(!Cmwdb::$db->update($this->tbl_attr_links_values, $queryData)){
							echo "Passed to update ".$queryData['unit_group'];
							Cmwdb::$db->rollback();
							return false;
						}
						if(isset($compaer[$val_group]))unset($compaer[$val_group]);
					}
					else{
						$new_values[$lang][] = $vals;
					}
				}
			}
		}
// 		var_dump($new_values);die;
		foreach ($compaer as $val_group=>$unneed){
// 			echo "Try to delete $attr_group - $val_group".'<br>';
			if(!$this->DeleteValue($attr_group, $val_group)){
// 				echo 'Passed to delete attribute - '.$attr_group.'/'.$val_group;
				Cmwdb::$db->rollback();
				return false;
			}
		}
		foreach ($passed_links as $val_group=>$unneed){
			if(!$this->DeleteValue($attr_group, $val_group)){
// 				echo 'Passed to delete attribute - '.$attr_group.'/'.$val_group;
				Cmwdb::$db->rollback();
				return false;
			}
		}
		//add new links
		$this->AddValues($new_values, $attr_group,true);
// 			echo 'Passed to delete attribute - '.$attr_group.'/';
// 			var_dump($new_values);
// 			Cmwdb::$db->rollback();
// 			return false;
// 		}
// echo 'before end';
		Cmwdb::$db->commit();
		return true;
	}
	
	function JustAddValues($args, $attr_group){
		Cmwdb::$db->startTransaction();
		$res = $this->GetUnits($attr_group);
		$compaer = array();
		$next_val_group = 0;
		if(!empty($res)){
			foreach ($res['values'] as $lang=>$values){
				foreach ($values as $val_group=>$unned){
					$compaer[$val_group] = "";
					$next_val_group = $val_group;
				}
			}
		}
		$passed_links = array();
		foreach ($args as $values){
			foreach ($values as $index=>$vals){
				if($vals['value']=="")$passed_links[$index] = "";
			}
		}
		$next_val_group++;
		//edit all exists links
		$queryData = array();
		$tmp = array();
		foreach ($args as $lang=>$values){
			foreach ($values as $index=>$vals){
				if(isset($passed_links[$index]))
					continue;
				else $tmp[$index][$lang] = $vals; 
			}
		}
		$ret = array();
// 		var_dump($args);die;
		foreach ($tmp as $values){
			foreach ($values as $lang=>$vals){
				$cur_image = null;
				if($lang==CLanguage::getInstance()->getDefaultUser()){
					if($vals['attach_id'])$cur_image = $vals['attach_id'];
				}
				$queryData['attr_group'] = $attr_group;
				if(isset($vals['unit']))
					$queryData['unit_group'] = $vals['unit'];
				else
					$queryData['unit_group'] = null;
				$queryData['val_group'] = $next_val_group;
				$queryData['unit_value'] = $vals['value'];
				if($vals['attach_id']){
					$queryData['unit_image'] = $vals['attach_id'];
					
				}
				else{
					$queryData['unit_image'] = $cur_image;
					$at = new CAttach($cur_image);
					$vals['unit_image_url'] = $at->GetURL();
				}
				$at = new CAttach($cur_image);
				$vals['unit_image_url'] = $at->GetURL();
				if($lang==CLanguage::getInstance()->getDefaultUser()){
					$ret[$next_val_group] = $vals;
				
				}
				$queryData['lang'] = $lang;
				if(!Cmwdb::$db->insert($this->tbl_attr_links_values, $queryData)){
					Cmwdb::$db->rollback();
					return false;
				}
			}
			$next_val_group++;
		}
		Cmwdb::$db->commit();
		return $ret;
	}
	
	
	function DeleteValue($attr_group, $val_group){
		Cmwdb::$db->where('attr_group', $attr_group);
		Cmwdb::$db->where('val_group', $val_group);
		
		if(Cmwdb::$db->delete($this->tbl_attr_links_values)){
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->where('attr_val_group', $val_group);
			Cmwdb::$db->delete($this->tbl_subjects);
			$multy = CModule::LoadComponent('product', 'multyprice');
			if(is_object($multy))
				$multy->RemoveLinksByAttributes($attr_group, $val_group);
		}
		
		return true;
	}
	
	function EditAttribute($args, $units, $attr_group){
		Cmwdb::$db->startTransaction();
		//Edit template datas
		$objects = null;
		$objects_type = null;
		$all_objects = null;
		if(isset($args['obj_ids'])){
			$objects = $args['obj_ids'];
			unset($args['obj_ids']);
		}
		if(isset($args['obj_all'])){
			$all_objects = $args['obj_all'];
			unset($args['obj_all']);
		}
		if(isset($args['obj_type'])){
			$objects_type = $args['obj_type'];
			unset($args['obj_type']);
		}
		$order = 0;
		if(isset($args['order'])){
			$order = $args['order'];
			unset($args['order']);
		}
// 		echo 'All objects? '.$all_objects.'<br>';
// 		echo 'Object type: '.$objects_type.'<br>';
// 		var_dump($objects);die;
		foreach ($args as $lang=>$values){
			$queryData['attr_group'] = $attr_group;
			$queryData['attr_name'] = $values['attr_name'];
			if(isset($values['attr_image'])){
				$tmp_img = new CImageExt($this->configs['work_dir']);
				$res = $tmp_img->CreateImage($values['attr_image']); 
				if(!$res){
					Cmwdb::$db->rollback();
					return false;
				}
				$queryData['attr_image'] = $res;
			}
			
			$queryData['template_order'] = $order;

			$queryData['lang'] = $lang;
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->where('lang', $lang);
			if(!Cmwdb::$db->update($this->tbl_attr_templates, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
		}
		//Start edit units
		$this->EditUnits($units, $attr_group);
// 		die;
		$this->UpdateSubjects($attr_group, $objects_type, $all_objects, $objects, true);
		Cmwdb::$db->commit();
		return true;
	}
	
	protected function EditUnits($args, $attr_group, $in_transaction=false){
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		//Prepare existing units
		Cmwdb::$db->where('t_link', $attr_group);
		$res = Cmwdb::$db->get($this->tbl_attr_units);
		$compaer = array();
// 		var_dump($res);echo '<hr>';
		foreach ($res as $values)$compaer[$values['unit_group']] = "";
		//Convert new units to starting edit
		$ret = array();
		$passed_links = array();
		$is_passed = false;
		$received_links = array();
		foreach ($args as $lang=>$values){
			foreach ($values['group'] as $index=>$vals){
				if($vals)$received_links[$vals] = "";
				$ret[$index][$lang]['unit'] = $values['name'][$index];
				$ret[$index][$lang]['u_order'] = $values['order'][$index];
				$ret[$index][$lang]['unit_group'] = $vals;
			}
		}
// 		var_dump($compaer);echo '<hr>';
// 		var_dump($received_links);die;
		$delLinks = array();
		foreach ($compaer as $uid=>$unneed){
			if(!isset($received_links[$uid])){
				$delLinks[] = $uid;
// 				echo 'Delete :'.$uid.'<br>';
				if(!$this->DeleteUnit($uid, $attr_group)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
// 		var_dump($compaer);
// 		echo "<hr>";
// 		var_dump($received_links);die;
		//Verify and fined null unit names to remove from this array then remove from db
		foreach ($ret as $index=>$langs){
			foreach ($langs as $lang=>$values){
				if($values['unit']==""){
					$is_passed = true;
					if($values['unit_group'])$passed_links[] = $values['unit_group']; 
				}
				
			}
			if($is_passed){
				unset($ret[$index]);
				$is_passed = false;
			}
		}
		//starting remove unit name passed units fro db, links stored in passed_links
// 		echo 'Starting remove passed datas';
// 		var_dump($passed_links);die;
		if(!empty($passed_links)){
			
			Cmwdb::$db->where('t_link', $attr_group);
			Cmwdb::$db->where('unit_group', $passed_links, "in");
			if(!Cmwdb::$db->delete($this->tbl_attr_units)){
				echo 'was not delated';
				if(!$in_transaction)Cmwdb::$db->rollback();
				return false;
			}

		}
		if(!empty($delLinks)){
			if(!$this->SetUnitAsNullInValues($delLinks, $attr_group)){
				if(!$in_transaction)Cmwdb::$db->rollback();
				return false;
			}
		}
		

		//starting update existing units
		$is_updatable = false;
		foreach ($ret as $index=>$langs){
			$queryData = array();
			foreach ($langs as $lang=>$values){
				if($values['unit_group']){
					$queryData['unit'] = $values['unit'];
					$queryData['u_order'] = $values['u_order'];
					$queryData['t_link'] = $attr_group;
					Cmwdb::$db->where('unit_group', $values['unit_group']);
					Cmwdb::$db->where('t_link', $attr_group);
					Cmwdb::$db->where('unit_lang', $lang);
					if(!Cmwdb::$db->update($this->tbl_attr_units, $queryData)){
						if(!$in_transaction)Cmwdb::$db->rollback();
						return false;
					}
					$is_updatable = true;
					
				}
				else{
					break;
				}
			}
			if($is_updatable){
				unset($ret[$index]);
				$is_updatable = false;
			}
		}
		//reconvert to add

		$recon = array();
		
		foreach ($ret as $index=>$langs){
			foreach ($langs as $lang=>$values){
				$recon[$lang]['name'][$index] = $values['unit'];
				$recon[$lang]['order'][$index] = $values['u_order'];
			}
		}
// 		var_dump($recon);die;
		//starting add new units
				
		if(!$this->AddUnits($recon, $attr_group)){
			if(!$in_transaction)Cmwdb::$db->rollback();
			return false;
		}
		return true;
	}
	
	protected function DeleteUnit($uid, $attr_group){
		Cmwdb::$db->where('unit_group', $uid);
		Cmwdb::$db->where('t_link', $attr_group);
		return Cmwdb::$db->delete($this->tbl_attr_units);
	}
	
	protected function SetUnitAsNullInValues($passed_links, $attr_group){
		Cmwdb::$db->where('attr_group', $attr_group);
		Cmwdb::$db->where('unit_group', $passed_links, "in");
		return Cmwdb::$db->update($this->tbl_attr_links_values, ['unit_group'=>null]);

	}
	
	protected function UpdateSubjects($attr_group, $subject_type, $subject_all=null, $subjects=null, $in_transaction=false){
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		$exists = array();
		$all = array();
		$received = array();
		if(is_array($subjects)){
			foreach ($subjects as $index=>$obj_id)$received[$obj_id] = "";
		}
		//Take exists links
		Cmwdb::$db->where('attr_group', $attr_group);
		$res = Cmwdb::$db->get($this->tbl_attr_links_subject);
		foreach ($res as $links)$exists[$links['obj_id']] = "";
		//Load module for subject then take all groups as it
		$obj = CModule::LoadModule($subject_type);
		if(is_object($obj)){
			$res = $obj->GetAllGroups();
			foreach ($res as $sid)$all[$sid] = "";
		}
		
		//If we have subject_all as true, add all links, that we have but there are not inserted
		$queryData = array();
		if($subject_all){
			foreach ($all as $obj_id=>$unned){
				if(!isset($exists[$obj_id])){
					$queryData['obj_id'] = $obj_id;
					$queryData['obj_type'] = $subject_type;
					$queryData['attr_group'] = $attr_group;
					if(!Cmwdb::$db->insert($this->tbl_attr_links_subject, $queryData)){
						if(!$in_transaction)Cmwdb::$db->rollback();
						return false;
					}
				}
			}
		}
		else{//Compaer exists links then 1 - remove miss links, 2 - add new links
// 			var_dump($received);die;
			foreach ($received as $obj_id=>$unned){
// 				echo 'Verify '.$obj_id.'<br>';
				if(isset($exists[$obj_id])){
// 					echo $obj_id.' exists<br>';
					unset($exists[$obj_id]);
				}
				else{
					$queryData['obj_id'] = $obj_id;
					$queryData['obj_type'] = $subject_type;
					$queryData['attr_group'] = $attr_group;
					if(!Cmwdb::$db->insert($this->tbl_attr_links_subject, $queryData)){
						if(!$in_transaction)Cmwdb::$db->rollback();
						return false;
					}
				}
			}
// 			die;
			//Now in array $exists we have all links, that is mising and now we must remove from db
// 			var_dump($exists);die;
			foreach ($exists as $obj_id=>$unned){
				Cmwdb::$db->where('obj_type', $subject_type);
				Cmwdb::$db->where('obj_id', $obj_id);
				Cmwdb::$db->where('attr_group', $attr_group);
				if(!Cmwdb::$db->delete($this->tbl_attr_links_subject)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
		if(!$in_transaction)Cmwdb::$db->commit();
		return true;
	}
	
	function GetSubjects($subjects, $subject_type, $lang=null){
		if(is_array($subjects))
			Cmwdb::$db->where('obj_id',$subjects, "in");
		if(is_numeric($subjects))Cmwdb::$db->where('obj_id',$subjects);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_attr_links_subject);
		$ret = array();
		if(!empty($res)){
			
			foreach ($res as $values){
				$ret[$values['attr_group']] = $this->GetAttributeOne($lang, $values['attr_group']);
			}
		}
		return $ret;
	}
	
	function GetSubjectsJSON($subjects, $subject_type, $lang=null){
		return json_encode($this->GetSubjects($subjects, $subject_type, $lang));
	}
	
	function ActivatePasivateAttr($attr_group){
		Cmwdb::$db->where('attr_group', $attr_group);
		$res = Cmwdb::$db->getOne($this->tbl_attr_templates, ['is_active']);
		if(empty($res))return false;
		Cmwdb::$db->where('attr_group', $attr_group);
		if($res['is_active']){
			if(!Cmwdb::$db->update($this->tbl_attr_templates, ['is_active'=>0]))
				return false;
		}
		else{
			if(!Cmwdb::$db->update($this->tbl_attr_templates, ['is_active'=>1]))
				return false;
		}
		return true;
	}
	
	function DeleteAttribute($attr_group){
		Cmwdb::$db->startTransaction();
		Cmwdb::$db->where('attr_group', $attr_group);
		if(Cmwdb::$db->delete($this->tbl_attr_templates)){
			//delete all linked units
			Cmwdb::$db->where('t_link', $attr_group);
			Cmwdb::$db->delete($this->tbl_attr_units);
			//delete linked subjects
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->delete($this->tbl_attr_links_subject);
			//delete all linked values
			Cmwdb::$db->where('attr_group', $attr_group);
			Cmwdb::$db->delete($this->tbl_attr_links_values);
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	//Add ant links to subject just width attribute
	function AddSubjectsLinks($subjects, $subject_type){
		$queryData = array();
		foreach ($subjects as $subject){
			$queryData['obj_id'] = $subject;
			$queryData['obj_type'] = $subject_type;
			if(!Cmwdb::$db->insert($this->tbl_attr_links_subject, $queryData))
				return false;
		}
		return true;
	}
	
	//Link some subjects with any current attribute and group
	function AddSubjects($obj_id, $obj_type, $args, $in_transaction=false){
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		$queryData = array();
		foreach ($args as $attr_group=>$values){
			foreach ($values as $val_group=>$unneed){
				$queryData['attr_group'] = $attr_group;
				$queryData['attr_val_group'] = $val_group;
				$queryData['obj_id'] = $obj_id;
				$queryData['obj_type'] = $obj_type;
				if(!Cmwdb::$db->insert($this->tbl_subjects, $queryData)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
		
		if(!$in_transaction)Cmwdb::$db->commit();
		return true;
	}
	
	function GetSubjectsVariations($subject_id, $subject_type, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		Cmwdb::$db->where('obj_id', $subject_id);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_subjects);
		if(!empty($res)){
			$prepare = array();
			//Take all attributes ids for onestep collecting values
			foreach ($res as $values)$prepare[$values['attr_group']] = "";
			//Reformat before call function
			$tmp = array();
			foreach ($prepare as $attr_group=>$unneed)$tmp[] = $attr_group;
			$attributes = $this->GetAttribute($lang, $tmp);
			$vals = $this->GetValues($tmp, $lang);
			//Reformat res for compaering and checking all checked values
			foreach ($res as $values)$prepare[$values['attr_group']][$values['attr_val_group']] = "";
			$ret = array();
			foreach ($vals as $attr_group=>$attr_values){
				foreach ($attr_values as $val_group=>$values){
					if(isset($prepare[$attr_group][$val_group])){
						$vals[$attr_group]['vals'][$val_group] = $vals[$attr_group][$val_group];
						unset($vals[$attr_group][$val_group]);
						$vals[$attr_group]['vals'][$val_group]['checked'] = 1;
					}
					else {
						$vals[$attr_group]['vals'][$val_group] = $vals[$attr_group][$val_group];
						unset($vals[$attr_group][$val_group]);
						$vals[$attr_group]['vals'][$val_group]['checked'] = 0;
					}
					if($values['unit_image']){
						$at = new CAttach($values['unit_image']);
						$vals[$attr_group]['vals'][$val_group]['unit_image_url'] = $at->GetURL();
					}
					else $vals[$attr_group]['vals'][$val_group]['unit_image_url'] = "";
					$vals[$attr_group]['text'] = $attributes[$attr_group]['attr_name'];
				}
			}
// 			var_dump($vals);die;
			return $vals;			
		}
		return array();
	}
	
	protected function GetValues(array $attr_group, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		Cmwdb::$db->where('attr_group', $attr_group, "in");
		Cmwdb::$db->where('lang', $lang);
		$res = Cmwdb::$db->get($this->tbl_attr_links_values);
		if(!empty($res)){
			$ret = array();
			foreach ($res as $values){
				$ret[$values['attr_group']][$values['val_group']] = $values;
			}
			return $ret;
		}
		return array();
	}
	
	function DeleteSubjects_ByAttributes($subject_id, $subject_type, $args, $in_transaction=null){
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		foreach ($args as $attr_group=>$vals){
			foreach ($vals as $attr_val_group=>$unneed){
				Cmwdb::$db->where('obj_id', $subject_id);
				Cmwdb::$db->where('obj_type', $subject_type);
				Cmwdb::$db->where('attr_group', $attr_group);
				Cmwdb::$db->where('attr_val_group', $attr_val_group);
				if(!Cmwdb::$db->delete($this->tbl_subjects)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
		if(!$in_transaction)Cmwdb::$db->commit();
		return true;
	}
	
	function AddUpdateSubjectLinks($subject_id, $subject_type, $args, $in_transaction=false){
		if(!is_array($args))return false;
		Cmwdb::$db->where('obj_id', $subject_id);
		Cmwdb::$db->where('obj_type', $subject_type);
		$res = Cmwdb::$db->get($this->tbl_subjects);
		$exists = array();
		foreach ($res as $value)$exists[$value['attr_group']][$value['attr_val_group']] = "";
		$queryData = array();
		
		if(!$in_transaction)Cmwdb::$db->startTransaction();
		foreach ($args as $attr_group=>$values){
			foreach ($values as $attr_val_group=>$unneed){
				if(isset($exists[$attr_group][$attr_val_group])){
					unset($exists[$attr_group][$attr_val_group]);//if exists nothing to do
				}
				else{//add new
					$queryData['attr_group'] = $attr_group;
					$queryData['attr_val_group'] = $attr_val_group;
					$queryData['obj_id'] = $subject_id;
					$queryData['obj_type'] = $subject_type;
					if(!Cmwdb::$db->insert($this->tbl_subjects, $queryData)){
						if(!$in_transaction)Cmwdb::$db->rollback();
						return false;
					}
				}
			}
		}
		
		//Remove missed links
		foreach ($exists as $attr_group=>$values){
			foreach ($values as $attr_val_group=>$unneed){
				Cmwdb::$db->where('obj_id', $subject_id);
				Cmwdb::$db->where('obj_type', $subject_type);
				Cmwdb::$db->where('attr_group', $attr_group);
				Cmwdb::$db->where('attr_val_group', $attr_val_group);
				if(!Cmwdb::$db->delete($this->tbl_subjects)){
					if(!$in_transaction)Cmwdb::$db->rollback();
					return false;
				}
			}
		}
		if(!$in_transaction)Cmwdb::$db->commit();
		return true;
		
	}
	
	function GetSubjectsByAttributes($attributes, $subject_type){
		$attr_groups = array();
		$attr_val_groups = array();
		foreach ($attributes as $attr_group=>$vals){
			$attr_groups[] = $attr_group;
			foreach ($vals as $val_groups)
				$attr_val_groups[] = $val_groups;
		}
		
		Cmwdb::$db->where('attr_group', $attr_groups, 'in');
		Cmwdb::$db->where('attr_val_group', $attr_val_groups, 'in');
		Cmwdb::$db->where('obj_type', $subject_type);
		Cmwdb::$db->groupBy('obj_id');
		$res = Cmwdb::$db->get($this->tbl_subjects, null, ['obj_id']);
		$ret = array();
		foreach ($res as $vals)$ret[] = $vals['obj_id'];
		return $ret;
	}
	
	//function will return associated attributes for $obj_id
	function GetSubjectsNotVarieted($obj_id, $obj_type, $with_details=false){
// 		var_dump($obj_type);
		if(is_array($obj_id) && !empty($obj_id))Cmwdb::$db->where('obj_id', $obj_id, "in");
		if(is_numeric($obj_id))Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		$res = Cmwdb::$db->get($this->tbl_attr_links_subject);
// 		die;
		$ret = array();
		foreach ($res as $values)$ret[$values['obj_id']][] = $values['attr_group'];
		if(!$with_details)return $ret;
		$tmp = array();
// 		var_dump($ret);die;
		foreach ($ret as $object=>$ids){
			if(is_array($obj_id) && count($obj_id)>1)
				$tmp[$object] = $this->GetAttribute(null, $ids);
			else $tmp = $this->GetAttribute(null, $ids);
		}
// 		var_dump($tmp);die;
		return $tmp;
	}
}
?>