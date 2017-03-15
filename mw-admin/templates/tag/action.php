<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_tag': {

//            var_dump($_POST);
//            die;

            $tag = new CStdTags();
            $data = $_POST['lang'];
            if ($tag->CreateTag($data)) {
                header('Location: index.php?menu=tag&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('tag_added'));
            }else{
                var_dump($_POST);
            }

            break;

        }
        case 'edit_tag': {

        /*    var_dump($_POST);
            die;*/

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];

            $tag = new CStdTags();
            if ($tag->UpdateDetails($data, $edit_id)) {
                header('Location: index.php?menu=tag&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('tag_edited'));
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

            $post = new CStdTags();
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }
        case 'delete_tag_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $post = new CTagsList();
            if ($post->Delete($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', CDictionary::GetKey('tag_deleted'));
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


            $post = new CTagsList();
            if ($post->Publish($id)) {
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


            $post = new CTagsList();
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