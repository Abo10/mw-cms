<?php

class CPageProp
{
    public $datas = null;

    function __construct()
    {
        $res = Cmwdb::$db->get("pageprop_temp");
        if (!empty($res)) {
            foreach ($res as $props) {
                switch ($props['short_key']) {
                    case 's_title': {
                        $this->datas[$props['short_key']] = json_decode($props['content'], true);
                        break;
                    }
                    case 's_descr': {
                        $this->datas[$props['short_key']] = json_decode($props['content'], true);
                        break;
                    }
                    case 'simg': {
                        $this->datas[$props['short_key']] = json_decode($props['content'], true);
                        break;
                    }
                    case 'mimg': {
                    	$this->datas[$props['short_key']] = json_decode($props['content'], true);
                    	break;
                    }
                    
                    case 's_keywords': {
                        $this->datas[$props['short_key']] = json_decode($props['content'], true);
                        break;
                    }
                    default: {
                        $this->datas[$props['short_key']] = $props['content'];
                        break;
                    }
                }
            }
        }
    }


    protected function AddUpdateProp($short_key, $args)
    {
//     	echo $short_key.'<br>';
//     	var_dump($args);
//     	echo '<hr>';
        $queryData = array();
        switch ($short_key) {
            case 's_title': {
                $queryData['short_key'] = $short_key;
                $queryData['content'] = json_encode($args);
                break;
            }
            case 's_descr': {
                $queryData['short_key'] = $short_key;
                $queryData['content'] = json_encode($args);
                break;
            }
            case 's_keywords': {
                $queryData['short_key'] = $short_key;
                $queryData['content'] = json_encode($args);
                break;
            }
            case 'simg': {
                $langs = CLanguage::getInstance()->get_lang_keys_user();
                $dlang = CLanguage::getInstance()->getDefaultUser();
                foreach ($langs as $clang) {
                    if ($clang === $dlang) {
                        if (!isset($args[$clang])) $args[$clang] = null;
                    } else {
                        if (!isset($args[$clang])) $args[$clang] = $args[$dlang];
                    }
                }
// 				var_dump($args);
// 				die;
                $queryData['short_key'] = $short_key;
                $queryData['content'] = json_encode($args);

                break;
            }
            case 'mimg': {
            	$langs = CLanguage::getInstance()->get_lang_keys_user();
            	$dlang = CLanguage::getInstance()->getDefaultUser();

            	foreach ($langs as $clang) {
            		if ($clang === $dlang) {
            			if (!isset($args[$clang])) $args[$clang] = null;
            		} else {
            			if (!isset($args[$clang])) $args[$clang] = $args[$dlang];
            		}
            	}
            	// 				var_dump($args);
            	// 				die;
            	$queryData['short_key'] = $short_key;
            	$queryData['content'] = json_encode($args);
            
            	break;
            }
            
            default: {
                $queryData['short_key'] = $short_key;
                $queryData['content'] = $args;
                break;
            }
        }
        if (isset($this->datas[$short_key])) {
            Cmwdb::$db->where('short_key', $short_key);
            if (Cmwdb::$db->update("pageprop_temp", $queryData)) {
                $this->datas[$short_key] = $args;
                return true;
            } else return false;
        } else {
            if (Cmwdb::$db->insert("pageprop_temp", $queryData)) {
                $this->datas[$short_key] = $args;
                return true;
            }
        }
        return false;
    }

    function CoreInitial($args, $sargs)
    {
        $in_langs = $args;
        foreach ($sargs as $shortkey => $value) {
            $this->AddUpdateProp($shortkey, $value);
        }
        $mass = array();
        $langs = CLanguage::getInstance()->get_lang_keys_user();
        $dlang = CLanguage::getInstance()->getDefaultUser();
        foreach ($langs as $clang) {
            if (!isset($in_langs[$clang]['simg'])) $in_langs[$clang]['simg'] = null;
            if (!isset($in_langs[$clang]['simg'])) $in_langs[$clang]['simg'] = null;
            foreach ($in_langs[$clang] as $shortkey => $value) {
                if ($shortkey === "simg") $mass[$shortkey][$clang] = $value['id'];
                else{
                	if(isset($value['id']))$mass[$shortkey][$clang] = $value['id'];
                	else $mass[$shortkey][$clang] = $value;
                }
                if ($shortkey === "mimg") $mass[$shortkey][$clang] = $value['id'];
                else{
                	if(isset($value['id']))$mass[$shortkey][$clang] = $value['id'];
                	else $mass[$shortkey][$clang] = $value;
                }
            }
        }
        foreach ($mass as $shortkey => $values) {
            $this->AddUpdateProp($shortkey, $values);
        }
        return true;

    }

