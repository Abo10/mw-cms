<?php
class CIcons{
	private static $_instance = null;
	protected static $KnownTypes = array("jpg","jpeg","gif","png","doc","docx","xls","xlsx","pdf","pdfx");
	private function __construct() {}
	protected function __clone() {}
	
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	static function GetIcon($ext){
		if(in_array($ext, self::$KnownTypes)){
			return ASSETS_BASE.'res/att_icons/'.$ext.'.png';
		}
		return ASSETS_BASE.'res/att_icons/unknown.png';
	}
}
?>