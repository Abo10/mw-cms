<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'file_upload': {
            $tmp_file = new CAttach();
            $ret_arr = array();
            foreach ($_FILES as $key => $file) {
//                echo $key;
                $r = $tmp_file->CreateAttachment($key);
                $ret_arr[]=$r;
//                var_dump($r);
            }
//            var_dump($ret_arr);die;
            echo json_encode($ret_arr);
//            echo 'Uploaded';
            break;
        }
        case 'get_img_details' : {
            $tmp_file = new CAttach($_POST['id']);
            $tmp_file->GetArray_JSON();
            echo $tmp_file->GetArray_JSON();;
            break;
        }
        case 'update_img_details' : {
            $tmp_file = new CAttach($_POST['id']);
            $tmp_file->UpdataDetails(array('descr'=>$_POST['descr'],'title'=>$_POST['title']));
            echo 1;
            break;
        }
        case 'delete_attachment' : {
            $tmp_file = new CAttach($_POST['id']);
            $tmp_file->DeleteThis();
            echo 1;
            break;
        }
        case 'show_media' : {
            include 'ajax_media.php';
//            $tmp_file = new CAttach($_POST['id']);
//            $tmp_file->UpdataDetails(array('descr'=>$_POST['descr'],'title'=>$_POST['title']));
//            echo 1;
            break;
        }

        default :
            break;
    }
}
?>