<?php

/**
 * Created by PhpStorm.
 * User: abo
 * Date: 11/27/2015
 * Time: 12:35 PM
 */
class CUrlManager
{
    private $query_string;
    private $url_parts;

    public function __construct()
    {
        $this->init_query_string();
    }

    public static function goHome()
    {

    }

    public function init_query_string()
    {
        if (isset($_GET['query'])) {

            $this->query_string = $_GET['query'];
        } else {
            $this->query_string = '';
        }
    }

    private function array_shift($array)
    {
        $ret_arr = array();
        unset($array[0]);
        foreach ($array as $item) {
            $ret_arr[] = $item;
        }
        return $ret_arr;

    }

    public function parse_query()
    {
        $this->url_parts = explode('/', $this->query_string);
        if (in_array($this->url_parts[0], CLanguage::get_lang_keys_user())) {

            CLanguage::setCurrentUser($this->url_parts[0]);

            $this->url_parts = $this->array_shift($this->url_parts);

            //set current lang to default

        } else {
            //set current lang to required
            CLanguage::setCurrentUser(CLanguage::getDefaultUser());
        }

//        var_dump($this->url_parts);


        if (!isset($this->url_parts[1])) {
            $this->url_parts[1] = null;
        }
        if (count($this->url_parts) > 2) {
            return ['controller' => 'NotFoundController', 'action' => $this->url_parts[1]];
        }
        
        if (!$this->url_parts || !$this->url_parts[0]) {
            return ['controller' => 'HomeController', 'action' => ''];
        }
        if ($this->url_parts[0] == 'account') {
            return ['module' => 'user', 'action' => $this->url_parts[1]];
        }
        if ($this->url_parts[0] == 'ajax') {
            return ['controller' => 'AjaxController', 'action' => $this->url_parts[1]];
        }
        if ($this->url_parts[0] == 'page') {
            return ['controller' => 'PageController', 'action' => $this->url_parts[1]];
        }
        if ($this->url_parts[0] == 'category') {
            return ['controller' => 'PostCategoryController', 'action' => $this->url_parts[1]];
        }
        if ($this->url_parts[0] == 'search') {
            return ['controller' => 'SearchController', 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'tag') {
            return ['controller' => 'TagController', 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'upload') {
            return ['controller' => 'upload', 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'product-category' && CConfig::HasModule('product_category')) {
            return ['module' => 'product_category', 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'product' && CConfig::HasModule($this->url_parts[0])) {
            return ['module' => $this->url_parts[0], 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'brand' && CConfig::HasModule($this->url_parts[0])) {
            return ['module' => $this->url_parts[0], 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'checkout' && CConfig::HasModule($this->url_parts[0])) {
            return ['module' => $this->url_parts[0], 'action' => $this->url_parts[1]];
        };
        if ($this->url_parts[0] == 'cart') {
            return ['module' => 'cart', 'action' => null];
        };

        //var_dump(CConfig::HasModule($this->url_parts[0]));
        //        if (CConfig::HasModule($this->url_parts[0])) {
        //            return ['module' => $this->url_parts[0], 'action' => $this->url_parts[1]];
        //        }

        return ['controller' => 'PostController', 'action' => $this->url_parts[0]];

    }

    static function GetURL($args)
    {
        $lang = CLanguage::getInstance();
        $cur_lang = "";
        $tbl_name = "";
        if ($lang->getCurrentUser() !== $lang->getDefaultUser()) $cur_lang = $lang->getCurrentUser() . '/';
        $ret_url = URL_BASE . $cur_lang;
        if (is_array($args)) {
            if (isset($args['type']) && isset($args['id'])) {
                switch ($args['type']) {
                    case 'home': {
                        return $ret_url;
                        break;
                    }
                    case 'cart': {
                        return $ret_url . 'cart';
                        break;
                    }
                    case 'page': {
                        $tbl_name = "std_pages";
                        Cmwdb::$db->where('pid', $args['id']);
                        Cmwdb::$db->where('page_lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("page_slug"));
                        $ret_url .= "page/" . $res['page_slug'];
                        return $ret_url;
                        break;
                    }
                    case 'tag': {
                        $tbl_name = "std_tags";
                        Cmwdb::$db->where('pid', $args['id']);
                        Cmwdb::$db->where('lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("tag_slug"));
                        $ret_url .= "tag/" . $res['tag_slug'];
                        return $ret_url;
                        break;
                    }
                    case 'post': {
                        $tbl_name = "std_post";
                        Cmwdb::$db->where('pid', $args['id']);
                        Cmwdb::$db->where('post_lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("post_slug"));
                        $ret_url .= $res['post_slug'];
                        return $ret_url;
                        break;
                    }
                    case 'post_category': {
                        $tbl_name = "std_category_post";
                        Cmwdb::$db->where('cid', $args['id']);
                        Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("slugs"));
                        $ret_url .= "category/" . $res['slugs'];
                        return $ret_url;
                        break;
                    }
                    case 'product_category': {
                        $tbl_name = "std_category_product";
                        Cmwdb::$db->where('cid', $args['id']);
                        Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("slugs"));
                        $ret_url .= "product-category/" . $res['slugs'];
                        return $ret_url;
                        break;
                    }
                    case 'product': {
                        $tbl_name = "std_products";
                        Cmwdb::$db->where('product_group', $args['id']);
                        Cmwdb::$db->where('product_lang', $lang->getCurrentUser());
                        $res = Cmwdb::$db->getOne($tbl_name, array("product_slug"));
                        $ret_url .= "product/" . $res['product_slug'];
                        return $ret_url;
                        break;
                    }

                    default: {
                        return $ret_url;
                        break;
                    }
                }
            }
        }
        return false;
    }

    static function GetStaticURL($type, $id = null, $lang = null)
    {
        if (!$lang) {
            $cur_lang = '';
            if (CLanguage::getCurrentUser() !== CLanguage::getDefaultUser()) $cur_lang = CLanguage::getCurrentUser() . DIRECTORY_SEPARATOR;
        } else {
            if ($lang != CLanguage::getDefaultUser()) {
                $cur_lang = $lang . DIRECTORY_SEPARATOR;
            } else {
                $cur_lang = '';
            }
        }

        $ret_url = URL_BASE . $cur_lang;
        switch ($type) {

            case 'page' : {
                $ret_url .= "page/" . $id;
                return $ret_url;
            }
            case 'post_category' : {
                $ret_url .= "category/" . $id;
                return $ret_url;
            }
            case 'post' : {
                $ret_url .= $id;
                return $ret_url;
            }
            case 'tag' : {
                $ret_url .= "tag/" . $id;
                return $ret_url;
            }
            case 'cart' : {
                $ret_url .= "cart/";
                return $ret_url;
            }
            case 'checkout' : {
                $ret_url .= "checkout/" . $id;
                return $ret_url;
            }
            case 'home' : {
                return $ret_url;
            }
            case 'user' : {
                $ret_url .= "account/" . $id;
                return $ret_url;
            }
            case 'product' : {
                if (CModule::HasModule('product')) {
                    $ret_url .= "product/" . $id;
                    return $ret_url;
                    break;
                }
            }
            case 'product_category' : {
                if (CModule::HasModule('product_category')) {
                    $ret_url .= "product-category/" . $id;
                    return $ret_url;
                    break;
                }
            }
            case 'brand' : {
                if (CModule::HasModule('brand')) {
                    $ret_url .= "brand/" . $id;
                    return $ret_url;
                    break;
                }
            }
            case 'product_tag' : {
                if (CModule::HasModule('tags')) {
                    $ret_url .= "product-tag/" . $id;
                    return $ret_url;
                    break;
                }
            }
            default : {
                return $ret_url;
            }
        }
    }

    static function GetMenuItemURL($m_item)
    {
        $lang = CLanguage::getInstance();
        $cur_lang = "";
        $tbl_name = "";
        if ($lang->getCurrentUser() !== $lang->getDefaultUser()) $cur_lang = $lang->getCurrentUser() . '/';
        $ret_url = URL_BASE . $cur_lang;
        if (is_array($m_item)) {
            switch ($m_item['m_type']) {
                case 'custom_link': {

                    return ['menu_url' => $m_item['menu_url'], 'menu_text' => $m_item['menu_text']];
                    break;
                }
                case 'post': {
                    $tbl_name = "std_post";
                    Cmwdb::$db->where('pid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('post_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("post_slug", 'post_title'));
                    $ret_url .= $res['post_slug'];

                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['post_title'];
                    } else {
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'post_category': {
                    $tbl_name = "std_category_post";
                    Cmwdb::$db->where('cid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("slugs", 'category_title'));
                    $ret_url .= "category/" . $res['slugs'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['category_title'];
                    } else {
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'product_category': {
                    $tbl_name = "std_category_product";
                    Cmwdb::$db->where('cid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('category_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("slugs", 'category_title'));
                    $ret_url .= "product-category/" . $res['slugs'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['category_title'];
                    } else {
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                case 'page': {
                    $tbl_name = "std_pages";
                    Cmwdb::$db->where('pid', $m_item['m_elem_id']);
                    Cmwdb::$db->where('page_lang', $lang->getCurrentUser());
                    $res = Cmwdb::$db->getOne($tbl_name, array("page_slug", 'page_title'));
                    $ret_url .= "page/" . $res['page_slug'];
                    $ret_arr['menu_url'] = $ret_url;
                    if (!$m_item['menu_text']) {
                        $ret_arr['menu_text'] = $res['page_title'];
                    } else {
                        $ret_arr['menu_text'] = $m_item['menu_text'];
                    }
                    return $ret_arr;
                    break;
                }
                default: {
                    return $ret_url;
                    break;
                }
            }

        }
        return false;
    }

    static function ChangeLangUrl($lang = null)
    {
        return URL_BASE . 'action.php?change_lang=yes&lang=' . $lang . '&obj_id=' . CWebApp::$_controller->uID . '&type=' . CWebApp::$_contextID;
    }
}