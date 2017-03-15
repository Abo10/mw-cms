<?php

class Cmwdb {
	private static $_instance = null;
	static public $db = null;
	
	private function __construct() {}
	protected function __clone() {}
	
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	//Use to initialise object, by defautlt it's will initialise in mod.php
	static function Initialise(){
		$values = CConfig::GetBlock();
		if($values!==CONFIG_NO_ENTRY){
			self::$db = new MysqliDb(
					$values['DB_HOST'],
					$values['DB_USER'],
					$values['DB_PASS'],
					$values['DB_NAME']
			);
		}

	}
	

}