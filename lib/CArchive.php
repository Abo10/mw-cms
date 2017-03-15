<?php
class CArchive{
	static public $arc = null;
	static protected $configs = [];
	static function CreateArchive($arch_name){
		try{
			if(!self::InitialConfig())
				throw new Exception('Error: cant initialise configuration');
			if(self::$arc){
				self::Finish();
			}
			self::$arc = new PclZip(self::$configs['save_dir'].$arch_name.'.zip');
			return[
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
	
	static function AddToArchive($path, $expect_this = null){
		try {
			$expect_url = SYSTEM_BASE_URI;
			if($expect_this)$expect_url.=$expect_this;
			if(!self::$arc->add($path, PCLZIP_OPT_REMOVE_PATH, $expect_url))
				throw new Exception('Error: cant to add to archive');
			return [
				'status'=>1,
				'result'=>""	
			];
		}
		catch(Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function Finish(){
		self::$arc = null;
	}
	
	static protected function InitialConfig(){
		$conf = CConfig::GetBlock('config', 'CArchive');
		if($conf===CONFIG_NO_ENTRY)return false;
		self::$configs = $conf;
// 		var_dump(self::$configs);
		return true;			
	}
}