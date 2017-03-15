<?php
define("LIB_BASE", __DIR__);
// define("modules_dir", $_)
define("FLIB_BASE", LIB_BASE . '/../flib/');
define("MW_CONFIGS", LIB_BASE . '/../configs/');
define('CONFIG_DIR', LIB_BASE . '/../configs/');
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $client_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $client_ip = $_SERVER['REMOTE_ADDR'];
}

require_once 'CConfig.php';

CConfig::getInstance();
$url = CConfig::GetConfig('base_url');
if ($url !== CONFIG_NO_ENTRY)
    define("URL_BASE", $url);
else define("URL_BASE", 'http://localhost/');

$theme = CConfig::GetConfig('theme');

define('ASSETS_BASE', URL_BASE . 'web' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR);

// if (strpos($client_ip,'192.168')===false) {
// 	//define("URL_BASE", 'http://192.168.4.2/glocal.local/');
//     define("URL_BASE", 'http://109.75.37.87/mastershop.local/');
// } else {
//     define("URL_BASE", 'http://192.168.4.2/mastershop.local/');
// }

define('ADMIN_URL', URL_BASE . 'mw-admin/');
define('ADMIN_DIR', __DIR__ . '/../mw-admin/');


// function __autoload($class_name)
// {
// 	if(file_exists(LIB_BASE . '/' . $class_name . '.php'))
//     	require_once(LIB_BASE . '/' . $class_name . '.php');
// 	else{
// 		if(file_exists(FLIB_BASE.$class_name.'.php')){
// 			require_once(FLIB_BASE.$class_name.'.php');
// 		}
// 		else
// 			require_once(LIB_BASE . '/../controller/' . $class_name . '.php');
// 	}
// }

function mw_autoload($class_name)
{
    //Simple directories for libraries
    $array_paths = array(
        LIB_BASE,
        FLIB_BASE,
        LIB_BASE . '/../controller/',
    );
    //Try load from standart directories
    foreach ($array_paths as $path) {
        if (file_exists($path . '/' . $class_name . '.php')) {
            require_once($path . '/' . $class_name . '.php');
            return;
        }
    }

    //Try load from flibs of modules
    $modules = CModule::GetModulesList();
    foreach ($modules as $module_name) {
        if (file_exists(LIB_BASE . '/../modules/' . $module_name . '/front/flib/' . $class_name . '.php')) {
            require_once(LIB_BASE . '/../modules/' . $module_name . '/front/flib/' . $class_name . '.php');
            return;
        }
        if (file_exists(LIB_BASE . '/../modules/' . $module_name . '/front/' . $class_name . '.php')) {
            require_once(LIB_BASE . '/../modules/' . $module_name . '/front/' . $class_name . '.php');
            return;
        }
    }

}

spl_autoload_register('mw_autoload');


interface IInteractivity
{
    function Interactive($argv = null);
}

interface ILogable
{
    function SetLog($from, $argv = null);

    function GetLog($log_id);
}

interface ISecurable
{
    function VerifyPermission($from = null, $to = null, $action = null, $argv = null);

    function SetPermission($from, $to = "all", $action = "all", $permission = "deny");
}

interface IFileActionable
{
    function WriteContent($url, $mode = true);
}

interface IConfigurable
{
    function CreateParentNode($node_name);

    function GetParentNode($node_name);

    function CreateSubNode($parent_node, $sub_node);

    function GetSubNode($sub_node, $parent_node = null);

    function AddValuesInNode($parent_node, $sub_node, $argv);

    function GetValuesInNode($parent_node, $sub_node, $argv = null);
}

interface IErrorHandleable
{
    static function RegisterHandle($handle_shortkey = null);
}

interface IShippingCore
{
    static function Calculate(array $args, $method = "local");

    static function AddShipping(array $args, $order_id, $method = "local");

    static function ConfirmShipping($shipping_id);

    static function CancelShipping($shipping_id);

    static function RemoveShipping($shipping_id);

    static function GetStatus($shipping_id);

    static function GetShipping($shipping_id);
}

