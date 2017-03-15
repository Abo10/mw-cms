<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'edit_options': {
            $data = $_POST['lang'];
            $link = $_POST['link'];
            $page_prop = new CPageProp();

            //var_dump($_POST);die;

            if ($page_prop->CoreInitial($data, $link)) {
                header('Location: index.php?menu=options');
                //CMessage::setFlash('message','Page Added');
            }
            break;
        }
        case 'add_favicon': {

            $page_prop = new CPageProp();
            if($page_prop->UploadFavicon('favicon')){
                header('Location: index.php?menu=options');
            }
            die;
            //var_dump($_POST);die;

            if ($page_prop->CoreInitial($data, $link)) {
                header('Location: index.php?menu=options');
                //CMessage::setFlash('message','Page Added');
            }
            break;
        }
        case 'update_sitemap': {
            $data = file_get_contents(URL_BASE . 'console.php');
            echo CDictionary::GetKey('update_sitemap');
            echo ' (';
            echo CDictionary::GetKey('last_update') . ': ';
            echo date('Y:m:d H:i:s', CSitemap::GetLastUpdate());
            echo ')';
            break;
        }
        default :
            break;
    }
}
?>