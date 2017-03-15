<?php
require_once 'stdlib.php';
class CDocumentDet extends CDocument{
	protected $d_id = null;
	protected $title = "";
	protected $descr = "";
	protected $lang = "";

	
	function __construct($id=null){
		if($id){
// 			$query = "SELECT std_img_det.*,std_images.id as iid, std_images.url, std_images.type 
// 						FROM std_images JOIN std_img_det ON std_images.id = std_img_det.aid 
// 						where std_images.id='$id'";
			Cmwdb::$db->where('std_docs.id',$id);
			Cmwdb::$db->join('std_doc_det','std_docs.id = std_doc_det.aid','left');
			$res = Cmwdb::$db->getOne('std_docs',array('std_doc_det.*','std_docs.id iid','std_docs.url','std_docs.type'));
			$this->id = $id;
			$this->d_id = $res['id'];
			$this->type = $res['type'];
			$this->title = $res['title'];
			$this->url = $res['url'];
			$this->descr = $res['descr'];
			$this->lang = $res['lang'];
		}
	}
	
	function CreateDocument($file, $argv=null){
		if(parent::CreateDocument($file)){
			if(isset($argv['descr']))$this->descr = $argv['descr'];
			else $this->descr = null;
			$this->descr = CSecurity::FilterString($this->descr);
			if(isset($argv['lang']))$this->lang = $argv['lang'];
			else{
				$lang = CLanguage::getInstance();
				$this->lang = $lang->getDefault();
			}
			if(isset($argv['title']))$this->title = $argv['title'];
			else $this->title = $_FILES[$file]['name'];
			$this->title = CSecurity::FilterString($this->title);
			$queryData['title'] = $this->title;
			$queryData['descr'] = $this->descr;
			$queryData['lang'] = $this->lang;
			$queryData['aid'] = $this->id;
			if(Cmwdb::$db->insert("std_doc_det", $queryData)){
				$this->d_id = Cmwdb::$db->getInsertId();
				return $this->id;
			}
		}
		return false;
	}
	
	function GetURL(){
		return URL_BASE.'uploads/documents/'.$this->url;
	}
	
	function GetURL_Local(){
		return DOC_LIB.$this->url;
	}
	function GetDescription(){return $this->descr;}
	function GetLang(){return $this->lang;}
	function GetDID(){
		return $this->d_id;
	}
	
	function UpdateDetails($argv=null){
		if(isset($argv['descr']))$this->descr = CSecurity::FilterString($argv['descr']);
		if(isset($argv['lang']))$this->lang = $argv['lang'];
		if(isset($argv['title']))$this->title = CSecurity::FilterString($argv['title']);
		$queryData['title'] = $this->title;
		$queryData['descr'] = $this->descr;
		$queryData['lang'] = $this->lang;
		Cmwdb::$db->where('id', $this->d_id);
		return Cmwdb::$db->update("std_doc_det", $queryData);
	}
	
	function GetTitle(){return $this->title;}
	function DeleteThis(){
		if(parent::DeleteInBase()){
			unlink($this->GetURL_Local());
			return true;
		}
		return false;
	}
}
?>