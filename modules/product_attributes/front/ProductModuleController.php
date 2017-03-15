<?php


class ProductModuleController extends CController
{

    public $name = __CLASS__;
    public $title = 'TITLE TEST';
    protected $_pageSlug;
    public $_db;
    public $_controllerName = 'category';
    public $_posts = [];
    public $cat_id = null;
    public $obj_id = null;
    public $obj_type = 'post_category';
    public $cat_data = null;
    public $seo = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $cat_tree = null;


    public function __construct($slug, $layout = '')
    {
        parent::__construct();
        CFrontCategoryPost::Initial();
        $data = CFrontCategoryPost::GetDatas($slug);
        foreach($data as $value){
            $this->cat_data = $value['category'];
            break;
        }

        CFrontSeo::Initial();
        $this->seo = CFrontSeo::GetDatas($this->cat_data['category_seo']);
        $this->cat_id = $this->cat_data['cid'];
        $this->obj_id = $this->cat_data['cid'];
        //var_dump($this->cat_data);
        if ($this->cat_data['category_parent'] == 0) {
            $this->is_main_cat = true;
        } else {
            $this->is_main_cat = false;
        }

        CFrontCategoryPost::Initial();
        $this->cat_tree = CFrontCategoryPost::GetCatsTree($this->cat_id);

        //$this->getAllPostsIDs();

    }

    static function aa()
    {

    }

    public function get_cat_parent()
    {
        $parent_id = $this->cat_data['category_parent'];
        $cat_tbl = (new CCategoryPost())->getTblName();
        Cmwdb::$db->where('cid', $parent_id);
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
        $this->cat_parent_data = Cmwdb::$db->getOne($cat_tbl);
        return $this->cat_parent_data;
    }

    public function getHeader()
    {
//        require_once
        echo 'Hello';
        //var_dump($this->_db);
    }

    public function getAllPostsIDs()
    {
        $cat_posts = new CPostToCatPost();
        $tmp_post_ids = $cat_posts->GetBySLink($this->cat_id);
        if ($tmp_post_ids) {
            Cmwdb::$db->where('pid', $tmp_post_ids, "IN");
            Cmwdb::$db->where('is_active', 1);
            Cmwdb::$db->where('post_lang', CLanguage::getInstance()->getCurrentUser());
            $this->_posts = Cmwdb::$db->get('std_post');
        }
    }

    public function get_posts($page=1,$limit = 2)
    {
        $cat_posts = new CPostToCatPost();
        $tmp_post_ids = $cat_posts->GetBySLink($this->cat_id);
        if ($tmp_post_ids) {
            CFrontPost::Initial();
            return CFrontPost::GetDatas($tmp_post_ids, ['post' => 1, 'maps' => 1],$limit,$page);
        }else{
            return [];
        }
    }

}