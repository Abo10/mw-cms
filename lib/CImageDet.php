<?php

class CImageDet extends CImage{
	protected $d_id = null;
	protected $title = "";
	protected $descr = "";
	protected $lang = "";
	
	function __construct($id=null){
		if($id){
// 			$query = "SELECT std_img_det.*,std_images.id as iid, std_images.url, std_images.type 
// 						FROM std_images JOIN std_img_det ON std_images.id = std_img_det.aid 
// 						where std_images.id='$id'";
			Cmwdb::$db->where('std_images.id',$id);
			Cmwdb::$db->join('std_img_det','std_images.id = std_img_det.aid','left');
			$res = Cmwdb::$db->getOne('std_images',array('std_img_det.*','std_images.id iid','std_images.url','std_images.type'));
			$this->id = $id;
			$this->d_id = $res['id'];
			$this->type = $res['type'];
			$this->url = $res['url'];
			$this->title = $res['title'];
			$this->descr = $res['descr'];
			$this->lang = $res['lang'];
		}
	}
	
	function CreateImage($file, $argv=null, $width=null, $height=null, $top=0, $left=0){
		if(parent::CreateImage($file, $width, $height, $top, $left)){
			if(isset($argv['title']))$this->title = $argv['title'];
			else $this->title = $_FILES[$file]['name'];
			$this->title = CSecurity::FilterString($this->title);
			if(isset($argv['descr']))$this->descr = $argv['descr'];
			else $this->descr = null;
			$this->descr = CSecurity::FilterString($this->descr);
			if(isset($argv['lang']))$this->lang = $argv['lang'];
			else{
				$lang = CLanguage::getInstance();
				$this->lang = $lang->getDefault();
			}
			$queryData['title'] = $this->title;
			$queryData['descr'] = $this->descr;
			$queryData['lang'] = $this->lang;
			$queryData['aid'] = $this->id;
			if(Cmwdb::$db->insert("std_img_det", $queryData)){
				Cmwdb::$db->getLastError();
				$this->d_id = Cmwdb::$db->getInsertId();
				return $this->id;
			}
		}
		return false;
	}
	
	function GetTitle(){return $this->title;}
	function GetDescription(){return $this->descr;}
	function GetLang(){return $this->lang;}
	function GetDID(){return $this->d_id;}
	
	function UpdateDetails($argv=null){
		if(isset($argv['descr']))$this->descr = $argv['descr'];
		if(isset($argv['title']))$this->title = $argv['title'];
		if(isset($argv['lang']))$this->lang = $argv['lang'];
		$queryData['descr'] = $this->descr;
		$queryData['title'] = $this->title;
		$queryData['lang'] = $this->lang;
		Cmwdb::$db->where('id', $this->d_id);
		return Cmwdb::$db->update("std_img_det", $queryData);
	}
	
	function DeleteThis(){
		if(parent::DeleteInBase()){
			unlink($this->GetURL_Local("original"));
			unlink($this->GetURL_Local("thumb"));
			unlink($this->GetURL_Local("medium"));
			return true;
		}
		return false;
	}
}
?>