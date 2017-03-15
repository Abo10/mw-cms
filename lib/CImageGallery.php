<?php

class CImageGallery{
	protected $images = null;
	protected $CurrentElement=0;//This member store id of current element
	protected $imageCount = 0;
	protected $currentIndex = -1;
	function __construct($ids=null, $width_details=true){
		if(is_array($ids)){
			$this->images = array();
			while ($unit = current($ids)){
				if($width_details){
					$this->images[$unit] = new CImageDet($unit);
				}
				else $this->images[$unit] = new CImage($unit);
				next($ids);
			}
			$this->imageCount = count($ids);
		}
		if(is_null($ids)){
			$res = Cmwdb::$db->get("std_images",null,"id");
			$this->imageCount = count($res);
			$this->images = array();
			while ($unit = current($res)){
				if($width_details){
					$this->images[$unit['id']] = new CImageDet($unit['id']);
				}
				else $this->images[$unit['id']] = new CImage($unit['id']);
				next($res);
			}			
		}
//		var_dump($this->images);
	}
	
	function GetImage($id){
		if(isset($this->images[$id]))
			return $this->images[$id];
		return false;
	}
	
	function GetImage_Array($id){
		if(isset($this->images[$id])){
			if(get_class($this->images[$id])==="CImageDet"){
				$tmp['id'] = $id;
				$tmp['did'] = $this->images[$id]->GetDID();
				$tmp['title'] = $this->images[$id]->GetTitle();
				$tmp['descr'] = $this->images[$id]->GetDescription();
				$tmp['url_original'] = $this->images[$id]->GetURL("original");
				$tmp['url_medium'] = $this->images[$id]->GetURL("medium");
				$tmp['url_thumb'] = $this->images[$id]->GetURL("thumb");
				$tmp['lang'] = $this->images[$id]->GetLang();
				$tmp['type'] = $this->images[$id]->GetType();
				return $tmp;
			}
			if(get_class($this->images[$id])==="CImage"){
				$tmp['id'] = $id;
				$tmp['type'] = $this->images[$id]->GetType();
				$tmp['url_original'] = $this->images[$id]->GetURL("original");
				$tmp['url_medium'] = $this->images[$id]->GetURL("medium");
				$tmp['url_thumb'] = $this->images[$id]->GetURL("thumb");
				return $tmp;
			}
		}
		return false;
	}
	
	function Reset(){$this->imageCount=0;}
	
	function NextElement(){
		$this->currentIndex = 0;
		if($this->CurrentElement==0){
			reset($this->images);
			$res = current($this->images);
			$this->CurrentElement = $res->GetID();
			$this->currentIndex = 0;
			return $this->GetImage_Array($this->CurrentElement);
		}
		else{
			reset($this->images);
			while ($unit = current($this->images)){
				$this->currentIndex+=1;
//				if(!($this->currentIndex<$this->imageCount))break;
				if($unit->GetID()===$this->CurrentElement && $this->currentIndex<$this->imageCount){
					next($this->images);
					$sub_unit =  current($this->images);
					$this->CurrentElement = $sub_unit->GetID();
					return $this->GetImage_Array($this->CurrentElement);
				}
				next($this->images);
			}
		}
		return false;
	}
	function GetAll(){return $this->images;}
}
?>