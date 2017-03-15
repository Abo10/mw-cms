<?php

class CFrontPost /* extends CFront*/
{
    protected static $datas = array();
    public static $tbl_name = "std_post";

    function __construct()
    {
        echo "hello from front class";
    }

    static function Initial()
    {
//         self::$tbl_name = "std_post";
    }

    static function GetDatas($oid = null, $args = null, $count = 10, $page = 1)
    {
        $limit_start = ($page - 1) * $count;
        $ret = array();
        if (!is_array($args)) $args['posts'] = 1;
        if (is_numeric($oid)) Cmwdb::$db->where("pid", $oid);
        if (is_string($oid)) Cmwdb::$db->where("post_slug", $oid);
        if (is_array($oid)) Cmwdb::$db->where("pid", $oid, "in");
        Cmwdb::$db->orderBy("post_s_date");
        if (is_array($args)) {
            if (!isset($args['posts'])) $args['posts'] = 1;
            if (isset($args['posts'])) {
                Cmwdb::$db->where("post_lang", CLanguage::getInstance()->getCurrentUser());
                if (is_array($args['posts'])) {
                    if (!in_array("pid", $args['posts'])) $args['posts'][] = "pid";
                    $ret['post'] = Cmwdb::$db->get(self::$tbl_name, [$limit_start, $count], $args['posts']);
                } else $ret['post'] = Cmwdb::$db->get(self::$tbl_name, [$limit_start, $count]);

            }

// 			if(isset($args['map'])){
// 				$maps = new CMapLink();
// 			}
        } else {

            if (is_numeric($oid)) {
                Cmwdb::$db->where('pid', $oid);
                Cmwdb::$db->where("post_lang", CLanguage::getInstance()->getCurrentUser());
                $ret['post'] = Cmwdb::$db->get(self::$tbl_name, [$limit_start, $count]);
            }
            if (is_string($oid)) {
                Cmwdb::$db->where('post_slug', $oid);
                Cmwdb::$db->where("post_lang", CLanguage::getInstance()->getCurrentUser());
                $ret['post'] = Cmwdb::$db->get(self::$tbl_name, [$limit_start, $count]);
                Cmwdb::$db->where('post_slug', $oid);
                $oid = Cmwdb::$db->getValue(self::$tbl_name, 'pid');

            }
            if (is_array($oid)) {
                Cmwdb::$db->where('pid', $oid, "in");
                Cmwdb::$db->where('post_lang', CLanguage::getInstance()->getCurrentUser());
                Cmwdb::$db->where('is_active', 1);
                $ret['post'] = Cmwdb::$db->get(self::$tbl_name, [$limit_start, $count]);
            }
        }


        if (empty($ret['post'])) return array();
        $retf = array();
        if (is_array($oid) || is_null($oid)) {

            foreach ($ret['post'] as $values) {
// 				echo "Foreach for ".$values['pid']."<br>";
                $attr_list = new CAttrLinkList($values['pid'], "post");
                $attr_mas = $attr_list->GetDatas();
                $retf[$values['pid']]['post'] = $values;
                if (isset($values['post_seo'])) {
                    CFrontSeo::Initial();
                    $retf[$values['pid']]['seo_content'] = CFrontSeo::GetDatas($values['post_seo']);
                }
                if (isset($args['categories'])) $retf[$values['pid']]['categories'] = self::GetCategories($values['pid']);
                //               if (isset($args['attributes'])) $retf[$values['pid']]['attributes'] = self::GetAttributes($values['pid']);
                if (isset($args['maps'])) $retf[$values['pid']]['maps'] = self::GetMaps($values['pid']);
                if (isset($args['tags'])) {
                    $tags = new CPostToTags();
                    $tags->LoadValues($values['pid']);
                    $tlist = $tags->GetAsArray($values['pid'])['values'];
                    CFrontTags::Initial();
                    $retf[$values['pid']]['tags'] = CFrontTags::GetDatas($tlist, $args['tags']);
                }
                $retf[$values['pid']]['attributes'] = $attr_mas;
            }
        } else {
//			var_dump($ret);
            $retf['post'] = $ret['post'][0];
            $attr_list = new CAttrLinkList($oid, "post");
            $attr_mas = $attr_list->GetDatas();
//             $retf['attributes'] = $attr_mas;
            if (isset($retf['post']['post_seo'])) {
                CFrontSeo::Initial();
                $retf['post']['seo_content'] = CFrontSeo::GetDatas($retf['post']['post_seo']);
            }

            if (isset($args['categories'])) $retf['categories'] = self::GetCategories($retf['post']['pid']);
            if (isset($args['attributes'])) $retf['attributes'] = self::GetAttributes($retf['post']['pid']);
            if (isset($args['maps'])) $retf['maps'] = self::GetMaps($retf['post']['pid']);
            if (isset($args['tags'])) {
                $tags = new CPostToTags();
                $tags->LoadValues($retf['post']['pid']);
                $tlist = $tags->GetAsArray()['values'];
                CFrontTags::Initial();
                $retf['tags'] = CFrontTags::GetDatas($tlist, $args['tags']);

            }
        }


        return $retf;
    }

