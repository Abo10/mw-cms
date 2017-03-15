<?php

class CCategoryPost extends CCategory{

	function __construct($slug=null){
		$this->tbl_name = "std_category_post";
		
	}
	
	function CreateCategory($argv){
		return parent::CreateCategory($argv);
	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('cid', $id);
			Cmwdb::$db->where('category_lang', $lang);
			if(!$type)$type = 'post_category';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'slugs');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('slugs', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'slugs'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('cid', $id);
				Cmwdb::$db->where('category_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['slugs'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('cid', $id);
				Cmwdb::$db->where('category_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['slugs'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	
	function DeleteCategory($cid){
		Cmwdb::$db->where('cid', $cid);
		$ids = Cmwdb::$db->get($this->tbl_name, null, ['category_seo']);
		$tmp = [];
		foreach ($ids as $vals)$tmp[] = $vals['category_seo'];
		
		if(parent::DeleteCategory($cid)){
			Cmwdb::$db->where('post_cat_id', $cid);
			if(Cmwdb::$db->delete('post_to_postCategory_links')){
				if(!empty($tmp))
					return CSeo::DeleteSeo($tmp);
				return true;
			}
				
		}
		return false;
	}
	
}

?>