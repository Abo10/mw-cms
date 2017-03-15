<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {


        case 'delete_user': {

            //var_dump($_POST);
            //die;

            $delete_id = $_POST['delete_id'];

            CModule::LinkModule('user');
            CUserExt::DeleteUser($delete_id);
            echo 1;
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'user_activate': {

            //var_dump($_POST);
            //die;

            $id = $_POST['id'];

            CModule::LinkModule('user');

            if (CUserExt::UnblockUser($id)['status']) {
                echo 1;
                die;
            } else {
                echo 0;
                die;
            }

            echo '<pre>';
            //header("Location: index.php?menu=page&submenu=add_page");

            break;

        }
        case 'user_block': {


            $id = $_POST['id'];

            CModule::LinkModule('user');

            if (CUserExt::BlockUser($id)['status']) {


                echo 1;
                die;
            } else {
                echo 0;
                die;
            }


            break;

        }
        default :
            break;
    }
}
?>