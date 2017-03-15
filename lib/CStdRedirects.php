<?php
class CStdRedirects{
	static protected $tbl_name = "std_redirects";
	
	static function AddRedirect($type, $obj_id, $old_slug, $new_slug){
		$queryData = array();
		$queryData['redirect_type'] = $type;
		$queryData['old_slug'] = $old_slug;
		$queryData['new_slug'] = $new_slug;
		$queryData['redirect_type_id'] = $obj_id;
		
		if(Cmwdb::$db->insert(self::$tbl_name, $queryData))
			return Cmwdb::$db->getInsertId();
		return false;
	}
	
	static function GetRedirect($slug, $type=null){
		Cmwdb::$db->where('old_slug', $slug);
		if($type)Cmwdb::$db->where('redirect_type', $type);
		$res = Cmwdb::$db->getOne(self::$tbl_name,['redirect_type', 'redirect_type_id']);
		if(empty($res)) return false;
		return CUrlManager::GetURL(['type'=>$res['redirect_type'],'id'=>$res['redirect_type_id']]);
	}
}
?>