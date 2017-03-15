<?php

class CAttach{
	protected $id = null;
	protected $type = "";
	protected $d_id = null;
	protected $descr = "";
	protected $lang = "";
	protected $object = null;
	protected $attach_types = null;
	protected $conent_type = "";
	
	function __construct($id=null){
		$this->attach_types['image'] = array("jpg", "jpeg", "gif", "png");
		$this->attach_types['document'] = array("docx", "doc", "xls", "xlsx", "pdf", "pdfx");
		if($id){
			Cmwdb::$db->where("id_attachment", $id);
			$res = Cmwdb::$db->getOne("std_attachment");
			if(!empty($res)){
				$this->id = $res['id_attachment'];
				$this->type = $res['attachment_type'];
				$this->d_id = $res['d_id'];
				$this->descr = $res['attachment_descr'];
				$this->lang = $res['attachment_lang'];
				switch ($this->GetAttacheType($this->type)){
					case 'image':{
						$this->conent_type = "image";
						$this->object = new CImageDet($this->d_id);
						break;
					}
					case 'document':{
						$this->conent_type = "document";
						$this->object = new CDocumentDet($this->d_id);
						break;
					}
					default:{
						break;
					}
				}
			}
		}
	}
	
	function ReInit($id){
		$this->attach_types['image'] = array("jpg", "jpeg", "gif", "png");
		$this->attach_types['document'] = array("docx", "doc", "xls", "xlsx", "pdf", "pdfx");

			Cmwdb::$db->where("id_attachment", $id);
			$res = Cmwdb::$db->getOne("std_attachment");
			if(!empty($res)){
				$this->id = $res['id_attachment'];
				$this->type = $res['attachment_type'];
				$this->d_id = $res['d_id'];
				$this->descr = $res['attachment_descr'];
				$this->lang = $res['attachment_lang'];
				switch ($this->GetAttacheType($this->type)){
					case 'image':{
						$this->conent_type = "image";
						$this->object = new CImageDet($this->d_id);
						return true;
						break;
					}
					case 'document':{
						$this->conent_type = "document";
						$this->object = new CDocumentDet($this->d_id);
						return true;
						break;
					}
					default:{
						break;
					}
				}
			}
			return false;
	
	}
	
	private function GetAttacheType($ext){
		if(in_array($ext, $this->attach_types['image']))return "image";
		if(in_array($ext, $this->attach_types['document']))return "document";
		return "unknown";
	}
	
	function GetURL($size="thumb"){
		if($this->id===0)return null;
		if($this->id)
			return $this->object->GetURL($size);
		return null;
	}
	
	function CreateAttachment($file, $argv=null){
		$temp = explode('.', strtolower($_FILES[$file]['name']));
		$type = end($temp);
		switch ($this->GetAttacheType($type)){
			case 'image':{
				$tmp_obj = new CImageDet();
				$id = $tmp_obj->CreateImage($file, $argv);
				if($id){
					if(isset($argv['attachment_descr']))$this->descr = $argv['attachment_descr'];
					else $this->descr = "";
					$this->descr = CSecurity::FilterString($this->descr);
					$this->lang = $tmp_obj->GetLang();
					$this->d_id = $id;
					$queryData['attachment_type'] = $tmp_obj->GetType();
					$queryData['d_id'] = $this->d_id;
					$queryData['attachment_descr'] = $this->descr;
					$queryData['attachment_lang'] = $this->lang;
					if(Cmwdb::$db->insert("std_attachment", $queryData)){
						$this->id = Cmwdb::$db->getInsertId();
						$this->object = new CImageDet($this->d_id);
						$this->type = $queryData['attachment_type'];
						$this->d_id = $queryData['d_id'];
						$this->descr = $queryData['attachment_descr'];
						$this->lang = $queryData['attachment_lang'];
						return $this->id;
					}
				}
				else return false;
				break;
			}
			case 'document':{
				$tmp_obj = new CDocumentDet();
				$id = $tmp_obj->CreateDocument($file, $argv);
				if($id){
					if(isset($argv['attachment_descr']))$this->descr = $argv['attachment_descr'];
					else $this->descr = "";
					$this->descr = CSecurity::FilterString($this->descr);
					$this->lang = $tmp_obj->GetLang();
					$this->d_id = $id;
					$queryData['attachment_type'] = $tmp_obj->GetType();
					$queryData['d_id'] = $this->d_id;
					$queryData['attachment_descr'] = $this->descr;
					$queryData['attachment_lang'] = $this->lang;
					if(Cmwdb::$db->insert("std_attachment", $queryData)){
						$this->id = Cmwdb::$db->getInsertId();
						$this->object = new CDocumentDet($this->d_id);
						return $this->id;
					}
				}	
				return false;
				break;
			}
			default:{
				break;
			}
		}
		return false;
	}
	
	function GetAsArray(){
		$ret['id'] = $this->id;
		$ret['type'] = $this->GetAttacheType($this->type);
		$ret['ext'] = $this->type;
		$ret['attachment_lang'] = $this->lang;
		$ret['attachment_descr'] = $this->descr;
		$ret['title'] = $this->object->GetTitle();
 		$ret['descr'] = $this->object->GetDescription();
		$ret['lang'] = $this->object->GetLang();
		if($ret['type']==="image"){
			$ret['url_original'] = $this->object->GetURL();
			$ret['url_medium'] = $this->object->GetURL("medium");
			$ret['url_thumb'] = $this->object->GetURL("thumb");
			$ret['title'] = $this->object->GetTitle();
		}
		if($ret['type']==="document"){
			$ret['url'] = $this->object->GetURL();
			$ret['name'] = $this->object->GetName();
		}
		return $ret;
	
	}
	
	function GetArray_JSON(){
		$ret = $this->GetAsArray();
		$ret = json_encode($ret);
		return $ret; 
	}
	
	function UpdataDetails($argv=null){
		return $this->object->UpdateDetails($argv);
	}
	
	function DeleteThis(){
		if($this->object && $this->object->DeleteThis()){
			Cmwdb::$db->where('id_attachment', $this->id);
			if(Cmwdb::$db->delete("std_attachment")){
				return true;
			}
		}
		return false;
	}
	
	function GetType(){return $this->conent_type;}
	
	function SearchInTitle($word){
		if($this->id){
			$source = strtolower($this->object->GetTitle());
			$word = strtolower($word);
			return stristr($source, $word);
		}
		return false;
	}
	
	function GetExtention(){
		return $this->type;
	}
}
?>