<?php
class CImageExt extends CImage{
	
	function __construct($work_dir){
		$this->work_dir = $work_dir;
	}
	
	function CreateImage($file, $width=null, $height=null, $top=0, $left=0){
		if(!isset($_FILES[$file]) || (strlen($_FILES[$file]['name'])<2))return false;
		$temp = explode('.', strtolower($_FILES[$file]['name']));
		$type = end($temp);
		if(!in_array($type, $this->allow_types))return false;
		$this->type = $type;
		$date_hash = md5(date("Y/m/d H:i:s"));
		$random = rand(1000,9999);
		$this->url = null;
		$this->url = md5($date_hash.'-'.$random).'.'.$type;
		if(move_uploaded_file($_FILES[$file]['tmp_name'], $this->work_dir.$this->url))
			return $this->url;
		return false;			
	}
	
	protected function CreateThumb($file, $F_width=225){

	}
	
	protected function CreateMedium($file, $width=450){

	}
	
	protected function DeleteInBase(){

	}
	/*
	 * The argument can be original|thumb|medium
	 * This function will return url of image
	 */
	protected function GetURL($res_type="original"){

	}
	
	protected function GetURL_Local($res_type="original"){

	}
	
	
}
?>