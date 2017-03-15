<?php

class CBrand{
	protected $datas = array();
	protected $tbl_name = "std_brands";
	protected $is_list = false;
	
	function __construct($oid = null){
		
		if(is_string($oid)){
			Cmwdb::$db->where('brand_lang', CLanguage::getInstance()->getCurrentUser());
			Cmwdb::$db->where('brand_slug', $oid);
			$res = Cmwdb::$db->getOne($this->tbl_name);
			if(!empty($res))$this->datas = $res;
		}
		if(is_numeric($oid)){
			Cmwdb::$db->where('brand_lang', CLanguage::getInstance()->getCurrentUser());
			Cmwdb::$db->where('brand_group', $oid);
			$res = Cmwdb::$db->getOne($this->tbl_name);
			if(!empty($res)){
				$this->datas = $res;
			}
			
		}
	}
	
	function CreateBrand($args){
		$brand_group = Cmwdb::$db->getOne($this->tbl_name, 'max(brand_group) brand_group');
		if ($brand_group['brand_group']) $brand_group['brand_group']++;
		else $brand_group['brand_group'] = 1;
		$brand_group = $brand_group['brand_group'];
		$slugs = array();
		foreach ($args as $lang=>$values){
			$slugs[$lang] = CSlug::ConvertToEnglish($values['brand_title']);
		}
		$slugs = CSlug::getInstance()->GetVerifiedSlugs($slugs, $this->tbl_name, 'brand_slug');
		$queryData = array();
		Cmwdb::$db->startTransaction();
		foreach ($args as $lang=>$values){
			$queryData['brand_title'] = $values['brand_title'];
			$queryData['brand_descr'] = $values['brand_descr'];
			if($values['seo_title']==="")$values['seo_title'] = $values['brand_title'];
			if($values['seo_descr']==="")$values['seo_descr'] = $values['brand_descr'];
			$seo_tmp = new CSeo();
			if(!$queryData['brand_seo'] = $seo_tmp->CreateSeo($values['seo_title'], $values['seo_descr'], $values['seo_keywords'])){
				Cmwdb::$db->rollback();
				return false;
			}
			if(isset($values['brand_gallery']))$queryData['brand_gallery'] = json_encode($values['brand_gallery']);
			else $queryData['brand_gallery'] = json_encode(array());
			if(isset($values['brand_covers']))$queryData['brand_covers'] = json_encode($values['brand_covers']);
			else $queryData['brand_covers'] = json_encode(array());
			if(isset($values['brand_files']))$queryData['brand_attaches'] = json_encode($values['brand_files']);
			else $queryData['brand_attaches'] = json_encode(array());
			if(isset($values['brand_img']))$queryData['brand_img'] = $values['brand_img']['id'];
			else $queryData['brand_img'] = null;
			if(isset($values['brand_img']))$queryData['brand_img_title'] = $values['brand_img']['title'];
			else $queryData['brand_img_title'] = "";
			$queryData['brand_slug'] = $slugs[$lang];
			$queryData['brand_lang'] = $lang;
			$queryData['brand_group'] = $brand_group;
			if(!Cmwdb::$db->insert($this->tbl_name, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
				
				
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function EditBrand($args, $brand_id){
		Cmwdb::$db->where('brand_group', $brand_id);
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		foreach ($res as $values){
			$ret[$values['brand_lang']] = $values;
		}
		
		$slugs = array();
		foreach ($args as $lang=>$values){
			$slugs[$lang] = CSlug::ConvertToEnglish($values['brand_title']);
		}
		$slugs = CSlug::getInstance()->GetVerifiedSlugs($slugs, $this->tbl_name, 'brand_slug');
		
		//verify and update content
		$queryData = array();
		Cmwdb::$db->startTransaction();
		foreach ($args as $lang=>$values){
			if($values['brand_title']!=="")$queryData['brand_title'] = $values['brand_title'];
			else $queryData['brand_title'] = $ret[$lang]['brand_title'];
			if($values['brand_descr']!=="")$queryData['brand_descr'] = $values['brand_descr'];
			else $queryData['brand_descr'] = $ret[$lang]['brand_descr'];
			$seo_tmp = new CSeo($ret[$lang]['brand_seo']);
//			var_dump($ret[$lang]);die;
			if(!$seo_tmp->UpdateDatas($values['seo_title'], $values['seo_descr'], $values['seo_keywords'])){
				Cmwdb::$db->rollback();
				return false;
			}
			if(isset($values['brand_gallery']) && is_array($values['brand_gallery']))
				$queryData['brand_gallery'] = json_encode($values['brand_gallery']);
			else $queryData['brand_gallery'] = $ret[$lang]['brand_gallery'];
			if(isset($values['brand_covers']) && is_array($values['brand_covers']))
					$queryData['brand_covers'] = json_encode($values['brand_covers']);
			else $queryData['brand_covers'] = $ret[$lang]['brand_covers'];
			if(isset($values['brand_files']) && is_array($values['brand_files']))
				$queryData['brand_attaches'] = json_encode($values['brand_files']);
			else $queryData['brand_attaches'] = $ret[$lang]['brand_attaches'];
			if(isset($values['brand_img']))$queryData['brand_img'] = $values['brand_img']['id'];
			else $queryData['brand_img'] = $ret[$lang]['brand_img'];
			if(isset($values['brand_img_title']))$queryData['brand_img_title'] = $values['brand_img']['title'];
			else $queryData['brand_img_title'] = $ret[$lang]['brand_img_title'];
			if($ret[$lang]['brand_slug']=="")
				$queryData['brand_slug'] = $ret[$lang]['brand_slug'];
			else $queryData['brand_slug'] = $slugs[$lang];
			
			$queryData['brand_lang'] = $lang;
			$queryData['brand_group'] = $ret[$lang]['brand_group'];
			Cmwdb::$db->where('brand_group', $ret[$lang]['brand_group']);
			Cmwdb::$db->where('brand_lang', $lang);
			if(!Cmwdb::$db->update($this->tbl_name, $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
		
		
		}
		Cmwdb::$db->commit();
		return true;
	}
	
	function GetDatas($brand_id){
		Cmwdb::$db->where('brand_group', $brand_id);
		$res = Cmwdb::$db->get($this->tbl_name);
		if(!empty($res)){
			$ret = array();
			foreach ($res as $values){
				$cur_lang = $values['brand_lang']; 
				$ret[$cur_lang] = $values;
				if($ret[$cur_lang]['brand_seo']){
					$tmp_seo = new CSeo($ret[$cur_lang]['brand_seo']);
					$ret[$cur_lang]['seo_title'] = $tmp_seo->GetTitle();
					$ret[$cur_lang]['seo_descr'] = $tmp_seo->GetDescr();
					$ret[$cur_lang]['seo_keywords'] = $tmp_seo->GetKeywords();
				}
				else{
					$ret[$cur_lang]['seo_title'] = "";
					$ret[$cur_lang]['seo_descr'] = "";
					$ret[$cur_lang]['seo_keywords'] = "";
				}
				$ret[$cur_lang]['brand_covers'] = json_decode($values['brand_covers'], true);
				$ret[$cur_lang]['brand_gallery'] = json_decode($values['brand_gallery'], true);
				$ret[$cur_lang]['brand_files'] = json_decode($values['brand_attaches'], true);
				unset($ret[$cur_lang]['brand_attaches']);
			}
			
			return $ret;
			
		}
		return array();
	}
	
	function GetElementsPage($lang, $limit = 20, $page = 1, $search = null){
		if ($search) {
			Cmwdb::$db->where('brand_title', "%" . $search . "%", 'like');
		}
		Cmwdb::$db->where('brand_lang', $lang);
	
		//$a = Cmwdb::$db->get('std_post p',null);
		Cmwdb::$db->pageLimit = $limit;
		$a = Cmwdb::$db->arraybuilder()->paginate($this->tbl_name, $page);
		$ret_arr['total_pages']=Cmwdb::$db->totalPages;

		$def_lang = CLanguage::getInstance()->getDefaultUser();
		$ret_arr['data'] = $a;
		foreach ($ret_arr['data'] as $index=>$values){
			if($values['brand_title']==""){
				Cmwdb::$db->where('brand_group', $values['brand_group']);
				Cmwdb::$db->where('brand_lang', CLanguage::getInstance()->getDefaultUser());
				$cur_title = Cmwdb::$db->getOne($this->tbl_name, array('brand_title'));
				$ret_arr['data'][$index]['brand_title'] = $cur_title['brand_title'];
				$ret_arr['data'][$index]['is_translated'] = false;
			}
			else $ret_arr['data'][$index]['is_translated'] = true;
		}

		return $ret_arr;
	
	}
	
	function DeleteBrand($brand_id){
		if(is_array($brand_id)){
			Cmwdb::$db->where('brand_group', $brand_id, "in");
			return Cmwdb::$db->delete($this->tbl_name);
		}
		if(is_numeric($brand_id)){
			Cmwdb::$db->where('brand_group', $brand_id);
			return Cmwdb::$db->delete($this->tbl_name);
		}
		return false;
	}

	function GetBrands($b_ids=null, $lang = null, $args=null){
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		Cmwdb::$db->where('brand_lang', $lang);
		if(is_numeric($b_ids))Cmwdb::$db->where('brand_group', $b_ids);
		if(is_array($b_ids))Cmwdb::$db->where('brand_group', $b_ids, "in");
		if(is_string($b_ids))Cmwdb::$db->where('brand_slug', $b_ids);
		$res = array();
		if(is_array($args))
			$res = Cmwdb::$db->get($this->tbl_name, null, $args);
		else $res = Cmwdb::$db->get($this->tbl_name);
		$ret = array();
		foreach($res as $values)$ret[$values['brand_group']] = $values;
		return $ret;
	}
	
	function AddLinks($obj_id, $obj_type, $s_links, $remove_exists=true){
		$links = CModule::LoadComponent('brand', 'product_links');
		if(is_object($links)){
			return $links->AddLinks($obj_id, $obj_type, $s_links, $remove_exists);
		}
		return false;
	}
	
	function GetLinks($obj_id=null, $obj_type=null, $s_link=null, $group_by_mlink = false){
		$links = CModule::LoadComponent('brand', 'product_links');
		if(is_object($links)){
			return $links->GetLinks($obj_id, $obj_type, $s_link, $group_by_mlink);
		}
		return false;
		
	}
	
	function GetLinksCompaq($obj_id=null, $obj_type=null, $s_link=null, $group_by_mlink = false){
		$links = CModule::LoadComponent('brand', 'product_links');
		if(is_object($links)){
			$res = $links->GetLinks($obj_id, $obj_type, $s_link, $group_by_mlink);
			$ret = array();
			foreach ($res as $key=>$unneed)$ret[] = $key;
			return $ret;
				
		}
		return false;
	
	}
	
	function RemoveLinks($m_id, $obj_type){
		$links = CModule::LoadComponent('brand', 'product_links');
		if(is_object($links)){
			return $links->RemoveMultyLinks($m_id, $obj_type);
		}
		return false;
		
	}
	
	function GetDatasByObj($obj_id, $obj_type, $lang=null){
		if(!$lang)$lang = CLanguage::getInstance()->getDefaultUser();
		$links = CModule::LoadComponent('brand', 'product_links');
		if(is_object($links)){
			$res = $links->GetLinks($obj_id, $obj_type, null, false);
			if(!empty($res)){
				$tmp = array();
				foreach ($res as $s_id=>$unneed){
					$tmp[] = $s_id;
				}
				Cmwdb::$db->where('brand_lang',$lang);
				Cmwdb::$db->where('brand_group', $tmp, "in");
				$ret = Cmwdb::$db->get($this->tbl_name);
				foreach ($ret as $values){
					$res[$values['brand_group']] = $values['brand_title'];
				}
				return $res;
			}
		}
		return array();
	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('brand_group', $id);
			Cmwdb::$db->where('brand_lang', $lang);
			if(!$type)$type = 'brand';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'brand_slug');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('brand_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'brand_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('brand_group', $id);
				Cmwdb::$db->where('brand_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['brand_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('brand_group', $id);
				Cmwdb::$db->where('brand_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['brand_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	
}
?>