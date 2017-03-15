<?php
require_once __DIR__.'/lib/stdlib.php';
require_once(LIB_BASE.'/CMod.php');

define('SYSTEM_BASE_URI', __DIR__.'/');
$mod = CMod::getInstance();
$mod::Initialise();
$e_handle = CErrorHandling::getInstance();
$e_handle->Initialise();
$dict = CDictionary::getInstance();
$dict->Initialise();
$configs = CConfig::getInstance();
$db = Cmwdb::getInstance();
$db->Initialise();

?>