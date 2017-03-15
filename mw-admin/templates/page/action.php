<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_page': {
            $data = $_POST['lang'];

            $page = new CPage();
            if ($page->CreatePage($data)) {
                header('Location: index.php?menu=page&submenu=all');
                CMessage::setFlash('message', CDictionary::GetKey('page_added'));
            }


            break;

        }
        case 'edit_page': {

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];

            $page = new CPage();
            if ($page->EditPage($data, $edit_id)) {
                header('Location: index.php?menu=page&submenu=add&edit_id=' . $edit_id);
                CMessage::setFlash('message', CDictionary::GetKey('page_edited'));

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

            $post = new CPage();
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }

        case 'delete_page_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $page = new CPage();
            if ($page->Delete($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', CDictionary::GetKey('page_deleted'));
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'page_activate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $page = new CPage();
            if ($page->Publish($id)) {
                //echo "Page was  edited<br>";

                //CMessage::setFlash('message', 'Post Deleted');
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'page_passivate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];


            $page = new CPage();
            if ($page->Passive($id)) {
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