<?php
class CAddressing{
	private static $_instance = null;
	private function __construct() {}
	protected function __clone() {}
	static protected $tbl_name = "module_addressing";
	static public function getInstance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	static function Initialise(){
		self::$tbl_name = "module_addressing";
	}
	
	static function DoSomething(){
// 		var_dump(self::$LoadedLibraries);
// 		echo "Libs is <hr>";
// 		var_dump(self::$ValidLibraries);
	}
	

	static function GetAllCountries($lang=null){
		self::getInstance();
		if(!$lang)$lang = CLanguage::getInstance()->getCurrentUser();
		$def_lang = CLanguage::getDefaultUser();
		try {
			Cmwdb::$db->where('addr_type', 'country');
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
			//	if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text'][$lang]))
					$ret[$vals['addr_id']]['text'] = $tmp['text'][$lang];
				else $ret[$vals['addr_id']]['text'] = "Untranslated value";
			}
			return $ret;
			
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return "Try";
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
				
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function GetCountriesAllLangs(){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			Cmwdb::$db->where('addr_type', 'country');
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
// 				if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text']))
					$ret[$vals['addr_id']]['text'] = $tmp['text'];
				else {
					foreach ($langs as $lang)
						$ret[$vals['addr_id']]['text'][$lang] = "Untranslated value";
				}
				$ret[$vals['addr_id']]['addr_order'] = $vals['addr_order'];
				$ret[$vals['addr_id']]['addr_tax'] = $vals['addr_tax'];
				$ret[$vals['addr_id']]['addr_zip'] = $vals['addr_zip'];
				$ret[$vals['addr_id']]['addr_time'] = $vals['addr_time'];
				$ret[$vals['addr_id']]['addr_tel_code'] = $vals['addr_tel_code'];
				$ret[$vals['addr_id']]['addr_price'] = $vals['addr_price'];
				$ret[$vals['addr_id']]['addr_currency'] = $vals['addr_currency'];
			}
			return $ret;
				
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
	
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function GetStates($country, $lang=null){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		try {
			Cmwdb::$db->where('addr_type', 'state');
			Cmwdb::$db->where('addr_parent', $country);
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
				if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text'][$lang]))
					$ret[$vals['addr_id']] = $tmp['text'][$lang];
				else $ret[$vals['addr_id']] = "Untranslated value";
			}
			return $ret;
			
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
				
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function GetCommunities($city, $lang=null){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		try {
			Cmwdb::$db->where('addr_type', 'community');
			Cmwdb::$db->where('addr_parent', $city);
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
				if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text'][$lang]))
					$ret[$vals['addr_id']] = $tmp['text'][$lang];
					else $ret[$vals['addr_id']] = "Untranslated value";
			}
			return $ret;
				
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
	
				default:{
					return [];
				}
			}
		}
		return [];
	}
	static function GetStatesAllLangs($country){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		try {
			Cmwdb::$db->where('addr_type', 'state');
			Cmwdb::$db->where('addr_parent', $country);
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of states',1);
			$ret = array();
			$langs = CLanguage::getInstance()->get_lang_keys_user();
			foreach ($res as $vals){
				$ret[$vals['addr_id']] = $vals;
				$ret[$vals['addr_id']]['addr_datas'] = json_decode($vals['addr_datas'], true);
				$ret[$vals['addr_id']]['text'] = $ret[$vals['addr_id']]['addr_datas']['text'];
				foreach ($langs as $lang){
					if(!isset($ret[$vals['addr_id']]['text'][$lang]))
						$ret[$vals['addr_id']]['text'][$lang] = "Untranslated value";
				}
			}
			return $ret;
				
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
	
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function GetStateAllLangs($id){
		Cmwdb::$db->where('addr_type', 'state');
		Cmwdb::$db->where('addr_id', $id);
		$res = Cmwdb::$db->getOne(self::$tbl_name);
		// 		var_dump($res);die;
		if($res){
			$res['addr_datas'] = json_decode($res['addr_datas'], true);
			$res['text'] = $res['addr_datas']['text'];
			$langs = CLanguage::get_lang_keys_user();
			foreach ($langs as $lang){
				if(!isset($res['text'][$lang]))
					$res['text'][$lang] = "Untranslated value";
			}
			return $res;
		}
		return false;
	}
	
	static function GetCityAllLangs($id){
		Cmwdb::$db->where('addr_type', 'city');
		Cmwdb::$db->where('addr_id', $id);
		$res = Cmwdb::$db->getOne(self::$tbl_name);
		// 		var_dump($res);die;
		if($res){
			$res['addr_datas'] = json_decode($res['addr_datas'], true);
			$res['text'] = $res['addr_datas']['text'];
			$langs = CLanguage::get_lang_keys_user();
			foreach ($langs as $lang){
				if(!isset($res['text'][$lang]))
					$res['text'][$lang] = "Untranslated value";
			}
			return $res;
		}
		return false;
	}
	
	static function GetCommunityAllLangs($id){
		Cmwdb::$db->where('addr_type', 'community');
		Cmwdb::$db->where('addr_id', $id);
		$res = Cmwdb::$db->getOne(self::$tbl_name);
		// 		var_dump($res);die;
		if($res){
			$res['addr_datas'] = json_decode($res['addr_datas'], true);
			$res['text'] = $res['addr_datas']['text'];
			$langs = CLanguage::get_lang_keys_user();
			foreach ($langs as $lang){
				if(!isset($res['text'][$lang]))
					$res['text'][$lang] = "Untranslated value";
			}
			return $res;
		}
		return false;
	}
	
	static function GetCities($args, $lang=null){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		try {
			Cmwdb::$db->where('addr_type', 'city');
			Cmwdb::$db->where('addr_parent', $args);
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
				if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text'][$lang]))
					$ret[$vals['addr_id']] = $tmp['text'][$lang];
				else $ret[$vals['addr_id']] = "Untranslated value";
			}
			return $ret;
			
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
				
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function GetCitiesAllLangs($id){
		try {
// 			var_dump($id);
			Cmwdb::$db->where('addr_type', 'city');
			Cmwdb::$db->where('addr_parent', $id);
			$res = Cmwdb::$db->get(self::$tbl_name);
// 			echo Cmwdb::$db->getLastQuery();
			if(empty($res))throw new Exception('Empty list of states',1);
			$ret = array();
			$langs = CLanguage::getInstance()->get_lang_keys_user();
			foreach ($res as $vals){
				$ret[$vals['addr_id']] = $vals;
				$ret[$vals['addr_id']]['addr_datas'] = json_decode($vals['addr_datas'], true);
				$ret[$vals['addr_id']]['text'] = $ret[$vals['addr_id']]['addr_datas']['text'];
				foreach ($langs as $lang){
					if(!isset($ret[$vals['addr_id']]['text'][$lang]))
						$ret[$vals['addr_id']]['text'][$lang] = "Untranslated value";
				}
			}
			return $ret;
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return $error->getMessage();
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
	
				default:{
					return $error->getMessage();
				}
			}
		}
		return [];
	}
	
	static function AddCountry($args){
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			$queryData = array();
			$queryData['addr_type'] = "country";
//			$queryData['addr_shortkey'] = $shortkey;
			foreach ($langs as $lang){
				if(isset($args[$lang]))$queryData['addr_datas']['text'][$lang] = $args[$lang];
				else $queryData['addr_datas']['text'][$lang] = "Untranslated datas";
			}
			$queryData['addr_datas'] = json_encode($queryData['addr_datas']);
			if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
				return Cmwdb::$db->getInsertId();
			throw new Exception('Insert error was accoured',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	

	
	static function AddState($country, $args){
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			$queryData = array();
			$queryData['addr_type'] = "state";
//			$queryData['addr_shortkey'] = $shortkey;
			foreach ($langs as $lang){
				if(isset($args[$lang]))$queryData['addr_datas']['text'][$lang] = $args[$lang];
				else $queryData['addr_datas']['text'][$lang] = "Untranslated datas";
			}
			$queryData['addr_datas'] = json_encode($queryData['addr_datas']);
			$queryData['addr_parent'] = $country;
			if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
				return Cmwdb::$db->getInsertId();
			throw new Exception('Insert error was accoured',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function EditCountry($id, $args){
		try {
			Cmwdb::$db->where('addr_id', $id);
			Cmwdb::$db->where('addr_type', 'country');
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if($res){
				$res = json_decode($res['addr_datas'], true);
				$langs = CLanguage::getInstance()->get_lang_keys_user();
				$ins = array();
				foreach ($langs as $lang){
					if(isset($args['lang'][$lang]))$ins[$lang] = $args['lang'][$lang];
					else $ins[$lang] = "untranslated value";
				}
				$res['addr_datas']['text'] = $ins;
				$res['addr_datas'] = json_encode($res['addr_datas']);
				$queryDatas = array();
				$queryDatas['addr_datas'] = $res['addr_datas'];
// 				$queryDatas['addr_datas']['text'] = json_encode($ins);
				$queryDatas['addr_tax'] = $args['addr_tax'];
				$queryDatas['addr_zip'] = $args['addr_zip'];
				$queryDatas['addr_time'] = $args['addr_time'];
				$queryDatas['addr_order'] = $args['addr_order'];
				$queryDatas['addr_tel_code'] = $args['addr_tel_code'];
				$queryDatas['addr_price'] = $args['addr_price'];
				if(isset($args['addr_currency']))
					$queryDatas['addr_currency'] = $args['addr_currency'];
				Cmwdb::$db->where('addr_type', 'country');
				Cmwdb::$db->where('addr_id', $id);
				if(Cmwdb::$db->update(self::$tbl_name, $queryDatas))return $id;
				throw new Exception('Cant update table',2);
			}
			throw new Exception('ID was not found', 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}

	static function EditState($id, $args){
		try {
			Cmwdb::$db->where('addr_id', $id);
			Cmwdb::$db->where('addr_type', 'state');
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if($res){
				$res = json_decode($res['addr_datas'], true);
				$langs = CLanguage::getInstance()->get_lang_keys_user();
				$ins = array();
				foreach ($langs as $lang){
					if(isset($args['lang'][$lang]))$ins[$lang] = $args['lang'][$lang];
					else $ins[$lang] = "untranslated value";
				}
				$res['addr_datas']['text'] = $ins;
				$res['addr_datas'] = json_encode($res['addr_datas']);
				$queryDatas = array();
				$queryDatas['addr_datas'] = $res['addr_datas'];
// 				$queryDatas['addr_datas']['text'] = json_encode($ins);
				$queryDatas['addr_tax'] = $args['addr_tax'];
				$queryDatas['addr_zip'] = $args['addr_zip'];
				$queryDatas['addr_time'] = $args['addr_time'];
				$queryDatas['addr_order'] = $args['addr_order'];
				$queryDatas['addr_tel_code'] = $args['addr_tel_code'];
				$queryDatas['addr_price'] = $args['addr_price'];
				if(isset($args['addr_currency']))
					$queryDatas['addr_currency'] = $args['addr_currency'];
				Cmwdb::$db->where('addr_type', 'state');
				Cmwdb::$db->where('addr_id', $id);
				if(Cmwdb::$db->update(self::$tbl_name, $queryDatas))return $id;
				throw new Exception('Cant update table',2);
			}
			throw new Exception('ID was not found', 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function EditCity($id, $args){
		try {
			Cmwdb::$db->where('addr_id', $id);
			Cmwdb::$db->where('addr_type', 'city');
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if($res){
				$res = json_decode($res['addr_datas'], true);
				$langs = CLanguage::getInstance()->get_lang_keys_user();
				$ins = array();
				foreach ($langs as $lang){
					if(isset($args['lang'][$lang]))$ins[$lang] = $args['lang'][$lang];
					else $ins[$lang] = "untranslated value";
				}
				$res['addr_datas']['text'] = $ins;
				$res['addr_datas'] = json_encode($res['addr_datas']);
				$queryDatas = array();
				$queryDatas['addr_datas'] = $res['addr_datas'];
// 				$queryDatas['addr_datas']['text'] = json_encode($ins);
				$queryDatas['addr_tax'] = $args['addr_tax'];
				$queryDatas['addr_zip'] = $args['addr_zip'];
				$queryDatas['addr_time'] = $args['addr_time'];
				$queryDatas['addr_order'] = $args['addr_order'];
				$queryDatas['addr_tel_code'] = $args['addr_tel_code'];
				$queryDatas['addr_price'] = $args['addr_price'];
				if(isset($args['addr_currency']))
					$queryDatas['addr_currency'] = $args['addr_currency'];
				Cmwdb::$db->where('addr_type', 'city');
				Cmwdb::$db->where('addr_id', $id);
				if(Cmwdb::$db->update(self::$tbl_name, $queryDatas))return $id;
				throw new Exception('Cant update table',2);
			}
			throw new Exception('ID was not found', 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function EditCommunity($id, $args){
			try {
			Cmwdb::$db->where('addr_id', $id);
			Cmwdb::$db->where('addr_type', 'community');
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if($res){
				$res = json_decode($res['addr_datas'], true);
				$langs = CLanguage::getInstance()->get_lang_keys_user();
				$ins = array();
				foreach ($langs as $lang){
					if(isset($args['lang'][$lang]))$ins[$lang] = $args['lang'][$lang];
					else $ins[$lang] = "untranslated value";
				}
				$res['addr_datas']['text'] = $ins;
				$res['addr_datas'] = json_encode($res['addr_datas']);
				$queryDatas = array();
				$queryDatas['addr_datas'] = $res['addr_datas'];
// 				$queryDatas['addr_datas']['text'] = json_encode($ins);
				$queryDatas['addr_tax'] = $args['addr_tax'];
				$queryDatas['addr_zip'] = $args['addr_zip'];
				$queryDatas['addr_time'] = $args['addr_time'];
				$queryDatas['addr_order'] = $args['addr_order'];
				$queryDatas['addr_tel_code'] = $args['addr_tel_code'];
				$queryDatas['addr_price'] = $args['addr_price'];
				if(isset($args['addr_currency']))
					$queryDatas['addr_currency'] = $args['addr_currency'];
				Cmwdb::$db->where('addr_type', 'community');
				Cmwdb::$db->where('addr_id', $id);
				if(Cmwdb::$db->update(self::$tbl_name, $queryDatas))return $id;
				throw new Exception('Cant update table',2);
			}
			throw new Exception('ID was not found', 1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function GetCountryAllLangs($id){
		Cmwdb::$db->where('addr_type', 'country');
		Cmwdb::$db->where('addr_id', $id);
		$res = Cmwdb::$db->getOne(self::$tbl_name);
// 		var_dump($res);die;
		try {
			if($res){
				$ret = array();
				$tmp = json_decode($res['addr_datas'], true);
				$langs = CLanguage::getInstance()->get_lang_keys_user();
				foreach ($langs as $lang){
					if(isset($tmp['text'][$lang]))$ret['text'][$lang] = $tmp['text'][$lang];
					else $ret['text'][$lang] = "Untranslated value";
				}
				$res['text'] = $ret['text'];
				return $res;
			}
			
			throw new Exception('No such country',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
		return false;
		
	}
	
	static function GetCommunitiesAllLangs($parent_id){
		self::getInstance();
		$def_lang = CLanguage::getDefaultUser();
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			Cmwdb::$db->where('addr_type', 'community');
			Cmwdb::$db->where('addr_parent', $parent_id);
				
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(empty($res))throw new Exception('Empty list of countries',1);
			$ret = array();
			foreach ($res as $vals){
				$tmp = json_decode($vals['addr_datas'], true);
// 				if(!isset($tmp['text']))throw new Exception('Invalid structure of json',2);
				if(isset($tmp['text']))
					$ret[$vals['addr_id']]['text'] = $tmp['text'];
				else {
					foreach ($langs as $lang)
						$ret[$vals['addr_id']]['text'][$lang] = "Untranslated value";
				}
				$ret[$vals['addr_id']]['addr_order'] = $vals['addr_order'];
				$ret[$vals['addr_id']]['addr_tax'] = $vals['addr_tax'];
				$ret[$vals['addr_id']]['addr_zip'] = $vals['addr_zip'];
				$ret[$vals['addr_id']]['addr_time'] = $vals['addr_time'];
				$ret[$vals['addr_id']]['addr_tel_code'] = $vals['addr_tel_code'];
				$ret[$vals['addr_id']]['addr_price'] = $vals['addr_price'];
				$ret[$vals['addr_id']]['addr_currency'] = $vals['addr_currency'];
			}
			return $ret;
				
		}
		catch (Exception $error){
			switch ($error->getCode()){
				case '1':{
					return [];
					break;
				}
				case '2':{
					return $error->getMessage();
					break;
				}
	
				default:{
					return [];
				}
			}
		}
		return [];
	}
	
	static function AddCity($state_id, $args){
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			$queryData = array();
			$queryData['addr_type'] = "city";
//			$queryData['addr_shortkey'] = $shortkey;
			foreach ($langs as $lang){
				if(isset($args[$lang]))$queryData['addr_datas']['text'][$lang] = $args[$lang];
				else $queryData['addr_datas']['text'][$lang] = "Untranslated datas";
			}
			$queryData['addr_datas'] = json_encode($queryData['addr_datas']);
			$queryData['addr_parent'] = $state_id;
			if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
				return Cmwdb::$db->getInsertId();
			throw new Exception('Insert error was accoured',1);
		}
		catch (Exception $error){
			return $error->getMessage();
		}
	}
	
	static function AddCommunity($city, $args){
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		try {
			$queryData = array();
			$queryData['addr_type'] = "community";
			//			$queryData['addr_shortkey'] = $shortkey;
			foreach ($langs as $lang){
				if(isset($args[$lang]))$queryData['addr_datas']['text'][$lang] = $args[$lang];
				else $queryData['addr_datas']['text'][$lang] = "Untranslated datas";
			}
			$queryData['addr_datas'] = json_encode($queryData['addr_datas']);
			$queryData['addr_parent'] = $city;
			if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
				return Cmwdb::$db->getInsertId();
				throw new Exception('Insert error was accoured',1);
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
	
	static function GetUnit($id){
		try {
			Cmwdb::$db->where('addr_id', $id);
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if($res){
				if(!$res['addr_price'] && $res['addr_parent']){
					$tmp = self::GetUnit($res['addr_parent']);
					if($tmp['status'])
						$res['addr_price'] = $tmp['result']['addr_price'];
				}
				$res['addr_datas'] = json_decode($res['addr_datas'], true);
				return [
					'status'=>1,
					'result'=>$res	
				];
			}
			throw new Exception('Adress with id:'.$id.' does not fount',1);
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function GetValidUnit($args){
		if(isset($args['community']) && $args['community']){
			return [
				'status'=>1,
				'result'=>$args['community']	
			];
		}
		if(isset($args['city']) && $args['city']){
			return [
					'status'=>1,
					'result'=>$args['city']
			];
		}
		if(isset($args['state']) && $args['state']){
			return [
					'status'=>1,
					'result'=>$args['state']
			];
		}
		if(isset($args['country']) && $args['country']){
			return [
					'status'=>1,
					'result'=>$args['country']
			];
		}
		return [
			'status'=>0,
			'result'=>""	
		];
		
		
	}
	
	static function GetTree($id){
		try {
			if(!$id)throw new Exception('Error: Null was gived as id');
			$temp = array();
			Cmwdb::$db->where('addr_id', $id);
			$temp[$id] = Cmwdb::$db->getOne(self::$tbl_name);
			if(!$temp[$id])throw new Exception('Error: Invalid id of address');
			$temp[$id]['addr_datas'] = json_decode($temp[$id]['addr_datas'], true);
			if($temp[$id]['addr_parent']){
				$id = $temp[$id]['addr_parent'];
				while (1){
					Cmwdb::$db->where('addr_id', $id);
					$res = Cmwdb::$db->getOne(self::$tbl_name);
					if(!$res)throw new Exception('Error: Invalid id of address');
					$temp[$id] = $res;
					$temp[$id]['addr_datas'] = json_decode($res['addr_datas'], true);
					if(!$res['addr_parent'])break;
					$id = $res['addr_parent'];
				}
			}
			$ret = array();
			foreach ($temp as $address)$ret[$address['addr_type']] = $address;
			return $ret;
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function FindTree($args){
		$id = null;
		if(isset($args['country']))$id =$args['country'];
		if(isset($args['state']))$id =$args['state'];
		if(isset($args['city']))$id =$args['city'];
		if(isset($args['community']))$id =$args['community'];
		return self::GetTree($id);
	}
	
	static function GetDatasParent($parent_id=0){
		try {
			Cmwdb::$db->where('addr_parent',$parent_id);
			$res = Cmwdb::$db->get(self::$tbl_name);
			if(!$res)throw new Exception("Error: elements fount width parent ".$parent_id);
			$ret = array();
			foreach ($res as $values)$ret[$values['addr_id']] = $values;
			return [
				'status'=>1,
				'result'=>$ret	
			];
		}
		catch(Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
}

?>