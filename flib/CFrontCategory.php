<?php

class CFrontCategory extends CFront
{
	static $datas = array();
	static $tbl_name = "std_categories";
	
    function __construct($oid, $args = null)
    {

    }

    static function Initial()
    {
        self::$tbl_name = "std_categories";
    }

    static function GetDatas($oid = null, $args = null)
    {
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());

        $ret = array();
        if (!$oid) {
            if (is_array($args['categories'])) {
                if (!in_array("cid", $args['categories'])) $args['categories'][] = "cid";
                $ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
            } else{
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
                    $ret = Cmwdb::$db->get(self::$tbl_name, null, $args['categories']);
                }
            } else $ret = Cmwdb::$db->get(self::$tbl_name);
        }
        $retf = array();
        foreach ($ret as $values) {
            $retf[$values['cid']]['category'] = $values;
        }
        return $retf;
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