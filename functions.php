<?php

function get_post($oid=null,$args = null,$count=10,$page = 1)
{
    CFrontPost::Initial();
    return CFrontPost::GetDatas($oid, $args,$count,$page);
}
function get_menu($name){
    $obj =  new CStdMenu();
    $obj->LoadByName($name);
    return $obj;
}
function post_attr($obj_id,$args=null,$grouped=false){
    CFrontAttrList::Initial();
    return CFrontAttrList::GetDatas($obj_id,'post',$args,$grouped);
}
function post_maps($post_id = null){
    if(!$post_id){
        $post_id =  CWebApp::$_controller->obj_id;
    }
    CFrontPost::Initial();

    return CFrontPost::GetMaps($post_id);
}
function page($page_id){

    CFrontPage::Initial();

    return CFrontPage::GetDatas($page_id);
}
function get_post_by_cat($oid=null,$args = null,$count=10,$page=1){
    CFrontCategoryPost::Initial();
    return CFrontCategoryPost::GetAllPosts($oid,$args ,$count,$page);
}
