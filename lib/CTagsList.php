<?php

class CTagsList
{
    protected $tags = null;

    function __construct()
    {
        $query = "select DISTINCT pid from std_tags";
        $res = Cmwdb::$db->query($query);
        $ret_arr = [];
        foreach ($res as $value) {
            $curr_tag = new CStdTags();
            $ret_arr[$value['pid']] = $curr_tag->GetAsArrayPID($value['pid']);
        }
        $this->tags = $ret_arr;

    }

    function GetAsArray()
    {
        return $this->tags;
    }

    function GetAsArrayJSON()
    {
        return json_encode($this->tags);
    }

    function GetByPID($pid)
    {
        if (isset($this->tags[$pid]))
            return $this->tags[$pid];
        return false;
    }

    function GetElementsPage($lang = 'am', $limit = 20, $page = 1, $search = null, $is_active = 2)
    {

        if ($search) {
            Cmwdb::$db->where('tag_name', "%" . $search . "%", 'like');
        }
        if ($is_active == 1) {
            Cmwdb::$db->where('is_active', 1);
        }
        if ($is_active == 0) {
            Cmwdb::$db->where('is_active', 0);
        }
        Cmwdb::$db->where('lang', $lang);

        Cmwdb::$db->groupBy('pid');
        //$a = Cmwdb::$db->get('std_post p',null);
        Cmwdb::$db->pageLimit = $limit;
        $a = Cmwdb::$db->arraybuilder()->paginate("std_tags", $page);
        $ret_arr['total_pages'] = Cmwdb::$db->totalPages;

        Cmwdb::$db->groupBy('lang');
        $ret_arr['total_all'] = Cmwdb::$db->getValue("std_tags", "count(*)");

        Cmwdb::$db->where('is_active', 1);
        Cmwdb::$db->groupBy('lang');
        $ret_arr['total_active'] = Cmwdb::$db->getValue("std_tags", "count(*)");
        if ($ret_arr['total_active'] === NULL) {
            $ret_arr['total_active'] = 0;
        }

        Cmwdb::$db->groupBy('lang');
        Cmwdb::$db->where('is_active', 0);
        $ret_arr['total_passive'] = Cmwdb::$db->getValue("std_tags", "count(*)");
        if ($ret_arr['total_passive'] === NULL) {
            $ret_arr['total_passive'] = 0;
        }

        $new_array = [];

        $def_lang = CLanguage::getInstance()->getDefaultUser();
        foreach ($a as $item) {
            Cmwdb::$db->where('pid', $item['pid']);
            Cmwdb::$db->where('lang', $lang);
            $post = Cmwdb::$db->getOne('std_tags');

            Cmwdb::$db->where('tag_pid', $item['pid']);
            $item['post_count'] = Cmwdb::$db->getValue("tags_to_post", "count(*)");

            if (!$post['tag_name']) {
                Cmwdb::$db->where('pid', $item['pid']);
                Cmwdb::$db->where('lang', $def_lang);
                $post = Cmwdb::$db->getOne('std_tags');
                $item['is_translated'] = false;
                $item['tag_name'] = $post['tag_name'];
                $new_array[] = $item;

            } else {
                $item['is_translated'] = false;
                $new_array[] = $item;

            }

        }
        $ret_arr['data'] = $new_array;
        return $ret_arr;

    }

    function Publish($pid)
    {
        if (is_array($pid)) {
            Cmwdb::$db->where('pid', $pid, "in");
            if (Cmwdb::$db->update('std_tags', array('is_active' => 1)))
                return true;
        }
        if (is_numeric($pid)) {
            Cmwdb::$db->where('pid', $pid);
            if (Cmwdb::$db->update('std_tags', array('is_active' => 1)))
                return true;
        }
        return false;
    }

    function Passive($pid)
    {
        if (is_array($pid)) {
            Cmwdb::$db->where('pid', $pid, "in");
            if (Cmwdb::$db->update('std_tags', array('is_active' => 0)))
                return true;
        }
        if (is_numeric($pid)) {
            Cmwdb::$db->where('pid', $pid);
            if (Cmwdb::$db->update('std_tags', array('is_active' => 0)))
                return true;
        }
        return false;
    }

    function Delete($pid)
    {
        if (is_array($pid)) {
            Cmwdb::$db->where('pid', $pid, "in");
            Cmwdb::$db->delete('std_tags');
            Cmwdb::$db->where('tag_pid', $pid, "in");
            Cmwdb::$db->delete('tags_to_post');

            return true;
        }
        if (is_numeric($pid)) {
            Cmwdb::$db->where('pid', $pid);
            Cmwdb::$db->delete('std_tags');
            Cmwdb::$db->where('tag_pid', $pid);
            Cmwdb::$db->delete('tags_to_post');
            return true;
        }
        return false;

    }

}

?>