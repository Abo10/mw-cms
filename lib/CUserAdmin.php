<?php

class CUserAdmin extends Cstd_user_adm{

	static function Initial(){
		self::$tbl_name = "adm_users";
		self::$UserType = "admin";
		return parent::Initial();
	}
	
}
?>