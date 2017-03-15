<?php

class CFrontAttach
{
    static $attach_table = 'std_attachment';
    static $image_table = 'std_images';
    static $docs_table = 'std_docs';

    static function GetImageUrl($oid, $size = 'all')
    {
        if (!$oid) return false;
        if (is_array($oid)) {
            Cmwdb::$db->where(self::$attach_table . '.id_attachment', $oid, 'in');
            Cmwdb::$db->join(self::$image_table, self::$image_table . '.id=' . self::$attach_table . '.d_id', 'left');
            $res = Cmwdb::$db->get(self::$attach_table, null, [self::$image_table . '.url', self::$attach_table . '.id_attachment']);
            $ret_arr = [];
            if ($size == 'all') {
                foreach ($res as $item) {
                    $ret_arr[$item['id_attachment']]['thumb'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/thumbs' . DIRECTORY_SEPARATOR . $item['url'];
                    $ret_arr[$item['id_attachment']]['medium'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/medium' . DIRECTORY_SEPARATOR . $item['url'];
                    $ret_arr[$item['id_attachment']]['original'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/original' . DIRECTORY_SEPARATOR . $item['url'];
                }
            }
            if ($size == 'thumb') {
                foreach ($res as $item) {
                    $ret_arr[$item['id_attachment']]['thumb'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/thumbs' . DIRECTORY_SEPARATOR . $item['url'];
                }
            }
            if ($size == 'medium') {
                foreach ($res as $item) {
                    $ret_arr[$item['id_attachment']]['medium'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/medium' . DIRECTORY_SEPARATOR . $item['url'];
                }
            }
            if ($size == 'original') {
                foreach ($res as $item) {
                    $ret_arr[$item['id_attachment']]['original'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/original' . DIRECTORY_SEPARATOR . $item['url'];
                }
            }
            return $ret_arr;
        }
        if (is_numeric($oid)) {
            Cmwdb::$db->where(self::$attach_table . '.id_attachment', $oid);
            Cmwdb::$db->join(self::$image_table, self::$image_table . '.id=' . self::$attach_table . '.d_id', 'left');
            $res = Cmwdb::$db->getOne(self::$attach_table, self::$image_table . '.url');
            $ret_arr = [];
            if ($size == 'all') {
                $ret_arr['thumb'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/thumbs' . DIRECTORY_SEPARATOR . $res['url'];
                $ret_arr['medium'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/medium' . DIRECTORY_SEPARATOR . $res['url'];
                $ret_arr['original'] = URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/original' . DIRECTORY_SEPARATOR . $res['url'];
                return $ret_arr;
            }
            if ($size == 'thumb') {
                return URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/thumbs' . DIRECTORY_SEPARATOR . $res['url'];
            }
            if ($size == 'medium') {
                return URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/medium' . DIRECTORY_SEPARATOR . $res['url'];
            }
            if ($size == 'original') {
                return URL_BASE . 'uploads' . DIRECTORY_SEPARATOR . 'images/original' . DIRECTORY_SEPARATOR . $res['url'];
            }
        }
    }

}