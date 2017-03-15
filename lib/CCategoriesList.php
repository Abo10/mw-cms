<?php

class CCategoriesList
{
    protected $categories = null;
    public $ret_arr = [];
    public $res = [];


    function __construct($list_type = null)
    {
        if (!$list_type) $list_type = "CCategory";
        $tbl_name = "";
        switch ($list_type) {
            case 'product': {
                $tbl_name = 'std_category_product';
                break;
            }
            case 'post': {
                $tbl_name = 'std_category_post';
                break;
            }
            default: {
                $tbl_name = 'std_category';
                break;
            }
        }


        $cats = Cmwdb::$db->get($tbl_name);
        foreach ($cats as $item) {
            $this->res[$item['cid']][$item['category_lang']] = $item;
        }
        return;


    }

    public function GetTree($level)
    {
        $level = (int)$level;
        if ($level > 0) {
            while ($level) {
                echo ' - ';
                $level--;
            }
        } else {
            return '';
        }
    }

    function search_for_cat($needle = 0, $level = 0)
    {
       // return $this->ret_arr = $this->res;
        if (!$this->res) return;
        $found = false;
        if ($needle == 0) {
            $level = 0;
        }
        foreach ($this->res as $key => $item) {

            if ($item[CLanguage::getInstance()->getDefault()]['category_parent'] == $needle) {
                $item['level'] = $level;
                $level++;
                $found = true;

                $this->ret_arr[] = $item;
                $tmp = $key;
                unset($this->res[$key]);

                break;
            }

        }
        if (!$found) {
            $level--;
            $this->search_for_cat($needle, $level);
        } else {
            $this->search_for_cat($tmp, $level);
        }
    }

}

?>