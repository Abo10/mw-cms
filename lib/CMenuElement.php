<?php

class CMenuElement{
	protected $tbl_name = "std_menu_elements";
	protected $data = array();
	protected $int_lang;
	/*
	 * Argument can have 3 type value
	 * 	1. null - will created empty object
	 * 	2. int - its id of menu element in database, object will intialise from db
	 * 	3. array - all fields given in array, so object will initialise from array
	 */
	function __construct($argv = null, $lang=null){
// 		if(!$lang){
// 			$lang = CLanguage::getInstance();
// 			$this->int_lang = $lang->getDefault();
// 		}
// 		else $this->int_lang = $lang;
		$this->data = $argv;
	}
	
	function GetMenu_Array($lang=null){
		return $this->data;		
	}
}
?>