<?php

class CFrontProductCategory extends CFrontCategory{
	static protected $configs = array();
	static $tbl_name = "std_category_product";
	static function Initial(){
		self::$tbl_name = "std_category_product";
		self::$configs = CModule::TakeConfigs('product_category');

	}
	
	function __construct(){
		self::Initial();
	}
	static function GetDatas($oid = null, $args = null, $lang = null, $page = 1, $count = 10, $assoc_prop=null, $order = "cid")
	{
		if (is_string($oid)){
			Cmwdb::$db->where('slugs', $oid);
			$oid = Cmwdb::$db->getValue(self::$tbl_name, 'cid');
			if(!$oid)return array();
		}
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		Cmwdb::$db->where('category_lang', $lang);
		Cmwdb::$db->orderBy($order);
		$cur_oids = array();
		$ret = array();
		if (!$oid) {
			if(isset($args['categories']) && is_array($args['categories'])){
				if (!in_array("cid", $args['categories'])) $args['categories'][] = "cid";
				$ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
			} 
			else $ret = Cmwdb::$db->get(self::$tbl_name);
			$tmp = array();
			foreach ($ret as $values)$tmp[] = $values['cid'];
			$oid = $tmp;
		} 
		else {
			if (is_numeric($oid)) Cmwdb::$db->where('cid', $oid);
			
			if (is_array($oid)) Cmwdb::$db->where('cid', $oid, "in");
			if (isset($args['categories'])) {
				if (is_array($args['categories'])) {
					if (!in_array("cid", $args['categories'])) $args['categories'][] = "cid";
				}
				$ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
				
			} 
			else $ret = Cmwdb::$db->get(self::$tbl_name);
		}
		
		
		$big_boom = array();
		if(is_array($args)){
			foreach ($args as $subject_type=>$unneed){
				$obj = CModule::LoadModuleFront($subject_type);
				if(is_object($obj)){
					$big_boom = array_merge($big_boom, $obj->GetLinksExt($oid, 'product_category'));
				}
			}
		}

		if(isset($args['product'])){
			$links = CModule::LoadComponent('product_category', 'product_links');
			$ext = $links->GetLinksCompaq(null, 'product', $oid, true);
			$ext = array_unique($ext);
			CModule::LoadModuleFront('product');
			$big_boom = array_merge($big_boom,CFrontProduct::GetDatas($ext));
			$big_boom['product_links'] = $links->GetLinks(null, 'product', $oid, false);
		}
		$retf = array();
		if(is_array($oid) || !$oid){
			foreach ($ret as $values) {
				$retf['category'][$values['cid']] = $values;
				$cur_oids[] = $values['cid'];
			}
			if(!isset($retf['category']))$retf['category'] = [];
		}
		else{
			$retf['category'] = $ret[0];
			$cur_oids[] = $ret[0]['cid'];
		}
		$retf = array_merge($retf, $big_boom);
		$recovert = $retf['category'];
		$tmp = array();
// 		var_dump($recovert);die;
		if(is_array($oid)){
			foreach ($recovert as $cid=>$datas){
				$tmp[$cid]['product_category'] = $datas;
			}
		}
		else{
			$tmp['product_category'] = $recovert;
		}
		if(isset($retf['product_links'])){
			$attr = CModule::LoadModule('attributika');
			if(is_array($oid)){
				foreach ($retf['product_links'] as $cid=>$pids){
					foreach ($pids as $pid){
						$tmp[$cid]['product'][$pid] = $retf['product'][$pid];
						if(is_object($attr)){
							$tmp[$cid]['attributika'] = $attr->GetSubjectsNotVarieted($cid, 'product_category', true);
						}
					}
				}
			}
			else{
				$tmp['product_category'] = $recovert;
				foreach ($retf['product_links'] as $cid=>$pids){
					foreach ($pids as $pid)
						$tmp['product'][$pid] = $retf['product'][$pid];
				}
				if(is_object($attr))
					$tmp['attributika'] = $attr->GetSubjectsNotVarieted($oid, 'product_category', true);
			}
		}
		return $tmp;
	}

	static function GetCatsTree($oid)
	{
		$ret_arr = [];

		Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
		Cmwdb::$db->where('cid', $oid);

		$data = Cmwdb::$db->getOne(self::$tbl_name);
		//$ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];


		while (isset($data['category_parent']) && $data['category_parent'] !== 0) {

			$ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];

			Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
			Cmwdb::$db->where('cid', $data['category_parent']);

			$data = Cmwdb::$db->getOne(self::$tbl_name);
		}

