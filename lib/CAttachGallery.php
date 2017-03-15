<?php

class CAttachGallery{
	protected $AttacheList = null;
	
	//Temp members to store any wokdatas
	protected $Page = 1;
	protected $ElemCount = 18;
	protected $CurrentType = "all";
	protected $SearchWord = "";
	
	function __construct($gall_type="all"){
		Cmwdb::$db->orderBy("id_attachment");
		switch ($gall_type){
			case "all":{
				$res = Cmwdb::$db->get("std_attachment", null, 'id_attachment');
				foreach ($res as $val){
					$this->AttacheList[$val['id_attachment']] = new CAttach($val['id_attachment']);
				}
				break;
			}
			case "images":{
				Cmwdb::$db->where('attachment_type', array("jpg", "jpeg", "png", "gif"), "in");
				$res = Cmwdb::$db->get("std_attachment", null, "id_attachment");
				foreach ($res as $val){
					$this->AttacheList[$val['id_attachment']] = new CAttach($val['id_attachment']);
				}
				break;
			}
			case "documents":{
				Cmwdb::$db->where('attachment_type', array("doc", "docx", "xls", "xlsx", "pdf", "pdfx"), "in");
				$res = Cmwdb::$db->get("std_attachment", null, "id_attachment");
				foreach ($res as $val){
					$this->AttacheList[$val['id_attachment']] = new CAttach($val['id_attachment']);
				}
				break;
			}
			default:{
				break;
			}
		}
		
	}
	
	function GetElementArray($id){
		if(isset($this->AttacheList[$id]))
			return $this->AttacheList[$id]->GetAsArray();
		return false;
	}
	
	function GetImages(){
		$ret = array();
		if($this->AttacheList){
			foreach ($this->AttacheList as $obj){
	 			$tmp = $obj->GetAsArray();
	 			if($tmp['type']==="image")
	 				$ret[$tmp['id']] = $tmp;
			}
		}
		return $ret;
	}
	
	function GetDocuments(){
		$ret = array();
		if($this->AttacheList){
			foreach ($this->AttacheList as $obj){
				$tmp = $obj->GetAsArray();
				if($tmp['type']==="document")
					$ret[$tmp['id']] = $tmp;
			}
		}
		return $ret;		
	}
	
	function GetGallery($gal_type="all", $sort="reverse"){
		$ret = array();
		switch ($gal_type){
			case 'images':{
				if($this->AttacheList){
					foreach ($this->AttacheList as $obj){
						$tmp = $obj->GetAsArray();
						if($tmp['type']==="image")
							$ret[$tmp['id']] = $tmp;
					}
				}
				break;
			}
			case 'documents':{
				if($this->AttacheList){
					foreach ($this->AttacheList as $obj){
						$tmp = $obj->GetAsArray();
						if($tmp['type']==="document")
							$ret[$tmp['id']] = $tmp;
					}
				}
				break;
			}
			default:{
				if($this->AttacheList){
					foreach ($this->AttacheList as $obj){
						$tmp = $obj->GetAsArray();
						$ret[$tmp['id']] = $tmp;
					}
				}
				break;
			}
		}
		if($sort==="reverse")
			return array_reverse($ret);
		return $ret;
	}
	
	function UpdateDetails($id, $argv){
		if(isset($this->AttacheList[$id]))
			return $this->AttacheList[$id]->UpdateDetails($argv);
		return false;
	}
	
	function DeleteAtachment($id){
		if(isset($this->AttacheList[$id]) && $this->AttacheList[$id]->DeleteThis()){
			unset($this->AttacheList[$id]);
			return true;
		}
		return false;
	}
	
	function GetPaged($page=1, $elem_count=20, $elem_type="all"){
		if($this->AttacheList){
			switch ($elem_type){
				case 'all':{
					$ret = array();
					$start_position = ($page-1)*$elem_count;
					$current_position = 0;
					$current_count = 0;
					reset($this->AttacheList);
					while ($elem = current($this->AttacheList)){
							if($current_position<$start_position){
								$current_position++;
								next($this->AttacheList);
								continue;
							}
							if($current_count<$elem_count){
								$ret[key($this->AttacheList)] = $elem->GetAsArray();
								$current_count++;
							}

						next($this->AttacheList);
					}
					return $ret;
				}
				default:{
					$ret = array();
					$start_position = ($page-1)*$elem_count;
					$current_position = 0;
					$current_count = 0;
					reset($this->AttacheList);
					while ($elem = current($this->AttacheList)){
						if($elem->GetType()===$elem_type){
							if($current_position<$start_position){
								$current_position++;
								next($this->AttacheList);
								continue;
							}
							if($current_count<$elem_count){
								$ret[key($this->AttacheList)] = $elem->GetAsArray();
								$current_count++;
							}
						}
						next($this->AttacheList);
					}
					return $ret;
				}
			}
		}
		return false;
	}
	
	function GetPageCount($elem_count=20,$elem_type="all"){
		if($this->AttacheList){
			switch ($elem_type){
				case 'all':{
					if(count($this->AttacheList)%$elem_count >0)return ceil(count($this->AttacheList)/$elem_count);
					return count($this->AttacheList)/$elem_count;
				}
				default:{
					reset($this->AttacheList);
					$page_count = 0;
					$current_count = 0;
					while ($elem = current($this->AttacheList)){
						if($elem->GetType()===$elem_type){
							$current_count++;
							if($current_count>=$elem_count){
								$current_count = 0;
								$page_count++;
							}
						}
						next($this->AttacheList);
					}
					if($current_count)$page_count++;
					return $page_count;
					break;
				}
			}
		}
		return 0;	
	}
	
	//Any fuynction special for ABO! jaaan
	function SetPage($page){$this->Page = $page;}
	function SetElemCount($count){$this->ElemCount=$count;}
	function SetType($type){$this->CurrentType = $type;}
	function SetSearchWord($word){
		if($word=="")return;
		$this->SearchWord = $word;
		if(is_array($this->AttacheList)){
			foreach ($this->AttacheList as $key=>$value){
				if(!$value->SearchInTitle($this->SearchWord)){
					unset($this->AttacheList[$key]);
				}
			}
		}
	}

	function GetPageRacional(){
		$end = ($this->Page-1) * $this->ElemCount;
		$arr = array();
		if(is_array($this->AttacheList)){
			$ret = array_slice($this->AttacheList,$end,$this->ElemCount);
			foreach ($ret as $key=>$value){
				$arr[$key] = $value->GetAsArray();
			}
			return $arr;
		}
		return [];
	}
	
	function GetPageCountRacional(){
		return $this->GetPageCount($this->ElemCount);
	}
}
?>