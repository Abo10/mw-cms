<?php
require_once 'vendor/autoload.php';

require_once 'mod.php';
require_once './vendor/autoload.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;
if (!$action) {
    $action = 'sitemap';
}
if ($action && file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'console' . DIRECTORY_SEPARATOR . $action . '.php')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'console' . DIRECTORY_SEPARATOR . $action . '.php';
    exit();
}
