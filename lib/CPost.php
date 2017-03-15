<?php

class CPost
{
    protected $post_id = null;
    protected $pid = null;
    protected $post_title = "";
    protected $post_content = "";
    protected $post_descr = "";
    protected $post_img = null;
    protected $post_files = array();
    protected $post_gallery = array();
    protected $post_covers = array();
    protected $post_slug = "";
    protected $post_seo = null;
    protected $post_category = null;
    protected $post_i_date = null;
    protected $post_s_date = null;
    protected $post_status = false;
    protected $post_lang = null;
    protected $tbl_name = "std_post";
    protected $tags_list = array();

    function __construct($slug = null)
    {

    }

    function CreatePost($argv)
    {
    	$maps = null;
    	if(isset($argv['map'])){
    		$maps = $argv['map'];
    		unset($argv['map']);
    	}
    	
        Cmwdb::$db->startTransaction();
        $pid = Cmwdb::$db->getOne($this->tbl_name, 'max(pid) pid');
        if ($pid['pid']) $pid['pid']++;
        else $pid['pid'] = 1;
        $pid = $pid['pid'];
        $slugs = array();
        while ($cur_slug = current($argv)) {
            $slugs[key($argv)] = CSlug::GetSlug($cur_slug['post_title']);
            next($argv);
        }
        reset($argv);
        $slugs = CSlug::GetVerifiedSlugs($slugs, $this->tbl_name, "post_slug");
        $lang = CLanguage::getInstance();
        $attribs = array();
        $ATTR_VALUES = array();
        $attr_templates = array();
        while ($current = current($argv)) {
            $queryData = array();
            //verify important datas
            if (!isset($current['post_title']) && key($current) === $lang->getDefault() && $current['post_title']=="") {
                Cmwdb::$db->rollback();
                return false;
            }
            $queryData['post_title'] = CSecurity::FilterString($current['post_title']);
            $cur_seo = null;
            //creating seo datas
            if ($current['seo_title'] !== "") $cur_seo['seo_title'] = $current['seo_title'];
            else $cur_seo['seo_title'] = $current['seo_title'];
            if ($current['seo_descr'] !== "") $cur_seo['seo_descr'] = $current['seo_descr'];
            else {
                $temp_str = strip_tags($current['post_content']);
                $temp_str = preg_replace("/&#?[a-z0-9]+;/i", "", $temp_str);
                $temp_str = substr($temp_str, 0, 155);
                $cur_seo['seo_descr'] = $temp_str;
            }
            $cur_seo['seo_keywords'] = $current['seo_keywords'];
            //creating seo
            $temp_seo = new CSeo();
            if (!$queryData['post_seo'] = $temp_seo->CreateSeo($cur_seo['seo_title'], $cur_seo['seo_descr'], $cur_seo['seo_keywords'])) {
                Cmwdb::$db->rollback();
                return false;
            }
            //creating slug
            $queryData['post_slug'] = $slugs[key($argv)];
            /*
             * FIXME: seo must be verified in base
             */
            $queryData['pid'] = $pid;
            $queryData['post_lang'] = key($argv);
            $queryData['post_content'] = $current['post_content'];
            $queryData['post_descr'] = $current['post_descr'];
            if ((!isset($current['post_img']['id'])) || $current['post_img']['id'] === "") $current['post_img']['id'] = 0;
            $queryData['post_img'] = $current['post_img']['id'];
            if ((!isset($current['post_img']['title'])) || $current['post_img']['title'] === "") $current['post_img']['title'] = '';
            $queryData['post_img_title'] = $current['post_img']['title'];
            if ((!isset($current['post_category'])) || $current['post_category'] === "") $queryData['post_category'] = 0;
            else {
                foreach ($current['post_category'] as $value) {
                    $queryData['post_category'] = $value;
                    break;
                }
            }
            if ((!isset($current['post_covers'])) || $current['post_covers'] === "") $current['post_covers'] = array();
            $queryData['post_covers'] = json_encode($current['post_covers']);
            if ((!isset($current['post_gallery'])) || $current['post_gallery'] === "") $current['post_gallery'] = array();
            $queryData['post_gallery'] = json_encode($current['post_gallery']);
            if ((!isset($current['post_files'])) || $current['post_files'] === "") $current['post_files'] = array();
            $queryData['post_files'] = json_encode($current['post_files']);
            $queryData['post_i_date'] = time();
            if (isset($current['post_date']) && !empty($current['post_date'])) {
                $queryData['post_s_date'] = strtotime($current['post_date']);
            } else {
                $queryData['post_s_date'] = time();
            }
            if (isset($current['post_status']) && $current['post_status'] == 1) {
                $queryData['post_status'] = 1;
            } else {
                $queryData['post_status'] = 0;
            }
            if (!Cmwdb::$db->insert($this->tbl_name, $queryData)) {
                Cmwdb::$db->rollback();
                return false;
            }
            if(!empty($current['post_attr_title']))$ATTR_VALUES[key($argv)] = $current['post_attr_title'];
            if(!empty($current['post_attr']))$attr_templates[key($argv)] = $current['post_attr'];
            next($argv);
        }

        $all_langs = CLanguage::getInstance()->get_lang_keys_user();
        $values = $attr_templates[CLanguage::getInstance()->getDefaultUser()];
		foreach ($values as $index=>$tmpl_id){
			$temp_array = array();
			$temp_array['template_id'] = $tmpl_id;
			foreach ($all_langs as $lang_codes){
				$temp_array['attr_values'][$lang_codes] = $ATTR_VALUES[$lang_codes][$index];
			}
			$temp_array['obj_id'] = $pid;
			$temp_array['obj_type'] = "post";
			$links = new CAttrLinkL();
 			$links->CreateLink($temp_array);
		}

        $tmp_links = new CPostToCatPost();
        foreach ($argv as $value) {
        	if(isset($value['post_category']))
	            $tmp_links->AddLinks($pid, $value['post_category']);
            break;
        }
        $lang = CLanguage::getInstance();
        $tag_temp = new CPostToTags();
        CErrorHandling::RegisterHandle("test_for_now");
//         echo "BRO: " . $pid . "<hr>";
        if(isset($argv[$lang->getDefault()]['tag_list'])){
        	$tag_temp->AddLinks($pid, $argv[$lang->getDefault()]['tag_list']);
        	$this->tags_list = $argv[$lang->getDefault()]['tag_list'];
        }
		$maps['obj_id'] = $pid;
		$maps['obj_type'] = "post";
		$cmap = new CMapLink();
		$cmap->CreateMapLinks($maps);
		
		CUserAdmin::Initial();
 		CUserAdmin::CreateLink($pid, "post");
        Cmwdb::$db->commit();
        return true;
    }

