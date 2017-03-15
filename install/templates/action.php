<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'step1': {
            $db_host = isset($_POST['db_host']) ? $_POST['db_host'] : null;
            $db_name = isset($_POST['db_name']) ? $_POST['db_name'] : null;
            $db_user = isset($_POST['db_user']) ? $_POST['db_user'] : null;
            $db_pass = isset($_POST['db_pass']) ? $_POST['db_pass'] : null;

            $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_name);

            if (mysqli_connect_errno()) {
                CMessage::setFlash('err_msg', "Connect failed: " . mysqli_connect_error());
                header("Location: ?");
                exit();
            }

            $_SESSION['runtime_config']['step1'] = [
                'db_host' => $db_host,
                'db_name' => $db_name,
                'db_user' => $db_user,
                'db_pass' => $db_pass,
            ];

            if (isset($_SERVER['HTTPS'])) {
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            } else {
                $protocol = 'http';
            }
            $protocol .= "://";
            $host = $_SERVER['HTTP_HOST'];

            $domain = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "install/"));

            CConfig::AddBlock($protocol . $host . $domain, 'config', 'base_url');
            CConfig::AddBlock([
                'DB_HOST' => $db_host,
                'DB_USER' => $db_user,
                'DB_PASS' => $db_pass,
                'DB_NAME' => $db_name
            ], 'config', 'Cmwdb');
            header("Location: ?");
            exit();
            break;
        }
        case 'back_to_step1': {
            unset($_SESSION['runtime_config']['step1']);
            header("Location: ?");
            break;
        }

        case 'step2': {
            $langs = isset($_POST['selected_langs']) ? $_POST['selected_langs'] : [];

            if (!in_array($_POST['default_lang'], $langs)) {
                array_unshift($langs, $_POST['default_lang']);
            }
            $lang_arr = $config_data['full_langs'];
            $not_has_indexes = [];
            foreach ($lang_arr['user_langs'] as $key1 => $item) {
                $has_item = false;
                foreach ($langs as $key2 => $item2) {
                    if ($item['key'] == $item2) {

                        $has_item = true;
                        break;
                    }
                }
                if (!$has_item) {
                    $not_has_indexes[] = $key1;
                }
            }
            foreach ($not_has_indexes as $item) {
                unset($lang_arr['user_langs'][$item]);
            }

            foreach ($lang_arr['user_langs'] as $item) {
                if ($item['key'] == $_POST['default_lang']) {
                    $new_lang_user[] = $item;
                    break;
                }
            }
            foreach ($lang_arr['user_langs'] as $item) {
                if ($item['key'] != $_POST['default_lang']) {
                    $new_lang_user[] = $item;
                }
            }
            $lang_arr['user_langs'] == $new_lang_user;
            $lang_arr['default_user'] = $_POST['default_lang'];

            CConfig::AddBlock($lang_arr, 'config', 'CLanguage');

            $db_host = isset($_SESSION['runtime_config']['step1']['db_host']) ? $_SESSION['runtime_config']['step1']['db_host'] : null;
            $db_name = isset($_SESSION['runtime_config']['step1']['db_name']) ? $_SESSION['runtime_config']['step1']['db_name'] : null;
            $db_user = isset($_SESSION['runtime_config']['step1']['db_user']) ? $_SESSION['runtime_config']['step1']['db_user'] : null;
            $db_pass = isset($_SESSION['runtime_config']['step1']['db_pass']) ? $_SESSION['runtime_config']['step1']['db_pass'] : null;

            $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
            $mysqli->multi_query($db_dump_simple);

            do {
                if ($res = $mysqli->store_result()) {
                    var_dump($res->fetch_all(MYSQLI_ASSOC));
                    $res->free();
                }
            } while ($mysqli->more_results() && $mysqli->next_result());


            if ($_POST['site_type'] == 'simple') {
                $not_required_modules = [
                    'product_category',
                    'brand',
                    'product',
                    'product_attributes',
                    'attributika',
                    'discount',
                    'tags',
                    'cart',
                    'addressing',
                ];
                foreach ($not_required_modules as $item) {
                    unset($config_modules[$item]);
                }

                CConfig::AddUpdateArea($config_modules, 'modules');

                CConfig::AddBlock('default_theme_basic', 'config', 'theme');

                $_SESSION['runtime_config']['step2'] = true;
                break;
            }


            if ($_POST['site_type'] == 'shop') {
                $mysqli->multi_query($db_dump_catalog);
                do {
                    if ($res = $mysqli->store_result()) {
                        var_dump($res->fetch_all(MYSQLI_ASSOC));
                        $res->free();
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());

                $mysqli->multi_query($db_dump_shop);
                do {
                    if ($res = $mysqli->store_result()) {
                        var_dump($res->fetch_all(MYSQLI_ASSOC));
                        $res->free();
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());


                $currency_list = $_POST['currency_list'];
                $save_currency_config = [];
                if (!in_array($_POST['default_currency_shop'], $currency_list)) {
                    array_unshift($currency_list, $_POST['default_currency_shop']);
                }
                foreach ($save_currency_config as $item) {
                    $save_currency_config[$item] = $config_data['currency'][$item];
                }

                CConfig::AddBlock($save_currency_config, 'config', 'CCurrency');

                CConfig::AddUpdateArea($config_modules, 'modules');

                CConfig::AddBlock('default_theme_shop', 'config', 'theme');

                $_SESSION['runtime_config']['step2'] = true;
                break;

            }

            if ($_POST['site_type'] == 'catalog') {
                $mysqli->multi_query($db_dump_catalog);
                do {
                    if ($res = $mysqli->store_result()) {
                        $res->free();
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());

                $not_required_modules = [
                    'addressing',
                ];
                foreach ($not_required_modules as $item) {
                    unset($config_modules[$item]);
                }

                unset($config_modules['product']['components']['multyprice']);
                CConfig::AddUpdateArea($config_modules, 'modules');

                CConfig::AddBlock('default_theme_catalog', 'config', 'theme');

                $_SESSION['runtime_config']['step2'] = true;
                break;

            }

            break;


        }

        case 'back_to_step2': {
            unset($_SESSION['runtime_config']['step2']);
            header("Location: ?");

            break;
        }
        case 'step3': {
            //$check = CUserAdmin::CreateUser(['login' => $_POST['admin_login'], 'password' => $_POST['admin_pass']]);
            if (isset($_POST['admin_login']) && isset($_POST['admin_pass'])) {
                $db_host = isset($_SESSION['runtime_config']['step1']['db_host']) ? $_SESSION['runtime_config']['step1']['db_host'] : null;
                $db_name = isset($_SESSION['runtime_config']['step1']['db_name']) ? $_SESSION['runtime_config']['step1']['db_name'] : null;
                $db_user = isset($_SESSION['runtime_config']['step1']['db_user']) ? $_SESSION['runtime_config']['step1']['db_user'] : null;
                $db_pass = isset($_SESSION['runtime_config']['step1']['db_pass']) ? $_SESSION['runtime_config']['step1']['db_pass'] : null;
                $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
                $mysqli_result = $mysqli->query("SELECT * FROM `adm_users` WHERE us_login='" . $_POST['admin_login'] . "'");
                if (!$mysqli_result->num_rows) {
                    $salt = md5(date("Y:m:d H:i:s"));
                    $pass = md5($_POST['admin_pass']);
                    $q = $mysqli->query("INSERT INTO `adm_users` (us_login,us_password,salt) VALUES ('" . $_POST['admin_login'] . "','" . $pass . "','" . $salt . "');");
                    if ($q) {
                        CConfig::AddBlock(['status' => true], 'config', 'installer');
                        unset($_SESSION['runtime_config']);
                        sleep(3);
                        header("Location: " . ADMIN_URL);
                    }
                } else {
                    header("Location: ?");
                }
                break;
            }
        }
        default :
            break;
    }
}
?>