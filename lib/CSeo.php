<?php

class CSeo{
	protected $id = null;
	protected $title = "";
	protected $descr = "";
	protected $keywords = "";
	static protected $tbl_name = "std_seo";
	function __construct($seo_id=null){
		if($seo_id){
			Cmwdb::$db->where("seo_id", $seo_id);
			$res = Cmwdb::$db->getOne("std_seo");
			if(!empty($res)){
				$this->id = $seo_id;
				if(isset($res['seo_title']))$this->title = $res['seo_title'];
				if(isset($res['seo_descr']))$this->descr = $res['seo_descr'];
				if(isset($res['seo_keywords']))$this->keywords = $res['seo_keywords'];
			}
		}
	}
	
	function CreateSeo($title, $descr, $keywords=null){
		$queryData['seo_title'] = CSecurity::FilterString($title);
		$queryData['seo_descr'] = CSecurity::FilterString($descr);
		$queryData['seo_keywords'] = $keywords;
		if(Cmwdb::$db->insert("std_seo", $queryData)){
			$this->id = Cmwdb::$db->getInsertId();
			$this->title = $title;
			$this->descr = $descr;
			$this->keywords = $keywords;
			return $this->id;
		}
		return false;
	}
	
	function UpdateKeywords($keywords){
		if($this->id){
			Cmwdb::$db->where($this->id);
			if(Cmwdb::$db->update("std_seo", array("seo_keywords"=>$keywords))){
				$this->keywords = $keywords;
				return true;
			}
		}
		return false;
	}
	
	function UpdateDatas($title, $descr, $keyword=null){
// 		echo 'Try to edit id: '.$this->id." set title - ".$title.'<br>';
		$arg = array("seo_title"=>CSecurity::FilterString($title), "seo_descr"=>CSecurity::FilterString($descr), "seo_keywords"=>$keyword);
// 		var_dump($arg);
		Cmwdb::$db->where('seo_id', $this->id);
		if(Cmwdb::$db->update("std_seo", $arg)){
			$this->descr = $descr;
			$this->title = $title;
			$this->keywords = $keyword;
// 			echo 'Updated';die;
			return true;
		}
		return false;
	}
	function GetTitle(){return $this->title;}
	function GetDescr(){return $this->descr;}
	function GetKeywords(){return $this->keywords;}
	
	function SearchInSeo($s_word){
		Cmwdb::$db->where('seo_title','%'.$s_word.'%', "like" );
		$res = Cmwdb::$db->get('std_seo', null, ['seo_id']);
		$ret = array();
		if(!empty($res)){
			foreach ($res as $values)$ret[] = $values['seo_id'];
		}
		return $ret;
	}
	
	static function DeleteSeo($ids){
		if(is_array($ids)){
			Cmwdb::$db->where('seo_id', $ids, "in");
		}
		if(is_numeric)Cmwdb::$db->where('seo_id', $ids);
		
		return Cmwdb::$db->delete(self::$tbl_name);
	}
}
?>