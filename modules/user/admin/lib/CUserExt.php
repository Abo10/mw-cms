<?php

class CUserExt extends CStd_user{
	static protected $ext_TblName = "user_ext";
	static function Initial(){
		self::$ext_TblName = "user_ext";
		return parent::Initial();
	}
	
	static function CreateUser($args){
		if(!isset($args['login']))
			$args['login'] = $args['user_mail'];
		$pass1 = $args['pass1'];
		$pass2 = $args['pass2'];
		unset($args['pass1']);
		unset($args['pass2']);
		if($pass1===$pass2){
			$args['password'] = $pass1;
		}
		else return false;
		if(!isset($args['user_mail']))return false;
		Cmwdb::$db->where('user_mail', $args['user_mail']);
		if(Cmwdb::$db->getValue(self::$ext_TblName, 'uid'))
			return false;
		$id = parent::CreateUser($args);
		if($id){
			$queryData = array();
			$queryData['uid'] = $id['id'];
			$queryData['user_mail'] = $args['user_mail'];
			if(isset($args['user_name']))
				$queryData['user_name'] = $args['user_name'];
			if(isset($args['userl_name']))
				$queryData['userl_name'] = $args['userl_name'];
			if(isset($args['tel_code']))
				$queryData['tel_code'] = $args['tel_code'];
			if(isset($args['user_tel']))
				$queryData['user_tel'] = $args['user_tel'];
			if(isset($args['user_avatar']))
				$queryData['user_avatar'] = $args['user_avatar'];
// 			var_dump($queryData);die;
			if(Cmwdb::$db->insert(self::$ext_TblName, $queryData)){
				$url = URL_BASE."account/activate?mail=".$queryData['user_mail']."&vercode=".$id['vercode'];
				$to = $args['user_mail'];
				$subject = "Welcome to best CMS of the world - MW";
				$text = 'To activate enter '.$url.'';
				mail($to, $subject, $text);
				return true;
			}
		}
		return false;
	}
	
	static function Authentication($args){
		if(!self::ImportDatas()){
			if(is_array($args)){
				if(isset($args['login']) && isset($args['password'])){
					Cmwdb::$db->where('std_users.us_login',$args['login']);
					Cmwdb::$db->join('std_users','std_users.uid = user_ext.uid','left');
					$res = Cmwdb::$db->getOne('user_ext');
// 					Cmwdb::$db->where('us_login', $args['login']);
// 					$res = Cmwdb::$db->getOne(self::$tbl_name);
					if(!empty($res)){
						if($res['us_status']===2 || $res['us_status']===0 || $res['us_status']===3)
							return false;
						$base_pass = $res['salt'].'-'.$res['us_password'];
						$verr_pass = $res['salt'].'-'.md5($args['password']);
						if($base_pass===$verr_pass){
							self::$datas = $res;
							self::$IsAuthenticated = true;
							self::UploadDatas();
							$login_date = date('Y/m/d H:i:s');
							Cmwdb::$db->where('uid', $res['uid']);
							Cmwdb::$db->update(self::$tbl_name, ['login_date'=>$login_date]);
							return true;
						}
					}
				}
			}
			
		}
		return false;
	}
	
	static function ActivateUser($mail, $vercode){
		Cmwdb::$db->where('us_login', $mail);
		Cmwdb::$db->where('reg_hash', $vercode);
		$id = Cmwdb::$db->getValue(self::$tbl_name, 'uid');
		if($id){
			Cmwdb::$db->where('uid', $id);
			return Cmwdb::$db->update(self::$tbl_name, ['is_activated'=>1]);
		}
		return false;
	}
	
	static function IsActive(){
		if(isset($_SESSION['user']['is_activated']))
			return $_SESSION['user']['is_activated'];
		return false;
	}
	
	static function HasMail($mail){
		Cmwdb::$db->where('us_login', $mail);
		if(Cmwdb::$db->getValue(self::$tbl_name, 'uid'))return true;
		Cmwdb::$db->where('user_mail', $mail);
		if(Cmwdb::$db->getValue(self::$ext_TblName, 'uid'))return true;
		return false;
		
	}
	
