<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_menu_elements': {
            $data = $_POST['data'];
            //var_dump($data);
            $data = json_decode($data,true);
            $menu = new CStdMenu();
            $menu->InsertMenuItems($data);
            break;

        }
        case 'add_menu': {
            $menu_name = $_POST['menu_name'];
            $menu = new CStdMenu();
            $ret = $menu->CreateMenuSchema($menu_name);

            print_r($ret);

            break;
        }
        case 'get_menu_elements': {
            $id = $_POST['id'];
            $menu = new CStdMenu($id);
            if ($menu->IsHaveElements()) {
                $menu->CollectDOM_admin(__DIR__ . '/menu_template.php');
            }
            break;
        }
        default :
            break;
    }
}
?>