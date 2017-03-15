<?php

session_set_cookie_params(3600000, "/");
session_start();
require_once '../vendor/autoload.php';
require_once '../mod.php';
//set_time_limit(10);

error_reporting(E_ALL);
ini_set('display_errors', false);

$lang = CLanguage::getInstance();

CLanguage::setCurrent(CLanguage::getDefault());
if (isset($_SESSION['admin_lang'])) {
    $lang->setCurrent($_SESSION['admin_lang']);
}

$langs = CLanguage::getInstance()->get_langs();

$user_langs = CLanguage::getInstance()->get_langsUser();
$current_lang = $lang->getCurrent();

$current_lang_user = $lang->getCurrentUser();

if (!CUserAdmin::Initial()) {
    header("Location: " . ADMIN_URL . 'login.php');
    die;
}


if (isset($_GET['change_lang'])) {
    if (in_array($_GET['change_lang'], $lang->get_lang_keys())) {
        $_SESSION['admin_lang'] = $_GET['change_lang'];
        $ret_url = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $ret_url);
        die;
    } else {
        die;
    }

//    $_REQUEST['']
//    header()
}

if (isset($_GET['module'])) {
    $module = $_GET['module'];
} else {
    $module = null;
}


if (isset($_GET['menu'])) {
    $menu = $_GET['menu'];
} else {
    $menu = 'home';
}
if (isset($_GET['submenu'])) {
    $sub_menu = $_GET['submenu'];
} else {
    $sub_menu = 'index';
}
if ($sub_menu == 'action') {

    if ($module) {
        if (!CModule::LoadTemplate($module, $sub_menu)) {
            include_once ADMIN_DIR . 'templates/404.php';
        }
        die;
    } else {
        include_once ADMIN_DIR . 'templates/' . $menu . '/action.php';
        die;
    }

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

    <title>MW Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!--datetimepicker-->
    <link rel="stylesheet" type="text/css" href="js/datetimepicker/jquery.datetimepicker.css"
    / >

    <!-- Custom Fonts -->
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet" media="all">
	 <link href="css/print.css" rel="stylesheet" media="print">

    <!-- Custom CSS -->
    <link href="js/chosen/chosen.min.css" rel="stylesheet">
    <!--    <link href="js/jquery-ui-multiselect-widget-master/jquery.multiselect.css" rel="stylesheet">-->


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Jquery UI Core JavaScript -->
    <script src="js/jquery-ui.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>

    <script src="js/ckeditor/ckeditor.js"></script>
    <script src="js/tinymce/tinymce.min.js"></script>

    <script src="js/chosen/chosen.jquery.min.js"></script>

    <!--    <script src="js/jquery-ui-multiselect-widget-master/src/jquery.multiselect.min.js"></script>-->

    <script src="js/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
    <script>
        var page_prop = {};
        page_prop.selected_items = [];
        page_prop.submited_items = [];
        page_prop.counter = 0;
        page_prop.edit_id = null;
        $(function () {

            if ($('[data-action=page_prop_start_counter]').val() > 0) {
                page_prop.counter = $('[data-action=page_prop_start_counter]').val();
            }

        })

    </script>
    <script src="js/scripts.js"></script>

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <?php include_once 'header.php'; ?>

       
        <!-- /.navbar-collapse -->
    </nav>
	<aside class="main-sidebar"> <?php include_once 'menu.php'; ?> </aside>

    <?php
    if ($module) {
        if (!CModule::LoadTemplate($module, $sub_menu)) {
            include_once ADMIN_DIR . 'templates/404.php';
        }
    } else {


        if (file_exists(ADMIN_DIR . 'templates/' . $menu . '/' . $sub_menu . '.php')) {
            include_once ADMIN_DIR . 'templates/' . $menu . '/' . $sub_menu . '.php';
        } else {
            include_once ADMIN_DIR . 'templates/404.php';

        }
    } ?>

</div>

<!-- /#wrapper -->


</body>

</html>

