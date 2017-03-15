<?php

class CDocumentGallery{
	protected $documents = null;
	protected $CurrentElement=0;//This member store id of current element
	protected $documentsCount = 0;
	protected $currentIndex = -1;
	function __construct($ids=null, $width_details=true){
		if(is_array($ids)){
			$this->documents = array();
			while ($unit = current($ids)){
				if($width_details){
					$this->documents[$unit] = new CDocumentDet($unit);
				}
				else $this->images[$unit] = new CDocument($unit);
				next($ids);
			}
			$this->documentsCount = count($ids);
		}
		if(is_null($ids)){
			$res = Cmwdb::$db->get("std_docs",null,"id");
			$this->documentsCount = count($res);
			$this->documents = array();
			while ($unit = current($res)){
				if($width_details){
					$this->documents[$unit['id']] = new CDocumentDet($unit['id']);
				}
				else $this->documents[$unit['id']] = new CDocuments($unit['id']);
				next($res);
			}			
		}
//		var_dump($this->images);
	}
	
	function GetDocument($id){
		if(isset($this->documents[$id]))
			return $this->documents[$id];
		return false;
	}
	
	function GetDocument_Array($id){
		if(isset($this->documents[$id])){
			if(get_class($this->documents[$id])==="CDocumentDet"){
 				$tmp['id'] = $id;
 				$tmp['did'] = $this->documents[$id]->GetDID();
 				$tmp['descr'] = $this->documents[$id]->GetDescription();
 				$tmp['url'] = $this->documents[$id]->GetURL();
 				$tmp['lang'] = $this->documents[$id]->GetLang();
 				$tmp['type'] = $this->documents[$id]->GetType();
 				$tmp['name'] = $this->documents[$id]->GetName();
 				return $tmp;
			}
			if(get_class($this->documents[$id])==="CDocument"){
 				$tmp['id'] = $id;
 				$tmp['type'] = $this->documents[$id]->GetType();
 				$tmp['url'] = $this->documents[$id]->GetURL();
 				$tmp['name'] = $this->documents[$id]->GetName();
 				return $tmp;
			}
		}
		return false;
	}
	
	function Reset(){$this->documentsCount=0;}
	
	function NextElement(){
		$this->currentIndex = 0;
		if($this->CurrentElement==0){
			reset($this->documents);
			$res = current($this->documents);
			$this->CurrentElement = $res->GetID();
			$this->currentIndex = 0;
			return $this->GetDocument_Array($this->CurrentElement);
		}
		else{
			reset($this->documents);
			while ($unit = current($this->documents)){
				$this->currentIndex+=1;
//				if(!($this->currentIndex<$this->imageCount))break;
				if($unit->GetID()===$this->CurrentElement && $this->currentIndex<$this->documentsCount){
					next($this->documents);
					$sub_unit =  current($this->documents);
					$this->CurrentElement = $sub_unit->GetID();
					return $this->GetDocument_Array($this->CurrentElement);
				}
				next($this->documents);
			}
		}
		return false;
	}
	function GetAll(){return $this->documents;}
}
?>