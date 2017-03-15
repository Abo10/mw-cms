<?php

class CStd_user{
	private static $_instance = null;
	private function __construct() {}
	protected function __clone() {}
	
	static $tbl_name = "std_users";
	static $datas = array();
	static $IsAuthenticated = false;
	static $UserType = "std_user";
	static $link_tblName = "user_links";
	static function Authentication($args){
		echo 'Ez';
		if(!self::ImportDatas()){
			if(is_array($args)){
				if(isset($args['login']) && isset($args['password'])){
					Cmwdb::$db->where('std_users.us_login',$args['login']);
					Cmwdb::$db->join('std_users','std_users.uid = user_ext.uid','left');
					$res = Cmwdb::$db->getOne('user_ext');
// 					Cmwdb::$db->where('us_login', $args['login']);
// 					$res = Cmwdb::$db->getOne(self::$tbl_name);
// 					var_dump($res);
					if(!empty($res)){
						$base_pass = $res['salt'].'-'.$res['us_password'];
						$verr_pass = $res['salt'].'-'.md5($args['password']);
						if($base_pass===$verr_pass){
							self::$datas = $res;
							self::$IsAuthenticated = true;
							$tmp = new CUserDetails($res['uid']);
							self::$datas['details'] = $tmp->GetDatas();
							self::UploadDatas();

							return true;
						}
					}
				}
			}
			
		}
		return false;
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
					$reg_salt = md5(date("Y:m:d H/i/s"));
					$args['password'] = md5($args['password']);
					$queryData['us_login'] = $args['login'];
					$queryData['us_password'] = $args['password'];
					$queryData['salt'] = $salt;
					$queryData['reg_hash'] = $reg_salt;
					$queryData['register_date'] = date('Y/m/d H:i:s');
					if(Cmwdb::$db->insert(self::$tbl_name, $queryData)){
						return ['id'=>Cmwdb::$db->getInsertId(), 'vercode'=>$reg_salt];
					}
				}
			}
		}
		return false;
	}
	
	static function LogOut(){
		if(isset($_SESSION['user']))
			unset($_SESSION['user']);
		if($_SESSION['state']['from_db'])
			$_SESSION['state']['from_db'] = false;
		
	}
	
	static function GetDatas(){
		return self::$datas;
	}
	
	static function Initial(){
		return self::ImportDatas();
	}
	
	static function GetUserType(){
		return self::$UserType;
	}
	
	static function GetUserID(){
		if(isset($_SESSION['user']['uid']))
			return $_SESSION['user']['uid'];
		return "";
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
			$_SESSION['user'] = self::$datas;
			$_SESSION['user']['is_autorised'] = self::$IsAuthenticated;
			$_SESSION['user']['type'] = self::$UserType;
			return true;
		}
		return false;
	}
	
	static protected function ImportDatas(){
		if(session_id() && isset($_SESSION['user'])){
			self::$datas = $_SESSION['user'];
			self::$IsAuthenticated = $_SESSION['user']['is_autorised'];
			self::$UserType = $_SESSION['user']['type'];
			return true;
		}
		return false;
	}
	
	static function IsAuthenticated(){
		if(isset($_SESSION['user']['is_autorised']))
			return $_SESSION['user']['is_autorised'];
		return false;
	}
}
?>