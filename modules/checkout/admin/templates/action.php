<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'delete_order_item': {

            $id = (int)$_POST['delete_id'];
            CModule::LinkModule('checkout');


            if (COrder::DeleteOrder($id)) {
                CMessage::setFlash('message', CDictionary::GetKey('order_deleted'));
            }


            break;

        }

        default :
            break;
    }
}
?>