    static function GetCategories($oid, $args = null)
    {
        $res = array();
        $links = new CPostToCatPost();
        $links->LoadValues($oid);
        $datas = $links->GetAsArray()['values'];
        // TODO:Categorianerin anmijakan dimumi poxaren petq e dimel CFronCategoryPost funckianerin
        CFrontCategoryPost::Initial();
        return CFrontCategoryPost::GetDatas($datas, $args);
    }

    static function GetMaps($oid)
    {
        $ret = array();
        if (is_array($oid)) {
            foreach ($oid as $value) {
                $maps = new CMapLink(array("obj_id" => $value, "obj_type" => "post"));
                $ret[$value] = $maps->GetDatas();
                foreach ($ret[$value] as $key => $values) {
                    $title = json_decode($values['map_title'], true)[CLanguage::getInstance()->getCurrentUser()];
                    $ret[$value][$key]['map_title'] = $title;
                }
            }
        } else {
            $maps = new CMapLink(array("obj_id" => $oid, "obj_type" => "post"));
            $ret = $maps->GetDatas();
            foreach ($ret as $key => $values) {
                $title = json_decode($values['map_title'], true)[CLanguage::getInstance()->getCurrentUser()];
                $ret[$key]['map_title'] = $title;
            }
        }
        return $ret;
    }

    static function GetAttributes($oid)
    {
        CFrontAttrList::Initial();
        return CFrontAttrList::GetDatas($oid, "post");
    }

    static function GetMapMarkers()
    {
        Cmwdb::$db->where('ml.obj_type', 'post');
        Cmwdb::$db->where('p.post_lang', CLanguage::getInstance()->getCurrentUser());
        Cmwdb::$db->groupBy('ml.obj_id');

        Cmwdb::$db->join('std_post p', 'p.pid=ml.obj_id', 'inner');
        $tmp_data = Cmwdb::$db->get('map_link ml');
        return $tmp_data;
    }

