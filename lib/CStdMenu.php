<?php

class CStdMenu
{
    protected $schema_id = null;
    protected $tbl_name = "std_menu_elements";
    protected $tbl_schema = "std_menus";
    protected $datas = array();
    protected $template_file = '';
    protected $schema_datas = array();
    protected $all_parents = array();
	protected $for_admin_use = array();
    function __construct($menu_id = null, $lang = null)
    {
        if (!$lang) {
            $lang = CLanguage::getInstance();
            $lang = $lang->getDefaultUser();
        }
        if ($menu_id) {
            //Initialise menu schema
            Cmwdb::$db->where('menu_id', $menu_id);
            $this->schema_datas = Cmwdb::$db->getOne($this->tbl_schema);
            Cmwdb::$db->where('m_group', $menu_id);
            Cmwdb::$db->where('lang', $lang);
            $temp = Cmwdb::$db->get($this->tbl_name);
			$this->schema_id = $menu_id;

            foreach ($temp as $value) {
                $this->datas[$value['menu_id']] = $value;
                $this->all_parents[$value['menu_pid']][] = $value['menu_id'];

            }
            $this->InitialForAdmin($menu_id);
//			echo "<pre>";
//			var_dump($this->all_parents);
//			echo "<hr>";
        }
    }
    
    function LoadByName($menu_name, $lang = null){
    	if(!$lang)$lang=CLanguage::getInstance()->getCurrentUser();
    	if (is_string($menu_name)) {
    		//Initialise menu schema
    		Cmwdb::$db->where('menu_name', $menu_name);
    		$this->schema_datas = Cmwdb::$db->getOne($this->tbl_schema);
    		Cmwdb::$db->where('m_group', $this->schema_datas['menu_id']);
    		Cmwdb::$db->where('lang', $lang);
    		$temp = Cmwdb::$db->get($this->tbl_name);
    		$this->schema_id = $this->schema_datas['menu_id'];
    	
    		foreach ($temp as $value) {
    			$this->datas[$value['menu_id']] = $value;
    			$this->all_parents[$value['menu_pid']][] = $value['menu_id'];
    	
    		}
    		$this->InitialForAdmin($this->schema_id);
  
    	} 	
    }
    
    function GetElements(){
    	return $this->datas;
    }

    function IsHaveElements(){
    	return !empty($this->datas);
    }
    
   	function CollectDOM()
    {
    	//var_dump($this->datas);die;
        $BigDom = "<span class='" . $this->schema_datas['menu_name'] . "'>";
		$BigDom.="<ul>";
        foreach ($this->all_parents[0] as $value) {
        	$title = null;
			if($this->datas[$value]['menu_text'])$title =$this->datas[$value]['menu_text'];
			else $title=CStdMenu::GetTitleIfExists(array("id"=>$this->datas[$value]['m_elem_id'], "type"=>$this->datas[$value]['m_type']))|| $title=$this->datas[$value]['menu_text'];
        	if(!$title)continue;	
           	$BigDom .= "<li class='menu_element' menu_id='" . $value . "'>";
           	if($this->datas[$value]['m_type']!=="custom_link"){
           		$BigDom.="<a href='".CUrlManager::GetURL(array("type"=>$this->datas[$value]['m_type'], "id"=>$this->datas[$value]['m_elem_id']))."'>";
           		$BigDom.=$title."</a>";
            }
           	else{
           		$BigDom.="<a href='".$this->datas[$value]['menu_url']."'>";
           		$BigDom.=$title."</a>";
           	}
            
           	if ($ret = $this->GetParentDom($value, 0)) {
           	    $BigDom .= $ret;
           	}
           	$BigDom .= "</li>";
        }
        
        $BigDom.="</ul>";
        $BigDom .= "</span>";
        return $BigDom;
    }
    
    function InitialForAdmin($menu_id){
    	Cmwdb::$db->where('m_group', $menu_id);
    	$res = Cmwdb::$db->get($this->tbl_name);
    	foreach ($res as $value){
    		$this->for_admin_use[$value['menu_id']][$value['lang']] = $value;
    	}
    	return true;
    }
	
    
    function GetText_Admin($elem_id){
    	if(isset($this->for_admin_use[$elem_id])){
    		$ret = array();
    		$temp = $this->for_admin_use[$elem_id];
   			foreach ($temp as $lang=>$mass){
   				$ret[$lang] = $mass['menu_text'];
   			}
   			return $ret;   			
    	}
    	return false;
    }
    
