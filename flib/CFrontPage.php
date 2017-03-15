<?php
class CFrontPage /*extends CFront*/{
	protected static $datas = array();
	public static $tbl_name = "std_pages";

	static function Initial(){
	//self::$tbl_name = "std_pages";
	}

	static function GetDatas($oid=null, $args=null){
		if(!$oid)return array();
		$res = array();
		Cmwdb::$db->where('page_lang', CLanguage::getCurrentUser());
		Cmwdb::$db->where('page_isactive', 1);
		if(is_string($oid))Cmwdb::$db->where('page_slug',$oid);
		if(is_numeric($oid))Cmwdb::$db->where('pid', $oid);

		if(is_array($oid)){

			Cmwdb::$db->where('pid', $oid, "in");

			if(is_array($args)){
				if(!in_array("pid", $args))$args[] = "pid";
				$res = Cmwdb::$db->get(self::$tbl_name, null, $args);

			}
			else $res = Cmwdb::$db->get(self::$tbl_name);
			if(!empty($res)){
				foreach ($res as $key => $values){
					if(isset($values['page_seo'])){
						$res[$key]['page_seo_content'] = CFrontSeo::GetDatas($values['page_seo']);
					}
					if(isset($values['page_gallery']))$res[$key]['page_gallery'] = json_decode($values['page_gallery'], true);
				}
			}

		}
		else{
			if(is_array($args)){
				if(!in_array("pid", $args))$args[] = "pid";
				$res = Cmwdb::$db->getOne(self::$tbl_name, null, $args);
			}
			else $res = Cmwdb::$db->getOne(self::$tbl_name);
			if(!empty($res)){
				if(isset($res['page_seo'])){
					$res['page_seo_content'] = CFrontSeo::GetDatas($res['page_seo']);
				}
				if(isset($res['page_gallery']))$res['page_gallery'] = json_decode($res['page_gallery'], true);
			}
		}

		return $res;
	}
}
?>