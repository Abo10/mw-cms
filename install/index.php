<?php

session_set_cookie_params(3600000, "/");
session_start();
require_once '../mod.php';

if (CConfig::GetBlock('config', 'installer')['status'] == true) {
//    header('Location: ' . URL_BASE);
//    die;
}
if(!isset($_SESSION['runtime_config'])){
    $_SESSION['runtime_config'] = [];
}
$runtime_config = $_SESSION['runtime_config'];

$config_data = include_once 'config_data.php';

$config_modules = include_once 'config_modules.php';

$db_dump_simple = include_once 'db_dump_simple.php';
$db_dump_catalog = include_once 'db_dump_catalog.php';
$db_dump_shop = include_once 'db_dump_shop.php';

$db_dump2 = include_once 'db_dump2.php';

//var_dump($runtime_config);

$step = 'step1';

if (isset($runtime_config['step1'])) {
    $step = 'step2';
}
if (isset($runtime_config['step2'])) {
    $step = 'step3'; 
}
if (isset($runtime_config['step3'])) {
    $step = 'step3';
}
error_reporting(E_ALL);
ini_set('display_errors', true);

$lang = CLanguage::getInstance();

CLanguage::setCurrent(CLanguage::getDefault());
if (isset($_SESSION['admin_lang'])) {
    $lang->setCurrent($_SESSION['admin_lang']);
}

$langs = CLanguage::getInstance()->get_langs();

$user_langs = CLanguage::getInstance()->get_langsUser();
$current_lang = $lang->getCurrent();

$current_lang_user = $lang->getCurrentUser();

//if (!CUserAdmin::Initial()) {
//    header("Location: " . ADMIN_URL . 'login.php');
//    die;
//}


//if (isset($_GET['step'])) {
//    $step = $_GET['step'];
//} else {
//    $step = 'step1';
//}
if (isset($_GET['menu']) && $_GET['menu'] == 'action') {
    include_once __DIR__ . '/templates/action.php';
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MW Install</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>

    <!--    <script src="js/jquery-ui-multiselect-widget-master/src/jquery.multiselect.min.js"></script>-->

    <script>
        var page_prop = {};

    </script>
    <script src="js/scripts.js"></script>
    <style>


    </style>
</head>

<body>
<section class="row top_header">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wc_msg"><img src="image/logo2.png" alt="Armenian"> - Կայքի կառավարման համակարգ</div>
            <div class="col-sm-3">
                
            </div>
            <div class="col-sm-3">
                <ul class="nav nav-pills">

                                             
                    

                    <li><a href="#"><img src="image/Armenia.png" alt="Armenian"></a></li>
                    <li><a href="#"><img src="image/Russia.png" alt="Russian"></a></li>
                    <li><a href="#"><img src="image/United-Kingdom.png" alt="United-Kingdom"></a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</section>
<div class="container">


    <?php
    if (file_exists(__DIR__ . '/templates/' . $step . '.php')) {
        include_once __DIR__ . '/templates/' . $step . '.php';
    } else {
        include_once __DIR__ . '/templates/404.php';

    }
    ?>
</div>

<!-- /#wrapper -->


</body>

</html>