  	function GetParentDom($pid, $step)
    {
        if (isset($this->all_parents[$pid])) {
             $step++;
            //Do recursia
            $dom_str = "<ul class='";
            $tmp_str = "";
            for ($i = 0; $i < $step; $i++) $tmp_str .= "sub_";
            $tmp_str .= $this->schema_datas['menu_name'];
            $dom_str .= $tmp_str . "'>";
            foreach ($this->all_parents[$pid] as $value) {
                $res = $this->GetParentDom($value, $step);
                $url = "#";
                $title = null;
                if($this->datas[$value]['menu_text'])$title =$this->datas[$value]['menu_text'];
                else $title=CStdMenu::GetTitleIfExists(array("id"=>$this->datas[$value]['m_elem_id'], "type"=>$this->datas[$value]['m_type']))|| $title=$this->datas[$value]['menu_text'];
                if(!$title)continue;
                if($this->datas[$value]['m_type']!="custom_link"){
                	$url = CUrlManager::GetURL(array("type"=>$this->datas[$value]['m_type'], "id"=>$this->datas[$value]['m_elem_id']));
                }
                else{
                  	 
                	$url = $this->datas[$value]['menu_url'];
                  }
                if (!$res) {
                    $dom_str .= "<li class='menu_item' menu_id='" . $value . "'><a href='$url'>".$title. "</a></li>";
                } else {
                    $dom_str .= "<li class='menu_item' menu_id='" . $value . "'><a href='$url'>".$title. "</a></li>";
                    $dom_str .= $res;
                    $dom_str .= "</li>";
                }
            }
            $dom_str .= "</ul>";
            return $dom_str;
        }
        return false;
    }
    
    function CreateMenuSchema($menu_name){
    	if($menu_name==="" || $menu_name===null)return false;
        Cmwdb::$db->where("menu_name", $menu_name);
        $res = Cmwdb::$db->getOne($this->tbl_schema);
        if (empty($res)) {
            if (Cmwdb::$db->insert($this->tbl_schema, array("menu_name" => CSecurity::FilterString($menu_name)))) {
                $this->schema_id = Cmwdb::$db->getInsertId();
                return json_encode(array("menu_id" => $this->schema_id, "menu_name" => $menu_name, "status" => 1));
            }
        }
        return json_encode(array("status" => 0));
    }

    function InsertMenuItems($argv)
    {
        $start = time();
        $heap = $argv;
        $ter = true;
        foreach ($heap as $key => $value) {
            foreach ($value as $lang => $datas) {
                $datas['lang'] = $lang;
                $datas['menu_id'] = $key;
                if ($ter) {
                    Cmwdb::$db->where('m_group', $datas['m_group']);
                    $ret = Cmwdb::$db->getOne($this->tbl_name);
                    if (!empty($ret)) {
                        Cmwdb::$db->where('m_group', $datas['m_group']);
                        Cmwdb::$db->delete($this->tbl_name);
                    }
                    $ter = false;
                }
                if (!Cmwdb::$db->insert($this->tbl_name, $datas))
                    return false;
            }
        }
        echo time() - $start;
        return true;
    }

    function CollectDOM_admin($template_file)
    {
        //$BigDom = "<span class='".$this->schema_datas['menu_name']."'>";
        //var_dump($this->all_parents);
        //return;
        $this->template_file = $template_file;
        $this->InitialForAdmin($this->schema_id);
        foreach ($this->all_parents[0] as $value) {

            include $this->template_file;
        }
        return;
    }

    function GetParentDom_admin($pid)
    {
        $value = $pid;
        include $this->template_file;
    }
    
    function GetMenusList(){
    	$res = Cmwdb::$db->get($this->tbl_schema);
    	$ret = array();
    	
    	foreach ($res as $value){
    		$ret[] = array("menu_id"=>$value['menu_id'], "menu_name"=>$value['menu_name']);
    	}
    	return $ret;
    }
    public function getMenuByParent($parent){
        $tmp_arr = [];
        foreach($this->datas as $val){
            if($val['menu_pid']==$parent){
                $tmp_arr[] = $val;
            }
        }
        return $tmp_arr;
    }
    static function GetAnyTitle($args){
    	$lang = CLanguage::getInstance()->getDefaultUser();
		if(isset($args['lang']) && $args['lang']!==null)
			$lang = $args['lang'];
        if(is_array($args)){
            if(isset($args['type']) && isset($args['id'])){
              	$cur = array();
                switch ($args['type']){
                    case 'post':{
                        $tbl_name = "std_post";
                        Cmwdb::$db->where('pid', $args['id']);
                        $res = Cmwdb::$db->get($tbl_name, null, array("post_title", "post_lang"));
                        $ret = array();
                        
 						foreach ($res as $value){
 							if($value['post_lang']===$lang)
 								$ret["title"] = $value['post_title'];
 							$cur[$value['post_lang']] = $value['post_title'];
 						}
                        $ret['json'] = json_encode($cur);
                        return $ret;
                        break;
                    }
                    case 'post_category':{
                        $tbl_name = "std_category_post";
                        Cmwdb::$db->where('cid', $args['id']);
                        $res = Cmwdb::$db->get($tbl_name,null, array("category_title", "category_lang"));
                        $ret = array();
 						foreach ($res as $value){
 							if($value['category_lang']===$lang)
 								$ret["title"] = $value['category_title'];
 								$cur[$value['category_lang']] = $value['category_title'];
 						}
                        $ret['json'] = json_encode($cur);
 						return $ret;
                        break;
                    }
                    case 'product_category':{
                        $tbl_name = "std_category_product";
                        Cmwdb::$db->where('cid', $args['id']);
                        $res = Cmwdb::$db->get($tbl_name,null, array("category_title","category_lang"));
                        $ret = array();
                        
 						foreach ($res as $value){
 							if($value['category_lang']===$lang)
 								$ret["title"] = $value['category_title'];
 								$cur[$value['category_lang']] = $value['category_title'];
 						}
                        $ret['json'] = json_encode($cur);
 						return $ret;
                        
                        break;
                    }
                    case 'page':{
                        $tbl_name = "std_pages";
                        Cmwdb::$db->where('pid', $args['id']);
                        $res = Cmwdb::$db->get($tbl_name,null, array("page_title", "page_lang"));
                        $ret = array();
 						foreach ($res as $value){
 							if($value['page_lang']===$lang)
 								$ret["title"] = $value['page_title'];
 								$cur[$value['page_lang']] = $value['page_title'];
 						}
                        $ret['json'] = json_encode($cur);
 						return $ret;
                        
                        break;
                    }
                    default:{
                        return false;
                        break;
                    }
                }
            }
        }
        return false;
    }
    
