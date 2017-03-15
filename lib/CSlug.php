<?php

/**
 * Created by PhpStorm.
 * User: abo
 * Date: 12/11/2015
 * Time: 2:32 PM
 */

class CSlug {
	private static $_instance = null;
	static public $slugKey;
	static public $lang;
	static protected $langs = array(' ','ա','բ','գ','դ','ե','զ','է','ը','թ','ժ','ի','լ','խ','ծ','կ','հ','ձ','ղ','ճ',
									'մ','յ','ն','շ','ո','չ','պ','ջ','ռ','ս','վ','տ','ր','ց','ւ','փ','ք','և','օ','ֆ',
									'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с',
									'т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','э',
									'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o',
									'p','q','r','s','t','u','v','w','x','y','z',
									'0','1','2','3','4','5','6','7','8','9');
	static protected $mirors = array('-','a','b','g','d','e','z','e','y','t','zh','i','l','kh','ts','k','h','dz','gh','ch',
									'm','y','n','sh','o','ch','p','j','r','s','v','t','r','c','u','p','q','ev','o','f',
									'a','b','v','g','d','e','io','jh','z','i','i','k','l','m','n','o','p','r','s','t',
									'u','f','kh','c','ch','sh','sh','','','e','u','ia','e','',
									'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o',
									'p','q','r','s','t','u','v','w','x','y','z',
									'0','1','2','3','4','5','6','7','8','9');
	
	private function __construct() {}
	protected function __clone() {}

	static public function getInstance() {
		if(is_null(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	static function mb_str_split( $string ) {
		# Split at all position not after the start: ^
		# and not before the end: $
		return preg_split('/(?<!^)(?!$)/u', $string );
	}
	
	static function ConvertToEnglish($title){
		$charlist = preg_split('/(?<!^)(?!$)/u', $title);
		$str_len = 0;
		$new = '';
		if(count($charlist)>255)
			$str_len = 255;
		else $str_len = count($charlist);
		for($i=0;$i<$str_len;$i++){
				$key = array_search(mb_strtolower($charlist[$i],'UTF-8'),self::$langs);
				$new .= self::$mirors[$key];
		}
		$arr = explode('-', $new);
		$new_arr = array();
		foreach ($arr as $value){
			if($value){
				$new_arr[]=$value;
			}
		};
		$new = implode('-', $new_arr);
		return $new;
	}
	
	static function GetSlug($title){
		return self::ConvertToEnglish($title);
	}
	
	static function GetVerifiedSlugs($slugs, $tbl_name, $slug_field = "slugs"){
		reset($slugs);
		$newSlug = "";
		$created_slugs = array();

		foreach ($slugs as $key=>$current){
			$newSlug = $current;
			if($current=="" || $current===null){
				$created_slugs[$key] = "";
				continue;
			}
			Cmwdb::$db->where($slug_field, $current);
			if(!empty(Cmwdb::$db->getOne($tbl_name))){
				$tmp_t = 1;
				$current_step = 0;
				while (1){
					$ver_slug = $newSlug.'-'.$tmp_t;
					Cmwdb::$db->where($slug_field, $ver_slug);
					if(!empty(Cmwdb::$db->getOne($tbl_name))){
						$tmp_t++;
					}
					else{
						//stugel ekrord ev errord ciklerum verslugi ev last_generetedi arjeqnery
						if(in_array($ver_slug, $created_slugs)){
							$tmp_t++;
							$current_step++;
						}
						else{
							$newSlug = $ver_slug;
							$created_slugs[$key] = $newSlug;
							break;
						}
					}
				}
			}
			else{
				if(!in_array($current, $created_slugs))
					$created_slugs[$key] = $newSlug;
				else{
					$tmp_t = 1;
					$current_step = 0;
					while (1){
						$ver_slug = $newSlug.'-'.$tmp_t;
						Cmwdb::$db->where($slug_field, $ver_slug);
						if(!empty(Cmwdb::$db->getOne($tbl_name))){
							$tmp_t++;
						}
						else{
							//stugel ekrord ev errord ciklerum verslugi ev last_generetedi arjeqnery
							if(in_array($ver_slug, $created_slugs)){
								$tmp_t++;
								$current_step++;
							}
							else{
								$newSlug = $ver_slug;
								$created_slugs[$key] = $newSlug;
								break;
							}
						}
					}
				}
			}
		}
		return $created_slugs;
	}
}


?>