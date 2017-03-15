<?php

class CFrontCategoryPost extends CFrontCategory
{
    static $datas = array();
    static $tbl_name = "std_category_post";

    static function Initial()
    {
        self::$tbl_name = "std_category_post";
    }

    static function GetAllPosts($oid = null, $args = null, $count = 10, $page = 1)
    {
// 		CErrorHandling::RegisterHandle("for pagination");
        if (!$oid) return array();
        if (is_string($oid)) {
            Cmwdb::$db->where('slugs', $oid);
            Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
            $res = Cmwdb::$db->getOne(self::$tbl_name, array("cid"));
            if (empty($res)) {
                return array();
            } else $oid = $res['cid'];
        }
        if (is_array($args) && is_array($args['posts'])) {
            if (!in_array("pid", $args['posts'])) $args['posts'][] = "pid";
        }

        $post_links = new CPostToCatPost();
        $posts = $post_links->GetBySLink($oid);
        if (!empty($posts)) {
            $ret = array();
            //		$config['post'] = 1;
            $res = array();
            CFrontPost::Initial();
            if (is_array($args)) {
                $args['post'] = $args;
                $res = CFrontPost::GetDatas($posts, $args, $count, $page);
            } else $res = CFrontPost::GetDatas($posts, null, $count, $page);
            return $res;
        }
        return array();
    }

    static function GetParentsCats()
    {
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
        Cmwdb::$db->where('category_parent', 0);
        return Cmwdb::$db->get(self::$tbl_name);
    }

    static function GetPageCount($oid, $count = 10)
    {
        $post = new CPostToCatPost;
        $pcount = $post->GetBySLink($oid);
        $pcount = count($pcount);
        $pagecount = $pcount / $count;
        if ($pagecount < 1) return 1;
        if (($pcount % $count) > 0) $pagecount++;
        return $pagecount;
    }

    static function GetCatsHierarchy($oid)
    {
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
        $ret = array();
        $res = array();
        Cmwdb::$db->where('category_parent', 0);
        if (is_array($oid))
            Cmwdb::$db->where('cid', $oid, "IN");
        else Cmwdb::$db->where('cid', $oid);
        $par_cat = Cmwdb::$db->getOne(self::$tbl_name, ["cid", 'category_parent', 'category_title']);

        Cmwdb::$db->where('category_parent', $par_cat['cid']);
        if (is_array($oid))
            Cmwdb::$db->where('cid', $oid, "IN");
        else Cmwdb::$db->where('cid', $oid);
        $child_cat = Cmwdb::$db->getOne(self::$tbl_name, ["cid", 'category_parent', 'category_title']);

        return array(0 => $par_cat, 1 => $child_cat);
    }

    static function GetCatsTree($oid)
    {
        $ret_arr = [];

        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
        Cmwdb::$db->where('cid', $oid);

        $data = Cmwdb::$db->getOne(self::$tbl_name);
        //$ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];


        while (isset($data['category_parent']) && $data['category_parent'] !== 0) {

            $ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];

            Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
            Cmwdb::$db->where('cid', $data['category_parent']);

            $data = Cmwdb::$db->getOne(self::$tbl_name);
        }

        $ret_arr[] = ['cid' => $data['cid'], 'title' => $data['category_title']];

        return array_reverse($ret_arr);
    }

    static function Find($string, $in_fields = null, $args = null, $count = 10, $page = 1)
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

    static function GetPageCount_Filtered($string, $in_fields = null, $count = 10)
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

    static function GetDatas($oid = null, $args = null)
    {
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());

        $ret = array();
        if (!$oid) {
            if (is_array($args['categories'])) {
                if (!in_array("cid", $args['categories'])) $args['categories'][] = "cid";
                $ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
            } else {
                $ret = Cmwdb::$db->get(self::$tbl_name);
            }
        } else {
            if (is_numeric($oid)) Cmwdb::$db->where('cid', $oid);
            if (is_string($oid)) Cmwdb::$db->where('slugs', $oid);
            if (is_array($oid)) Cmwdb::$db->where('cid', $oid, "in");
            if (is_array($args['categories'])) {
                if (isset($args['categories'])) {
                    if (is_array($args['categories'])) {
                        if (!in_array("cid", $args['categories'])) $args['categories'][] = "cid";
                    }
                    if (is_array($oid)) {
                        $ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
                    } else {
                        $ret = Cmwdb::$db->getOne(self::$tbl_name, null, $args['categories']);
                    }
                }
            } else {
                if (is_array($oid)) {
                    $ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
                } else {
                    $ret = Cmwdb::$db->getOne(self::$tbl_name, null, $args['categories']);
                }
            }
        }
        return $ret;
    }

    static function GetCategoriesByParent($parent_id = 0)
    {
        Cmwdb::$db->where('category_lang', CLanguage::getCurrentUser());
        Cmwdb::$db->where('category_parent', $parent_id);
        Cmwdb::$db->orderBy('category_order');
        return Cmwdb::$db->get(self::$tbl_name);
    }

    static function GetCategoriesWithMaxLevel($max_level = 0)
    {
        $ret = [];
        $res = self::GetCategoriesByParent(0);
        if ($res) {
            foreach ($res as $item) {
                $ret[] = array('level' => 0, 'data' => $item);
                if ($max_level > 0) {
                    $res2 = self::GetCategoriesByParent($item['cid']);
                    if ($res2) {
                        foreach ($res2 as $item2) {
                            $ret[] = array('level' => 1, 'data' => $item2);
                            if ($max_level > 1) {
                                $res3 = self::GetCategoriesByParent($item2['cid']);
                                if ($res3) {
                                    foreach ($res3 as $item3) {
                                        $ret[] = array('level' => 2, 'data' => $item3);
                                        if ($max_level > 2) {
                                            $res4 = self::GetCategoriesByParent($item3['cid']);
                                            if ($res4) {
                                                foreach ($res4 as $item3) {
                                                    $ret[] = array('level' => 3, 'data' => $item3);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }


}

?>