    function GetCoreProps()
    {
        $in_lang = array();
        $ret = array();
        if (!$this->datas) return ['lang' => []];
        foreach ($this->datas as $shortkey => $props) {
            switch ($shortkey) {
                case 's_title': {
                    foreach ($props as $lang => $value) $in_lang[$lang][$shortkey] = $value;
                    break;
                }
                case 's_descr': {
                    foreach ($props as $lang => $value) $in_lang[$lang][$shortkey] = $value;
                    break;
                }
                case 'simg': {
                    foreach ($props as $lang => $value) {
                        $in_lang[$lang][$shortkey] = $value;
                    }
                    break;
                }
                case 'mimg': {
                	foreach ($props as $lang => $value) {
                		$in_lang[$lang][$shortkey] = $value;
                	}
                	break;
                }
                
                case 's_keywords': {
                    foreach ($props as $lang => $value) {
                        $in_lang[$lang][$shortkey] = $value;
                    }
                    break;
                }
                default: {
                    $ret['slang'][$shortkey] = $props;
                    break;
                }
            }
        }
        $ret['lang'] = $in_lang;
//		var_dump($ret);die;
        return $ret;
    }

    function GetCurrentProps($lang = null)
    {
        if (!$lang) $lang = CLanguage::getInstance()->getCurrentUser();
        $ret = array();
        if (isset($this->datas["s_title"])) $ret['s_title'] = $this->datas['s_title'][$lang];
        if (isset($this->datas["s_descr"])) $ret['s_descr'] = $this->datas['s_descr'][$lang];
        if (isset($this->datas["s_keywords"])) $ret['s_keywords'] = $this->datas['s_keywords'][$lang];
        if (isset($this->datas['simg'])) {
            if (!is_null($this->datas['simg'][$lang])) {
                $img = new CAttach($this->datas['simg'][$lang]);
                $ret['simg'] = $img->GetURL("original");
            }
        }
        if (isset($this->datas['mimg'])) {
        	if (!is_null($this->datas['mimg'][$lang])) {
        		$img = new CAttach($this->datas['mimg'][$lang]);
        		$ret['mimg'] = $img->GetURL("original");
        	}
        }
        
		if(!empty($this->datas)){
	        foreach ($this->datas as $shortkey => $value) {
	            if ($shortkey === 's_title' || $shortkey === 's_descr' || $shortkey === 'simg' || $shortkey === 'mimg' || $shortkey === 's_keywords')
	                continue;
	            $ret[$shortkey] = $value;
	        }
		}
        return $ret;
    }

    function AddProps($args)
    {
        foreach ($args as $shortkey => $values) {
            $this->AddUpdateProp($shortkey, $values);
        }
        return true;
    }
    
    function GetFavicon(){
    	if(file_exists(LIB_BASE.'/../favicon.ico'))
    		return URL_BASE.'favicon.ico';
    		return false;
    }
    
    function UploadFavicon($file){
//     	var_dump($_FILES);
    	if(file_exists(URL_BASE.'favicon.ico'))
    		unlink(LIB_BASE.'/../favicon.ico');
    	return move_uploaded_file($_FILES[$file]['tmp_name'], LIB_BASE.'/../favicon.ico');
    }
    
    function DeleteFavicon(){
    	if(file_exists(URL_BASE.'favicon.ico'))
    		return unlink(URL_BASE.'favicon.ico');
    	return true;
    }
    
}

?>