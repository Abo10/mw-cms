<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'edit_attr': {
            var_dump($_POST);
            $obj = new CAttrTemplate();
            $obj->EditDatas($_POST['data']['key'], $_POST['data']['values']);
            break;

        }
        case 'add_attr': {
            $obj = new CAttrTemplate();
            if ($obj->CreateTemplate($_POST['data']['key'], $_POST['data']['values'])) {
                echo 1;
                CMessage::setFlash('message', CDictionary::GetKey('added'));

            } else {
                CMessage::setFlash('message', CDictionary::GetKey('exists'));
            }

            break;

        }
        default :
            break;
    }
}
?>