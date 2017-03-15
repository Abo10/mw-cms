<?php

class ProductCategoryModuleController extends CController
{
    public $_products = [];
    public $_attributika;


    public function __construct($slug, $layout = '')
    {
        parent::__construct();

        $this->_viewDir .= DIRECTORY_SEPARATOR . '_modules/';

        $this->_slug = $slug;

        $this->_data = CFrontProductCategory::GetDatas($slug)['product_category'];

        if (!$this->_data) {
            $redirect_check = CStdRedirects::GetRedirect($this->_slug, self::$_contextID);
            if ($redirect_check !== false) {
                header('Location: ' . $redirect_check);
                die;
            } else {
                $this->not_found();
            }
        }
        $this->seo = CFrontSeo::GetDatas($this->_data['category_seo']);

        $this->covers = json_decode($this->_data['category_cover_gallery'], true);

        $this->_uID = $this->_data['cid'];

        self::$_menuElemID = $this->_uID;

        $this->_productData = CFrontProduct::GetFiltered(['product_category' => [$this->_uID], 'search' => $this->search_key], ['attributika' => 1, 'product' => 1], null,  self::$_currentPage,self::$_limitPerPage);

        $this->_products = isset($this->_productData['product']) ? $this->_productData['product'] : [];

        $this->_attributika = (isset($this->_productData['attributika']) && $this->_productData['attributika']) ? $this->_productData['attributika'] : null;

        self::$_pageCount = isset($this->_productData['page_count']) ? (int)$this->_productData['page_count'] : 0;

        if ($this->_data['category_parent'] == 0) {
            $this->is_main_cat = true;
        } else {
            $this->is_main_cat = false;
        }

        self::$_breadcrumb = [
            ['type' => 'home', 'label' => CDictionaryUser::GetKey('home'), 'id' => null, 'active' => false]
        ];

        $this->cat_tree = CFrontProductCategory::GetCatsTree($this->_uID);
        foreach ($this->cat_tree as $cat) {
            self::$_breadcrumb[] = ['type' => 'product_category', 'label' => $cat['title'], 'id' => $cat['cid'], 'active' => true];
        }
        $this->main_cat_id = self::$_breadcrumb[1]['id'];


        $_SESSION['history']['contextID'] = self::$_contextID;
        $_SESSION['history']['uID'] = $this->_uID;


        return;
    }


}