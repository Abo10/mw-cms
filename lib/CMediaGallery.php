<?php

class CMediaGallery{
	protected $id = null;
	protected $galleryName = "";
	protected $galleryLang = "";
	protected $galleryAttachments = null;
	protected $galleryDescr = "";
	protected $galleryDate = null;
	
	function __construct($gal_id=null){
		if($gal_id){
			Cmwdb::$db->where("gallery_id", $gal_id);
			$res = Cmwdb::$db->getOne("media_gallery");
			if(!empty($res)){
				$this->id = $res['gallery_id'];
				$this->galleryName = $res['gallery_name'];
				$this->galleryLang = $res['g_lang'];
				$this->galleryDescr = $res['gallery_descr'];
				$this->galleryDate = $res['gallery_date'];
				$this->galleryAttachments = json_decode($res['attachments']);
			}
			CErrorHandling::RegisterHandle("gal_initial");
		}

	}
	
	function InitialByName($gal_name){
		Cmwdb::$db->where("gallery_name", $gal_name);
		$res = Cmwdb::$db->getOne("media_gallery");
		if(!empty($res)){
			$this->id = $res['gallery_id'];
			$this->galleryName = $res['gallery_name'];
			$this->galleryLang = $res['g_lang'];
			$this->galleryDescr = $res['gallery_descr'];
			$this->galleryDate = $res['gallery_date'];
			$this->galleryAttachments = json_decode($res['attachments']);
		}		
	}
	
	function GetAsArray(){
		if($this->id){
			$ret['id'] = $this->id;
			$ret['name'] = $this->galleryName;
			$ret['lang'] = $this->galleryLang;
			$ret['descr'] = $this->galleryDescr;
			$ret['date'] = $this->galleryDate;
			$ret['attachments'] = $this->galleryAttachments;
			return $ret;
		}
		return false;
	}
	
	function GetAsJSON(){
		if($gallery = $this->GetAsArray()){
			return json_encode($gallery);
		}
		return false;
	}
	
	//The argument attachment is array, that have
	// [ID] = Attachment ID
	// [ID]['descr'] = Description of current attachment, can be null
	function CreateGallery($attachments, $name, $descr=null, $lang = null){
		Cmwdb::$db->where("gallery_name", $name);
		$res = Cmwdb::$db->get("media_gallery");
		if(empty($res)){
			$queryData['attachments'] = json_encode($attachments);
			$queryData['gallery_name'] = $name;
			if($descr)$queryData['gallery_descr'] = $descr;
			else $queryData['gallery_descr'] = "";
			$queryData['gallery_descr'] = CSecurity::FilterString($queryData['gallery_descr']);
			if($lang)$queryData['g_lang'] = $lang;
			else{
				$cur_lang = CLanguage::getInstance();
				$queryData['g_lang'] = $cur_lang->getCurrent();
			}
			$queryData['gallery_date'] = date("Y/m/d H:i:s");
			if(Cmwdb::$db->insert("media_gallery", $queryData)){
				$this->id = Cmwdb::$db->getInsertId();
				$this->galleryAttachments = $attachments;
				$this->galleryDate = $queryData['gallery_date'];
				$this->galleryDescr = $queryData['gallery_descr'];
				$this->galleryLang = $queryData['g_lang'];
				$this->galleryName = $queryData['gallery_name'];
				return true;
			}
		}
		return false;
	}
}
?>