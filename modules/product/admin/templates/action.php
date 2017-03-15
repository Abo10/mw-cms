<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_product': {
//            print_r($_POST);
//            die;
            $data = $_POST['lang'];
            $product_obj = CModule::LoadModule('product');
            $multiprice = isset($_POST['multiprice']) ? $_POST['multiprice'] : null;
            $attr_vals = isset($_POST['attr_vals'])?$_POST['attr_vals']:null;
            //           unset($_POST['predefines']['brand']);
            $a = $product_obj->CreateProduct($data, $_POST['predefines'], $multiprice, $attr_vals);
            if ($a) {
                header('Location: index.php?module=product&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('product_added'));
                die;
            }

            break;


        }
        case 'edit_product': {

//            print_r($_POST);
//            die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];
            $product_obj = CModule::LoadModule('product');
            if (isset($_POST['multiprice'])) {
                $multiprice = $_POST['multiprice'];
            } else {
                $multiprice = null;
            }
            if (isset($_POST['attr_vals'])) {
                $attr_vals = $_POST['attr_vals'];
            } else {
                $attr_vals = null;
            }
            //unset($_POST['predefines']['brand']);
            $a = $product_obj->EditProduct($edit_id, $data, $_POST['predefines'], $multiprice, $attr_vals);
            if ($a) {
                header('Location: index.php?module=product&submenu=add&edit_id=' . $edit_id);
                CMessage::setFlash('message', CDictionary::GetKey('product_edited'));
                die;
            }

            break;

        }
        case 'edit_slug': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];
            $lang = $_POST['lang'];
            $slug = $_POST['slug'];

            $post = CModule::LoadModule('product');
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }
        case 'check_code': {
//            var_dump($_POST);
//            die;
            $code = $_POST['code'];
            $product_obj = CModule::LoadModule('product');

            if ($product_obj->VerifyCodeUnique($code)) {
                echo 1;
            } else {
                echo 0;
            }
            break;
        }
        case 'update_product_order': {

            $id = $_POST['id'];
            $order = $_POST['order'];

            $product_obj = CModule::LoadModule('product');

            echo $product_obj->UpdateOrder($id, $order);
            break;
        }
//        case 'edit_post': {
//
//            //var_dump($_POST);
//            //die;
//
//            $edit_id = $_POST['edit_id'];
//            $data = $_POST['lang'];
//            echo "Try to edit post<br>";
//            $post = new CPost();
//            if ($post->EditPost($data, $edit_id)) {
//                echo "Post was not edited<br>";
//                header('Location: index.php?menu=post&submenu=all&edit_id=' . $edit_id);
//                CMessage::setFlash('message', 'Post Edited');
//                die;
//            }
//
//            echo '<pre>';
//            //header("Location: index.php?menu=page&submenu=add_page");
//
//            break;
//
//        }
        case 'delete_product_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];
            $product_obj = CModule::LoadModule('product');
            if ($product_obj->DeleteProduct($delete_id)) {

                    CMessage::setFlash('message', CDictionary::GetKey('product_deleted'));
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'product_activate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $product_obj = CModule::LoadModule('product');
            if ($product_obj->ActivePassiveProduct($id)) {

                die;
            }

            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'product_passivate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $product_obj = CModule::LoadModule('product');
            if ($product_obj->ActivePassiveProduct($id)) {
                die;
            }

            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        default :
            break;
    }
}
?>