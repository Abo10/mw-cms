<?php
define("NO_PRODUCT","no_product");
class CProduct{
	protected $configs = array();
	protected $datas = array();
	protected $tbl_name = "std_products";
	
	function __construct($product_id=null){
		$this->configs = CConfig::GetModuleConfig('product');
		$this->configs['predefines'] = CConfig::GetBlock('predefines', 'product');
	}

	function CreateProduct($args, $predefines=null, $multyprice=null, $attr_notMultyprice=null){
// 		var_dump($multyprice);
// 		echo '<hr>';
// 		var_dump($_POST);
// 		die;
// 		var_dump($predefines);die;
		$def_lang = CLanguage::getInstance()->getDefaultUser();
		if(!isset($args[$def_lang]['product_code']))return false;
		$unique_args = ['product_code','product_count','product_price','product_old_price'];
		if(isset($args[CLanguage::getInstance()->getDefaultUser()]['product_instock']))
			$unique_args[] = 'product_instock';
		$uniques = $this->VerifyUniques($args, $unique_args);
		if(!is_array($uniques))return false;
		if(CheckValueUniques($this->tbl_name, 'product_code', $uniques['product_code']))return false;
		Cmwdb::$db->startTransaction();
		$product_group = Cmwdb::$db->getOne($this->tbl_name, 'max(product_group) product_group');
		if ($product_group['product_group']) $product_group['product_group']++;
		else $product_group['product_group'] = 1;
		$product_group = $product_group['product_group'];
		$slugs = array();
		foreach ($args as $lang=>$values){
			$slugs[$lang] = CSlug::ConvertToEnglish($values['product_title']);
		}
		$slugs = CSlug::getInstance()->GetVerifiedSlugs($slugs, $this->tbl_name, 'product_slug');
		$queryData = array();
		foreach ($args as $lang=>$values){
			$queryData['product_title'] = $values['product_title'];
			$queryData['product_descr'] = $values['product_content'];
			$queryData['product_short_descr'] = $values['product_descr'];
			if($values['seo_title']==="")$values['seo_title'] = $values['product_title'];
			if($values['seo_descr']==="")$values['seo_descr'] = $values['product_descr'];
			$seo_tmp = new CSeo();
			$queryData['product_seo'] = $seo_tmp->CreateSeo($values['seo_title'], $values['seo_descr'], $values['seo_keywords']);
			if(!$queryData['product_seo']){
				Cmwdb::$db->rollback();
				return false;
			}
			if(isset($values['product_gallery']))$queryData['product_gallery'] = json_encode($values['product_gallery']);
			else $queryData['product_gallery'] = json_encode(array());
			if(isset($values['product_covers']))$queryData['product_covers'] = json_encode($values['product_covers']);
			else $queryData['product_covers'] = json_encode(array());
			if(isset($values['product_files']))$queryData['product_attaches'] = json_encode($values['product_files']);
			else $queryData['product_attaches'] = json_encode(array());
			if(isset($values['product_img']))$queryData['product_image'] = $values['product_img']['id'];
			else $queryData['product_image'] = null;
			if(isset($values['product_img']))$queryData['product_img_title'] = $values['product_img']['title'];
			else $queryData['product_img_title'] = "";
			$queryData['product_code'] = $uniques['product_code'];
			$queryData['product_count'] = $uniques['product_count'];
			$queryData['product_price'] = $uniques['product_price'];
			$queryData['product_old_price'] = $uniques['product_old_price'];
			(isset($uniques['product_instock']))?$queryData['product_instock'] = $uniques['product_instock']:$queryData['product_instock']=0;
			if(isset($values['product_isactive']))$queryData['product_isactive'] = $values['product_isactive'];
			$queryData['product_slug'] = $slugs[$lang];
			$queryData['product_lang'] = $lang;
			$queryData['product_group'] = $product_group;
			if(!Cmwdb::$db->insert($this->tbl_name, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}


		}
		if(!isset($predefines['product_category']) || empty($predefines['product_category'])){
			Cmwdb::$db->rollback();
			return false;
		}
		if(is_array($predefines)){
			foreach ($predefines as $module=>$values){
				$temp = CModule::LoadModule($module);
				if(is_object($temp)){
					$temp->AddLinks($product_group, 'product', $values, true);
				}
			}
			if($multyprice){
				$multy = CModule::LoadComponent('product', 'multyprice');
				if(is_object($multy)){
					$multy->AddLinks($multyprice, $product_group, 'product');
				}
			}
			if(!empty($attr_notMultyprice)){
				$obj = CModule::LoadModule("attributika");
				if(is_object($obj)){
					$obj->AddSubjects($product_group,'product', $attr_notMultyprice);
				}
			}
		}
		
		
		Cmwdb::$db->commit();
// 		die;
		return true;

	}
	
	function EditProduct($product_group, $args, $predefines=null, $multyprice=null, $attr_notMultyprice=null){
		Cmwdb::$db->where('product_group', $product_group);
		$predatas = Cmwdb::$db->get($this->tbl_name);
		if(!$predatas)return false;
		$compaer = array();
		foreach ($predatas as $values)$compaer[$values['product_lang']] = $values;
		$def_lang = CLanguage::getInstance()->getDefaultUser();
		$unique_args = ['product_code','product_count','product_price','product_old_price'];
		if(isset($args[CLanguage::getInstance()->getDefaultUser()]['product_instock']))
			$unique_args[] = 'product_instock';
		$uniques = $this->VerifyUniques($args, $unique_args);
		if(!is_array($uniques))return false;
		//Starting collect old datas to compaer
		if($uniques['product_code']!==$compaer[$def_lang]['product_code']){
			if($this->VerifyCodeUnique($uniques['product_code']))
				return false;
		}
		Cmwdb::$db->startTransaction();
		$product_group = $compaer[$def_lang]['product_group'];
		$slugs = array();
		foreach ($args as $lang=>$values){
			$slugs[$lang] = CSlug::ConvertToEnglish($values['product_title']);
		}
		$slugs = CSlug::getInstance()->GetVerifiedSlugs($slugs, $this->tbl_name, 'product_slug');
		$queryData = array();
		$seo_updates = array();
		foreach ($args as $lang=>$values){
			if((!isset($values['product_title']) || $values['product_title']==="") && $lang===$def_lang){
				Cmwdb::$db->rollback();
				return false;
			}
			$queryData['product_title'] = $values['product_title'];
			$queryData['product_descr'] = $values['product_content'];
			$queryData['product_short_descr'] = $values['product_descr'];
			if($values['seo_title'])
				$seo_updates[$compaer[$lang]['product_seo']]['seo_title'] = $values['seo_title'];
			else $seo_updates[$compaer[$lang]['product_seo']]['seo_title'] = $values['product_title'];
			if($values['seo_descr'])
				$seo_updates[$compaer[$lang]['product_seo']]['seo_descr'] = $values['seo_descr'];
			else $seo_updates[$compaer[$lang]['product_seo']]['seo_descr'] = $values['product_descr'];
			$seo_updates[$compaer[$lang]['product_seo']]['seo_keywords'] = $values['seo_keywords'];
			if(isset($values['product_gallery']))$queryData['product_gallery'] = json_encode($values['product_gallery']);
			else $queryData['product_gallery'] = json_encode(array());
			if(isset($values['product_covers']))$queryData['product_covers'] = json_encode($values['product_covers']);
			else $queryData['product_covers'] = json_encode(array());
			if(isset($values['product_files']))$queryData['product_attaches'] = json_encode($values['product_files']);
			else $queryData['product_attaches'] = json_encode(array());
			if(isset($values['product_img']))$queryData['product_image'] = $values['product_img']['id'];
			else $queryData['product_image'] = null;
			if(isset($values['product_img']))$queryData['product_img_title'] = $values['product_img']['title'];
			else $queryData['product_img_title'] = "";
			$queryData['product_code'] = $uniques['product_code'];
			$queryData['product_count'] = $uniques['product_count'];
			$queryData['product_price'] = $uniques['product_price'];
			$queryData['product_old_price'] = $uniques['product_old_price'];
				(isset($uniques['product_instock']))?$queryData['product_instock'] = $uniques['product_instock']:$queryData['product_instock']=0;
			if(!$compaer[$lang]['product_slug'])	
				$queryData['product_slug'] = $slugs[$lang];
			$queryData['product_lang'] = $lang;
			$queryData['product_group'] = $product_group;
			if(isset($values['product_isactive']))$queryData['product_isactive'] = $values['product_isactive'];
			Cmwdb::$db->where('product_group', $product_group);
			Cmwdb::$db->where('product_lang', $lang);
			if(!Cmwdb::$db->update($this->tbl_name, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
		}
		$multy = CModule::LoadComponent('product', 'multyprice');
		if(is_object($multy)){
			$multy->EditLinks($multyprice, $product_group, 'product', true);
		}
		$attributika = CModule::LoadModule('attributika');
		if(is_object($attributika)){
			$attributika->AddUpdateSubjectLinks($product_group, 'product', $attr_notMultyprice, true);
		}
		Cmwdb::$db->commit();
		foreach ($seo_updates as $seo_id=>$values){
			$tmp_seo = new CSeo($seo_id);
			$tmp_seo->UpdateDatas($values['seo_title'], $values['seo_descr']);
		}
		//this part will update datas in predefines, by default its meen zero action
		if(is_array($predefines)){
			foreach ($predefines as $module=>$values){
				$temp = CModule::LoadModule($module);
				if(is_object($temp)){
					$temp->AddLinks($product_group, 'product', $values);
				}
			}
		}
		return true;
	}
	
	function GetDatas($product_id, $args=null){
		$ret = array();
		Cmwdb::$db->where('product_group', $product_id);
// 		$curencsies = CModule::LoadComponent('product', 'currency');
		$res = array();
		if(is_array($args)){
			if(!in_array('product_group', $args))$args[] = 'product_group';
			$res = Cmwdb::$db->get($this->tbl_name, null, $args);
		}
		else
			$res = Cmwdb::$db->get($this->tbl_name);
		if(empty($res))return false;
		foreach ($res as $values){
			$cur_lang = $values['product_lang'];
			$ret['langs'][$cur_lang] = $values;
			$ret['langs'][$cur_lang]['product_price'] = $ret['langs'][$cur_lang]['product_price'];
			$ret['langs'][$cur_lang]['product_old_price'] = $ret['langs'][$cur_lang]['product_price'];
// 			if(isset($values['product_image']) && $values['product_image']){
// 				$at = new CAttach($values['product_image']);
// 				$ret['langs'][$cur_lang]['product_image_url'] = $at->GetURL();
// 			}
// 			else $ret['langs'][$cur_lang]['product_image_url'] = "";
// 			$ret['langs'][$cur_lang]['product_price'] = $curencsies->GetPrice($ret['langs'][$cur_lang]['product_price'],$curencsies->GetDefaultCurrency,GetCurrentCurrency);
// 			$ret['langs'][$cur_lang]['product_old_price'] = $curencsies->GetPrice($ret['langs'][$cur_lang]['product_old_price'],$curencsies->GetDefaultCurrency,GetCurrentCurrency);
			if(isset($values['product_covers']))
				$ret['langs'][$cur_lang]['product_covers'] = json_decode($values['product_covers'], true);
			if(isset($values['product_gallery']))
				$ret['langs'][$cur_lang]['product_gallery'] = json_decode($values['product_gallery'], true);
			if(isset($values['product_attaches']))
				$ret['langs'][$cur_lang]['product_attaches'] = json_decode($values['product_attaches'], true);
			if(isset($values['product_seo'])){
				$tmp_seo = new CSeo($values['product_seo']);
				$ret['langs'][$cur_lang]['seo_title'] = $tmp_seo->GetTitle();
				$ret['langs'][$cur_lang]['seo_descr'] = $tmp_seo->GetDescr();
				$ret['langs'][$cur_lang]['seo_keywords'] = $tmp_seo->GetKeywords();
			}
		}
		
		foreach ($this->configs['predefines'] as $mod_name){
			$tmp = CModule::LoadModule($mod_name);
			if(is_object($tmp)){
				$ret['predefines'][$mod_name] = $tmp->GetLinks($product_id, "product", null, false);
			}
		}
		
		//Take multyprice links, if we have multyprice module
		$multy = CModule::LoadComponent('product', 'multyprice');
		$attributes = CModule::LoadModule('attributika');
		if(is_object($multy)){
			$ret['multyprice'] = $multy->GetLinks($product_id, 'product');
			$cat_links = null;
			if(is_object($attributes)){
				if(isset($ret['predefines']['product_category'])){
// 					var_dump($ret['predefines']['product_category']);die;
					$assoc_cats = array();
					
					foreach ($ret['predefines']['product_category'] as $cats=>$unneed){
// 						var_dump($cats);die;
						$assoc_cats[] = $cats;
					}
					
// 					var_dump($ret['predefines']['product_category']);die;
					$cat_links = $attributes->GetAttributesBySubjects($assoc_cats,'product_category');
					
// 					var_dump($cat_links);die;
				}
				if($cat_links)$ret['empty_attributes'] = $cat_links;
				else $ret['empty_attributes'] = array();
				$ret['attributes'] = $attributes->GetSubjectsVariations($product_id, 'product');
// 				var_dump($ret['attributes']);die;
				if($cat_links){
// 					var_dump($ret['empty_attributes']);die;
					foreach ($ret['empty_attributes'] as $attr_group=>$unneed){
						if(isset($ret['attributes'][$attr_group]))
							unset($ret['empty_attributes'][$attr_group]);
						
					}
					
				}
// 				var_dump($ret['empty_attributes']);die;
// 				var_dump($ret['attributes']);die;
// 				var_dump($ret['attributes']);die;
				foreach ($ret['multyprice'] as $mid=>$values){
					if(isset($ret['attributes'][$values['attr_group1']])){
						$ret['attributes'][$values['attr_group1']]['checked'] = 1;
					}
					if(isset($ret['attributes'][$values['attr_group2']])){
						$ret['attributes'][$values['attr_group2']]['checked'] = 1;
					}
					$ret['multyprice'][$mid]['price'] = $ret['multyprice'][$mid]['price'];
						
				}
// 				var_dump($ret['attributes']);die;
			}
		}
		
// 		var_dump($ret);die;
// 		echo "Finish collecting<hr>";die;
		return $ret;
	}
	
	//fill_type 2: all, 1: active, 0: passive
	function GetProducts($page=1, $count=20, $lang=null, $needle_string=null, $predefines=null, $fill_type=2){
		CErrorHandling::RegisterHandle("product_list");
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		$def_lang = CLanguage::getInstance()->getDefaultUser();
		$uniques = array();
		if(is_array($predefines)){
			$ret = array();
			$filtered = array();
			$multyspace = 0;
			foreach ($predefines as $module_name=>$values){
				if(is_array($values)){
					$obj = CModule::LoadModule($module_name);
					if(is_object($obj)){
						$ret[$module_name] = $obj->GetLinks(null, 'product', $values, true);
						$multyspace++;
						foreach ($ret[$module_name] as $pr_id=>$unneed){
							$filtered[$pr_id] = $unneed;
						}
					}
				}
			}

			if($multyspace>1){
				foreach ($filtered as $pr_id=>$unneed){
					if(count($unneed)==$multyspace)
						$uniques[] = $pr_id;
				}
			}
			else{
//				var_dump($filtered);
				foreach ($filtered as $pr_id=>$unneed)
					$uniques[] = $pr_id;
			}
			if(empty($uniques) && $multyspace)return array('products'=>array(), 'page_count'=>0);
		}
		Cmwdb::$db->pageLimit = ($count*count(CLanguage::get_langs()));
		if(!empty($uniques)){
			
			Cmwdb::$db->where('product_group', $uniques, "in");
		}
		$fields = array('product_group', 'product_title', 'product_lang','product_isactive', 'product_count', 'product_price', 'product_old_price','product_code', 'product_instock', 'product_image', 'product_order');
		if(is_array($needle_string)){

			foreach ($needle_string as $in_field=>$needle)
				Cmwdb::$db->where($in_field,'%'.$needle.'%', "like" );
		}
		if($fill_type==0)Cmwdb::$db->where('product_isactive',0);
		if($fill_type==1)Cmwdb::$db->where('product_isactive',1);
		Cmwdb::$db->where('product_lang', $lang);
		$ret = Cmwdb::$db->get($this->tbl_name,null, 'product_group');
		
		$tmp = array();
		foreach ($ret as $values){
			$tmp[] = $values['product_group'];
		}
// 		var_dump($tmp);die;
		if(empty($tmp)){
			return array('products'=>array(), 'page_count'=>0);
		}
		Cmwdb::$db->where('product_group', $tmp, "in");
		Cmwdb::$db->orderBy('product_order', 'desc');
		$ret = Cmwdb::$db->arraybuilder()->paginate($this->tbl_name,$page, ['product_group']);
		$page_count = Cmwdb::$db->totalPages;
// 		echo $page_count;die;
		//Reconfigure array
		$tmp_array = array();
		foreach ($ret as $vals)$tmp_array[] = $vals['product_group'];
		//Set arguments for get
		Cmwdb::$db->where('product_group', $tmp_array, "in");
		Cmwdb::$db->orderBy('product_order', 'desc');
		
		$ret = Cmwdb::$db->get($this->tbl_name);
// 		var_dump($ret);die;
		$global_area = array();
// 		var_dump($ret);die;
		foreach ($ret as $values){
			$global_area['products'][$values['product_group']][$values['product_lang']] = $values;
			if($values['product_image']){
				$at = new CAttach($values['product_image']);
				$global_area['products'][$values['product_group']][$values['product_lang']]['product_image_url'] = $at->GetURL();
			}
			else $global_area['products'][$values['product_group']][$values['product_lang']]['product_image_url'] = "";
			if($values['product_title']==""){
				$global_area['products'][$values['product_group']][$values['product_lang']]['product_title'] = $global_area['products'][$values['product_group']][$def_lang]['product_title'].' - '.CDictionary::GetKey('not_translated', $values['product_lang']);
				$global_area['products'][$values['product_group']][$values['product_lang']]['is_translated'] = false;
			}
			else $global_area['products'][$values['product_group']][$values['product_lang']]['is_translated'] = true;
			
		}
		foreach ($global_area['products'] as $pr_id=>$unneed){
			if($predefines){
				foreach ($predefines as $module_name=>$unneed){
					$obj = CModule::LoadModule($module_name);
					if(is_object($obj)){
						$global_area[$module_name][$pr_id] = $obj->GetDatasByObj($pr_id, 'product', null);
					}
					
				}
			}
		}
	
		$global_area['page_count'] = $page_count;
// 		var_dump($global_area);
		return $global_area;
	}
	
	function UpdateOrder($product_group, $order){
		Cmwdb::$db->where('product_group', $product_group);
		if(Cmwdb::$db->update($this->tbl_name, ['product_order'=>$order])){
			Cmwdb::$db->where('product_group', $product_group);
			return Cmwdb::$db->getValue($this->tbl_name,'product_order');
		}
		return false;
	}

	protected function VerifyUniques(&$args, $verList){
		$langs = CLanguage::get_lang_keys_user();
//		var_dump($langs);die;
		$ret = array();
		foreach($verList as $ver_val){
			$value = "";
			$firstTake = false;
			foreach($langs as $lang){
				if(!$firstTake){
					if(isset($args[$lang][$ver_val])){
						$value = $args[$lang][$ver_val];
						$firstTake = true;
					}
					else return false;
				}
				else{
					if(!isset($args[$lang][$ver_val]) || $value!==$args[$lang][$ver_val])
						return false;
				}
			}
			$ret[$ver_val] = $value;
		}
		
		return $ret;
	}
	
	function GetProductCounts(){
		Cmwdb::$db->where('product_isactive',0);
		$ret['passive'] = Cmwdb::$db->getValue($this->tbl_name, 'count(*)')/CLanguage::getInstance()->GetLangsCountUser();
		Cmwdb::$db->where('product_isactive',1);
		$ret['active'] = Cmwdb::$db->getValue($this->tbl_name, 'count(*)')/CLanguage::getInstance()->GetLangsCountUser();
		$ret['all'] = $ret['passive']+$ret['active'];
		return $ret;
		
	}
	
	function VerifyCodeUnique($value){
		return CheckValueUniques($this->tbl_name, 'product_code', $value);
	}
	
	function DeleteProduct($product_id){
		if(is_array($product_id))Cmwdb::$db->where('product_group', $product_id, "in");
		else
			Cmwdb::$db->where('product_group', $product_id);
		if(Cmwdb::$db->delete($this->tbl_name)){
			foreach ($this->configs['predefines'] as $module_name){
				$obj = CModule::LoadModule($module_name);
				if(is_object($obj)){
					if(!$obj->RemoveLinks($product_id, 'product'))
						return false;
				}
			}
			$multy = CModule::LoadComponent('product', 'multyprice');
			if(is_object($multy))$multy->RemoveLinks($product_id, 'product');
		}
		return true;
	}
	
	function ActivePassiveProduct($product_id){
		Cmwdb::$db->where('product_group', $product_id);
		Cmwdb::$db->where('product_lang', CLanguage::getInstance()->getDefaultUser());
		$res = Cmwdb::$db->getOne($this->tbl_name,['product_isactive']);
		$status = 1;
		if(empty($res))return NO_PRODUCT;
		if($res['product_isactive'])$status = 0;
		else $status=1;
		Cmwdb::$db->where('product_group', $product_id);
		Cmwdb::$db->update($this->tbl_name, ['product_isactive'=>$status]);
		return $status;
	}
	
	function GetLinks($obj_id, $obj_type, $s_link=null, $sort_by_mlink=true){
		
	}
	
	function ApplyDiscount($id, $value, $action="plus", $type="fixed"){
		try {
			Cmwdb::$db->where('product_group', $id);
			if($value===0 || $value<0)throw new Exception('Invalid argument, the value cant be 0 or minus',5);
			$current_price = Cmwdb::$db->getValue($this->tbl_name, 'product_price');
			if($current_price){
				$multy = CModule::LoadComponent('product', 'multyprice');
				switch ($action){
					case 'plus':{
						$new_price = 0;
						switch ($type){
							case 'fixed':{
								$new_price = $current_price+$value;
								
								Cmwdb::$db->where('product_group', $id);
								if(Cmwdb::$db->update($this->tbl_name, ['product_price'=>$new_price])){
									//TODO: Continue and try to update price of multiprice
									if(is_object($multy)){
										return $multy->ApplyDiscount($id, $value, $action="plus", $type="fixed");
									}
									return true;
								}
								throw new Exception('Failed update price, DB error was accoured',3);	
								break;
							}
							case 'percent':{
								$new_price = $current_price+($current_price/100*$value);
								Cmwdb::$db->where('product_group', $id);
								if(Cmwdb::$db->update($this->tbl_name, ['product_price'=>$new_price])){
									//TODO: Continue and try to update price of multiprice
									if(is_object($multy)){
										return $multy->ApplyDiscount($id, $value, $action="plus", $type="fixed");
									}
									return true;
								}
								throw new Exception('Failed update price, DB error was accoured',3);	
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
								Cmwdb::$db->where('product_group', $id);
								if(Cmwdb::$db->update($this->tbl_name, ['product_price'=>$new_price])){
									//TODO: Continue and try to update price of multiprice
									if(is_object($multy)){
										return $multy->ApplyDiscount($id, $value, $action="plus", $type="fixed");
									}
									return true;
								}
								throw new Exception('Failed update price, DB error was accoured',3);	
								break;
							}
							case 'percent':{
								$new_price = $current_price-($current_price/100*$value);
								if($new_price<=0)throw new Exception('Invalid area of price, price is null or minus');
								Cmwdb::$db->where('product_group', $id);
								if(Cmwdb::$db->update($this->tbl_name, ['product_price'=>$new_price])){
									//TODO: Continue and try to update price of multiprice
									if(is_object($multy)){
										return $multy->ApplyDiscount($id, $value, $action="plus", $type="fixed");
									}
									return true;
								}
								throw new Exception('Failed update price, DB error was accoured',3);	
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
			throw new Exception("Product was not fount",1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		return true;
	}
	
	function GetPriceRange($id){
		try {
			Cmwdb::$db->where('product_group', $id);
			$res = Cmwdb::$db->getOne($this->tbl_name, "min(product_price) min, max(product_price) max");
			//Try to take multyprices if have multyprice module
			$multy = CModule::LoadComponent('product', 'multyprice');
			$res_multy = null;
			if(is_object($multy)){
				$res_multy = $multy->GetPriceRange($id);
			}
			$tmp = array();
			if($res['min'])$tmp[] = $res['min'];
			if($res['max'])$tmp[] = $res['max'];
			if($res_multy){
				if($res_multy['min'])$tmp[] = $res_multy['min'];
				if($res_multy['max'])$tmp[] = $res_multy['max'];
			}
			if(empty($tmp))return ['max'=>0, 'min'=>0];

			$ret['max'] = max($tmp);
			$ret['min'] = min($tmp);
			return $ret;
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		return true;
	}
	
	function GetPriceRangeList(array $ids){
		$ret = array();
		$firstStep = true;
		foreach ($ids as $id){
			$tmp = $this->GetPriceRange($id);
// 			var_dump($tmp);die;
			if($firstStep){
				$ret = $tmp;
				$firstStep=false;
			}
			else{
				if($tmp['max']>$ret['max'])$ret['max']=$tmp['max'];
				if($tmp['min'] <= $ret['min'])$ret['min']=$tmp['min'];
			}
		}
		return $ret;
	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('product_group', $id);
			Cmwdb::$db->where('product_lang', $lang);
			if(!$type)$type = 'product';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'product_slug');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('product_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'product_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('product_group', $id);
				Cmwdb::$db->where('product_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['product_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('product_group', $id);
				Cmwdb::$db->where('product_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['product_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	
	function GetShippingNeeded($oids = null){
		if(is_array($oids))Cmwdb::$db->where('product_group', $oids, "in");
		if(is_numeric($oids))Cmwdb::$db->where('product_group', $oids);
		$need = [
			'product_weight_unit',
			'product_weight',
			'product_height_unit',
			'product_height',
			'product_width_unit',
			'product_width',
			'product_length_unit',
			'product_length',
			'product_group',
		];
		$res = Cmwdb::$db->get($this->tbl_name,null, $need);
		$ret = [];
		if($res){
			if(is_array($oids)){
				foreach ($res as $values)$ret[$values['product_group']] = $values;
			}
			else $ret = $res[0];
		}
		return $ret;
	}
	
	
}
?>