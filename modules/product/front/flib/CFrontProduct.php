<?php


class CFrontProduct{
	static public $tbl_name = "std_products";
	function __construct(){
		self::Initial();
	}

	static function Initial(){
// 		self::$tbl_name = "std_products";
	}

	static function GetDatas($oid = null, $args = null, $lang = null, $page = 1, $count = 10, $assocs=null, $order="product_order"){
// 		var_dump($oid);return ;
		$limit_start = ($page - 1) * $count;
		if(is_string($oid)){
			Cmwdb::$db->where('product_slug', $oid);
			$oid = Cmwdb::$db->getValue(self::$tbl_name, 'product_group');
		}
		if(is_null($oid)){
			Cmwdb::$db->where('product_lang', CLanguage::getInstance()->getCurrentUser());
			$work = Cmwdb::$db->get(self::$tbl_name, null, ['product_group']);

			foreach ($work as $vals)$tmp[] = $vals['product_group'];
			$oid = $work;
		}
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		if(is_array($args)){
			if(isset($args['product']) && is_array($args['product'])){
				if(!in_array("product_group", $args['product']['product_group']))
					$args['product'][] = 'product_group';
			}
			else $args['product'] = 1;
		}
		if(is_numeric($oid))Cmwdb::$db->where('product_group', $oid);
		if(is_array($oid) && !empty($oid)){
			foreach ($oid as $ggg){
				$ttt[] = $ggg;
			}	
			Cmwdb::$db->where('product_group', $ttt, "in");
		}

		Cmwdb::$db->where('product_lang', $lang);
		Cmwdb::$db->orderBy($order, 'desc');
		$ret = array();
		$tmp = array();
// // 		echo Cmwdb::$db->getLastQuery();
		
		$tmp = Cmwdb::$db->get(self::$tbl_name/*,[$limit_start, $count], $args['product']*/);
// 		var_dump($tmp);die;


		$multyprice = CModule::LoadComponent('product', 'multyprice');
		$attr = CModule::LoadModule('attributika');
		
		foreach ($tmp as $values){
			$ret['product'][$values['product_group']] = $values;
			$ret['product'][$values['product_group']]['product_price'] = CFrontCurrency::GetPrice($values['product_price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
			$ret['product'][$values['product_group']]['product_old_price'] = CFrontCurrency::GetPrice($values['product_old_price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
			if($ret['product'][$values['product_group']]['product_image']){
				$at = new CAttach($ret['product'][$values['product_group']]['product_image']);
				$ret['product'][$values['product_group']]['product_image_url'] = $at->GetURL();
			}
			if(is_object($multyprice)){
				if(is_array($oid)){
					$ret['product'][$values['product_group']]['multyprice'] = $multyprice->GetLinksArray($values['product_group'], 'product');
					foreach ($ret['product'][$values['product_group']]['multyprice'] as $mid=>$variants){
// 						if($variants['price']==0)
// 							$ret['product'][$values['product_group']]['multyprice'][$mid]['price'] = $values['product_price'];
						if($variants['price']==0)
							$ret['product'][$values['product_group']]['multyprice'][$mid]['price'] = CFrontCurrency::GetPrice($values['product_price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
						else{
							$ret['product'][$values['product_group']]['multyprice'][$mid]['price'] = CFrontCurrency::GetPrice($variants['price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
						}
						if($ret['product'][$values['product_group']]['multyprice'][$mid]['o_img']){
							$at = new CAttach($ret['product'][$values['product_group']]['multyprice'][$mid]['o_img']);
							$ret['product'][$values['product_group']]['multyprice'][$mid]['o_img'] = $at->GetURL();
						}
						else{
							$at = new CAttach($ret['product'][$values['product_group']]['product_image']);
							$ret['product'][$values['product_group']]['multyprice'][$mid]['o_img'] = $at->GetURL();
						}
					}
					if(is_object($attr))$ret['product'][$values['product_group']]['multyvariation'] = $attr->GetSubjectsVariations($values['product_group'], 'product', $lang);
				}
				else{
					$ret['multyprice'] = $multyprice->GetLinksArray($values['product_group'], 'product');
					foreach ($ret['multyprice'] as $mid=>$variants){
						if($variants['o_img']){
							$at = new CAttach($variants['o_img']);
							$ret['multyprice'][$mid]['o_img'] = $at->GetURL();
						}
						else{
							$at = new CAttach($values['product_image']);
							$ret['multyprice'][$mid]['o_img'] = $at->GetURL();
						}
						if($variants['price']==0)
							$ret['multyprice'][$mid]['price'] = CFrontCurrency::GetPrice($values['product_price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
						else $ret['multyprice'][$mid]['price'] = CFrontCurrency::GetPrice($ret['multyprice'][$mid]['price'], CFrontCurrency::GetDefaultCurrency(), CFrontCurrency::GetCurrentCurrency());
					}
					if(is_object($attr))$ret['multyvariation'] = $attr->GetSubjectsVariations($values['product_group'], 'product', $lang);
				}
			}
		}
		$discounts = CModule::LoadModule('discount');

		if(is_object($discounts)){
			if(is_array($oid)){
				foreach ($oid as $pid){
					$ret['discount'][$pid] = $discounts->GetLinks($pid, 'product');
				}
			}
			else
				$ret['discount'] = $discounts->GetLinks($oid, 'product');
		}
		return $ret;
	}

	static function GetFiltered(array $filters, $asocs=null, $lang=null, $page=1, $count=20, $order="product_order"){
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		$mass = array();
		foreach ($filters as $filter_name=>$filter_values){
			switch ($filter_name){
				case 'price_range':{
					$min = null;
					$max = null;
					if(isset($filter_values['min']) && $filter_values['min'])$min = $filter_values['min'];
					if(isset($filter_values['max']) && $filter_values['max'])$max = $filter_values['max'];
					$mass[$filter_name] = self::GetByPriceRange($min, $max, $lang);
					
					break;
				}
				case 'attributika':{
					if(!empty($filter_values)){
						$attributes = CModule::LoadModule('attributika');
						if(is_object($attributes)){
							$mass[$filter_name] = $attributes->GetSubjectsByAttributes($filter_values, 'product');
						}
						else{
							$mass[$filter_name] = "module is miss";
						}
					}
					break;
				}
				case 'product_category':{
						$cats = CModule::LoadModule('product_category');
						if(is_object($cats)){
							if(empty($filter_values))
								$mass[$filter_name] = $cats->GetLinksCompaq(null, 'product', null, true);
							else $mass[$filter_name] = $cats->GetLinksCompaq(null, 'product', $filter_values, true);
						}
					
					break;
				}
				case 'search':{
					if($filter_values){
						$mass[$filter_name] = self::SearchProducts($filter_values, ['product_title'], $lang);
					}
					break;
				}
				case 'brand':{
					if(!empty($filter_values)){
						$brands = CModule::LoadModule('brand');
						if(is_object($brands)){
							$mass[$filter_name] = $brands->GetLinksCompaq(null, 'product', $filter_values, true);
						}
					}
					break;
				}
				default:{
					$mass[$filter_name] = "Unknown filter";
					break;
				}
			}
		}
// 		var_dump($mass);
		foreach ($mass as $filter=>$subjects){
			foreach ($subjects as $index=>$current_subject){
				foreach ($mass as $verifies){
					if(!in_array($current_subject, $verifies)){
						unset($mass[$filter][$index]);
						break;
					}
				}
			}
		}
// 		var_dump($mass);die;
		$verified_subjects = array();
		foreach ($mass as $subjects){
			foreach ($subjects as $current_subject){
				if(in_array($current_subject, $verified_subjects))continue;
				else $verified_subjects[] = $current_subject;
			}
		}
// 		var_dump($filters);
// 		echo '<hr>';
// 		var_dump($mass);
// 		echo '<hr>';
// 		var_dump($verified_subjects);die;
		$page_count = 0;
		if((count($verified_subjects)/$count)<=1)$page_count = 1;
		else{
			if((count($verified_subjects)%$count)>0)$page_count = (int)count($verified_subjects)/$count+1;
			else $page_count = (int)count($verified_subjects)/$count;
		}
		if($page)$page--;
		$start_pos = $page*$count;
		$ret_vals = array_slice($verified_subjects, $start_pos, $count);
		 
		$ret = array();
		if(!empty($verified_subjects)){
			$ret = self::GetDatas($ret_vals, $asocs, $lang, $page, $count, null, $order);
// 			var_dump($ret);die;
			$ret['page_count'] = $page_count;
			if(isset($filters['product_category'])){
				Cmwdb::$db->get(self::$tbl_name);
				$attr = CModule::LoadModule('attributika');
				if(is_object($attr)){
// 					var_dump($filters['product_category']);die;
					$ret['attributika'] = $attr->GetSubjectsNotVarieted($filters['product_category'], 'product_category', true);
				}
			}
			$ret['price_range'] = self::GetPriceRangeByProducts($verified_subjects);
		}
		else $ret['price_range'] = ['max'=>0, 'min'=>0];
		
		return $ret;
	}

	static function GetByPriceRange($min=null, $max=null, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		if($min)Cmwdb::$db->where('product_price', $min, '>=');
		if($max)Cmwdb::$db->where('product_price', $max, '<=');
		Cmwdb::$db->where('product_lang', $lang);
		Cmwdb::$db->orderBy('product_group');

		$tmp = Cmwdb::$db->get(self::$tbl_name, null, ['product_group']);
		$ret = array();
		foreach ($tmp as $vals)$ret[] = $vals['product_group'];
		return $ret;
	}
	
	static function GetLinksExt($links, $subject_type){
		return array();
	}
	
	static function SearchProducts($search_word, $in_fields=null ,$lang=null){
		
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		if(is_array($in_fields)){
			foreach ($in_fields as $field_name){
				Cmwdb::$db->orWhere($field_name,'%'.$search_word.'%', "like" );
			}
		}
		if(is_string($in_fields))Cmwdb::$db->where($in_fields,'%'.$search_word.'%', "like" );
		Cmwdb::$db->where('product_lang', $lang);
		$res = Cmwdb::$db->get(self::$tbl_name, null, ['product_group']);
		$ret = array();
		foreach ($res as $fount){
			$ret[] = $fount['product_group'];
		}
// 		echo 1;die;
		//now we go to get list of seo ids, where we have searched word
		$seo = new CSeo();
		$s_ids = $seo->SearchInSeo($search_word);
// 		var_dump($s_ids);die;
		if(!empty($s_ids)){
			Cmwdb::$db->where('product_seo', $s_ids, "in");
			Cmwdb::$db->where('product_lang', $lang);
			$s_f = Cmwdb::$db->get(self::$tbl_name, null, ['product_group']);
// 			var_dump($s_f);
			if(!empty($s_f)){
				foreach ($s_f as $vals){
					if(in_array($vals['product_group'], $ret))
						continue;
					else $ret[] = $vals['product_group'];
				}
			}
		}
		return $ret;
	}
	
	static function GetPriceRange($cats){
		$product_categories = CModule::LoadModule('product_category');
		if(is_object($product_categories)){
			$res = $product_categories->GetLinksCompaq(null, 'product', $cats, true);
// 			var_dump($res);die;
			if(!empty($res)){
				Cmwdb::$db->where('product_group', $res, "in");
				Cmwdb::$db->where('product_lang', CLanguage::getInstance()->getCurrentUser());
				$ret = Cmwdb::$db->getOne(self::$tbl_name, 'max(product_price) max, min(product_price) min');
				return $ret;				
			}

		}
		return ['min'=>0, 'max'=>0];
	}
	
	static function GetPriceRangeByProducts($oids){
		Cmwdb::$db->where('product_group', $oids, "in");
		Cmwdb::$db->where('product_lang', CLanguage::getInstance()->getCurrentUser());
		$ret = Cmwdb::$db->getOne(self::$tbl_name, 'max(product_price) max, min(product_price) min');
		return $ret;
	}
	
	static function GetBrands($cats){
		$product_categories = CModule::LoadModule('product_category');
		if(is_object($product_categories)){
			$res = $product_categories->GetLinksCompaq(null, 'product', $cats, true);
			if(!empty($res)){
				$brands = CModule::LoadModule('brand');
				if(is_object($brands)){
					$bids = $brands->GetLinksCompaq($res, 'product', null, false);
					$fb = CModule::LoadModuleFront('brand');
					if(is_object($fb))
						return $fb->GetDatas($bids);
				}
			}
		
		}
		return [];		
	}
	
	static function GetByMultyprice($oid, $args=null, $lang=null, $page=1, $count=20, $assocs=null, $order="product_order"){
		if(is_array($oid) && empty($oid))return [];
		$multy = CModule::LoadComponent('product', 'multyprice');
		if(is_object($multy)){
			$res = $multy->GetSubjectsByMLink($oid);
			return $res;
		}
		return [];
	}
}
?>