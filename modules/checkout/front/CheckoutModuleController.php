<?php


class CheckoutModuleController extends CController
{

    public $_data = [];
    public $seo = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $cat_tree = null;


    public function __construct($slug, $layout = '')
    {
        parent::__construct();
        $this->_viewDir .= '/_modules';
        if($slug){
            $this->_viewFile = $slug.'.php';
        }

    }



}