<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_product_attribute': {
//            var_dump($_POST);
//            die;

            $data = $_POST['lang'];
            $units = $_POST['units'];
            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $a = $attr_obj->AddAttribute($data, $units);

            if ($a) {
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('product_Added')); 
                die;
            }

            break;

        }
        case 'edit_product_attribute': {


            $data = $_POST['lang'];
            $units = $_POST['units'];
            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $a = $attr_obj->EditAttribute($data, $units, $_POST['edit_id']);
            if ($a) {
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('attribute_edeted')); 
                die;
            }

            break;

        }
        case 'get_attr_values_units': {
//            var_dump($_POST);
//            die;

            $id = $_POST['id'];
            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $a = $attr_obj->GetUnitsJSON($id);
            echo $a;
            die;


            break;

        }
        case 'add_product_attribute_value': {

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $a = $attr_obj->AddValues($_POST['values'], $_POST['attr_group']);
            if ($a) {
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('value_added'));
                die;
            }else{
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('value_not_added'));
            }

            break;

        }
        case 'edit_product_attribute_value': {

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $a = $attr_obj->EditValues($_POST['values'], $_POST['attr_group']);
            if ($a) {
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('value_edited')); 
                die;
            }else{
                header('Location: index.php?module=product_attributes&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('value_not_edited'));
                die;
            }

            break;

        }
        case 'edit_product_attribute_value_modal': {
//            var_dump($_POST);die;

            $attr_obj = CModule::LoadModule('attributika');

            $a = $attr_obj->JustAddValues($_POST['values'], $_POST['attr_group']);

            if (is_array($a)) {
                echo json_encode($a);
            }else{
                echo json_encode([]);
            }

            break;

        }
        case 'get_subjects': {
//            var_dump($_POST);die;

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            if (isset($_POST['data'])) {
                $a = $attr_obj->GetSubjectsJSON($_POST['data'], 'product_category');
                echo $a;
            }else{
                echo json_encode([]);
            }

            break;

        }
        case 'attribute_activate': {
//            var_dump($_POST);die;

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $attr_obj->ActivatePasivateAttr($_POST['id']);

            break;
        }
        case 'attribute_passivate': {
//            var_dump($_POST);die;

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $attr_obj->ActivatePasivateAttr($_POST['id']);
            break;

        }
        case 'delete_attr_item': {
//            var_dump($_POST);die;

            $attr_obj = CModule::LoadModule('attributika');
            //           unset($_POST['predefines']['brand']);
            $attr_obj->DeleteAttribute($_POST['id']);
            break;

        }



        case 'edit_product': {

//            var_dump($_POST);
//            die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];
            $product_obj = CModule::LoadModule('product');
            //unset($_POST['predefines']['brand']);
            $a = $product_obj->EditProduct($edit_id, $data, $_POST['predefines']);
            if ($a) {
                header('Location: index.php?module=product&submenu=add&edit_id=' . $edit_id);
                CMessage::setFlash('message', 'Product Edited');
                die;
            }

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
        case 'edit_post': {

            //var_dump($_POST);
            //die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];
            echo "Try to edit post<br>";
            $post = new CPost();
            if ($post->EditPost($data, $edit_id)) {
                echo "Post was not edited<br>";
                header('Location: index.php?menu=post&submenu=all&edit_id=' . $edit_id);
                CMessage::setFlash('message', 'Post Edited');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'delete_product_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];
            $product_obj = CModule::LoadModule('product');
            if ($product_obj->DeleteProduct($delete_id)) {

                CMessage::setFlash('message', 'Product Deleted');
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