<?php

/**
 * Created by PhpStorm.
 * User: abo
 * Date: 11/27/2015
 * Time: 3:09 PM
 */
class CLanguage
{
    private static $_instance = null;

    static protected $default_language = null;
    static protected $default_language_user = null;
    static protected $current_language = 'am';
    static protected $current_language_user = 'am';
    
    static protected $lang_list = array();

	static protected $user_langs = array();
    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static function GetLangsCount(){
    	return count(self::$lang_list);
    }
    
    static function GetLangsCountUser(){
    	return count(self::$user_langs);
    }
    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::Initial();
             
        }
       
        return self::$_instance;
    }
    
    static function Initial(){
    	$res = CConfig::GetBlock();

    	if(isset($res['lang_list']))self::$lang_list = $res['lang_list'];
    	if(isset($res['user_langs']))self::$user_langs = $res['user_langs'];
   		if(isset($res['default_user']))self::$default_language_user = $res['default_user'];
   		if(isset($res['default_admin']))self::$default_language = $res['default_admin'];
    }

    static public function setCurrent($lang){
        self::$current_language = $lang;
    }

    static public function getCurrent(){
       	return self::$current_language;
    }

    static public function getDefault(){
       	return self::$default_language;
    }
    static public function getDefaultTitleUser(){
        foreach (self::$user_langs as $item) {
            if($item['key']==self::$current_language_user) return $item['title'];
        }
        return null;
    }

    static public function setDefault($key){
       	if (in_array($key, self::get_lang_keys())){
       	    self::$default_language = $key;
       	    return true;
       	} else {
       	    echo 'Language not  found';
	   	}
    }


    static public function get_langs(){
       	return self::$lang_list;
    }

    static public function get_lang_keys()
    {
        $ret_arr = [];
  
        foreach (self::$lang_list as $val) {
            $ret_arr[] = $val['key'];
        }
        return $ret_arr;
   }

   static public function setCurrentUser($lang){
   	self::$current_language_user = $lang;
   }
    
   static public function getCurrentUser(){
   	return self::$current_language_user;
   }
    
   static public function getDefaultUser(){
   	return self::$default_language_user;
   }
    
   static public function setDefaultUser($key){
   	if (in_array($key, self::get_lang_keys_user())){
   		self::$default_language_user = $key;
   		return true;
   	} else {
   		echo 'Language not  found';
   	}
   }
    
    
   static public function get_langsUser(){
   	return self::$user_langs;
   }
    
   static public function get_lang_keys_user()
   {
   	$ret_arr = [];
   	 
   	foreach (self::$user_langs as $val) {
   		$ret_arr[] = $val['key'];
   	}
	return $ret_arr;
   }
   
}
