<?php
/**
 * Created by PhpStorm.
 * User: Rafik Rushanian
 * Date: 4/10/2016
 * Time: 9:21 PM
 */
interface IFrontMod{
    static function GetDatas($oid=null, $assocs=null, $lang=null, $page=1, $count=20, $assoc_props=null);
    static function GetLinks($subject_id, $subject_type, $group_m = true, $page=1, $count=20);
    static function GetLinksBySLink($slink, $subject_type=null, $group_m = true, $page=1, $count=20);
    static function AddLinks($oid, $subject_id, $subject_type);
}

interface IConfigurableMW{
    static function TakeYourConfigs($mod_name);
}

class CMFront implements IFrontMod, IConfigurableMW{
    static protected $configs = null;
    static protected $tbl_name = null;

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
        // TODO: Implement GetDatas() method.
    }

    static function GetLinks($subject_id, $subject_type, $group_m = true, $page = 1, $count = 20){
        // TODO: Implement GetLinks() method.
    }

    static function GetLinksBySLink($slink, $subject_type = null, $group_m = true, $page = 1, $count = 20){
        // TODO: Implement GetLinksBySLink() method.
    }

    static function AddLinks($oid, $subject_id, $subject_type){
        // TODO: Implement AddLinks() method.
    }

    static function TakeYourConfigs($mod_name){
        // TODO: Implement TakeYourConfigs() method.
    }

    static function Initial(){

    }




}

?>