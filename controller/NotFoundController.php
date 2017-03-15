<?php


class NotFoundController extends CController
{

    public $seo = ['seo_title' => 'NOT FOUND', 'seo_descr' => '', 'seo_keywords' => ''];


    public function __construct($slug = null, $layout = '')
    {
        parent::__construct();
    }

}