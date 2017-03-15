<?php


class UserModuleController extends CController
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

    public $template = null;


    public function __construct($action, $layout = '')
    {

        parent::__construct();
        $this->_viewDir .= '/_modules';
        $this->action = $action;
    }


    public function start()
    {
        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            return $this->profile();
        }
    }

    protected function action()
    {
        if ($_GET['action'] == 'login') {

            $res = CUser::Authentication(['login' => $_POST['login'], 'password' => $_POST['password']]);
            if ($res) {
                CFrontWishlist::InitWishlist();
                CFrontCart::InitCart();
                return header('Location: ' . CUrlManager::GetStaticURL('user', 'profile'));
            } else {
                CMessage::setFlash('login_message', CDictionaryUser::GetKey('wrong_username_or_pass'));
                return header('Location: ' . CUrlManager::GetStaticURL('user', 'login'));
            }
        }
        if ($_GET['action'] == 'login_from_checkout') {

            $res = CUser::Authentication(['login' => $_POST['login'], 'password' => $_POST['password']]);
            if ($res) {
                CFrontWishlist::InitWishlist();
                CFrontCart::InitCart();
                return header('Location: ' . CUrlManager::GetStaticURL('checkout'));
            } else {
                CMessage::setFlash('login_message', CDictionaryUser::GetKey('wrong_username_or_pass'));
                return header('Location: ' . CUrlManager::GetStaticURL('checkout'));
            }
        }
        if ($_GET['action'] == 'signup') {
            $check = CUser::CreateUser($_POST);
            if ($check) {
                CMessage::setFlash('registration_message', CDictionaryUser::GetKey('successfull_registration_check_email'));
            } else {
                CMessage::setFlash('registration_message', CDictionaryUser::GetKey('shomething_is_wrong_registration'));
            }
            //$res = CUser::Authentication(['login' => $_POST['login'], 'password' => $_POST['password']]);
            header('Location: ' . CUrlManager::GetStaticURL('user', 'login'));
            die;
        }


    }

    protected function profile()
    {

        $this->renderFile('profile');
    }

    protected function login()
    {
        if (CUser::IsAuthenticated()) {
            header('Location: ' . CUrlManager::GetStaticURL('user', 'profile'));
        }
        $this->_viewFile = 'login.php';
        $this->render();
    }


    protected function logout()
    {
        if (CUser::IsAuthenticated()) {

            CUser::LogOut();
            return header('Location: ' . CUrlManager::GetStaticURL('home'));
        }
    }


    protected function orders()
    {
        $this->renderFile('orders');

    }

    protected function reg()
    {
        $this->renderFile('reg');

    }

    protected function activate()
    {
        if (isset($_GET['mail']) && isset($_GET['vercode'])) {
            CUser::Initial();
            if (CUser::ActivateUser($_GET['mail'], $_GET['vercode']))
                header('Location: ' . CUrlManager::GetStaticURL('user', 'login'));
        }
        return false;
    }

    protected function wishlist()
    {
        $this->_viewFile = 'wishlist.php';
        $this->render();
    }


}