<?php
require_once 'stdlib.php';

/**
 * @author Rafik Rushanian
 * @date 26/11/2015
 * Det. - By default all modules we store in [modules] area
 * 		  If need, we can store ather configuration units in ather areas, if we need
 */
class CMod {
	private static $_instance = null;
	static protected $InternallURL = null;
	static protected $Libs = array();
	
	
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
		self::$InternallURL = LIB_BASE.'/mod.ini';
		if(file_exists(self::$InternallURL)){
			self::$Libs = parse_ini_file(self::$InternallURL, true);
//			var_dump(self::$Libs);
			return true;
		}
		return false;
	}
	
	static function LoadModule($mod_name){
		if(isset(self::$Libs['modules'][$mod_name])){
			require_once(LIB_BASE.'/'.self::$Libs['modules'][$mod_name]);
			return true;
		}
		return false;
	}

	//Do not tuch this
	static protected function WriteContent(){
		$file = fopen(self::$InternallURL, "w+");
		if($file){
			reset(self::$Libs);
			while($unit = current(self::$Libs)){
				$string = '['.key(self::$Libs)."]\r\n";
				fwrite($file, $string);
				while ($sub_unit = current($unit)){
					$string = key($unit).' = '.$sub_unit."\r\n";
					fwrite($file, $string);
					next($unit);
				}
				next(self::$Libs);
			}
			fclose($file);
			return true;
		}
		return false;
	}
	
	static function AddModule($mod_name, $mod_file){
		if(isset(self::$Libs['modules'][$mod_name]))
			return false;
		self::$Libs['modules'][$mod_name] = $mod_file;
		return self::WriteContent();
	}
}