    function GetYourType()
    {
        return get_class();
    }

    function GetAsArray()
    {
        if ($this->pid) {
            Cmwdb::$db->where('pid', $this->pid);
            $res = Cmwdb::$db->get($this->tbl_name);
            $ret = array();
            $cur_pos = 0;
            $attr_list = new CAttrLinkList($this->pid, "post");
            $attr_mas = $attr_list->GetDatas();
            $exists_langs = array();
            $langs = CLanguage::getInstance()->get_lang_keys_user();
            
            while ($current = current($res)) {
            	$exists_langs[] = $current['post_lang'];
            	 
                foreach ($current as $key => $value) {
                    $ret[$current['post_lang']][$key] = $value;
                    if ($key === "post_seo") {
                        $temp_seo = new CSeo($current['post_seo']);
                        $ret[$current['post_lang']]['seo_title'] = $temp_seo->GetTitle();
                        $ret[$current['post_lang']]['seo_descr'] = $temp_seo->GetDescr();
                        $ret[$current['post_lang']]['seo_keywords'] = $temp_seo->GetKeywords();
                    }
                }
                $ret[$current['post_lang']]['post_files'] = json_decode($ret[$current['post_lang']]['post_files'], true);
                $ret[$current['post_lang']]['post_covers'] = json_decode($ret[$current['post_lang']]['post_covers'], true);
                $ret[$current['post_lang']]['post_gallery'] = json_decode($ret[$current['post_lang']]['post_gallery'], true);
                next($res);
            }
            $ret['post_attributes'] = $attr_mas;
            
            $tags = new CPostToTags();
            $tags->LoadValues($this->pid);
            $ret['post_tags'] = $tags->GetAsArray()['values'];
            if(is_null($ret['post_tags']))$ret['post_tags'] = array();
			$ret['map'] = array();
			$maps = new CMapLink(array("obj_id"=>$this->pid, "obj_type"=>"post"));
			$ret['map'] = $maps->GetDatas();
			foreach ($langs as $cur_lang){
				if(in_array($cur_lang, $exists_langs))continue;
				//  				var_dump($cur_lang);die;
				$ret[$cur_lang]['pid'] = $this->pid;
				$ret[$cur_lang]['post_lang'] = $cur_lang;
				$ret[$cur_lang]['is_active'] = 1;
					
			
			}
				
 
            return $ret;
        }
        return false;
    }

