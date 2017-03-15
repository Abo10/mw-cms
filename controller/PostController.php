<?php


class PostController extends CController
{
    public $name = __CLASS__;
    public $title = '';
    public $content = '';
    protected $_pageSlug;
    public $_db;
    public $seo;
    public $obj_id;
    public $obj_type = 'post';
    public $_postData;
    public $_slug;
    public $post_img;
    public $post_maps;
    public $cover_img;
    public $covers;

//    public $_controllerName = 'post';

    public function __construct($slug, $layout = '')
    {

        parent::__construct();
        if ($layout) {
            $this->_layout = $layout . '.php';
        }
        $this->_slug = $slug;
        $this->_postData = CFrontPost::GetDatas($this->_slug, ['posts' => 1, 'categories' => 1, 'attributes' => 1, 'maps' => 1]);

        if (!$this->_postData) {
            $redirect_check = CStdRedirects::GetRedirect($this->_slug, self::$_contextID);
            if ($redirect_check !== false) {
                header('Location: ' . $redirect_check);
                die;
            } else {
                $this->not_found();
            }
        }

        $this->_data = $this->_postData['post'];
        $this->_uID = $this->_data['pid'];

        self::$_menuElemID = $this->_uID;

        $this->categories = $this->_postData['categories'];
        $this->post_attr = $this->_postData['attributes'];
        $this->post_maps = $this->_postData['maps'];

        $this->seo = CFrontSeo::GetDatas($this->_data['post_seo']);


        $this->main_img = CFrontAttach::GetImageUrl($this->_data['post_img']);
        $this->main_img_title = $this->_data['post_img_title'];

        //---post cover images
        $attach_decoded = json_decode($this->_data['post_covers'], true);
        $attach_array = [];
        foreach ($attach_decoded as $cover) {
            $attach_array[] = $cover['id'];
        }
        $attach_url = CFrontAttach::GetImageUrl($attach_array);
        foreach ($attach_decoded as $key => $item) {
            $attach_decoded[$key]['url'] = $attach_url[(int)$item['id']];
        }
        $this->covers = $attach_decoded;
        //----------

        //---post gallery images
        $attach_decoded = json_decode($this->_data['post_gallery'], true);
        $attach_array = [];
        foreach ($attach_decoded as $cover) {
            $attach_array[] = $cover['id'];
        }
        $attach_url = CFrontAttach::GetImageUrl($attach_array);
        foreach ($attach_decoded as $key => $item) {
            $attach_decoded[$key]['url'] = $attach_url[(int)$item['id']];
        }
        $this->gallery = $attach_decoded;
        //----------

        //---post files
        $attach_decoded = json_decode($this->_data['post_files'], true);
        $attach_array = [];
        foreach ($attach_decoded as $cover) {
            $attach_array[] = $cover['id'];
        }
        $attach_url = CFrontAttach::GetImageUrl($attach_array);
        foreach ($attach_decoded as $key => $item) {
            $attach_decoded[$key]['url'] = $attach_url[(int)$item['id']];
        }
        $this->files = $attach_decoded;
        //----------

        $cover_index = key($this->covers);
        if (isset($covers_arr[$cover_index]['id'])) {

            $attach = new CAttach($covers_arr[$cover_index]['id']);
            $this->cover_img = $attach->GetURL('original');
        }

        self::$_breadcrumb = [
            ['type' => 'home', 'label' => CDictionaryUser::GetKey('home'), 'id' => null, 'active' => false]
        ];

        if (isset($_SESSION['history']['contextID']) && $_SESSION['history']['contextID'] == 'post_category') {
            $cat_tree = CFrontCategoryPost::GetCatsTree($_SESSION['history']['uID']);
            foreach ($cat_tree as $cat) {
                self::$_breadcrumb[] = ['type' => 'post_category', 'label' => $cat['title'], 'id' => $cat['cid'], 'active' => true];
            }
        }

        if (isset($_SESSION['history']['contextID']) && $_SESSION['history']['contextID'] == 'tag') {
            $tag_data = CFrontTags::GetDatas($_SESSION['history']['uID']);
            self::$_breadcrumb[] = ['type' => 'tag', 'label' => $tag_data['tag_name'], 'id' => $tag_data['pid'], 'active' => true];
        }

        self::$_breadcrumb[] = ['type' => 'post', 'label' => $this->_data['post_title'], 'id' => $this->_data['pid'], 'active' => false];

    }
}