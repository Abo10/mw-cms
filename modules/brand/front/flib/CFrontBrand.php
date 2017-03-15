<?php
/**
 * Created by PhpStorm.
 * User: Rafik Rushanian
 * Date: 4/10/2016
 * Time: 9:40 PM
 */

class CFrontBrand extends CMFront{

    static $tbl_name = "std_brands";
	function __construct(){
		self::Initial();
	}
    static function Initial(){
        // TODO: Implement Initial() method.
        self::$tbl_name = "std_brands";
        self::$configs = CModule::TakeConfigs('brand');
    }

    /*
     * $assoc_props can be array, where we can have for example index product and defined sub index like page=>1 count=>15
     * $lang if null, will define as current user language
     * $assocs - can be array, where index is subject type, if it defined, will return array of links, where subject type
     *           is index of array and s_link is defined in $oid
     * $oid - as standard, we have 3 variant of argument
     *          numeric - its group id
     *          string - slug of type(language is very important to find object in db
     *          array - in array we have numeric values, that is group ids
     */
    static function GetDatas($oid = null, $assocs = null, $lang = null, $page = 1, $count = 20, $assoc_props = null){
        if(!$lang)$lang = CLanguage::getInstance()->GetCurrentUser();
        if(is_string($oid)){
            Cmwdb::$db->where('brand_slug', $oid);
            $oid = Cmwdb::$db->getValue(self::$tbl_name, 'brand_group');
        }
        if(is_numeric($oid))Cmwdb::$db->where('brand_group', $oid);
        if(is_array($oid))Cmwdb::$db->where('brand_group', $oid, "in");
        Cmwdb::$db->where('brand_lang', $lang);
        $brands = Cmwdb::$db->get(self::$tbl_name);
        if(is_null($oid)){
            foreach($brands as $values)$oid[] = $values['brand_group'];
        }
        $mass = array();
        $big_boom = array();
        if(is_array($assocs)){
            $links = CModule::LoadComponent('brand', 'product_links');
            $assocs_id = array();
            $big_boom = array();
            foreach($assocs as $subject_type=>$unneed){
                $mass[$subject_type] = $links->GetLinks(null, $subject_type, $oid, false);
                foreach($mass[$subject_type] as $subject_group=>$unneed){
                    $assocs_id[$subject_type][] = $subject_group;
                }
                $big_boom['brand_links'][$subject_type] = $links->GetLinks(null, $subject_type, $oid, false);
                
            }
            
            foreach($assocs_id as $subject_type=>$subject_ids){
            	$obj = CModule::LoadModuleFront($subject_type);
            	if(is_object($obj)){
            		$big_boom[$subject_type] = $obj->GetDatas($subject_ids);
            	}
            }
        }
        $ret = array();
        if(is_array($oid) || is_null($oid)){
            foreach($brands as $values){
                $ret['brand'][$values['brand_group']] = $values;
            }
            $ret = array_merge($ret, $big_boom);
        }
        else{
        	if(isset($brands[0]))
            	$ret['brand'] = $brands[0];
        	else $ret['brand'] = [];
            $ret = array_merge($ret, $big_boom);
        }
       return $ret;
    }
    
    static function GetLinksExt($links, $subject_type){
    	$linksext = CModule::LoadComponent('brand', 'product_links');
//     	var_dump($links->GetLinks(null, 'product', null, false));
    	if(is_object($linksext)){
    		$ext_links = $linksext->GetLinks(null, $subject_type, $links, false);
    		$ext = array();
    		foreach ($ext_links as $brand_group=>$unneed)$ext[] = $brand_group;
    		
    		$ret = self::GetDatas($ext);
    		$ret['brand']['brand_links'] = $linksext->GetLinks(null, $subject_type, $links, true);
    		return $ret;
    	}
    }
}

?>