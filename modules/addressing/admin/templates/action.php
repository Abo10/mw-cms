<?php
//var_dump($_POST);
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_country': {
            CModule::LinkModule('addressing');
            $res = CAddressing::AddCountry($_POST);

            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'edit_country': {
            CModule::LinkModule('addressing');
            $res = CAddressing::EditCountry($_POST['edit_id'], $_POST);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'country': {
            CModule::LoadTemplate('addressing', 'add_country');
            break;
        }
        case 'add_state': {
            $country = $_POST['country'];
            CModule::LinkModule('addressing');
            $res = CAddressing::AddState($country,$_POST['lang']);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'edit_state': {
            $country = $_POST['country'];
            CModule::LinkModule('addressing');
            $res = CAddressing::EditState($_POST['edit_id'],$_POST);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'state': {
            $country = $_POST['country'];
            CModule::LoadTemplate('addressing', 'add_state', ['country' => $country]);
            break;

        }
        case 'get_states': {
            $country = $_POST['country'];
            CModule::LinkModule('addressing');
            $states = CAddressing::GetStatesAllLangs($country);
            echo '<option value="">' . CDictionary::GetKey('select') . '</option>';

            foreach ($states as $key => $state) {
                echo '<option value="' . $key . '">' . $state['text'][CLanguage::getCurrent()] . '</option>';
            }

            break;

        }
        case 'get_cities': {
            $country = $_POST['country'];
            $state = $_POST['state'];
            CModule::LinkModule('addressing');
            $cities = CAddressing::GetCitiesAllLangs($state);
            echo '<option value="">' . CDictionary::GetKey('select') . '</option>';
            foreach ($cities as $key => $city) {
                echo '<option value="' . $key . '">' . $city['text'][CLanguage::getCurrent()] . '</option>';
            }

            break;

        }
        case 'add_city': {
            $country = $_POST['country'];
            $state = $_POST['state'];
            CModule::LinkModule('addressing');
            $res = CAddressing::AddCity($state,$_POST['lang']);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'edit_city': {

            CModule::LinkModule('addressing');
            $res = CAddressing::EditCity($_POST['edit_id'],$_POST);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'city': {
            $country = $_POST['country'];
            $state = $_POST['state'];
            CModule::LoadTemplate('addressing', 'add_city', ['country' => $country, 'state' => $state]);
            break;

        }
        case 'community': {
            $country = $_POST['country'];
            $state = $_POST['state'];
            $city = $_POST['city'];
            CModule::LoadTemplate('addressing', 'add_community', ['country' => $country, 'state' => $state,'city'=>$city]);
            break;

        }
        case 'add_community': {
            $city = $_POST['city'];
            CModule::LinkModule('addressing');
            $res = CAddressing::AddCommunity($city,$_POST['lang']);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        case 'edit_community': {
            CModule::LinkModule('addressing');
            $res = CAddressing::EditCommunity($_POST['edit_id'],$_POST);
            if (is_numeric($res)) {
                echo 1;
            } else {
                echo $res;
            }
            break;

        }
        default :
            break;
    }
}
?>