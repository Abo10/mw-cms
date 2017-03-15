<?php

class CAllPages{
	protected $pages = null;
	
	function __construct(){
		$query = "select DISTINCT pid from std_pages";
		$res = Cmwdb::$db->query($query);
		$ret_arr = [];

		foreach ($res as $value){
			$curr_page = new CPage();
			$ret_arr[$value['pid']] = $curr_page->GetAsArrayPID($value['pid']);
		}
		$this->pages = $ret_arr;
	
	}
	
	function GetAsArray(){
		return $this->pages;
	}
	
	function GetAsArrayJSON(){
		return json_encode($this->pages);
	}
	function GetElementsPage($lang='am', $limit = 20, $page = 1,  $search = null, $is_active = 2){

		if ($search) {
			Cmwdb::$db->where('p.page_title', "%" . $search . "%", 'like');
		}
		if($is_active == 1){
			Cmwdb::$db->where('p.page_isactive',1);
		}
		if($is_active == 0){
			Cmwdb::$db->where('p.page_isactive',0);
		}
		Cmwdb::$db->where('p.page_lang', $lang);

		Cmwdb::$db->groupBy('p.pid');
		//$a = Cmwdb::$db->get('std_post p',null);
		Cmwdb::$db->pageLimit = $limit;
		$a = Cmwdb::$db->arraybuilder()->paginate("std_pages p", $page);
		$ret_arr['total_pages']=Cmwdb::$db->totalPages;

		Cmwdb::$db->groupBy('page_lang');
		$ret_arr['total_all']=Cmwdb::$db->getValue("std_pages", "count(*)");

		Cmwdb::$db->where('page_isactive',1);
		Cmwdb::$db->groupBy('page_lang');
		$ret_arr['total_active']=Cmwdb::$db->getValue("std_pages", "count(*)");
		if($ret_arr['total_active'] === NULL){
			$ret_arr['total_active']=0;
		}

		Cmwdb::$db->groupBy('page_lang');
		Cmwdb::$db->where('page_isactive', 0);
		$ret_arr['total_passive']=Cmwdb::$db->getValue("std_pages", "count(*)");
		if($ret_arr['total_passive'] === NULL){
			$ret_arr['total_passive']=0;
		}

		$new_array = [];

		$def_lang = CLanguage::getInstance()->getDefaultUser();
		foreach($a as $item ){
			Cmwdb::$db->where('pid', $item['pid']);
			Cmwdb::$db->where('page_lang', $lang);
			$post =  Cmwdb::$db->getOne('std_pages');
			if(!$post['page_title']){
				Cmwdb::$db->where('pid', $item['pid']);
				Cmwdb::$db->where('page_lang', $def_lang);
				$post =  Cmwdb::$db->getOne('std_pages');
				$item['is_translated'] = false;
				$item['page_title'] = $post['page_title'];
				$new_array[] = $item;

			}else{
				$item['is_translated'] = true;
				$new_array[] = $item;

			}

		}
		$ret_arr['data'] = $new_array;
		return $ret_arr;

	}
}

?>