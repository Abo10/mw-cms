<?php

class CDictionaryUser extends CFileSupport{
	private static $_instance = null;
	static protected $InternallURL = './../configs/udic.ini';
	static protected $Dict = null;
	static protected $DectHierarchy = false;//Dictionary content single layer array or multyarray otherwise
	private function __construct() {}
	protected function __clone() {}

	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	static function Initialise($uri=null){
// 		echo static::$InternallURL;
		static::$DectHierarchy = true;
		static::$Dict = parse_ini_file(static::$InternallURL,true);
		return true;
	}

	static function GetKey($key, $lang=null){
		$slan = CLanguage::getInstance();
		if(!$lang)$lang = $slan->getCurrentUser();
		if(isset(self::$Dict[$key]))return self::$Dict[$key][$lang];
		return false;
	}
	
	static function GetCurrentURL(){
		return static::$InternallURL;
	}
	
	static function SetKey($shortkey, $argv){

		if(self::$Dict){
			
			if(is_array($argv)){
				if(!isset(self::$Dict[$shortkey])){
					self::$Dict[$shortkey] = $argv;
					$langs = CLanguage::getInstance();
					$lang = $langs->get_lang_keys_user();
					foreach ($lang as $value){
						if(!isset(self::$Dict[$shortkey][$value]))self::$Dict[$shortkey][$value]="";
					}
					return static::StoreContent();
				}
			}
		}
		return false;
	}
	
	static function EditKey($shortkey, $argv){
		if(self::$Dict){
			if(is_array($argv)){
				$lang = CLanguage::getInstance();
				$lang = $lang->get_lang_keys();
				
				self::$Dict[$shortkey] = $argv;
				foreach ($lang as $value){
					if(!isset(self::$Dict[$shortkey][$value]))self::$Dict[$shortkey][$value]="";
				}
// 				var_dump(self::$Dict[$shortkey]);
				return self::StoreContent();
			}
		}
		return false;
	}
	
	static function GetKeyBlock($shortkey){
		if(isset(self::$Dict[$shortkey]))return self::$Dict[$shortkey];
		return false;
	}
	
	static function GetKeyBlock_JSON($shortkey){
		if($row=self::GetKeyBlock($shortkey))return json_encode($row);
		return false;
	}
	
	static function GetAllDict(){
		if(self::$Dict){
			return self::$Dict;
		}
		return false;
	}
	
	static protected function StoreContent()
	{
		if (file_exists(static::$InternallURL)) {
			$handle = fopen(static::$InternallURL, "w+");
			reset(static::$Dict);
			if (static::$DectHierarchy) {
				foreach (static::$Dict as $key => $unit) {
					$string = '[' . $key . ']' . "\r\n";
					fwrite($handle, $string);
					foreach ($unit as $leng_key => $sub_unit) {
						$string = $leng_key . ' = "' . $sub_unit . '"' . "\r\n";
						fwrite($handle, $string);
	
					}
					fwrite($handle, "\r\n");
	
				}
			} else {
				while ($unit = current(static::$Dict)) {
					$string = key(static::$Dict) . ' = "' . $unit . '"' . "\r\n";
					fwrite($handle, $string);
					next(static::$Dict);
				}
			}
			fclose($handle);
			return true;
		}
		return false;
	}
	

	
}


?>
