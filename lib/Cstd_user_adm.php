<?php

class Cstd_user_adm{
	private static $_instance = null;
	private function __construct() {}
	protected function __clone() {}
	
	static protected $tbl_name = "std_users";
	static protected $datas = array();
	static protected $IsAuthenticated = false;
	static protected $UserType = "std_user";
	static protected $link_tblName = "user_links";
	static function Authentication($args){
		if(!self::ImportDatas()){
			if(is_array($args)){
				if(isset($args['login']) && isset($args['password'])){
					Cmwdb::$db->where('us_login', $args['login']);
					$res = Cmwdb::$db->getOne(self::$tbl_name);
					if(!empty($res)){
						$base_pass = $res['salt'].'-'.$res['us_password'];
						$verr_pass = $res['salt'].'-'.md5($args['password']);
						if($base_pass===$verr_pass){
							self::$datas = $res;
							$tmp = new CUserDetails($res['uid']);
							self::$datas['details'] = $tmp->GetDatas();
							self::$IsAuthenticated = true;
	
							self::UploadDatas();
							return true;
						}
					}
				}
			}
			return false;
		}
		else return true;
	
	}
	
	static function CreateUser($args){
		if(is_array($args)){
			if(isset($args['login']) && isset($args['password']) && $args['password']!==""){
				Cmwdb::$db->where('us_login', $args['login']);
				$res = Cmwdb::$db->getOne(self::$tbl_name);
				//				var_dump($res);
				if(empty($res)){
					// 					echo 'Done, user was not fount';
					$salt = md5(date("Y:m:d H:i:s"));
					$args['password'] = md5($args['password']);
					$queryData['us_login'] = $args['login'];
					$queryData['us_password'] = $args['password'];
					$queryData['salt'] = $salt;
					if(Cmwdb::$db->insert(self::$tbl_name, $queryData)){
						echo Cmwdb::$db->getLastError();
						return Cmwdb::$db->getInsertId();
					}
				}
			}
		}
		return false;
	}
	
	static function LogOut(){
		if(isset($_SESSION['user_adm']))
			unset($_SESSION['user_adm']);
		
	}
	
	static function GetDatas(){
		return self::$datas;
	}
	
	static function Initial(){
		return self::ImportDatas();
	}
	
	static protected function GetUserType(){
		return self::$UserType;
	}
	
	static protected function GetUserID(){
		return self::$datas['uid'];
	}
	
	static function CreateLink($obj_id, $obj_type){
		$queryData['uid'] = self::GetUserID();
		$queryData['u_type'] = self::GetUserType();
		$queryData['obj_id'] = $obj_id;
		$queryData['obj_type'] = $obj_type;
		return Cmwdb::$db->insert(self::$link_tblName, $queryData);
	}
	
	static protected function UploadDatas(){
		if(session_id()){
			$_SESSION['user_adm'] = self::$datas;
			$_SESSION['user_adm']['is_autorised'] = self::$IsAuthenticated;
			$_SESSION['user_adm']['type'] = self::$UserType;
			return true;
		}
		return false;
	}
	
	static protected function ImportDatas(){
		if(session_id() && isset($_SESSION['user_adm'])){
			self::$datas = $_SESSION['user_adm'];
			self::$IsAuthenticated = $_SESSION['user_adm']['is_autorised'];
			self::$UserType = $_SESSION['user_adm']['type'];
			return true;
		}
		return false;
	}
	
	static function IsAuthenticated(){
		if(isset($_SESSION['user_adm']['is_autorised']))
			return $_SESSION['user_adm']['is_autorised'];
		return false;
	}
	
	
	static function GetLogin(){
		if(self::$datas)
			return self::$datas['us_login'];
		return false;
	}
}