    static function GetTitleIfExists($args){
 //   	CErrorHandling::RegisterHandle("trytitlegetting");
    	$lang = CLanguage::getInstance()->getCurrentUser();
    	if(isset($args['lang']) && $args['lang']!==null)
    		$lang = $args['lang'];
    		if(is_array($args)){
    			if(isset($args['type']) && isset($args['id'])){
    				$cur = array();
    				switch ($args['type']){
    					case 'post':{
    						$tbl_name = "std_post";
    						Cmwdb::$db->where('pid', $args['id']);
    						Cmwdb::$db->where('post_lang', $lang);
    						$res = Cmwdb::$db->getOne($tbl_name, null, array("post_title"));
    						if(!empty($res))return $res['post_title'];
    						return false;
    						break;
    					}
    					case 'post_category':{
    						$tbl_name = "std_category_post";
    						Cmwdb::$db->where('cid', $args['id']);
    						Cmwdb::$db->where('category_lang', $lang);
    						$res = Cmwdb::$db->getOne($tbl_name,null, array("category_title"));
 							if(!empty($res))return $res['category_title'];
    						return false;
    						
    						break;
    					}
    					case 'product_category':{
    						$tbl_name = "std_category_product";
    						Cmwdb::$db->where('cid', $args['id']);
    						Cmwdb::$db->where('category_lang', $lang);
    						$res = Cmwdb::$db->getOne($tbl_name, null, array("category_title"));
 							if(!empty($res))return $res['category_title'];
    						return false;
    						
    						break;
    					}
    					case 'page':{
    
    						$tbl_name = "std_pages";
    						Cmwdb::$db->where('pid', $args['id']);
    						Cmwdb::$db->where('page_lang', $lang);
							$res = Cmwdb::$db->getOne($tbl_name, null, array("page_title"));
	

 							if(!empty($res))return $res['page_title'];
    						return false;
    						
    						break;
    					}
    					default:{
    						return false;
    						break;
    					}
    				}
    			}
    		}
    		return false;
    }

    function GetMenuItemURL($m_item)
    {
        $lang = CLanguage::getInstance();
        $cur_lang = "";
        $tbl_name = "";
        if ($lang->getCurrentUser() !== $lang->getDefaultUser()) $cur_lang = $lang->getCurrentUser() . '/';
        $ret_url = URL_BASE . $cur_lang;
        if (is_array($m_item)) {
            if($m_item['jq_handle']=='home_url'){
                return ['menu_url' => CUrlManager::GetStaticURL('home'), 'menu_text' => $m_item['menu_text']];
            }

            switch ($m_item['m_type']) {
                case 'custom_link': {

                    return ['menu_url' => $m_item['menu_url'], 'menu_text' => $m_item['menu_text']];
                    break;
                }
                case 'post': {
                    $tbl_name = "std_post";
                    Cmwdb::$db->where('pid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('post_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("post_slug",'post_title'));
                    $ret_url .= $res['post_slug'];

                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['post_title'];
                    }else{
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'post_category': {
                    $tbl_name = "std_category_post";
                    Cmwdb::$db->where('cid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("slugs",'category_title'));
                    $ret_url .= "category/" . $res['slugs'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['category_title'];
                    }else{
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'product_category': {
                    $tbl_name = "std_category_product";
                    Cmwdb::$db->where('cid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("slugs",'category_title'));
                    $ret_url .= "product-category/" . $res['slugs'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['category_title'];
                    }else{
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'page': {
                    $tbl_name = "std_pages";
                    Cmwdb::$db->where('pid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('page_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("page_slug",'page_title'));
                    $ret_url .= "page/" . $res['page_slug'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['page_title'];
                    }else{
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                default: {
                    return $ret_url;
                    break;
                }
            }

        }
        return false;
    }

}

?>
