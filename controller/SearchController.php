<?php


class SearchController extends CController
{
    public $name = __CLASS__;
    public $title = 'TITLE TEST';
    protected $_pageSlug;
    public $_db;
//    public $_controllerName = 'search';
    public $seo = ['seo_title' => 'Search', 'seo_descr' => '', 'seo_keywords' => ''];
    public $_posts = [];
    public $cat_id = null;
    public $obj_id = null;
    public $obj_type = 'home';
    public $cat_data = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $products = [];
    public $covers = [];


    public function __construct($slug, $layout = '')
    {
        parent::__construct();
        CFrontProductCategory::Initial();
        CModule::LoadModuleFront('product_category');
        CModule::LoadModuleFront('product');
        $filters = [];
        if (isset($_GET['category']) && is_numeric($_GET['category'])) {

            $red_url = CUrlManager::GetURL(['type' => 'product_category', 'id' => $_GET['category']]);
            $red_url.='?q='.$_GET['q'];
            header("Location: $red_url");
            return;
            $a = CFrontProductCategory::GetDatas((int)$_GET['category']);
            $this->cat_id = $a['product_category']['cid'];
            $filters['product_category'] = [$this->cat_id];

        } else {
            $filters['product_category'] = [];
        }
        if (isset($_GET['q']) && !empty($_GET['q'])) {

            $filters['search'] = $_GET['q'];
            $this->search_key = $_GET['q'];

        } else {
            $this->cat_id = null;

        }
        $this->obj_id = $this->cat_id;
        $this->_data = CFrontProduct::GetFiltered($filters, ['attributika' => 1, 'product' => 1], null, 1, 20);

        if (isset($this->_data['product'])) $this->products = $this->_data['product'];

    }
}