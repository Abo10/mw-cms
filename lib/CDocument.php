<?php

define("DOC_LIB", __DIR__.'/../uploads/documents/');
class CDocument extends CFile{
	protected $allow_types = array('doc', 'docx', 'pdf', 'pdfx', 'xls', 'xlsx');	
	function __construct($id=null){
		if($id){
			Cmwdb::$db->where("id", $id);
			$res = Cmwdb::$db->getOne("std_docs");
			if(!empty($res)){
				$this->id = $id;
				$this->type = $res['type'];
				$this->url = $res['url'];
			}
		}
	}
	
	function CreateDocument($file){
		$this->type = "document";
		if(!isset($_FILES[$file]) || (strlen($_FILES[$file]['name'])<2))return false;
		$temp = explode('.', strtolower($_FILES[$file]['name']));
		$type = end($temp);
		$this->type = $type;
		if(!in_array($type, $this->allow_types))return false;
		$this->url = null;
		$query_data['type'] = $this->type;
		if(Cmwdb::$db->insert("std_docs", $query_data)){
			$this->id = Cmwdb::$db->getInsertId();
			$this->url = $this->id.'.'.$type;
			if(move_uploaded_file($_FILES[$file]['tmp_name'], DOC_LIB.$this->url)){
				Cmwdb::$db->where('id', $this->id);
				Cmwdb::$db->update("std_docs", array('url'=>$this->url));
				return $this->id;
			}
			Cmwdb::$db->DeleteInBase();
		}
		return false;
	}
	
	protected function DeleteInBase(){
		if($this->id){
			Cmwdb::$db->where('id', $this->id);
			Cmwdb::$db->delete("std_docs");
		}
		return true;
	}
	
	function GetURL(){
		return URL_BASE.'uploads/documents/'.$this->url;
	}
	
	function GetName(){return $this->url;}
	

}
?>