    static function Find($string, $in_fields = null, $args = null, $count = 10, $page = 1, $assocc = null)
    {
        if (is_array($assocc)) {
            $scats = array();
            $stags = array();
            $was_in_cats = false;
            $was_in_tags = false;
            if (isset($assocc['categories'])) {
                Cmwdb::$db->where('post_cat_id', $assocc['categories'], "in");
                $cats = Cmwdb::$db->get("post_to_postCategory_links", null, ["post_id"]);
                $sval = array();
                foreach ($cats as $values) $scats[] = $values['post_id'];
                if (empty($scats)) return array("post" => []);
                $was_in_cats = true;
            }
            if (isset($assocc['tags'])) {

                Cmwdb::$db->where('tag_pid', $assocc['tags'], "in");
                $tags = Cmwdb::$db->get("tags_to_post", null, ["post_pid"]);

                $sval = array();
                foreach ($tags as $values) $stags[] = $values['post_pid'];
                if (empty($stags)) return array("post" => []);
                $was_in_tags = true;
            }
            $filtr = array();
// 			var_dump($scats);
// 			echo "<hr>";
// 			var_dump($stags);die;
            if (!empty($scats)) {
                foreach ($scats as $value) {
                    $filtr[] = $value;
                }
            }
            if (!empty($stags)) {
                foreach ($stags as $value) {
                    $filtr[] = $value;
                }
            }


            if ($was_in_cats && $was_in_tags) {
                $reformated = array();
                foreach ($filtr as $index => $value) $reformated[$value][] = $index;
                $temp_arr = array();
                foreach ($reformated as $index => $value) {
                    if (count($value) > 1) $temp_arr[] = $index;
                }
                $filtr = $temp_arr;
            }

            if (!empty($filtr)) Cmwdb::$db->where('pid', $filtr, "in");
        }
        Cmwdb::$db->where("post_lang", CLanguage::getInstance()->getCurrentUser());
        $ret["post"] = self::BaseFind($string, $in_fields, $args, $count, $page);
        $ret['page_count'] = $ret['post']['page_count'];
        unset($ret['post']['page_count']);
        $retf = array();
        foreach ($ret['post'] as $values) $retf[$values['pid']] = $values;
        $ret['post'] = $retf;
// 		$ret['page_count'] = self::GetPageCount_Filtered($string, $in_fields, $count, $assocc);
        return $ret;
    }

    static function GetPageCount_Filtered($string, $in_fields = null, $args = null, $count = 10, $page = 1, $assocc = null)
    {
        if (is_array($assocc)) {
            $scats = array();
            $stags = array();
            $was_in_cats = false;
            $was_in_tags = false;
            if (isset($assocc['categories'])) {
                Cmwdb::$db->where('post_cat_id', $assocc['categories'], "in");
                $cats = Cmwdb::$db->get("post_to_postCategory_links", null, ["post_id"]);
                $sval = array();
                foreach ($cats as $values) $scats[] = $values['post_id'];
                if (empty($scats)) return array();
                $was_in_cats = true;
            }
            if (isset($assocc['tags'])) {

                Cmwdb::$db->where('tag_pid', $assocc['tags'], "in");
                $tags = Cmwdb::$db->get("tags_to_post", null, ["post_pid"]);

                $sval = array();
                foreach ($tags as $values) $stags[] = $values['post_pid'];
                if (empty($stags)) return array();
                $was_in_tags = true;
            }
            $filtr = array();
// 			var_dump($scats);
// 			echo "<hr>";
// 			var_dump($stags);die;
            if (!empty($scats)) {
                foreach ($scats as $value) {
                    $filtr[] = $value;
                }
            }
            if (!empty($stags)) {
                foreach ($stags as $value) {
                    $filtr[] = $value;
                }
            }
            if ($was_in_cats && $was_in_tags) {
                if (!empty($filtr))
                    Cmwdb::$db->where('pid', $filtr, "in");
                foreach ($filtr as $key => $values) {
                    if (count(array_values([$values])) <= 1) {
                        unset($filtr[$key]);
                    }
                }
            }
            if (!empty($filtr)) Cmwdb::$db->where('pid', $filtr, "in");
        }
        Cmwdb::$db->where("post_lang", CLanguage::getInstance()->getCurrentUser());
        return self::BaseGetPageCount_Filtered($string, $in_fields, $count);

    }

    static function GetCats($oid)
    {
        if (is_null($oid)) return array();
        if (is_string($oid)) {
            Cmwdb::$db->where('post_slug', $oid);
            $res = Cmwdb::$db->getOne(self::$tbl_name, ["pid"]);
            $oid = $res['pid'];
        }
        $links = new CPostToCatPost();
        $links->LoadValues($oid);
        $cats = $links->GetAsArray()['values'];
        CFrontCategoryPost::Initial();
        return CFrontCategoryPost::GetCatsHierarchy($cats);
    }

