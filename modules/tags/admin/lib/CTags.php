<?php

class CTags extends CStdTags{
	public $tbl_name = "product_tags";
	protected $tags = null;
	function __construct($pid=null){
		parent::__construct($pid);
// 		$this->LoadList();
// 		echo "hello from tags2";
	}
	
	////////////////////
	function LoadList()
	{
		$res = Cmwdb::$db->get($this->tbl_name);
		$ret_arr = [];
		foreach ($res as $value) {
			$ret_arr[$value['pid']] = $this->GetAsArrayPID($value['pid']);
		}
		$this->tags = $ret_arr;
	
	}
	
	function GetAsArrayList()
	{
		return $this->tags;
	}
	
	function GetAsArrayJSONList()
	{
		return json_encode($this->tags);
	}
	
	function GetByPIDList($pid)
	{
		if (isset($this->tags[$pid]))
			return $this->tags[$pid];
			return false;
	}
	
	function GetElementsPageList($lang = 'am', $limit = 20, $page = 1, $search = null, $is_active = 2)
	{
	
		if ($search) {
			Cmwdb::$db->where('tag_name', "%" . $search . "%", 'like');
		}
		if ($is_active == 1) {
			Cmwdb::$db->where('is_active', 1);
		}
		if ($is_active == 0) {
			Cmwdb::$db->where('is_active', 0);
		}
		Cmwdb::$db->where('lang', $lang);
	
		Cmwdb::$db->groupBy('pid');
		//$a = Cmwdb::$db->get('std_post p',null);
		Cmwdb::$db->pageLimit = $limit;
		$a = Cmwdb::$db->arraybuilder()->paginate($this->tbl_name, $page);
		$ret_arr['total_pages'] = Cmwdb::$db->totalPages;
	
		Cmwdb::$db->groupBy('lang');
		$ret_arr['total_all'] = Cmwdb::$db->getValue($this->tbl_name, "count(*)");
	
		Cmwdb::$db->where('is_active', 1);
		Cmwdb::$db->groupBy('lang');
		$ret_arr['total_active'] = Cmwdb::$db->getValue($this->tbl_name, "count(*)");
		if ($ret_arr['total_active'] === NULL) {
			$ret_arr['total_active'] = 0;
		}
	
		Cmwdb::$db->groupBy('lang');
		Cmwdb::$db->where('is_active', 0);
		$ret_arr['total_passive'] = Cmwdb::$db->getValue($this->tbl_name, "count(*)");
		if ($ret_arr['total_passive'] === NULL) {
			$ret_arr['total_passive'] = 0;
		}
	
		$new_array = [];
	
		$def_lang = CLanguage::getInstance()->getDefaultUser();
		foreach ($a as $item) {
			Cmwdb::$db->where('pid', $item['pid']);
			Cmwdb::$db->where('lang', $lang);
			$post = Cmwdb::$db->getOne($this->tbl_name);
	
			Cmwdb::$db->where('tag_pid', $item['pid']);
			$item['post_count'] = Cmwdb::$db->getValue("tags_to_post", "count(*)");
	
			if (!$post['tag_name']) {
				Cmwdb::$db->where('pid', $item['pid']);
				Cmwdb::$db->where('lang', $def_lang);
				$post = Cmwdb::$db->getOne($this->tbl_name);
				$item['is_translated'] = false;
				$item['tag_name'] = $post['tag_name'];
				$new_array[] = $item;
	
			} else {
				$item['is_translated'] = false;
				$new_array[] = $item;
	
			}
	
		}
		$ret_arr['data'] = $new_array;
		return $ret_arr;
	
	}
	
	function Publish($pid)
	{
		if (is_array($pid)) {
			Cmwdb::$db->where('pid', $pid, "in");
			if (Cmwdb::$db->update($this->tbl_name, array('is_active' => 1)))
				return true;
		}
		if (is_numeric($pid)) {
			Cmwdb::$db->where('pid', $pid);
			if (Cmwdb::$db->update($this->tbl_name, array('is_active' => 1)))
				return true;
		}
		return false;
	}
	
	function Passive($pid)
	{
		if (is_array($pid)) {
			Cmwdb::$db->where('pid', $pid, "in");
			if (Cmwdb::$db->update($this->tbl_name, array('is_active' => 0)))
				return true;
		}
		if (is_numeric($pid)) {
			Cmwdb::$db->where('pid', $pid);
			if (Cmwdb::$db->update($this->tbl_name, array('is_active' => 0)))
				return true;
		}
		return false;
	}
	
	function Delete($pid)
	{
		if (is_array($pid)) {
			Cmwdb::$db->where('pid', $pid, "in");
			Cmwdb::$db->delete($this->tbl_name);
			Cmwdb::$db->where('tag_pid', $pid, "in");
			Cmwdb::$db->delete('tags_to_post');
	
			return true;
		}
		if (is_numeric($pid)) {
			Cmwdb::$db->where('pid', $pid);
			Cmwdb::$db->delete($this->tbl_name);
			Cmwdb::$db->where('tag_pid', $pid);
			Cmwdb::$db->delete('tags_to_post');
			return true;
		}
		return false;
	
	}
	
	function AddLinks($obj_id, $obj_type, $s_links, $remove_exists=true){
		$links = CModule::LoadComponent('tags', 'product_links');
		if(is_object($links)){
			return $links->AddLinks($obj_id, $obj_type, $s_links, $remove_exists);
		}
		return false;
	}
	
	function GetLinks($obj_id=null, $obj_type=null, $s_link=null, $group_by_mlink = false){
		$links = CModule::LoadComponent('tags', 'product_links');
		if(is_object($links)){
			return $links->GetLinks($obj_id, $obj_type, $s_link, $group_by_mlink);
		}
		return false;
	
	}
	
	function RemoveLinks($m_id, $obj_type){
		$links = CModule::LoadComponent('tags', 'product_links');
		if(is_object($links)){
			return $links->RemoveMultyLinks($m_id, $obj_type);
		}
		return false;
	
	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('pid', $id);
			Cmwdb::$db->where('lang', $lang);
			if(!$type)$type = 'product_tag';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'tag_slug');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('tag_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'tag_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['tag_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['tag_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	
}