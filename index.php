<?php
session_start();
$seconds_to_cache = 36000;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
$ts2 = gmdate("D, d M Y H:i:s", time() - $seconds_to_cache) . " GMT";
header("Cache-Control: max-age=86400");
header("Expires: $ts");
header("Last-Modified: $ts2");

//require_once 'vendor/autoload.php';

require_once 'mod.php';
require_once './vendor/autoload.php';
require_once 'functions.php';

if(CConfig::GetBlock('config','installer')['status'] !== true){
    header('Location: '.$_SERVER['REQUEST_URI'].'install');
    die;
}
error_reporting(E_ALL);

CDictionary::Initialise();
CDictionaryUser::Initialise();

CLanguage::getInstance();

$url = new CUrlManager;

$cont_action = $url->parse_query();


CWebApp::run($cont_action);
