<?php

/**
 * Created by PhpStorm.
 * User: abo
 * Date: 5/25/2016
 * Time: 12:15 PM
 */
class CSitemap
{
    static $tbl_name = 'std_sitemap_history';

    public static function GetLastUpdate(){
        Cmwdb::$db->orderBy('last_update','DESC');
        $res = Cmwdb::$db->getOne(self::$tbl_name);
        if($res){
            return $res['last_update'];
        }else{
            return false;
        }
    }
    public static function Update($data = null){
        Cmwdb::$db->insert(self::$tbl_name,['last_update'=>time(),'data'=>$data]);
    }
}