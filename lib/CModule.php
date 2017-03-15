<?php
define("FILE_NOT_FOUND", 'module_file_not_found');
define("SUCSESS_LINKED", 'module_sucsessful_linked');
class CModule{
	
	
	static function TakeConfigs($mod_name){
		CConfig::getInstance();
		$config = CConfig::GetModuleConfig($mod_name);
		return $config;
	}
	
	static function LoadModule($mod_name, $argv=null){
// 		echo 'We are in load module<br>';
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['class'].'.php'))
				require_once __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['class'].'.php';
			else{
				return FILE_NOT_FOUND;
			}
			if($argv)return new $ret['class']($argv);
			else return new $ret['class'];
		}
		return CONFIG_NO_ENTRY;
	}

	static function LinkModule($mod_name){
		// 		echo 'We are in load module<br>';
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['class'].'.php'))
				require_once __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['class'].'.php';
				else{
					return FILE_NOT_FOUND;
				}
			return SUCSESS_LINKED;	
		}
		return CONFIG_NO_ENTRY;
	}
	static function LoadModuleFront($mod_name, $argv=null){
		// 		echo 'We are in load module<br>';
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(file_exists(__DIR__.'/../modules/'.$mod_name.'/front/flib/'.$ret['front_class'].'.php'))
				require_once __DIR__.'/../modules/'.$mod_name.'/front/flib/'.$ret['front_class'].'.php';
				else{
					return FILE_NOT_FOUND;
				}
				if($argv)return new $ret['front_class']($argv);
				else return new $ret['front_class'];
		}
		return CONFIG_NO_ENTRY;
	}
	
	static function GetModulesList(){
		$mod_conf = CConfig::GetTree('modules');
		$ret = array();
		if($mod_conf!==CONFIG_NO_ENTRY){
			foreach ($mod_conf as $mod_name=>$unneed){
				$ret[] = $mod_name;
			}
		}
		return $ret;
	}
	
	static function LoadComponent($mod_name, $component_name, $args=null){
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(isset($ret['components']) && isset($ret['components'][$component_name])){
//				echo __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php';
				if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php'))
					require_once __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php';
				else{
					return FILE_NOT_FOUND;
				}
				if($args)return new $ret['components'][$component_name]($argv);
				else return new $ret['components'][$component_name];
			}
			
		}
		return CONFIG_NO_ENTRY;
		
	}
	
	static function LinkComponent($mod_name, $component_name){
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(isset($ret['components']) && isset($ret['components'][$component_name])){
				//				echo __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php';
				if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php'))
					require_once __DIR__.'/../modules/'.$mod_name.'/admin/lib/'.$ret['components'][$component_name].'.php';
					else{
						return [
							'status'=>0,
							'result'=>FILE_NOT_FOUND
						];
					}
					return [
						'status'=>1,
						'result'=>	$ret['components'][$component_name]							
					];
			}
				
		}
		return [
			'status'=>0,
			'result'=>FILE_NOT_FOUND
		];
	
	}
	static function LoadCSS($mod_name, $file=null){
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(!$file){
				if(isset($ret['css'])){
					$res = "";
					foreach ($ret['css'] as $link){
						$res.='<link rel="stylesheet" href="'.URL_BASE.'modules/'.$mod_name.'/admin/css/'.$link.'.css" />';
					}

					return $res;
				}
			}
			else{
				if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/css/'.$file.'.css')){
					return $res='<link rel="stylesheet" href="'.URL_BASE.'modules/'.$mod_name.'/admin/css/'.$file.'.css" />';
				}
			}
		}
		return;
	}
	
	static function LoadTemplate($mod_name, $file, $args=null){
		if(is_array($args)){
			foreach ($args as $key=>$value)
				${$key} = $value;
		}
		$ret = CModule::TakeConfigs($mod_name);
		if($ret!==CONFIG_NO_ENTRY){
			if(file_exists(__DIR__.'/../modules/'.$mod_name.'/admin/templates/'.$file.'.php')){
				require_once(__DIR__.'/../modules/'.$mod_name.'/admin/templates/'.$file.'.php');
				return true;
			}
			
		}
		return null;
		
	}
	
	static function HasModule($mod_name){
		return CConfig::HasModule($mod_name);
	}
	
	
}
?>