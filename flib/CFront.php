<?php

class CFront
{
    protected static $datas = array();
    protected static $tbl_name = null;
// 	private static $_instance = null;
// 	private function __construct() {}
// 	protected function __clone() {}

// 	static public function getInstance() {
// 		if(is_null(self::$_instance))
// 		{
// 			self::$_instance = new self();
// 		}
// 		return self::$_instance;
// 	}

    static function Initial()
    {

    }

    static function GetDatas($oid = null, $args = null)
    {
        self::Initial();
    }

    static function Find($string, $in_fields = null, $args = null, $count = 10, $page = 1)
    {
        $ret = array();
        $limit_start = ($page - 1) * $count;
        Cmwdb::$db->pageLimit = $count;
        if (is_array($in_fields)) {
            Cmwdb::$db->where($in_fields[0], '%' . $string . '%', "like");
            unset($in_fields[0]);
            foreach ($in_fields as $field_name) {
                Cmwdb::$db->orWhere($field_name, '%' . $string . '%', "like");
            }
            $ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, $page);
// 			if(is_array($args)){
// 				$ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name,$page);
// // 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count], $args);
// 			}
// 			else{
// 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count]);
// 			}

        } else {
            Cmwdb::$db->where($in_fields, '%' . $string . '%', "like");
            $ret = Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, $page);
// 			if(is_array($args)){
// 				$ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count], $args);
// 			}
// 			else $ret = Cmwdb::$db->get(self::$tbl_name, [$limit_start,$count]);
        }
        $ret['page_count'] = Cmwdb::$db->totalPages;

        return $ret;
    }

    static function GetPageCount_Filtered($string, $in_fields = null, $count = 10)
    {
// 		$ret = 0;
        if (is_array($in_fields)) {
            Cmwdb::$db->where($in_fields[0], '%' . $string . '%', "like");
            unset($in_fields[0]);
            foreach ($in_fields as $field_name) {
                Cmwdb::$db->where($field_name, '%' . $string . '%', "like");
            }
            Cmwdb::$db->pageLimit = $count;
            Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, 1);
            $ret = Cmwdb::$db->totalPages;


        } else {
            Cmwdb::$db->pageLimit = $count;
            Cmwdb::$db->where($in_fields, '%' . $string . '%', "like");
            Cmwdb::$db->get(self::$tbl_name);
            Cmwdb::$db->arraybuilder()->paginate(self::$tbl_name, 1);
            $ret = Cmwdb::$db->totalPages;

        }

        return $ret;
    }

    static function RegisterJs($js)
    {
        if (is_array($js)) {
            foreach ($js as $item) {
                echo '<script src="' . ASSETS_BASE . '/js/' . $item . '"></script>' . "\n";
            }
            return;
        }
        if ($js) {
            echo '<script src="' . ASSETS_BASE . '/js/' . $js . '"></script>' . "\n";
        }
    }

    static function RegisterCss($css)
    {
        if (is_array($css)) {
            foreach ($css as $item) {
                echo '<link rel="stylesheet" href="' . ASSETS_BASE . '/css/' . $item . '">' . "\n";
            }
            return;
        }
        if ($css) {
            echo '<link rel="stylesheet" href="' . ASSETS_BASE . '/css/' . $css . '">' . "\n";
            return;
        }

    }

    static function RenderBreadcrumb($breadcrumb = null)
    {
        if (!$breadcrumb) {
            $breadcrumb = CWebApp::$_breadcrumb;
        }
        echo '<ul class="breadcrumb">';
        foreach ($breadcrumb as $key => $item) {
//            if ($item['active']) {
            if ($key !== count($breadcrumb) - 1) {
                echo '<li class="active">';
                echo '<a href="' . CUrlManager::GetURL(['type' => $item['type'], 'id' => $item['id']]) . '" title="">';
                echo $item['label'] . '</a>';
                echo '<span class="breadcrumb_arrow">&#10095;</span></li>';
            } else {
                echo '<li>';
                echo '<a title="">';
                echo $item['label'] . '</a></li>';
            }


        }
        echo '</ul>';
    }

    static function RenderPagination($page_count = null, $active_page = null)
    {
        if (!$page_count) $page_count = (int)self::$_pageCount;
        if (!$active_page) $active_page = (int)self::$_currentPage;
        if ($page_count < 2) return false;
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $page_count; $i++) {
            if ($i == (int)$active_page) {
                $active = 'active';
            } else {
                $active = '';
            }
            echo '<li class="' . $active . '"><a href="javascript::void(0)" data-value="' . $i . '" data-action="main_pagination">' . $i . '</a></li>';
        }
        echo '</ul>';
    }
}

?>

