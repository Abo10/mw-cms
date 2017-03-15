<?php


class ProductModuleController extends CController
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
    public $obj_type = 'product';
    public $cat_data = null;
    public $seo = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $cat_tree = null;


    public function __construct($slug, $layout = '')
    {

        parent::__construct();
        $this->_viewDir .= DIRECTORY_SEPARATOR . '_modules/';
        $this->_contextDir = $this->_viewDir . DIRECTORY_SEPARATOR . self::$_contextID;

        $this->_productData = CFrontProduct::GetDatas($slug, ['category' => 1]);
//         var_dump($this->_productData);
//        var_dump($this->_productData);
//        $this->cat_data = $this->_data['categories'];
        $key = key($this->_productData['product']);
        $this->_data = $this->_productData['product'][$key];

        $this->seo = CFrontSeo::GetDatas($this->_data['product_seo']);

        $this->cat_id = $this->cat_data['cid'];
        $this->obj_id = $this->cat_data['cid'];
        $this->_uID = $this->_data['product_group'];
        $this->covers = json_decode($this->cat_data['category_cover_gallery'], true);
//        var_dump($this->cat_data);
        if ($this->cat_data['category_parent'] == 0) {
            $this->is_main_cat = true;
        } else {
            $this->is_main_cat = false;
        }
        CFrontProductCategory::Initial();
        $this->breadcrumb = CFrontProductCategory::GetCatsTree($this->cat_id);
        $this->main_cat_id = $this->breadcrumb[0]['cid'];

    }


}