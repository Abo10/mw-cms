<?php

define("CONFIG_NO_ENTRY","CONFNOKEY");
class CConfig{
	private static $_instance = null;
	static protected $InternallURL = null;
	static protected $configs = null;

	
	private function __construct() {}
	protected function __clone() {}
	
	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
			self::Initialise();
		}
		return self::$_instance;
	}
	
	//Use to initialise object
	static function Initialise(){
		self::$InternallURL = CONFIG_DIR.'config.php';
		self::$configs = require_once(self::$InternallURL);

	}
	
	static protected function StoreContent(){
		$contents = var_export(self::$configs, true);
		return file_put_contents(self::$InternallURL, "<?php\n return {$contents};\n ?>");
	}
	
	static function AddBlock($values, $in_area='config', $from_class=null){
		if(!$from_class){
			$ret = debug_backtrace();
			$from_class = $ret[1]['class'];
		}
		if(!$in_area)$in_area = 'config';
		self::$configs[$in_area][$from_class] = $values;
		return self::StoreContent();
	}
	
	static function GetBlock($in_area='config', $from_class=null){
		if(!$from_class){
			$ret = debug_backtrace();
			$from_class = $ret[1]['class'];
		}
		if(!$in_area)$in_area = 'config';
		if(isset(self::$configs[$in_area][$from_class]))
			return self::$configs[$in_area][$from_class];
		
		return CONFIG_NO_ENTRY;
	}
	
	static function IsExists($in_area='config', $from_class=null){
		if(!$from_class){
			$ret = debug_backtrace();
			$from_class = $ret[1]['class'];
		}
		if(!$in_area)$in_area = 'config';
		return isset(self::$configs[$in_area][$from_class]);		
	}
	
	static function GetKey($config_key, $in_area='config', $from_class=null, $sea_area=true){
		if(!$in_area)$in_area = 'config';
		if(!$from_class){
			if(!$sea_area){
				$ret = debug_backtrace();
				$from_class = $ret[1]['class'];
				if(isset(self::$configs[$in_area][$from_class]))
					return self::FilterKeys(self::$configs[$in_area][$from_class], $config_key);
			}
			else{
				if(isset(self::$configs[$in_area])){
					return self::FilterKeys(self::$configs[$in_area], $config_key);
				}
			}
				
		}
		else{
			return self::FilterKeys(self::$configs[$in_area][$from_class], $config_key);
		}
		return CONFIG_NO_ENTRY;
	}
	
	static protected function FilterKeys($arg, $needle){
		if(is_array($arg)){
			foreach ($arg as $index=>$value){
				if($index===$needle)return $value;
				if(is_array($value)){
					$res = self::FilterKeys($value, $needle);
					if($res!=CONFIG_NO_ENTRY)return $res;
						
				}
			}
		}
		return CONFIG_NO_ENTRY;
	}
	
	static function HasModule($mod_name){
		return isset(self::$configs['modules'][$mod_name]);
	}
	
	static function GetModuleConfig($mod_name){
		if(isset(self::$configs['modules'][$mod_name])){
			$ret = self::$configs['modules'][$mod_name];
			if(isset(self::$configs['predefines'][$mod_name]))
				$ret['predefines'] = self::$configs['predefines'][$mod_name];
			return $ret;
		}
		return CONFIG_NO_ENTRY;
	}
	
	static function StoreConfigs($args, $in_area='config', $from_class=null){
		if(!$from_class){
			$ret = debug_backtrace();
			$from_class = $ret[1]['class'];
		}
		if(!$in_area)$in_area = 'config';
		self::$configs[$in_area][$from_class] = $args;
		return self::StoreContent();
	}
	
	static function EditKey($value, $key, $in_area='config'){
		if(!$in_area)$in_area = 'config';
		self::$configs[$in_area][$key] = $value;
		return self::StoreContent();
	}
	
	static function GetTree($tree_name){
		if(isset(self::$configs[$tree_name]))
			return self::$configs[$tree_name];
		return CONFIG_NO_ENTRY;
		
	}
	
	static function AddUpdateArea($values, $from_class=null){
		if(!$from_class){
			$ret = debug_backtrace();
			$from_class = $ret[1]['class'];
		}
		self::$configs[$from_class] = $values;
		return self::StoreContent();		
	}
	
	static function GetConfig($key){
		if(isset(self::$configs['config'][$key]))
			return self::$configs['config'][$key];
		return CONFIG_NO_ENTRY;
	}
	
	static function SetConfig($key, $value){
		self::$configs['config'][$key] = $value;
		return self::StoreContent();
	}
}

?>