    static function GetCategoriesHierarchy($oid, $args = null)
    {
        if (is_string($oid)) {
            Cmwdb::$db->where('post_slug', $oid);
            $res = Cmwdb::$db->getOne(self::$tbl_name);
            if (empty($res)) return false;
            $oid = $res['pid'];
        }
        $links = new CPostToCatPost();
        $links->LoadValues($oid);
        $res = $links->GetAsArray()['values'];
        CFrontCategoryPost::Initial();
        $res = CFrontCategoryPost::GetDatas($res, $args);
        if (empty($res)) return array();
        //Cickle for 0 level
        $ret = array();
        foreach ($res as $cid => $values) {
            if (!$values['category']['category_parent']) {
                $ret[$cid]['category_title'] = $values['category']['category_title'];
                $ret[$cid]['childs'] = array();
            }
        }
        //Second lavel
        $sec_level = array();
        foreach ($res as $cid => $values) {
            if (isset($ret[$values['category']['category_parent']])) {
                $sec_level[$cid] = $values['category']['category_parent'];
                $ret[$values['category']['category_parent']]['childs'][$cid]['category_title'] = $values['category']['category_title'];
                $ret[$values['category']['category_parent']]['childs'][$cid]['childs'] = array();
            }
        }
        //Thirth level
        foreach ($res as $cid => $values) {
            if (isset($sec_level[$values['category']['category_parent']])) {
                $ret[$sec_level[$values['category']['category_parent']]]['childs'][$values['category']['category_parent']]['childs'][$cid] = $values['category']['category_title'];
            }
        }
        return $ret;
    }

    static function BaseFind($string, $in_fields = null, $args = null, $count = 10, $page = 1)
    {
        $ret = array();
        $limit_start = ($page - 1) * $count;
        Cmwdb::$db->pageLimit = $count;
        if (is_array($in_fields)) {
            Cmwdb::$db->where($in_fields[0], '%' . $string . '%', "like");
            unset($in_fields[0]);
            foreach ($in_fields as $field_name) {
                Cmwdb::$db->orWhere($field_name, '%' . $string . '%', "like");
            }
            $ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, $page);
            // 			if(is_array($args)){
            // 				$ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name,$page);
            // // 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count], $args);
            // 			}
            // 			else{
            // 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count]);
            // 			}

        } else {
            Cmwdb::$db->where($in_fields, '%' . $string . '%', "like");
            $ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, $page);
            // 			if(is_array($args)){
            // 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count], $args);
            // 			}
            // 			else $ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count]);
        }
        $ret['page_count'] = Cmwdb::$db->totalPages;

        return $ret;
    }

    static function BaseGetPageCount_Filtered($string, $in_fields = null, $count = 10)
    {
        // 		$ret = 0;
        if (is_array($in_fields)) {
            Cmwdb::$db->where($in_fields[0], '%' . $string . '%', "like");
            unset($in_fields[0]);
            foreach ($in_fields as $field_name) {
                Cmwdb::$db->where($field_name, '%' . $string . '%', "like");
            }
            Cmwdb::$db->pageLimit = $count;
            Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, 1);
            $ret = Cmwdb::$db->totalPages;


        } else {
            Cmwdb::$db->pageLimit = $count;
            Cmwdb::$db->where($in_fields, '%' . $string . '%', "like");
            Cmwdb::$db->get(self::$tbl_name);
            Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, 1);
            $ret = Cmwdb::$db->totalPages;

        }

        return $ret;
    }

    static function GetPageCount($cat_id = null, $count = 10)
    {
        if (is_numeric($cat_id)) {
            Cmwdb::$db->where('post_lang', CLanguage::getInstance()->getCurrentUser());
        }
        Cmwdb::$db->where('is_active', 1);
        $pcount = Cmwdb::$db->getValue(self::$tbl_name, 'count(*)');
        $pagecount = $pcount / $count;
        if ($pagecount < 1) return 1;
        if (($pcount % $count) > 0) $pagecount++;
        return $pagecount;
    }

}

?>