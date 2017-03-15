<?php
class CAddressing{
	private static $_instance = null;
	protected static $DefDir = MW_CONFIGS.'addressing/';
	protected static $IndexRoot = MW_CONFIGS.'addressing/list.php';
	protected static $configs = array();
	protected static $ValidLibraries = array();
	protected static $LoadedLibraries = array();
	protected static $IsInitialised = false;
	private function __construct() {}
	protected function __clone() {}
	
	static public function getInstance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
			self::Initialise();
		}
		return self::$_instance;
	}
	
	static function Initialise(){
		if(self::$IsInitialised){
			return true;
		}
		if(self::LoadValids()){
			self::$IsInitialised = true;
			return true;
		}
		return false;
	}
	static function DoSomething(){
		var_dump(self::$LoadedLibraries);
		echo "Libs is <hr>";
		var_dump(self::$ValidLibraries);
	}
	
	protected static function LoadValids(){
		if(file_exists(self::$IndexRoot)){
			self::$configs = require_once(self::$IndexRoot);
			self::$ValidLibraries = self::$configs['libs'];
			return true;
		}
		return false;
			
	}
	
	static function LoadLibrary($lib_name){
		self::getInstance();
		if(self::$IsInitialised){
			if(isset(self::$ValidLibraries[$lib_name])){
				if(isset(self::$LoadedLibraries[$lib_name]))return true;
				try {
					if(!file_exists(self::$DefDir.self::$ValidLibraries[$lib_name]))
						throw new Exception("File of library does not exists");
					self::$LoadedLibraries[$lib_name]= require_once(self::$DefDir.self::$ValidLibraries[$lib_name]);
					return true;
// 					echo "Something after throw";
				}
				catch (Exception $error){
// 					echo $error->getMessage();
					return false;
				}
			}
		}
		return false;
	}
	
	static function GetAllCountries($lang=null){
		self::getInstance();
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		if(self::$IsInitialised){
			$ret = array();
			try {
				if(!isset(self::$configs['text']))throw new Exception("Missing section in configuration");
				foreach (self::$configs['text'] as $country=>$texts){
					if(isset($texts[$lang]))$ret[$country] = $texts[$lang];
					else{
						$ret[$country] = $texts[CLanguage::getInstance()->getDefaultUser()];
					}
				}
				return $ret;
			}catch(Exception $error){
				echo $error->getMessage();
				return [];
			}
		}
		return [];
	}
	
	static function GetStates($country, $lang=null){
		self::getInstance();
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		try {
			if(!isset(self::$configs['libs'][$country]))throw new Exception('Library was not exists', 1);
			if(self::LoadLibrary($country)){
				$temp = self::$LoadedLibraries[$country];
				//TODO: Here we take _datas as configuration to do any predefined actions if need
				//for now we did'nt neeed
				unset($temp['_datas']);
				$ret = array();
				foreach ($temp as $key=>$values){
					if(isset($values['_datas']['text'][$lang]))
						$ret[$key] = $values['_datas']['text'][$lang];
					else $ret[$key] = "No translated entry";
				}
				return $ret;
			}
		}catch (Exception $error){
			return [];
		}
		return [];
	}
	
	static function GetCities($args, $lang=null){
		self::getInstance();
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		try {
			if(isset($args['country']) && isset($args['state'])){
				if(!isset(self::$LoadedLibraries[$args['country']]))
					if(!self::LoadLibrary($args['country']))throw new Exception('Cant load library',1);
				
				if(isset(self::$LoadedLibraries[$args['country']][$args['state']])){
					$temp = self::$LoadedLibraries[$args['country']][$args['state']];
					//TODO: Now we remove _datas as unneeded
					unset($temp['_datas']);
					$ret = array();
					foreach ($temp as $key=>$values){
						if(isset($values['_datas']['text'][$lang]))
							$ret[$key] = $values['_datas']['text'][$lang];
						else $ret[$key] = "No translated entry";
					}
					return $ret;
				}
				else throw new Exception('State was not found');
				
				
				
			}
			else throw new Exception('Argument is miss', 'arg_miss');
		}catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function AddCountry($shortkey, $args){
		self::getInstance();
		try {
			if(isset(self::$configs['text'][$shortkey]))throw new Exception("exists", 1);
			if(!file_exists(self::$DefDir.$shortkey.'.php')){
				$data = "<?php \n return array();\n ?>";
				if(is_array($args)){
					self::$configs['text'][$shortkey] = $args;
					self::$configs['libs'][$shortkey] = $shortkey.'.php';
				}
				else throw new Exception('invalid_argument',3);
				if(file_put_contents(self::$DefDir.$shortkey.'.php', $data)){
					self::StoreConfigs();
					return true;
				}
				else{
					throw new Exception("fail",2);
					
				}
			}
			else{
				return true;
			}
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					print_r($error->getMessage());
					return;
					break;
				}
				case '2':{
					print_r($error->getMessage());
					return;
					break;
				}
				case '3':{
					print_r($error->getMessage());
					return;
					break;
				}
				default:{
					print_r('unknown');
					
					break;
				}
			}
		}
	}
	
	static protected function StoreConfigs(){
		self::getInstance();
		$config = var_export(self::$configs, true);
		if(file_put_contents(self::$IndexRoot, "<?php\n return {$config};\n ?>"))
			return true;
		return false;
	}
	
	static function AddState($country, $args){
		self::getInstance();
		try {
			if(self::LoadLibrary($country)){
				if(is_array($args)){
					$vals['_datas']['text'] = $args;
					$vals['_datas']['unit_type'] = 'city';
					$id = count(self::$LoadedLibraries[$country])+1;
					self::$LoadedLibraries[$country][$id] = $vals;
					if(self::StoreLibrary($country))
						return true;
					throw new Exception("Cant store library",3);
					
				}
				else throw new Exception("Invalid argument",2);
			}
			else throw new Exception("Library was not found", 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	protected static function StoreLibrary($lib_name){
		self::getInstance();
		if(isset(self::$LoadedLibraries[$lib_name])){
			$content = var_export(self::$LoadedLibraries[$lib_name], true);
			if(file_put_contents(self::$DefDir.$lib_name.'.php', "<?php\n return {$content};\n ?>"))
				return true;
			return false;
		}
		return true;
	}
	
	static function GetCommunities($args, $lang=null){
		self::getInstance();
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		try{
			if(isset($args['country']) && isset($args['state']) && isset($args['city'])){
				if(self::LoadLibrary($args['country'])){
					if(isset(self::$LoadedLibraries[$args['country']][$args['state']])){
						if(isset(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']])){
							if(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']]['_datas']['unit_type']=="community"){
								$temp = self::$LoadedLibraries[$args['country']][$args['state']][$args['city']];
								$ret = array();
								unset($temp['_datas']);
								foreach ($temp as $id=>$vals)
									$ret[$id] = $vals['_datas']['text'][$lang];
								return $ret;
							}
							return array();
						}
						throw new Exception("Missing index of city: ".$args['city']);
					}
					throw new Exception("Missing index in library for state: ".$args['state']);
				}
				throw new Exception("Error while loading library",2);
			}
			throw new Exception("Invalid list of arguments", 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function AddCity($args, $datas){
		self::getInstance();
		try {
			if(isset($args['country']) && isset($args['state'])){
				if(self::LoadLibrary($args['country'])){
					if(isset(self::$LoadedLibraries[$args['country']][$args['state']])){
						$id = count(self::$LoadedLibraries[$args['country']][$args['state']]);
						if(is_array($datas)){
							$temp['_datas']['text'] = $datas;
							$temp['_datas']['unit_type'] = "community";
							self::$LoadedLibraries[$args['country']][$args['state']][$id] = $temp;
							if(self::StoreLibrary($args['country']))return true;
							throw new Exception('Error to store library');
						}
						throw new Exception('Invalid list of datas for city');
					}
					throw new Exception('Missing state: '.$args['state']);
				}
				throw new Exception('Cant load library for country: '.$args['country']);
			}
			throw new Exception('Invalid list of arguments',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function AddStreet($args, $datas){
		self::getInstance();
		try {
			if(isset($args['country']) && isset($args['state']) && isset($args['city'])){
				if(self::LoadLibrary($args['country'])){
					//We must now check type of subject in city, its can be community or address
					if(!isset(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']]))
						throw new Exception('Missing city: '.$args['city']);
					$type = self::$LoadedLibraries[$args['country']][$args['state']][$args['city']]['_datas']['unit_type'];
					if($type=="community"){
						if(isset($args['community'])){
							if(isset(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']][$args['community']])){
								$id = count(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']][$args['community']]);
								$temp['_datas']['text'] = $datas;
								$temp['_datas']['unit_type'] = "address";
								self::$LoadedLibraries[$args['country']][$args['state']][$args['city']][$args['community']][$id] = $datas;
								if(self::StoreLibrary($args['country']))return true;
								throw new Exception('Error to store library');
							}
							throw new Exception('Missing id for community',2);
						}
						throw new Exception('Invalid list of arguments',1);
					}
					if($type=="address"){
						$id = count(self::$LoadedLibraries[$args['country']][$args['state']][$args['city']]);
						$temp['_datas']['text'] = $datas;
						self::$LoadedLibraries[$args['country']][$args['state']][$args['city']][$id] = $datas;
						if(self::StoreLibrary($args['country']))return true;
						throw new Exception('Error to store library');
					}
					throw new Exception('Unknown subtype for this city',5);
				}
				throw new Exception('Cant load library for country: '.$args['country']);
			}
			throw new Exception('Invalid list of arguments',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		
	}
	
}

?>