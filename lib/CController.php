<?php

class CController extends CWebApp
{

    public $_theme;
    public $_viewDir;
    public $_layoutDir;
    public $_layout;
    public $_contextDir;
    public $_viewFile;

    public $_slug;
    public $_uID;
    public $_data;

    static $_currentPage = 1;
    static $_limitPerPage = 10;
    static $_pageCount = 1;

    public $seo;

    public $active_cat = null;
    public $search_key = null;
    public $template = null;
    public $action = null;


    public function __construct()
    {
        self::RegisterCss([
//            'reset.css',
            'main_new.css',
            'nivo-slider.css',
            'magnific-popup.css',
            'custom.css',
            'style.css',

        ]);
        self::RegisterJs([
            'jquery.nivo.slider.js',
            'magnific-popup/dist/jquery.magnific-popup.js',
            'core.js',
            'scripts.js',
        ]);

        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            self::$_currentPage = (int)$_GET['page'];
        }
        if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
            self::$_limitPerPage = (int)$_GET['limit'];
        }
        if (isset($_GET['search']) && is_numeric($_GET['search'])) {
            self::AddParam('search',$_GET['search']);
        }
        if (isset($_GET['order_by']) && is_numeric($_GET['order_by'])) {
            self::AddParam('order_by',$_GET['order_by']);
        }
        if (isset($_GET['order_type']) && is_numeric($_GET['order_type'])) {
            self::AddParam('order_type',$_GET['order_type']);
        }

        $this->_theme = CConfig::GetConfig('theme');
        $this->_viewDir = realpath(__DIR__ . '/../view' . DIRECTORY_SEPARATOR . $this->_theme);
        $this->_layoutDir = $this->_viewDir . '/layout';
        $this->_contextDir = $this->_viewDir . DIRECTORY_SEPARATOR . self::$_contextID . DIRECTORY_SEPARATOR;

        $this->_viewFile = 'content.php';
        $this->_layout = 'index.php';

    }

    public function start()
    {
        return $this->render();
    } //todo start function

    public function render($data = null)
    {
        //var_dump($this->_layoutDir.$this->_layout);
        if ($data && is_array($data)) {
            extract($data);
        }
        require_once $this->_layoutDir . DIRECTORY_SEPARATOR . $this->_layout;
    }

    public function renderFile($file, $data = null)
    {
        $this->template = $file . '.php';
        require_once $this->_layoutDir .DIRECTORY_SEPARATOR. $this->_layout;
    }

    public function renderPartial($filename)
    {
        require_once $this->_layoutDir . $filename . '.php';
    }


    public function not_found()
    {
        CWebApp::run(['controller' => 'NotFoundController', 'action' => null]);
        die;
    }

    public function getCurrentPage()
    {
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            return (int)$_GET['page'];
        } else {
            return 1;
        }
    }


}