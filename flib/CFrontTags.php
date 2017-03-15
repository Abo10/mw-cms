<?php

class CFrontTags /*extends CFront*/
{
    protected static $datas = array();
    public static $tbl_name = "std_tags";

    static function Initial()
    {
// 		self::$tbl_name = "std_tags";
    }

    static function GetDatas($oid = null, $args = null)
    {
        if (!$oid) return array();
        if (is_array($args)) {
            if (!in_array("pid", $args)) $args[] = "pid";
        }
        Cmwdb::$db->where('lang', CLanguage::getInstance()->getCurrentUser());
        if (is_numeric($oid)) {
            Cmwdb::$db->where('pid', $oid);
            return Cmwdb::$db->getOne(self::$tbl_name);

        }
        if (is_array($oid)) {
            Cmwdb::$db->where('pid', $oid, "in");
            return Cmwdb::$db->get(self::$tbl_name);

        }
        if (is_string($oid)) {
            Cmwdb::$db->where('tag_slug', $oid);
            return Cmwdb::$db->getOne(self::$tbl_name);
        }
        return [];
    }

    static function GetAllPosts($oid = null, $args = null, $count = 10, $page = 1)
    {
        if (is_string($oid)) {
            Cmwdb::$db->where('tag_slug', $oid);
            $res = Cmwdb::$db->getOne(self::$tbl_name, null, ["pid"]);
            $oid = $res['pid'];
        }
        $elems = new CPostToTags();
        CFrontPost::Initial();
        return CFrontPost::GetDatas($elems->GetBySLink($oid), $args, $count, $page);
    }

    static function GetAllTags()
    {
        Cmwdb::$db->where("lang", CLanguage::getInstance()->getCurrentUser());
        $res = Cmwdb::$db->get(self::$tbl_name);
        $ret = array();
        foreach ($res as $values) $ret[$values['pid']] = $values;
        return $ret;
    }
}

?>