<?php

class CFrontSeo /*extends CFront*/{
	protected static $datas = array();
	static $tbl_name = "std_seo";
	static function Initial(){
// 		self::$tbl_name = "std_seo";
	}
	
	static function GetDatas($oid=null, $args=null){
		if(!$oid)return array();
		if(is_numeric($oid)){
			Cmwdb::$db->where('seo_id', $oid);
			$res = Cmwdb::$db->getOne(self::$tbl_name);
			return $res;
		}
		if(is_array($oid)){
			Cmwdb::$db->where('seo_id', $oid, "in");
			$res = Cmwdb::$db->get(self::$tbl_name);
			$ret = array();
			foreach ($res as $values)$ret[$values['seo_id']] = $values;
			return $ret;
			
		}
		return array();
	}
}
?>