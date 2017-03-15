<?php

class CErrorHandling implements IErrorHandleable{
	private static $_instance = null;
	static protected $tbl_name = "er_h";
	
	private function __construct() {}
	protected function __clone() {}
	
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	static function Initialise(){

	}
	
	//if shortkey not defined, its be name of class, where caled handling function
	static function RegisterHandle($shortkey=null){
		$ret = debug_backtrace();
		$class = $ret[1]['class'];
		$func = $ret[1]['function'];
		$file = $ret[1]['file'];
		$line = $ret[1]['line'];
		$args = $ret[1]['args'];
		if(!$shortkey)$shortkey = $class;
		$queryData['er_file'] = $file;
		$queryData['er_line'] = $line;
		$queryData['er_class'] = $class;
		$queryData['er_function'] = $func;
		$queryData['er_argv'] = json_encode($args);
		$queryData['er_date'] = date('Y/m/d H:i:s');
		$queryData['er_shortkey'] = $shortkey;
		if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
			return Cmwdb::$db->getInsertId();
		return false;
		
	}
}
?>