		$ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];

		return array_reverse($ret_arr);
	}
	
	static function GetCID($slug){
		Cmwdb::$db->where('slugs', $slug);
		return Cmwdb::$db->getValue(self::$tbl_name, 'cid');
	}
	
	static function GetLastCID($obj_id, $obj_type){
		Cmwdb::$db->where('obj_id', $obj_id);
		Cmwdb::$db->where('obj_type', $obj_type);
		$last_cid = Cmwdb::$db->getOne(self::tbl_name, 'max(s_link) s_link');
		if(!empty($last_cid))return false;
		return $last_cid['s_link'];
	}
	
	static function GetFiltered($search_word=null, $in_fields=null, $lang = null, $page=1, $count=20, $order="cid", $filters=null){
		if(!$in_fields)$in_fields = ['category_title'];
		else {
			if(!in_array("category_title", $in_fields))$in_fields[] = 'category_title';
		}
		if($search_word){
			foreach ($in_fields as $field_name)
				Cmwdb::$db->orWhere($field_name, '%'.$search_word.'%', "like");
		}
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		Cmwdb::$db->where('category_lang', $lang);
		Cmwdb::$db->orderBy($order);
		Cmwdb::$db->pageLimit = $count;
		$res = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name,$page, ['cid', 'category_title', 'category_img', 'category_parent', 'category_order', 'is_active', 'is_complated']);
		//     	$res = Cmwdb::$db->get($this->tbl_name);
		$reconvert = array();
		$needs = array();
		foreach ($res as $values){
			$reconvert[$values['cid']] = $values;
			$needs[] = $values['cid'];
		}
		if(empty($needs))return ['page_count'=>0, 'current_page'=>1];
		$brands = CModule::LoadModule('brand');
		$brand_links = $brands->GetLinksCompaq($needs, 'product_category');
		CFrontBrand::Initial();
		$ret = array();
		$ret = CFrontProductCategory::GetDatas($needs, null, $lang, $page, $count, null, $order);
		$reconvert = array();
		$deflang = CLanguage::getInstance()->getDefaultUser();
