<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_tag': {

//            var_dump($_POST);
//            die;

            $tag = CModule::LoadModule('tags');
            $data = $_POST['lang'];
            if ($tag->CreateTag($data)) {
                header('Location: index.php?module=tags&submenu=all');
                CMessage::setFlash('message', 'Tag Added');
            }else{
                var_dump($_POST);
            }

            break;

        }
        case 'edit_tag': {
//
//            var_dump($_POST);
//            die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];

            $tag = CModule::LoadModule('tags');
            if ($tag->UpdateDetails($data, $edit_id)) {
                header('Location: index.php?module=tags&submenu=all');
                CMessage::setFlash('message', 'Tag Edited');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");
die;
            break;

        }
        case 'delete_tag_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $tag = CModule::LoadModule('tags');
            if ($post->Delete($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', 'Tag Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'activate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $tag = CModule::LoadModule('tags');
            if ($tag->Publish($id)) {
                echo "Post was not edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'passivate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $tag = CModule::LoadModule('tags');
            if ($tag->Passive($id)) {
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