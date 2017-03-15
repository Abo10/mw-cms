<?php


class AjaxController extends CController
{

    public $name = __CLASS__;
    public $title = 'TITLE TEST';
    protected $_pageSlug;
    public $_db;
//    public $_controllerName = 'category';
    public $_posts = [];
    public $cat_id = null;
    public $obj_id = null;
    public $obj_type = 'ajax';
    public $cat_data = null;
    public $seo = null;
    public $cat_parent_data = null;
    public $is_main_cat = null;
    public $cat_tree = null;


    public function __construct($action, $layout = '')
    {
        parent::__construct();

        //$this->getAllPostsIDs();

    }

    public function start()
    {

        //$action = $_GET['action'];
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
        }

        if ($action == 'send_contact_form') {
//            $send_email1 = 'abo2009abo@gmail.com';
            $send_email1 = CWebApp::$_pageProp['contact_mail'];

            $email = $_POST['your_email'];
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if(!$email){
                echo 'Please enter correct email';
                return;
            }
            if (!$_POST['your_name']) {
                echo 'Please fill your name';
                return;
            }
            if (!$_POST['your_message']) {
                echo 'Please fill your message';
                return;
            }
            $text = 'Message from: ' . $_POST['your_name'] . "<br>";
            $text .= 'Email: ' . $email . "<br>";
            $text .= 'Message text: ' . $_POST['your_message'];

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: <' . $send_email1 . '>' . "\r\n";
            $headers .= 'From: <' . 'info@1-ring.net' . '>' . "\r\n";
            $mail = mail($send_email1, '1-Ring Contact', $text, $headers);
            if ($mail){
                echo '1';
            }else {
                echo 'Something went wrong, please try again';
            }
            return;
        }
        if ($action == 'save_subscription') {
            $email = $_POST['email'];
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if(!$email){
                echo 'Please enter correct email';
                return;
            }
            $check = Cmwdb::$db->insert('usr_suscriptions', ['email'=>$email, 'date'=>Cmwdb::$db->now()]);
            if ($check) {
                echo '1';
            }else{
                echo 'Something went wrong, please try again';
            }
            return;
        }

        if ($action == 'get_product_list') {
            $this->renderPartial('get_product_list');

        }
        if ($action == 'add_product_to_cart') {
//            var_dump($_POST);
            CFrontCart::AddToCart($_POST['data'], $_POST['type']);
            $this->renderPartial('cart_content');

        }
        if ($action == 'inc_product_count_in_cart') {
//            var_dump($_POST);
            echo CFrontCart::IncCount($_POST['id'], $_POST['type']);
            return;
        }
        if ($action == 'dec_product_count_in_cart') {
            echo CFrontCart::DecCount($_POST['id'], $_POST['type']);
            return;
        }
        if ($action == 'delete_product_from_cart') {
            CFrontCart::DeleteFromCart($_POST['id'], $_POST['type']);
            $this->renderPartial('cart_content');

        }

        if ($action == 'delete_product_from_wishlist') {
            CFrontWishlist::DeleteFromWishlist((int)$_POST['id']);
            var_dump($_SESSION);
        }

        if ($action == 'add_product_to_wishlist') {
            CFrontWishlist::AddToWishlist((int)$_POST['id']);
        }
        if ($action == 'check_email_exists') {
            if (CUser::HasMail($_POST['email'])) echo 1; else echo 0;
        }
        if ($action == 'get_states') {

            CModule::LinkModule('addressing');

            $states = CAddressing::GetStates($_POST['id'], CLanguage::getCurrentUser());
            if ($states) {
                $html = '<option value="">' . CDictionaryUser::GetKey('select') . '</option>';
                foreach ($states as $key => $state) {
                    $html .= '<option value="' . $key . '">' . $state . '</option>';
                }
                echo $html;
            } else {
                echo 0;
            }
            return false;
        }
        if ($action == 'get_cities') {
            CModule::LinkModule('addressing');
            $cities = CAddressing::GetCities($_POST['id'], CLanguage::getCurrentUser());
            if ($cities) {
                $html = '<option value="">' . CDictionaryUser::GetKey('select') . '</option>';
                foreach ($cities as $key => $state) {
                    $html .= '<option value="' . $key . '">' . $state . '</option>';
                }
                echo $html;
            } else {
                echo 0;
            }
            return false;
        }
        if ($action == 'get_communities') {
            CModule::LinkModule('addressing');
            $communities = CAddressing::GetCommunities($_POST['id'], CLanguage::getCurrentUser());
            if ($communities) {
                $html = '<option value="">' . CDictionaryUser::GetKey('select') . '</option>';
                foreach ($communities as $key => $state) {
                    $html.= '<option value="' . $key . '">' . $state . '</option>';
                }
            }else{
                echo 0;
            }
            return false;
        }
        if ($action == 'chechout_shipping_info') {
            unset($_POST['action']);
            CModule::LinkModule('checkout');
            COrder::AddStep('shipping', $_POST);
            if (isset($_POST['use_as_billing'])) {
                unset($_POST['use_as_billing']);
                COrder::AddStep('billing', $_POST);
            }
            CModule::LinkModule('shipping');
            echo json_encode(CShipping::Calculate(['shipping_to' => $_POST['country']]));
            die;
//            var_dump();

//            var_dump($_SESSION);

//            $this->renderPartial('chechout_shipping_info');

        }
        if ($action == 'chechout_billing_info') {
            unset($_POST['action']);
            CModule::LinkModule('checkout');
            COrder::AddStep('billing', $_POST);

            COrder::AddStep('billing', $_POST);
            echo 1;
//            $this->renderPartial('chechout_shipping_info');

        }
        if ($action == 'checkout_payment_info') {

            unset($_POST['action']);
            CModule::LinkModule('checkout');
            COrder::AddStep('payment', $_POST);
            $this->renderPartial('checkout_order_review');
            return;
//            $this->renderPartial('chechout_shipping_info');

        }
        if ($action == 'checkout_create_order') {
            unset($_POST['action']);
            CModule::LinkModule('checkout');

            COrder::AddStep('order_datas', $_POST);
            $res = COrder::CreateOrder();
            if($res['status']){
                $res['result']['url'] = CUrlManager::GetStaticURL('checkout','success');
            }

            echo json_encode($res);
            return;

//            $this->renderPartial('chechout_shipping_info');

        }

        return false;
    }

    public function render($data = null)
    {
        //parent::render($data); // TODO: Change the autogenerated stub
    }

    public function renderPartial($filename)
    {
        require_once $this->_viewDir . DIRECTORY_SEPARATOR . self::$_controllerID . DIRECTORY_SEPARATOR . $filename . '.php';
    }

}