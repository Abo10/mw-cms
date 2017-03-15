<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_post': {
//            var_dump($_POST);
//            die;
 	        $post = new CPost();
            $data = $_POST['lang'];
            if ($post->CreatePost($data)) {
                header('Location: index.php?menu=post&submenu=all');
                CMessage::setFlash('message', CDictionary::GetKey('post_added'));
            }else{
                var_dump($_POST);
            }

            break;

        }
        case 'edit_post': {

            //var_dump($_POST);
            //die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];
            $post = new CPost();
            if ($post->EditPost($data, $edit_id)) {
                header('Location: index.php?menu=post&submenu=all&edit_id=' . $edit_id);
                CMessage::setFlash('message', CDictionary::GetKey('post_edited'));
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

            $post = new CPost();
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
            var_dump($update);
            if (true) {
                echo "Post was not edited<br>";
                //header('Location: index.php?menu=post&submenu=all&edit_id=' . $edit_id);
                //CMessage::setFlash('message', CDictionary::GetKey('post_edited'));
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'delete_post_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $post = new CPost();
            if ($post->Delete($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', CDictionary::GetKey('post_deleted'));
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'post_activate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $post = new CPost();
            if ($post->Publish($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'post_passivate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $post = new CPost();
            if ($post->Passive($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'update_post_order': {

            var_dump($_POST);
            die;

            $id = $_POST['id'];


            $post = new CPost();
            if ($post->Passive($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        default :
            break;
    }
}
?>