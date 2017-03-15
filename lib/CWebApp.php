<?php

/**
 * Created by PhpStorm.
 * User: abo
 * Date: 11/27/2015
 * Time: 12:35 PM
 */
class CWebApp
{
    static $_controller;  //Globally accessible Controller instance
    static $_controllerPath;
    static $_controllerName;
    static $_controllerID; //Controller ID used in most cases
    static $_action;

    static $_contextID;  //the same $_controllerID in most cases
    static $_objID;  //the same $_controllerID in most cases
    static $_contextType;  //main or module

    static $_menuElemID;  //the same $_controllerID in most cases
    static $_menuType;  //main or module

    static $_breadcrumb; // (array) overwritable in eevery controller
    static $_params;  // (array) globbaly accessible wariable for storing params
    static $_pageProp;  // (array) globbaly accessible wariable for storing params

    static $css_asset = [];
    static $js_asset = [];

    public function __construct()
    {

    }

    static function run($controllerAction)
    {

        if (isset($controllerAction['controller'])) {
            self::$_contextType = 'MAIN';
            self::$_controllerPath = __DIR__ . '/../controller/' . $controllerAction['controller'] . ".php";
            self::$_controllerName = $controllerAction['controller'];
            self::$_controllerID = from_camel_case(substr_replace($controllerAction['controller'], '', strpos($controllerAction['controller'], 'Controller')));
        }

        if (isset($controllerAction['module'])) {
            self::$_contextType = 'MODULE';
            $str = '';
            $module_explode = explode('_', $controllerAction['module']);

            foreach ($module_explode as $item) {
                $str .= ucfirst($item);
            }

            $str .= 'ModuleController';

            self::$_controllerID = $controllerAction['module'];
            self::$_controllerPath = __DIR__ . '/../modules/' . $controllerAction['module'] . '/front/' . $str . '.php';

            self::$_controllerName = $str;

        }

        $page_prop = new CPageProp();
        self::$_pageProp = $page_prop->GetCurrentProps();

        // require_once self::$_controllerPath;

        self::$_contextID = self::$_controllerID;

        self::$_menuType = self::$_controllerID;
        self::$_action = $controllerAction['action'];
        self::$_controller = new self::$_controllerName(self::$_action);
        self::$_controller->start();
    }

    static function RegisterCss($css)
    {
        if (is_array($css)) {
            self::$css_asset = array_merge(self::$css_asset, $css);
            self::$css_asset = array_unique(self::$css_asset);
            return;
        }
        if ($css) {
            self::$css_asset[] = $css;
            self::$css_asset = array_unique(self::$css_asset);
            return;
        }
    }

    static function RegisterJS($js)
    {
        if (is_array($js)) {
            self::$js_asset = array_merge(self::$js_asset, $js);
            self::$js_asset = array_unique(self::$js_asset);
            return;
        }
        if ($js) {
            self::$js_asset[] = $js;
            self::$js_asset = array_unique(self::$js_asset);
            return;
        }
    }

    static function AddParam($key, $value)
    {
        self::$_params[$key] = $value;
        return true;
    }

    static function GetParam($key)
    {
        if (isset(self::$_params[$key])) {
            return self::$_params[$key];
        }
        return null;
    }

    static function isAjaxRequest()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

}