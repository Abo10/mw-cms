<?php

require_once './mod.php';
session_start();
if(isset($_GET['change_lang'])){
    $type = $_GET['type'];
    $obj_id = $_GET['obj_id'];
    $user_lang = $_GET['lang'];
    $lang = CLanguage::getInstance()->setCurrentUser($user_lang);
    $url = CUrlManager::GetURL(['type'=>$type,'id'=>$obj_id]);
    header("Location: $url");

}
if(isset($_GET['change_currency'])){
    $currency = $_GET['currency'];
    $currency_obj = CModule::LoadComponent('product','currency');
    $currency_obj->SetCurrentCurrency($currency);

//$lang = CLanguage::getInstance()->setCurrentUser($user_lang);
    $url = $_SERVER['HTTP_REFERER'];
    header("Location: $url");

}

?>