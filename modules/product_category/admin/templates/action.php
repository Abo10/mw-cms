<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_category': {
            $category = CModule::LoadModule('product_category');
            $data = $_POST['lang'];

            if ($category->CreateCategory($data)) {
                header('Location: index.php?module=product_category&submenu=all2');
                CMessage::setFlash('message', 'Category Added');
            }

            break;

        }
        case 'edit_category': {

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];

            $category =  CModule::LoadModule('product_category');
            if ($category->EditCategory($data, $edit_id)) {
                header('Location: index.php?module=product_category&submenu=add&edit_id=' . $edit_id);
                CMessage::setFlash('message', 'Category Edited');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'edit_slug': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];
            $lang = $_POST['lang'];
            $slug = $_POST['slug'];

            $post = CModule::LoadModule('product_category');
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }
        case 'order_category': {

            $data = $_POST['data'];

            $category =  CModule::LoadModule('product_category');
            if ($category->UpdateOrders($data)) {

                //CMessage::setFlash('message', 'Category Edited');
                echo 1;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'cat_apply_discount': {

            $data = $_POST['data'];

            $category =  CModule::LoadModule('product_category');
            var_dump($category->ApplyDiscount($data['cat_id'],$data['value'],$data['select_2'],$data['select_1']));die;
            if (true) {

                //CMessage::setFlash('message', 'Category Edited');
                echo 1;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'delete_cat_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $cat =  CModule::LoadModule('product_category');
            if ($cat->DeleteCategory($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', 'Category Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'cat_activate': {

//            var_dump($_POST);
//            die;

            $id = $_POST['id'];


            $cat =  CModule::LoadModule('product_category');
            if ($cat->Activate($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'cat_passivate': {

//            var_dump($_POST);
//            die;

            $id = $_POST['id'];


            $cat =  CModule::LoadModule('product_category');
            if ($cat->Disable($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'update_post_order': {

//            var_dump($_POST);
//            die;

            $id = $_POST['id'];
            $order = $_POST['order'];

            $cat =  CModule::LoadModule('product_category');
            echo (int)$cat->UpdateOrder($id, $order);


            break;

        }
        default :
            break;
    }
}
?>