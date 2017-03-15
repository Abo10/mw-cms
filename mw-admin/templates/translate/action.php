<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'edit_translate': {
            var_dump($_POST);
            CDictionary::EditKey($_POST['data']['key'],$_POST['data']['values']);
            break;

        }
        case 'add_translate': {
            var_dump($_POST);
            CDictionary::SetKey($_POST['data']['key'],$_POST['data']['values']);

            break;

        }
        default :
            break;
    }
}
?>