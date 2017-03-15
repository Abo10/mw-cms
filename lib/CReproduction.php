<?php

class CReproduction{
	/*
	 * For now we have only 3 variations for our framework and cms
	 * 	1. basic - Only basic cms with minimum complatation of library
	 * 	2. catalog - Cms with all libraries, that need to create catalog site
	 *  3. shop - Full cms and framework for creation fully qualified and functionalite
	 *  			site with products, payments methods and shipping modules
	 *  
	 *  TODO: this is a first iteration of this functionality, in next iteration we must
	 *  realise methods to dinamicaly create variations of framework and read its 
	 *  variations somewhere else, like configuration storage
	 */
	static protected $available_types = [
		'basic',
		'catalog',
		'shop',	
	];
	
	static public $type = "basic";
	
	static protected $catalogs = [];
	
	static protected function InitialReproduction($reproduction_type){
		if(!in_array($reproduction_type, self::$available_types))
			throw new Exception('Error: Unknown reproduction type');
		self::$catalogs = self::$available_uris[$reproduction_type];
		return true;
	}
	
	static protected $available_uris = [
		'basic'=>[
			'exports/basic/'=>SYSTEM_BASE_URI.'exports/basic/configs',
			'0'=>SYSTEM_BASE_URI.'controller',
			'1'=>SYSTEM_BASE_URI.'console',
			'2'=>SYSTEM_BASE_URI.'flib',
			'3'=>SYSTEM_BASE_URI.'lib',
			'4'=>SYSTEM_BASE_URI.'install',
			'5'=>SYSTEM_BASE_URI.'modules',
			'6'=>SYSTEM_BASE_URI.'mw-admin',	
			'exports/'=>SYSTEM_BASE_URI.'exports/uploads',
			'7'=>SYSTEM_BASE_URI.'view/default_theme_basic',
			'8'=>SYSTEM_BASE_URI.'web/default_theme_basic',
			'9'=>SYSTEM_BASE_URI.'.htaccess',
			'10'=>SYSTEM_BASE_URI.'action.php',
			'11'=>SYSTEM_BASE_URI.'functions.php',
			'12'=>SYSTEM_BASE_URI.'index.php',
			'13'=>SYSTEM_BASE_URI.'mod.php',
			'14'=>SYSTEM_BASE_URI.'console.php',
		],
		'catalog'=>[
			'exports/catalog/'=>SYSTEM_BASE_URI.'exports/catalog/configs',
			'0'=>SYSTEM_BASE_URI.'controller',
			'1'=>SYSTEM_BASE_URI.'console',
			'2'=>SYSTEM_BASE_URI.'flib',
			'3'=>SYSTEM_BASE_URI.'lib',
			'4'=>SYSTEM_BASE_URI.'install',
			'5'=>SYSTEM_BASE_URI.'modules',
			'6'=>SYSTEM_BASE_URI.'mw-admin',
			'exports/'=>SYSTEM_BASE_URI.'exports/uploads',
			'7'=>SYSTEM_BASE_URI.'view/default_theme_catalog',
			'8'=>SYSTEM_BASE_URI.'web/default_theme_catalog',
			'9'=>SYSTEM_BASE_URI.'.htaccess',
			'10'=>SYSTEM_BASE_URI.'action.php',
			'11'=>SYSTEM_BASE_URI.'functions.php',
			'12'=>SYSTEM_BASE_URI.'index.php',
			'13'=>SYSTEM_BASE_URI.'mod.php',
			'14'=>SYSTEM_BASE_URI.'console.php',
		],
		'shop'=>[
			'exports/shop/'=>SYSTEM_BASE_URI.'exports/shop/configs',
			'0'=>SYSTEM_BASE_URI.'controller',
			'1'=>SYSTEM_BASE_URI.'console',
			'2'=>SYSTEM_BASE_URI.'flib',
			'3'=>SYSTEM_BASE_URI.'lib',
			'4'=>SYSTEM_BASE_URI.'install',
			'5'=>SYSTEM_BASE_URI.'modules',
			'6'=>SYSTEM_BASE_URI.'mw-admin',
			'exports/'=>SYSTEM_BASE_URI.'exports/uploads',
			'7'=>SYSTEM_BASE_URI.'view/default_theme_shop',
			'8'=>SYSTEM_BASE_URI.'web/default_theme_shop',
			'9'=>SYSTEM_BASE_URI.'.htaccess',
			'10'=>SYSTEM_BASE_URI.'action.php',
			'11'=>SYSTEM_BASE_URI.'functions.php',
			'12'=>SYSTEM_BASE_URI.'index.php',
			'13'=>SYSTEM_BASE_URI.'mod.php',
			'14'=>SYSTEM_BASE_URI.'console.php',
		],	
	];
	
	static function StartReproduction($reproduction_name, $reproduction_type){
		try {
			self::InitialReproduction($reproduction_type);
			$start_status = CArchive::CreateArchive($reproduction_name);
			if(!$start_status['status'])return $start_status;
			$add_status = "";
			foreach (self::$catalogs as $index=>$url){
				if(is_numeric($index))
					$add_status = CArchive::AddToArchive($url);
				else $add_status = CArchive::AddToArchive($url, $index);
				if(!$add_status['status'])
					throw new Exception('Error: while adding path '.$url);
			}
			return [
				'status'=>1,
				'result'=>""	
			];
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
}