// 		CFrontBrand::Initial();
		foreach ($ret as $cid=>$vals){
			$tmp_links = $brands->GetLinksCompaq($cid, 'product_category');
// 			echo "BRO";
// 			var_dump($tmp_links);
			$reconvert[$cid] = $vals;
			if(empty($tmp_links))
				$reconvert[$cid]['brand'] = []; 
			else	
				$reconvert[$cid] = array_merge($reconvert[$cid],CFrontBrand::GetDatas($tmp_links));
		}
		$temp = self::GetNeeds($needs, $lang);

		$component_links = CModule::LoadComponent('product_category', 'product_links');
		$product_module = CModule::LoadModule('product');
		foreach ($temp as $cid=>$vals){
// 			print_r($lang);
			$reconvert[$cid]['product_category']['is_active_langs'] = $vals['is_active_langs'];
			$reconvert[$cid]['product_category']['category_level'] = $vals['category_level'];
			$reconvert[$cid]['product_category']['category_title'] = $vals['category_title'];
			if($vals['category_parent']){
				$reconvert[$cid]['product_category']['category_parent'] = $vals['category_parent'];
				$reconvert[$cid]['product_category']['parent_is_translated'] = true;
			}
			else{
				Cmwdb::$db->where('cid', $vals['category_parent_id']);
				Cmwdb::$db->where('category_lang', $deflang);
				$reconvert[$cid]['product_category']['category_parent'] = Cmwdb::$db->getValue(self::$tbl_name, 'category_title');
				$reconvert[$cid]['product_category']['parent_is_translated'] = false;
			}
			$reconvert[$cid]['product_category']['is_translated'] = $vals['is_active_langs'][$lang];
			$reconvert[$cid]['product_category']['category_img'] = $vals['category_img'];
			if(is_object($component_links)){
				//TODO: Here we must take price range for products of this category
				$prod_links = $component_links->GetLinksCompaq(null, 'product',$cid, true);
				$reconvert[$cid]['product_category']['product_count'] = count($prod_links);
				$reconvert[$cid]['product_category']['price_range'] = $product_module->GetPriceRangeList($prod_links);
			}
				
		}
		$is_changed = false;
		$new_count=0;
		if($filters){
			if(isset($filters['brand']) && $filters['brand']){
				$brand_links = $brands->GetLinksCompaq(null, 'product_category', $filters['brand'], true);
				$elem_count = 0;
				foreach ($reconvert as $cid=>$unneed){
					if(in_array($cid, $brand_links))continue;
					unset($reconvert[$cid]);
					$elem_count++;
					$is_changed = true;
				}
				$new_count = count($reconvert);
			}
		}
		if($is_changed)$reconvert['page_count'] = (int)$new_count/$count;
		$reconvert['page_count'] = Cmwdb::$db->totalPages;
		$reconvert['current_page'] = $page;

		return $reconvert;
	}
	
	static function GetNeeds(array $oids, $lang=null){
		if(!$lang)$lang=CLanguage::getInstance()->getCurrentUser();
		$deflang = CLanguage::getInstance()->getDefaultUser();
		if(empty($oids))return [];
		Cmwdb::$db->where('cid', $oids, "in");
		$res = Cmwdb::$db->get(self::$tbl_name);
		$ret = array();
		foreach ($res as $vals)$ret[$vals['cid']][$vals['category_lang']] = $vals;
		$forret = array();
		foreach ($ret as $cid=>$values){
			foreach ($values as $cur_lang=>$details){
					
				if($details['category_title']!="")
					$forret[$cid]['is_active_langs'][$cur_lang] = true;
					else $forret[$cid]['is_active_langs'][$cur_lang] = false;
					if($lang===$cur_lang){
						if($details['category_title']){
							$forret[$cid]['category_title'] = $details['category_title'];
							$forret[$cid]['is_translated'] = true;
	
						}
						else {
							Cmwdb::$db->where('cid', $cid);
							Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getDefaultUser());
							$forret[$cid]['category_title'] = Cmwdb::$db->getValue(self::$tbl_name, 'category_title');
							$forret[$cid]['is_translated'] = false;
	
						}
						if($details['category_parent']){
							Cmwdb::$db->where('cid', $details['category_parent']);
							Cmwdb::$db->where('category_lang', $lang);
							$forret[$cid]['category_parent'] = Cmwdb::$db->getValue(self::$tbl_name, 'category_title');
							$forret[$cid]['parent_is_translated'] = true;
							$forret[$cid]['category_parent_id'] = $details['category_parent'];
							if($forret[$cid]['category_parent']==""){
								Cmwdb::$db->where('cid', $cid);
								Cmwdb::$db->where('category_lang', $deflang);
								$forret[$cid]['category_parent'] = Cmwdb::$db->getValue(self::$tbl_name, 'category_title');
								$forret[$cid]['parent_is_translated'] = false;
								
							}
							$tmp_cid = $details['category_parent'];
							$level = 0;
							do{
								//     						echo "Bro: step to one<br>";
								$level++;
								Cmwdb::$db->where('cid', $tmp_cid);
							}while($tmp_cid = Cmwdb::$db->getValue(self::$tbl_name, 'category_parent'));
							$forret[$cid]['category_level'] = $level;
						}
						else{
							$forret[$cid]['category_parent_id'] = 0;
							$forret[$cid]['category_parent'] = "";
							$forret[$cid]['category_level'] = 0;
						}
						if($details['category_img']){
							$at = new CAttach($details['category_img']);
							$forret[$cid]['category_img'] = $at->GetURL();
						}
						else $forret[$cid]['category_img'] = "";
						//     				$forret[$cid]['is_active_langs'][$cur_lang] = true;
						$forret[$cid]['category_order'] = $details['category_order'];
						$forret[$cid]['is_active'] = $details['is_active'];
					}
					else{
						//     				$forret[$cid]['is_active_langs'][$cur_lang] = $details['is_active'];
					}
			}
				
			// 			$posts = $post_links->GetBySLink($cid);
			// 			$forret[$cid]['posts_count'] = count($posts);
		}
			
		return $forret;
	}

	static function GetCategoriesByParent($parent_id = 0)
	{
		Cmwdb::$db->where('category_lang', CLanguage::getCurrentUser());
		Cmwdb::$db->where('category_parent', $parent_id);
		Cmwdb::$db->orderBy('category_order');
		return Cmwdb::$db->get(self::$tbl_name);
	}
	static function GetCategoriesWithMaxLevel($max_level = 0)
	{
		$ret = [];
		$res = self::GetCategoriesByParent(0);
		if ($res) {
			foreach ($res as $item) {
				$ret[] = array('level' => 0, 'data' => $item);
				if ($max_level > 0) {
					$res2 = self::GetCategoriesByParent($item['cid']);
					if ($res2) {
						foreach ($res2 as $item2) {
							$ret[] = array('level' => 1, 'data' => $item2);
							if ($max_level > 1) {
								$res3 = self::GetCategoriesByParent($item2['cid']);
								if ($res3) {
									foreach ($res3 as $item3) {
										$ret[] = array('level' => 2, 'data' => $item3);
										if ($max_level > 2) {
											$res4 = self::GetCategoriesByParent($item3['cid']);
											if ($res4) {
												foreach ($res4 as $item3) {
													$ret[] = array('level' => 3, 'data' => $item3);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $ret;
	}
}