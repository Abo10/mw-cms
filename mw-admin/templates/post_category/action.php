<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_category': {
            $category = new CCategoryPost();
            $data = $_POST['lang'];

            if ($category->CreateCategory($data)) {
                CMessage::setFlash('message', CDictionary::GetKey('post_cat_added'));
                header('Location: index.php?menu=post_category&submenu=all2');
            }

            break;

        }
        case 'edit_category': {

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];

            $category = new CCategoryPost();
            if ($category->EditPage($data, $edit_id)) {
                CMessage::setFlash('message', CDictionary::GetKey('post_cat_edited'));
                header('Location: index.php?menu=post_category&submenu=add&edit_id=' . $edit_id);
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

            $post = new CCategoryPost();
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }
        case 'order_category': {

            $data = $_POST['data'];

            $category = new CCategoryPost();
            if ($category->UpdateOrders($data)) {

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


            $cat = new CCategoryPost();
            if ($cat->DeleteCategory($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', CDictionary::GetKey('post_cat_deleted'));
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


            $cat = new CCategoryPost();
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


            $cat = new CCategoryPost();
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

            $cat = new CCategoryPost();
            echo (int)$cat->UpdateOrder($id, $order);


            break;

        }
        default :
            break;
    }
}
?>