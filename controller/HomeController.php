<?php


class HomeController extends CController
{

    public function __construct($slug, $layout = '')
    {
        parent::__construct();
        $this->seo = [
          'seo_title'=>self::$_pageProp['s_title'],
          'seo_descr'=>self::$_pageProp['s_descr'],
          'seo_keywords'=>self::$_pageProp['s_keywords']
        ];

    }



}