interface IShipping
{
    static function Calculate(array $args);

    static function AddShipping(array $args, $order_id);

    static function ConfirmShipping($shipping_id);

    static function CancelShipping($shipping_id);

    static function RemoveShipping($shipping_id);

    static function GetStatus($shipping_id);

    static function GetShipping($shipping_id);
}

abstract class std_lib
{
}

abstract class fstd implements IFileActionable
{
    protected $current_url;
    protected $data_mode;
    protected $datas;

    function ReadContent($url, $mode = true)
    {
    }

    function WriteContent($url, $mode = true)
    {
    }
}

abstract class singletone_fstd implements IInteractivity, ILogable
{
    private static $_instance = null;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static public function getInstance()
    {
    }

    function Interactive($argv = null)
    {
    }

    function SetLog($from, $argv = null)
    {
    }

    function GetLog($log_id)
    {
    }
}


/**
 * @author Rafik Rushanian
 *
 */
class CFileSupport implements IFileActionable
{
    private static $_instance = null;
    static protected $InternallURL = null;
    static protected $Dict = null;
    static protected $DectHierarchy = false;//Dictionary content single layer array or multyarray otherwise

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //Use to initialise object
    static function Initialise($uri = null)
    {
        echo "Step 3:" . self::$InternallURL . '<br>';
        if (file_exists(static::$InternallURL)) {
// 			echo "Try to load file ".self::$InternallURL."<br>";
            if (static::$DectHierarchy)
                static::$Dict = parse_ini_file(static::$InternallURL, true);//load dictionary as multylayer array
            else static::$Dict = parse_ini_file(static::$InternallURL);//content of dictionary is simple one layer array, so load it as simple array
//             var_dump(static::$Dict);
// 			echo "<hr>";
// 			var_dump(self::$Dict);
            return true;
        }
        return false;
    }

    static function SetAsMultyArray()
    {
        static::$DectHierarchy = true;
    }

    function WriteContent($url, $mode = true)
    {
        if (file_exists(static::$InternallURL)) {
            $handle = fopen(static::$InternallURL, "w+");
            reset(static::$Dict);
            if (static::$DectHierarchy) {
                foreach (static::$Dict as $key => $unit) {
                    $string = '[' . $key . ']' . "\r\n";
                    fwrite($handle, $string);
                    foreach ($unit as $leng_key => $sub_unit) {
                        $string = $leng_key . ' = "' . $sub_unit . '"' . "\r\n";
                        fwrite($handle, $string);

                    }
                    fwrite($handle, "\r\n");

                }
            } else {
                while ($unit = current(static::$Dict)) {
                    $string = key(static::$Dict) . ' = "' . $unit . '"' . "\r\n";
                    fwrite($handle, $string);
                    next(static::$Dict);
                }
            }
            fclose($handle);
            return true;
        }
        return false;
    }
}

function GetMenu($menu_header)
{
    $menu = new CStdMenu();
    $menu->LoadByName($menu_header);
    return $menu->CollectDOM();
}

function from_camel_case($input)
{
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

//Any global system based functions only for internall use
function CheckValueUniques($tbl_name, $field_name, $value)
{
    Cmwdb::$db->where($field_name, $value);
    $res = Cmwdb::$db->getOne($tbl_name);
    return (!empty($res));
}

if (CModule::HasModule('user')) {
    require_once 'CUser.php';
}
require_once 'CUserAdmin.php';
function GetUserDatas()
{
    try {
        if (isset($_SESSION['user'])) {
            return [
                'status' => 1,
                'result' => $_SESSION['user']
            ];
        }
        throw new Exception('No user loged', 1);

    } catch (Exception $error) {
        return [
            'status' => 0,
            'result' => $error->getMessage()
        ];
    }
}

//Creating simple tocken
function CreateToken()
{
    $token = md5(date("Y/m/d H:i:s"));
    $token = md5($token . rand(1, 99)) . '-';
    for ($i = 0; $i < 5; $i++)
        $token .= rand(1, 9);
    return $token;
}

?>