	static function GetUsersByID($id=null){
		if(is_numeric($id))
			Cmwdb::$db->where(self::$tbl_name.'.uid', $id);
		if(is_array($id))
			Cmwdb::$db->where(self::$tbl_name.'.uid', $id, "in");
		Cmwdb::$db->join(self::$ext_TblName, self::$tbl_name.'.uid='.self::$ext_TblName.'.uid');
		try {
			$res = Cmwdb::$db->get(self::$tbl_name);
			$ret = array();
			foreach ($res as $values)$ret[$values['uid']] = $values;
			return [
				'status'=>1,
				'result'=>$ret	
			];
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>'Error: no specified data for get user(s)'	
			];
		}
	}
	
	static function FindUsers($string, $in_fields="us_login", $user_status=null, $page=1, $count=20){
		try {
			if(is_string($in_fields)){
				if($in_fields=="us_login")
					Cmwdb::$db->where(self::$tbl_name.'.'.$in_fields, '%'.$string.'%', "like");
				else Cmwdb::$db->where(self::$ext_TblName.'.'.$in_fields, '%'.$string.'%', "like");
			}
			if(is_array($in_fields)){
				foreach ($in_fields as $fields){
					if($fields=="us_login")
						Cmwdb::$db->orWhere(self::$tbl_name.'.'.$fields, '%'.$string.'%', "like");
					else Cmwdb::$db->orWhere(self::$ext_TblName.'.'.$fields, '%'.$string.'%', "like");
				}	
			}
			if(!is_null($user_status)){
				Cmwdb::$db->where(self::$tbl_name.'.us_status', $user_status);
			}
			Cmwdb::$db->pageLimit = $count;
				
			Cmwdb::$db->join(self::$ext_TblName, self::$tbl_name.'.uid='.self::$ext_TblName.'.uid');
			$res = Cmwdb::$db->arrayBuilder()->paginate(self::$tbl_name, $page);
			$ret = array();
			if(!empty($res))
				foreach ($res as $values)$ret[$values['uid']] = $values;
			return [
				'status'=>1,
				'result'=>$ret,
				'page_count'=>Cmwdb::$db->totalPages	
			];
			
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function BlockUser($uid){
		try{
			Cmwdb::$db->where('uid', $uid);
			if(Cmwdb::$db->update(self::$tbl_name, ['us_status'=>2])){
				return [
					'status'=>1,
					'result'=>""	
				];
			}
			throw new Exception('Error: cant update status');
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	static function UnblockUser($uid){
		try{
			Cmwdb::$db->where('uid', $uid);
			if(Cmwdb::$db->update(self::$tbl_name, ['us_status'=>1])){
				return [
						'status'=>1,
						'result'=>""
				];
			}
			throw new Exception('Error: cant update status');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function DeleteUser($uid){
		try{
			Cmwdb::$db->where('uid', $uid);
			Cmwdb::$db->delete(self::$tbl_name);
			Cmwdb::$db->where('uid', $uid);
			Cmwdb::$db->delete(self::$ext_TblName);
			if(CModule::HasModule('checkout')){
				CModule::LoadModule('checkout');
				return COrder::DeleteByUsers($uid);
			}
			return [
				'status'=>1,
				'result'=>""	
			];
			throw new Exception('Error: cant update status');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function GetStatus($uid){
		try{
			Cmwdb::$db->where('uid', $uid);
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			if(empty($res))
				throw new Exception('Error: no such user defined');
			return [
				'status'=>1,
				'result'=>$res['us_status']	
			];
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function ChancgePassword(array $args){
		try{
			$exists_user = GetUserDatas();
			if(!$exists_user['status'])throw new Exception('Notice: no user account was loged in');
			$old_password = "";
			$new_password = "";
			$confirm_password = "";
			if(isset($args['old_password']) && $args['old_password'])$old_password = $args['old_password'];
			else throw new Exception("Error: no entered login");
			if(isset($args['new_password']) && $args['new_password'])$new_password = $args['new_password'];
			else throw new Exception("Error: no entered login");
			if(isset($args['confirm_password']) && $args['confirm_password'])$confirm_password = $args['confirm_password'];
			else throw new Exception("Error: no entered login");
			if($new_password!==$confirm_password)throw new Exception('Error: new password and confirm password is diferend');
			Cmwdb::$db->where('us_login', $login);
			$account = Cmwdb::$db->getOne(self::$tbl_name);
			if(empty($account))throw new Exception("Error: no such account fount");
			$base_pass = $account['salt'].'-'.$account['us_password'];
			$verr_pass = $account['salt'].'-'.md5($old_password);
			if($base_pass===$verr_pass){
				$new_password = md5($new_password);
				Cmwdb::$db->where('uid', $account['uid']);
				if(!Cmwdb::$db->update(self::$tbl_name, ['us_password'=>$new_password]))
					throw new Exception('Error: cant update data in db');
				return [
					'status'=>1,
					'result'=>""	
				];
			}
			throw new Exception('Error: invalid old password entered');
		}
		catch (Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage()
			];
		}
	}
	
	static function UpdateUserDetails(array $details){
		try {
			$current_user = GetUserDatas();
			if(!$current_user['status'])return $current_user;
			
			Cmwdb::$db->where('uid', $current_user['result']['uid']);
			if(!Cmwdb::$db->update(self::$ext_TblName, ['us_details'=>json_encode($details)]))
				throw new Exception('Error: cant update in db');
			
		}
		catch (Exception $error){
			return [
				'status'=>0,
				'result'=>$error->getMessage()	
			];
		}
	}
	
	
}

?>