    function GetAsArrayPID($pid)
    {
        $this->pid = $pid;
        $res = $this->GetAsArray();
        $cats = new CPostToCatPost();
        $cats->LoadValues($this->pid);
        $cats = $cats->GetAsArray();
        $res['category'] = $cats['values'];

        return $res;

    }

    function GetList_Title()
    {
        $res = Cmwdb::$db->get($this->tbl_name);
        $ret = array();
        foreach ($res as $value) {
            $ret[$value['pid']][$value['post_lang']]['post_title'] = $value['post_title'];
        }
        return $ret;
    }

    function EditPost($args, $pid)
    {
    	$langs = CLanguage::getInstance()->get_lang_keys_user();
    	Cmwdb::$db->where('pid', $pid);
    	$exists_langs = Cmwdb::$db->get($this->tbl_name, null, ['post_lang']);
    	$tmp = [];
    	foreach ($exists_langs as $vals)$tmp[] = $vals['post_lang'];
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
    		$tmp_query['post_seo'] = $tmp_seo->CreateSeo("", "");
    		$tmp_query['pid'] = $pid;
    		$tmp_query['post_lang'] = $adding_lang;
    		if(isset($argv[$adding_lang]) && isset($argv[$adding_lang]['post_title']) && $argv[$adding_lang]['post_title']!==""){
    			$converted_title = CSlug::GetSlug($argv[$adding_lang]['post_title']);
    			$converted_title = CSlug::GetVerifiedSlugs([$converted_title], $this->tbl_name,'post_slug');
    			$tmp_query['post_slug'] = $converted_title;
    		}
    		Cmwdb::$db->insert($this->tbl_name, $tmp_query);
    	}
    	 
    	$maps = null;
    	if(isset($args['map'])){
    		$maps = $args['map'];
    		unset($args['map']);
    	}
        $lang = CLanguage::getInstance();
        Cmwdb::$db->startTransaction();
        $old_version = $this->GetAsArrayPID($pid);
        $new_slugs = array();
        foreach ($args as $key => $value) {
            $new_slugs[$key] = CSlug::ConvertToEnglish($value['post_title']);
        }
// 		var_dump($old_version);die;
        $new_slugs = CSlug::GetVerifiedSlugs($new_slugs, $this->tbl_name, "post_slug");
        $iter = 0;
        $lang = CLanguage::getInstance();
        $attribs = array();
        $ATTR_VALUES = array();
        $attr_templates = array();
        
