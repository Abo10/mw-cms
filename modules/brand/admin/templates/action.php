<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_brand': {
//            var_dump($_POST);
//            die;
            $data = $_POST['lang'];
            $brand = CModule::LoadModule('brand');
            if ($brand->CreateBrand($data)) {
                header('Location: index.php?module=brand&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('brand_added'));
            }else{
                var_dump($_POST);
            }

            break;

        }
        case 'edit_brand': {

            //var_dump($_POST);
            //die;

            $edit_id = $_POST['edit_id'];
            $data = $_POST['lang'];
            $brand = CModule::LoadModule('brand');
            if ($brand->EditBrand($data,$edit_id)) {
                header('Location: index.php?module=brand&submenu=add');
                CMessage::setFlash('message', CDictionary::GetKey('brand_edited'));
            }else{
                var_dump($_POST);
            }

            break;
        }
        case 'edit_slug': {

            //var_dump($_POST);
            //die;
            $id = $_POST['id'];
            $lang = $_POST['lang'];
            $slug = $_POST['slug'];

            $post = CModule::LoadModule('brand');
            $update = $post->UpdateSlug($id,$lang, $slug);
            echo json_encode($update);
            break;
        }
        case 'delete_brand_item': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];


            $brand = CModule::LoadModule('brand');
            if ($brand->DeleteBrand($delete_id)) {
                echo "Post was not edited<br>";

                CMessage::setFlash('message', CDictionary::GetKey('brand_deleted'));
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