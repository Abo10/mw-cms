<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_slider_elements': {
//            $data = $_POST['data'];
            $slider = CModule::LoadModule('slider');
            $ret = $slider->CreateSlider((int)$_POST['slider_id'],$_POST['lang'],$_POST['main']);
            var_dump($ret);
            break;

        }
        case 'add_menu': {
            $menu_name = $_POST['menu_name'];
            $slider = CModule::LoadModule('slider');

            $ret = $slider->CreateSlider($menu_name);

            print_r($ret);

            break;
        }
        case 'get_slider_elements': {
            $id = $_POST['id'];
            $slider = CModule::LoadModule('slider');
            $items = $slider->GetSlider($id);
            if(isset($items['main']) &&$items['main']){

            include_once __DIR__.'/slider_template.php';
            }
            die;
            var_dump($items);die;
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