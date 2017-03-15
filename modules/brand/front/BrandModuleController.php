<?php


class BrandModuleController extends CController
{
    public $_products = [];
    public $_attributika;


    public function __construct($slug, $layout = '')
    {
        parent::__construct();

        $this->_viewDir .= DIRECTORY_SEPARATOR . '_modules/';

        $this->_slug = $slug;

        $this->_data = CFrontBrand::GetDatas($this->_slug)['brand'];
        if (!$this->_data) {
            $redirect_check = CStdRedirects::GetRedirect($this->_slug, self::$_contextID);
            if ($redirect_check !== false) {
                header('Location: ' . $redirect_check);
                die;
            } else {
                $this->not_found();
            }
        }
        $this->seo = CFrontSeo::GetDatas($this->_data['brand_seo']);

        $this->covers = json_decode($this->_data['brand_covers'], true);

        $this->_uID = $this->_data['brand_group'];

        self::$_menuElemID = $this->_uID;

        $this->_productData = CFrontProduct::GetFiltered(['brand' => [$this->_uID]], ['attributika' => 1, 'product' => 1], null,  self::$_currentPage,self::$_limitPerPage);

        $this->_products = isset($this->_productData['product']) ? $this->_productData['product'] : [];

        $this->_attributika = (isset($this->_productData['attributika']) && $this->_productData['attributika']) ? $this->_productData['attributika'] : null;

        self::$_pageCount = isset($this->_productData['page_count']) ? (int)$this->_productData['page_count'] : 0;


        self::$_breadcrumb = [
            ['type' => 'home', 'label' => CDictionaryUser::GetKey('home'), 'id' => null, 'active' => false]
        ];


        self::$_breadcrumb[] = ['type' => 'brand', 'label' => $this->_data['brand_title'], 'id' => $this->_uID, 'active' => true];
        $this->main_cat_id = self::$_breadcrumb[1]['id'];


        $_SESSION['history']['contextID'] = self::$_contextID;
        $_SESSION['history']['uID'] = $this->_uID;


        return;
    }

}