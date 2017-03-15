<?php
use abeautifulsite\SimpleImage;
require_once 'stdlib.php';

define("IMG_LIB", __DIR__ . '/../uploads/images/');

/**
 * Created by PhpStorm.
 * User: Rafik Rushanian
 * Date: 01.12.2015
 * Time: 13:55
 */
class CImage extends CFile
{
    protected $work_dir = IMG_LIB;
    protected $allow_types = array('gif', 'jpg', 'jpeg', 'png');
    protected $URL_original = 'original/';
    protected $URL_thumb = 'thumbs/';
    protected $URL_medium = 'medium/';

    function __construct($id = null)
    {
        if ($id) {
            Cmwdb::$db->where('id', $id);
            $res = Cmwdb::$db->getOne('std_images');
            if (!empty($res)) {
                $this->id = $id;
                $this->type = $res['type'];
                $this->url = $res['url'];
            }

        }
    }

    //Return ID in DB
    function CreateImage($file, $width = null, $height = null, $top = 0, $left = 0)
    {
        if (!isset($_FILES[$file]) || (strlen($_FILES[$file]['name']) < 2))
            return false;
        $temp = explode('.', strtolower($_FILES[$file]['name']));
        $type = end($temp);
        if (!in_array($type, $this->allow_types)) return false;
        $this->type = $type;
        $this->url = null;
        $query_data['type'] = $this->type;
        if (Cmwdb::$db->insert("std_images", $query_data)) {
            $this->id = Cmwdb::$db->getInsertId();
            $this->url = $this->id . '.' . $type;
            if (move_uploaded_file($_FILES[$file]['tmp_name'], IMG_LIB . $this->URL_original . $this->url)) {
                if ($width && $height) {
                	$tmp_img = new SimpleImage(IMG_LIB . $this->URL_original . $this->url, $width, $height);
                	$tmp_img->save();
//                 	$tmp_img = new imageLib(IMG_LIB . $this->URL_original . $this->url);
//                     $pos = $left . 'x' . $top;
//                     $tmp_img->cropImage($width, $height, $pos);
//                     $tmp_img->saveImage(IMG_LIB . $this->URL_original . $this->url);
                }
                $this->CreateThumb(IMG_LIB . $this->URL_original . $this->url);
                $this->CreateMedium(IMG_LIB . $this->URL_original . $this->url);
                Cmwdb::$db->where('id', $this->id);
                Cmwdb::$db->update("std_images", array('url' => $this->url));
                return $this->id;
            }
            $this->DeleteInBase();
        }
        return false;
    }

    protected function CreateThumb($file, $F_width = 225)
    {
    		$tmp_img = new SimpleImage($file);
    		$tmp_img->thumbnail($F_width);
    		$url = IMG_LIB . $this->URL_thumb . $this->url;
    		return $tmp_img->save($url);
//         $tmp_img = new imageLib($file);
//         $width = $tmp_img->getWidth();
//         $height = $tmp_img->getHeight();
//         $top = 0;
//         $left = 0;
//         $tmp_width = 0;
//         if ($width > $height) {
//             $tmp_s = (int)(($width - $height) / 2);
//             $left = $tmp_s;
//             $tmp_width = $height;
//         }
//         if ($width < $height) {
//             $tmp_s = (int)(($height - $width) / 2);
//             $top = $tmp_s;
//             $tmp_width = $width;
//         }

//         $tmp_img->cropImage($tmp_width, $tmp_width, $left . 'x' . $top);
//         $url = IMG_LIB . $this->URL_thumb . $this->url;
//         if($tmp_width>$F_width){
//             $tmp_img->resizeImage($F_width, $F_width);
//         }
//         $tmp_img->saveImage($url);

//         return true;
    }

    protected function CreateMedium($file, $width = 450)
    {
    	$tmp_img = new SimpleImage($file);
    	$tmp_img->adaptive_resize($width);
    	return $tmp_img->save(IMG_LIB . $this->URL_medium . $this->url);
//         $tmp_img = new imageLib($file);
//         $tmp_img->resizeImage($width, 200, 2);
//         $tmp_img->saveImage(IMG_LIB . $this->URL_medium . $this->url);
    }

    protected function DeleteInBase()
    {
        if ($this->id) {
            Cmwdb::$db->where('id', $this->id);
            Cmwdb::$db->delete("std_images");
        }
        return true;
    }

    /*
     * The argument can be original|thumb|medium
     * This function will return url of image
     */
    function GetURL($res_type = "original")
    {
        switch ($res_type) {
            case 'original':
                return URL_BASE . 'uploads/images/' . $this->URL_original . $this->url;
            case 'thumb': {
                //  			echo URL_BASE.'uploads/images/'.$this->URL_thumb.$this->url;
                return URL_BASE . 'uploads/images/' . $this->URL_thumb . $this->url;
            }
            case 'medium':
                return URL_BASE . 'uploads/images/' . $this->URL_medium . $this->url;
            default:
                return URL_BASE . 'uploads/images/' . $this->URL_original . $this->url;
        }
    }

    function GetURL_Local($res_type = "original")
    {
        switch ($res_type) {
            case 'original':
                return IMG_LIB . $this->URL_original . $this->url;
            case 'thumb': {
                //  			echo URL_BASE.'uploads/images/'.$this->URL_thumb.$this->url;
                return IMG_LIB . $this->URL_thumb . $this->url;
            }
            case 'medium':
                return IMG_LIB . $this->URL_medium . $this->url;
            default:
                return IMG_LIB . $this->URL_original . $this->url;
        }
    }


}