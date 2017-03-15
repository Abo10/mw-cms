<?php
class CUserDetails{
	protected $tbl_name = "std_users_details";
	protected $datas = array();
	function __construct($uid=null, $u_type=null, $u_token=null){
		try {
			if($uid && $u_type){
				Cmwdb::$db->where('uid', $uid);
				Cmwdb::$db->where('utype', $u_type);
			}
			if($u_token)Cmwdb::$db->where('u_token', $u_token);
			$res = Cmwdb::$db->getOne($this->tbl_name);
			if(!empty($res)){
				$this->datas = json_decode($res['u_datas']);
			}
		}
		catch (Exception $error){
			
		}
	}
	
	function AddDetails($datas, $uid=null, $u_type=null){
		try {
			//Verify, if row exists, go to update data field or do insertion
			if($uid && $u_type){
				//verify uid exists
				Cmwdb::$db->where('uid', $uid);
				Cmwdb::$db->where('u_type', $u_type);
				$id = Cmwdb::$db->getValue($this->tbl_name, 'dit');
				$queryData = array();
				if($id){//row exists, so go to update
					$queryData['u_datas'] = json_encode($datas);
					if(Cmwdb::$db->update($this->tbl_name, $queryData)){
						return [
								'status'=>1,
								'result'=>$id
						];
					}
					else throw new Exception('Error, cant update value in table',1);
				}
				else{//no row fount, so go to insert
					$queryData['uid'] = $uid;
					$queryData['u_type'] = $u_type;
					$queryData['u_datas'] = json_encode($datas);
					if(Cmwdb::$db->insert($this->tbl_name, $queryData)){
						$id = Cmwdb::$db->getInsertId();
						return [
								'status'=>1,
								'result'=>$id
						];
					}
					else throw new Exception('Error, cant insert row in table',2);
				}
			}
			//else generate utoken as it
			else{
				$queryData['u_type'] = 'token';
				$token = md5(date("Y/m/d H:i:s")).'-'.rand(1000,9999);
				$queryData['u_token'] = $token;
				$queryData['u_datas'] = json_encode($datas);
				if(Cmwdb::$db->insert($this->tbl_name, $queryData)){
					$id = Cmwdb::$db->getInsertId();
					return [
							'status'=>1,
							'result'=>$id
					];
				}
				else throw new Exception('Error, cant insert row in table',2);
			}
		}
		catch(Exception $error){
			return [
					'status'=>0,
					'result'=>$error->getMessage(),
					'error'=>Cmwdb::$db->getLastError()
			];
		}
		
	}
	
	function GetDatas(){
		return $this->datas;
	}
}
?>