        foreach ($args as $key => $value) {
            if(!isset($value['post_title']) && $key === $lang->getDefault()) {
                Cmwdb::$db->rollback();
                return false;
            }
            $queryData = array();
            $queryData['post_title'] = CSecurity::FilterString($value['post_title']);
            $queryData['post_content'] = $value['post_content'];
            $queryData['post_descr'] = CSecurity::FilterString($value['post_descr']);
            $tmp_seo = new CSeo($old_version[$key]['post_seo']);
            $tmp_seo->UpdateDatas($value['seo_title'], $value['seo_descr'], $value['seo_keywords']);
            if ($value['post_date'] !== 0 || $value['post_date'] !== "") $queryData['post_s_date'] = strtotime($value['post_date']);
            (isset($value['post_status']))?$queryData['post_status'] = $value['post_status']:null;
            if ($old_version[$key]['post_slug'] === "") $queryData['post_slug'] = $new_slugs[$key];
            if (isset($value['post_gallery'])) $queryData['post_gallery'] = json_encode($value['post_gallery']);
            else $queryData['post_gallery'] = json_encode(array());

            if (isset($value['post_covers'])) $queryData['post_covers'] = json_encode($value['post_covers']);
            else $queryData['post_covers'] = json_encode(array());
            if (isset($value['post_files'])) $queryData['post_files'] = json_encode($value['post_files']);
            else $queryData['post_files'] = json_encode(array());
            if (isset($value['post_img'])) {
                $queryData['post_img'] = $value['post_img']['id'];
                $queryData['post_img_title'] = $value['post_img']['title'];
            }
            Cmwdb::$db->where('pid', $pid);
            Cmwdb::$db->where('post_lang', $key);
            if (!Cmwdb::$db->update($this->tbl_name, $queryData)) {
                echo "Something was go is not sucsesful<br>";
                Cmwdb::$db->rollback();
                return false;
            }
            if (isset($value['post_category'])) {
                $cats = new CPostToCatPost();
                $cats->LoadValues($pid);
                $cats->DeleteThis();
                $cats->AddLinks($pid, $value['post_category']);
            }
            else{
            	$cats = new CPostToCatPost();
            	$cats->LoadValues($pid);
            	$cats->DeleteThis();
            }
            if (isset($value['tag_list'])) {
                $tags = new CPostToTags();
                $tags->LoadValues($pid);
                $tags->DeleteThis();
                $tags->AddLinks($pid, $value['tag_list']);
            }
            if(isset($value['post_attr_title']))$ATTR_VALUES[$key] = $value['post_attr_title'];
            if(isset($value['post_attr']))$attr_templates[$key] = $value['post_attr'];
            
            $iter++;
        }
        $atr_list = new CAttrLinkList();
        $atr_list->DeleteAssociations($pid, "post");
		$all_langs = CLanguage::getInstance()->get_lang_keys_user();
        $values = $attr_templates[CLanguage::getInstance()->getDefaultUser()];
        foreach ($values as $index=>$tmpl_id){
        	$temp_array = array();
        	$temp_array['template_id'] = $tmpl_id;
        	foreach ($all_langs as $lang_codes){
        		$temp_array['attr_values'][$lang_codes] = $ATTR_VALUES[$lang_codes][$index];
        	}
        	$temp_array['obj_id'] = $pid;
        	$temp_array['obj_type'] = "post";
         	$links = new CAttrLinkL();
         	$links->CreateLink($temp_array);
        }
        $maps['obj_id'] = $pid;
        $maps['obj_type'] = "post";
        $cmap = new CMapLink();
        $cmap->DeleteLinks($maps['obj_id'], $maps['obj_type']);
        $cmap->CreateMapLinks($maps);
        
