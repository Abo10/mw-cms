<?php
define("ARGUMENT_MISS", "ERR001");
class CPage{
	protected $id = null;
	protected $pid = null;
	protected $slug = "";
	protected $title = "";
	protected $descr = "";
	protected $seo = null;
	protected $gallery = array();
	protected $content = "";
	protected $lang = "";
	protected $page_isactive = false;
	protected $tbl_name = "std_pages";

	function __construct($page_slug=null){
		if($page_slug){
			Cmwdb::$db->where("page_slug",$page_slug);
			$res = Cmwdb::$db->getOne("std_pages");
			if(!empty($res)){
				$this->id = $res['page_id'];
				$this->slug = $res['page_slug'];
				$this->title = $res['page_title'];
				if(isset($res['page_descr']))$this->descr = $res['page_descr'];
				$this->content = $res['page_content'];
				if(isset($res['page_seo']))$this->seo = $res['page_seo'];
				if(isset($res['page_gallery']))$this->gallery = json_decode($res['page_gallery']);
				if(isset($res['page_lang']))$this->lang = $res['page_lang'];
				if(isset($res['pid']))$this->pid = $res['pid'];
				if(isset($res['page_isactive']))$this->page_isactive = $res['page_isactive'];
			}
		}
	}

	function CreatePage($argv){
		$ByLangs = array();
		$slugs = array();
		$lang = CLanguage::getInstance();
		while ($current = current($argv)){
 			if((!isset($current['title']) || $current['title']==="") && key($current)===$lang->getDefault())return ARGUMENT_MISS;
// 			if(!isset($current['content']))return ARGUMENT_MISS;
			if($current['seo_title']==="")$current['seo_title'] = $current['title'];
			if($current['seo_desc']===""){
 				$temp_str = strip_tags($current['content']);
 				$temp_str = preg_replace("/&#?[a-z0-9]+;/i","",$temp_str);
				$temp_str = substr($temp_str, 0, 155);
				$current['seo_desc'] = $temp_str;
			}
			if(isset($current['gallery']))$current['gallery'] = json_encode($current['gallery']);
			else $current['gallery'] = json_encode(array());
			$current['page_slug'] = CSlug::GetSlug($current['title'], key($argv));
			$slugs[key($argv)] = $current['page_slug'];
			$ByLangs[key($argv)] = $current;

			next($argv);
		}
		$slugs = CSlug::GetVerifiedSlugs($slugs, "std_pages", "page_slug");
		reset($ByLangs);
 		Cmwdb::$db->startTransaction();
		$new_pid = Cmwdb::$db->getOne("std_pages",'max(pid) pid');
		if($new_pid['pid'])$new_pid['pid']++;
		else $new_pid['pid'] = 1;
		$newSlug = "";
		$last_genereted = 0;
		$created_slugs = array();
		$exists_langs = array();
		while ($current = current($ByLangs)){
			$tmp_cur = 0;
			$exists_langs[] = key($ByLangs);
			$queryData['pid'] = $new_pid['pid'];
			$newSlug = $current['page_slug'];
			$seo = new CSeo();
			if(!$current['seo_id']=$seo->CreateSeo($current['seo_title'], $current['seo_desc'], $current['seo_keywords'])){
				$this->db->rollback();
				return false;
			}
			$queryData['page_slug']=$current['page_slug'];
			$queryData['page_title']=CSecurity::FilterString($current['title']);
			$queryData['page_descr']=null;
			$queryData['page_seo']=$current['seo_id'];
			$queryData['page_gallery']=$current['gallery'];
			$queryData['page_content']=$current['content'];
			$queryData['page_lang']=key($ByLangs);
			$queryData['page_slug'] = $slugs[key($ByLangs)];
			$queryData['page_isactive'] = 1;
			if(!Cmwdb::$db->insert("std_pages", $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
			next($ByLangs);
		}
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		//Add empty rows for missed languages of page
		foreach ($langs as $cur_lang){
			if(in_array($cur_lang, $exists_langs))continue;
			$queryData = array();
			$queryData['pid'] = $new_pid;
			$queryData['page_lang'] = $cur_lang;
			$queryData['page_isactive'] = 1;
			if(!Cmwdb::$db->insert("std_pages", $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
				
		}
		Cmwdb::$db->commit();
		return true;
	}

	function InitialPageFromArgv($argv){
// 		var_dump($argv);
	}

	function InitialPageFromDB($page_slug){

	}
	function GetPageContent(){
		return $this->content;
	}
	
	function GetAsArray(){
		if($this->pid){
			Cmwdb::$db->where('pid', $this->pid);
 			$res = Cmwdb::$db->get("std_pages");
 			$ret = array();
 			$cur_pos = 0;
 			$cur_pid = null;
 			$exists_langs = array();
 			$langs = CLanguage::getInstance()->get_lang_keys_user();
 			while($current = current($res)){
 				$exists_langs[] = $current['page_lang'];
  				$ret[$current['page_lang']]['page_id'] = $res[$cur_pos]['page_id'];
 				$ret[$current['page_lang']]['pid'] = $res[$cur_pos]['pid'];
 				$cur_pid = $res[$cur_pos]['pid'];
 				$ret[$current['page_lang']]['page_slug'] = $res[$cur_pos]['page_slug'];
 				$ret[$current['page_lang']]['page_title'] = $res[$cur_pos]['page_title'];
 				$ret[$current['page_lang']]['page_descr'] = $res[$cur_pos]['page_descr'];
 				$temp_seo = new CSeo($current['page_seo']);
 				$ret[$current['page_lang']]['seo_title'] = $temp_seo->GetTitle();
 				$ret[$current['page_lang']]['seo_descr'] = $temp_seo->GetDescr();
 				$ret[$current['page_lang']]['seo_keywords'] = $temp_seo->GetKeywords();
 				$ret[$current['page_lang']]['page_gallery'] = $res[$cur_pos]['page_gallery'];
 				$ret[$current['page_lang']]['page_content'] = $res[$cur_pos]['page_content'];
 				$ret[$current['page_lang']]['page_isactive'] = $res[$cur_pos]['page_isactive'];
 				$cur_pos++;
 				next($res);
 			}
 			foreach ($langs as $cur_lang){
 				if(in_array($cur_lang, $exists_langs))continue;
//  				var_dump($cur_lang);die;
 				$ret[$cur_lang]['pid'] = $cur_pid;
 				$ret[$cur_lang]['page_lang'] = $cur_lang;
 				$ret[$cur_lang]['page_isactive'] = 1;
 				
 			
 			}
 			return $ret;
		}
		return false;
	}
	
	function GetAsArrayPID($pid){
		$this->pid = $pid;
		return $this->GetAsArray();
	}
	
	function InitialByPID($pid){
		/*
		 * TODO: Do it is very important
		 */
	}
	function EditPage($argv, $pid){
		$langs = CLanguage::getInstance()->get_lang_keys_user();
		Cmwdb::$db->where('pid', $pid);
		$exists_langs = Cmwdb::$db->get($this->tbl_name, null, ['page_lang']);
		$tmp = [];
		foreach ($exists_langs as $vals)$tmp[] = $vals['page_lang'];
		$exists_langs = $tmp;
		$missing_langs = [];
		foreach ($langs as $compaer_lang){
			$tmp_seo = new CSeo();
			if(!in_array($compaer_lang, $exists_langs))
				$missing_langs[] = $compaer_lang;
		}
		$tmp_query = [];
		foreach ($missing_langs as $adding_lang){
			$tmp_query = [];
			$tmp_query['page_seo'] = $tmp_seo->CreateSeo("", "");
			$tmp_query['pid'] = $pid;
			$tmp_query['page_lang'] = $adding_lang;
			if(isset($argv[$adding_lang]) && isset($argv[$adding_lang]['page_title']) && $argv[$adding_lang]['page_title']!==""){
				$converted_title = CSlug::GetSlug($argv[$adding_lang]['page_title']);
				$converted_title = CSlug::GetVerifiedSlugs([$converted_title], $this->tbl_name,'page_slug');
				$tmp_query['page_slug'] = $converted_title;
			}
			Cmwdb::$db->insert($this->tbl_name, $tmp_query);
		}
		Cmwdb::$db->startTransaction();
		while($current = current($argv)){
			$queryData['page_title'] = CSecurity::FilterString($current['title']);
			$queryData['page_content'] = $current['content'];
			$queryData['page_isactive'] = 1;
			Cmwdb::$db->where('pid', $pid);
			Cmwdb::$db->where("page_lang", key($argv));
			$res = Cmwdb::$db->getOne("std_pages", 'page_seo');
			if(empty($res))return false;
			$temp_seo = new CSeo($res['page_seo']);
			if(!$temp_seo->UpdateDatas($current['seo_title'], $current['seo_desc'], $current['seo_keywords'])){
				Cmwdb::$db->rollback();
				return false;
			}
			if(isset($current['gallery']))$queryData['page_gallery'] = json_encode($current['gallery']);
			else $queryData['page_gallery'] = json_encode(array());
			Cmwdb::$db->where('pid', $pid);
			Cmwdb::$db->where("page_lang", key($argv));
			if(!Cmwdb::$db->update("std_pages", $queryData)){
				Cmwdb::$db->rollback();
				return false;
			}
			next($argv);
		}
		Cmwdb::$db->commit();
		return true;
	}

	function GetMaxID_Gallery(){
		Cmwdb::$db->where('pid', $this->pid);
		$res = Cmwdb::$db->get("std_pages", null, 'page_gallery');
		$max_id = 0;
		if(!empty($res)){
			foreach ($res as $value){
				$value = json_decode($value['page_gallery'], true);
				foreach ($value as $key=>$ids){
					if($key>$max_id)$max_id = $key;
				}
			}
			return  $max_id;
		}
		
		return false;
	}
	
	function ChangePageStatus($status=true, $lang=null, $for_pid=null){
		if(!$lang){
			$def_lang = CLanguage::getInstance();
			$lang = $def_lang->getDefault();
		}
		if(!$for_pid)$for_pid=$this->pid;
		if(!$for_pid)return false;
		Cmwdb::$db->where('pid', $for_pid);
		Cmwdb::$db->where('page_lang', $lang);
		if(Cmwdb::$db->update("std_pages", array('page_isactive'=>$status)))return true;
		return false;
		
	}
	function Publish($pid){
		if(is_array($pid)){
			Cmwdb::$db->where('pid',$pid,"in");
			if(Cmwdb::$db->update("std_pages", array('page_isactive'=>1)))
				return true;
		}
		if(is_numeric($pid)){
			Cmwdb::$db->where('pid', $pid);
			if(Cmwdb::$db->update("std_pages", array('page_isactive'=>1)))
				return true;
		}
		return false;
	}
	function Passive($pid){
		if(is_array($pid)){
			Cmwdb::$db->where('pid',$pid,"in");
			if(Cmwdb::$db->update("std_pages", array('page_isactive'=>0)))
				return true;
		}
		if(is_numeric($pid)){
			Cmwdb::$db->where('pid', $pid);
			if(Cmwdb::$db->update("std_pages", array('page_isactive'=>0)))
				return true;
		}
		return false;
	}
	function Delete($pid){
		if(is_array($pid)){
			Cmwdb::$db->where('pid',$pid,"in");
			if(Cmwdb::$db->delete("std_pages"))
				return true;
		}
		if(is_numeric($pid)){
			Cmwdb::$db->where('pid', $pid);
			if(Cmwdb::$db->delete("std_pages"))
				return true;
		}
		return false;

	}
	
	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('pid', $id);
			Cmwdb::$db->where('page_lang', $lang);
			if(!$type)$type = 'page';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'page_slug');
				
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('page_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'page_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
	
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('page_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['page_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('page_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['page_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
	

}
?>