<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Cache-control" content="public">
    <meta http-equiv="expires" content="<?= date(DATE_RFC822, strtotime("+3 days")); ?>">

    <title><?= CWebApp::$_pageProp['s_title'] ?></title>
    <link href="<?= ASSETS_BASE ?>css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<input type="hidden" id="url_base" value="<?= CUrlManager::GetURL(['type' => 'home', 'id' => 1]) ?>">

<?php if (file_exists(CController::$_controller->_viewDir . DIRECTORY_SEPARATOR . CController::$_controllerID . '/header.php'))
    require_once CController::$_controller->_viewDir . DIRECTORY_SEPARATOR . CController::$_controllerID . '/header.php';
else
    require_once CController::$_controller->_layoutDir . '/header.php';
?>
<?php require_once CController::$_controller->_viewDir . DIRECTORY_SEPARATOR . CController::$_controllerID . '/' . $this->_viewFile; ?>
</div>
<!-- FOOTER-->
<?php if (file_exists(CController::$_controller->_viewDir . DIRECTORY_SEPARATOR . CController::$_controllerID . '/footer.php'))
    require_once CController::$_controller->_viewDir . DIRECTORY_SEPARATOR . CController::$_controllerID . '/footer.php';
else require_once CController::$_controller->_layoutDir . '/footer.php';
?>
<script src="<?= ASSETS_BASE ?>js/jquery-2.2.0.min.js"></script>

</body>
</html>
