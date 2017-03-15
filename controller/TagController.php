<?php


class TagController extends CController
{
    public $_posts;

    public function __construct($slug, $layout = '')
    {
        parent::__construct();

        $this->_slug = $slug;


        $this->_data = CFrontTags::GetDatas($slug);

        if (!$this->_data) {
            $redirect_check = CStdRedirects::GetRedirect($this->_slug, self::$_contextID);
            if ($redirect_check !== false) {
                header('Location: ' . $redirect_check);
                die;
            } else {
                $this->not_found();
            }
        }
        $this->_uID = $this->_data['pid'];

        self::$_menuElemID = $this->_uID;

        $this->_posts = CFrontTags::GetAllPosts($this->_uID, null, self::$_limitPerPage, self::$_currentPage);

        $this->seo = ['seo_title'=>'tag'];

        self::$_breadcrumb = [
            ['type' => 'home', 'label' => CDictionaryUser::GetKey('home'), 'id' => null, 'active' => false]
        ];

        self::$_breadcrumb[] = ['type' => 'tag', 'label' => $this->_data['tag_name'], 'id' => $this->_data['pid'], 'active' => true];

        $_SESSION['history']['contextID'] = self::$_contextID;
        $_SESSION['history']['uID'] = $this->_uID;

    }

}