        Cmwdb::$db->commit();
        return true;
    }

    //$isactive 2=all   1=active not_active=0
    function GetElementsPage($lang, $limit = 20, $page = 1, $cat_id = null, $search = null, $is_active = 2){
        if ($cat_id) {
            Cmwdb::$db->where('c.post_cat_id', $cat_id);
            Cmwdb::$db->join('post_to_postCategory_links c', 'c.post_id=p.pid', 'inner');
        }

        if ($search) {
            Cmwdb::$db->where('p.post_title', "%" . $search . "%", 'like');
        }
        if($is_active == 1){
            Cmwdb::$db->where('p.is_active',1);
        }
        if($is_active == 0){
            Cmwdb::$db->where('p.is_active',0);
        }
        Cmwdb::$db->where('p.post_lang', $lang);

        Cmwdb::$db->orderBy('p.post_s_date');

        Cmwdb::$db->groupBy('p.pid');
        //$a = Cmwdb::$db->get('std_post p',null);
        Cmwdb::$db->pageLimit = $limit;
        $a = Cmwdb::$db->arraybuilder()->paginate($this->tbl_name." p", $page);
        $ret_arr['total_pages']=Cmwdb::$db->totalPages;

        Cmwdb::$db->groupBy('post_lang');
        $ret_arr['total_all']=Cmwdb::$db->getValue($this->tbl_name, "count(*)");

        Cmwdb::$db->where('is_active', 1);
        Cmwdb::$db->groupBy('post_lang');
        $ret_arr['total_active']=Cmwdb::$db->getValue($this->tbl_name, "count(*)");


        Cmwdb::$db->groupBy('post_lang');
        Cmwdb::$db->where('is_active', 0);
        $ret_arr['total_passive']=Cmwdb::$db->getValue($this->tbl_name, "count(*)");
        $new_array = [];
        $def_lang = CLanguage::getInstance()->getDefaultUser();
        foreach($a as $item ){
            Cmwdb::$db->where('c.post_id', $item['pid']);
            Cmwdb::$db->where('cp.category_lang', $lang);
            Cmwdb::$db->join('post_to_postCategory_links c', 'c.post_cat_id=cp.cid', 'inner');
            $cats =  Cmwdb::$db->get('std_category_post cp',null,array('cid','category_title'));
            Cmwdb::$db->where('pid', $item['pid']);
            $post_status = Cmwdb::$db->get('std_post', null,array('post_status','post_lang'));
            $item['categories'] = $cats;
            $item['post_status'] = $post_status;
            if(!empty($item['post_title'])){
                $item['is_translated'] = true;
                $new_array[] = $item;
            }else{
                Cmwdb::$db->where('pid',$item['pid'] );
                Cmwdb::$db->where('post_lang',$def_lang);
                $new_title = Cmwdb::$db->getOne($this->tbl_name)['post_title'];
                $item['is_translated'] = false;
                $item['post_title'] = $new_title;
                $new_array[] = $item;

            }
        }
        $ret_arr['data'] = $new_array;
        return $ret_arr;

    }
    
    function Publish($pid){
    	if(is_array($pid)){
    		$status = null;
    		Cmwdb::$db->where('pid',$pid,"in");
    		$res = Cmwdb::$db->get($this->tbl_name,null, array("pid","is_active"));
    		foreach ($res as $value){
    			if($value['is_active']==1)$status = 0;
    			else $status = 1;
    			Cmwdb::$db->where('pid', $value['pid']);
    			Cmwdb::$db->update($this->tbl_name, array('is_active'=>$status));
    		}
   			return true;
    	}
    	if(is_numeric($pid)){
    		Cmwdb::$db->where('pid', $pid);
    		$res = Cmwdb::$db->getOne($this->tbl_name);
    		$status = null;
    		if($res['is_active']==1)$status = 0;
    		else $status = 1;
    		if(Cmwdb::$db->update($this->tbl_name, array('is_active'=>$status)))
    			return true;
    	}
    	return false;
    }
    function Passive($pid){
        if(is_array($pid)){
            Cmwdb::$db->where('pid',$pid,"in");
            if(Cmwdb::$db->update($this->tbl_name, array('is_active'=>0)))
                return true;
        }
        if(is_numeric($pid)){
            Cmwdb::$db->where('pid', $pid);
            if(Cmwdb::$db->update($this->tbl_name, array('is_active'=>0)))
                return true;
        }
        return false;
    }
    function Delete($pid){
    	if(is_array($pid)){
    		Cmwdb::$db->where('pid',$pid,"in");
    		if(Cmwdb::$db->delete($this->tbl_name))
    			return true;
    	}
    	if(is_numeric($pid)){
    		Cmwdb::$db->where('pid', $pid);
    		if(Cmwdb::$db->delete($this->tbl_name))
    			return true;
    	}
    	return false;
    	 
    }

	function UpdateSlug($id, $lang, $new_slug, $type=null){
		try {
			Cmwdb::$db->where('pid', $id);
			Cmwdb::$db->where('post_lang', $lang);
			if(!$type)$type = 'post';
			$old_slug = Cmwdb::$db->getValue($this->tbl_name, 'post_slug');
			
			$new_slug = CSlug::ConvertToEnglish($new_slug);
			if(is_numeric($new_slug))$new_slug = 'stdslug-'.$new_slug;
			Cmwdb::$db->where('post_slug', $new_slug);
			if(Cmwdb::$db->getValue($this->tbl_name, 'post_slug'))throw new Exception('The new slug exists in db.',1);
			if($old_slug){
				
				Cmwdb::$db->startTransaction();
				if(!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)){
					Cmwdb::$db->rollback();
					throw new Exception("Cant create redirect",2);
				}
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('post_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['post_slug'=>$new_slug])){
					Cmwdb::$db->rollback();
					throw new Exception("Error, cant insert new slug into post table",3);
				}
				Cmwdb::$db->commit();
				return ['status'=>1,'message'=>$new_slug];
			}
			else {
				Cmwdb::$db->where('pid', $id);
				Cmwdb::$db->where('post_lang', $lang);
				if(!Cmwdb::$db->update($this->tbl_name, ['post_slug'=>$new_slug]))throw new Exception('Error: Cant insert new slug into post table');
				return ['status'=>1,'message'=>$new_slug];
			}
		}
		catch (Exception $error){
			return ['status'=>0,'message'=>$error->getMessage()];
		}
	}
}

?>