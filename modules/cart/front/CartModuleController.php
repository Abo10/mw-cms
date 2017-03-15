<?php


class CartModuleController extends CController
{

    public $name = __CLASS__;
    public $title = 'TITLE TEST';
    protected $_pageSlug;
    public $_db;
    //public $_controllerName = 'category';
    public $_data = [];
    public $breadcrumb = [];
    public $cat_id = null;
    public $obj_id = null;
    public $obj_type = 'cart';
    public $cat_data = null;
    public $seo = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $cat_tree = null;


    public function __construct($slug, $layout = '')
    {
        parent::__construct();
        $this->_viewDir .= '/_modules';
    }



}