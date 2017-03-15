<?php

class CFrontAttrList/* extends CFront*/
{
    static  $tmpl_tbl_name='attr_templates';
    static $datas = array();
    static $tbl_name = "attr_link_languaged";

    static function Initial()
    {
// 		self::$tbl_name = "attr_link_languaged";
// 		self::$tmpl_tbl_name = "attr_templates";
    }

    static function GetDatas($obj_id = null, $obj_type = null, $args = null, $grouped = false)
    {
        if (!$obj_id || !$obj_type) return array();
        Cmwdb::$db->where('obj_id', $obj_id);
        Cmwdb::$db->where('obj_type', $obj_type);
        Cmwdb::$db->where('attr_lang', CLanguage::getInstance()->getCurrentUser());
        $res = Cmwdb::$db->get(self::$tbl_name);
        $converted = array();
        if (!empty($res)) {

            foreach ($res as $values) $converted[$values['l_group']] = $values;
            //Collect all templates
            $tmpl = array();
            $queryData = array();
            foreach ($converted as $values) $tmpl[$values['template_id']] = 1;
            foreach ($tmpl as $tid => $val) $queryData[] = $tid;
            Cmwdb::$db->where('attr_id', $queryData, "in");
            $templates = Cmwdb::$db->get(self::$tmpl_tbl_name);
            foreach ($templates as $values)
                $tmpl[$values['attr_id']] = json_decode($values['attr'], true)[CLanguage::getInstance()->getCurrentUser()];
            foreach ($converted as $attr_id => $attr_values) {
                $attr_values['template_title'] = $tmpl[$attr_values['template_id']];
                $converted[$attr_id] = $attr_values;
            }
            if (is_array($args)) {
                if (!in_array("template_id", $args)) $args[] = "template_id";
                foreach ($converted as $attr_id => $attr_values) {
                    $tmp = array();
                    foreach ($attr_values as $current_field => $cur_value)
                        if (in_array($current_field, $args)) $tmp[$current_field] = $cur_value;
                    $converted[$attr_id] = $tmp;
                }
            }

            if ($grouped) {
                $ret = array();
                foreach ($converted as $attr_id => $attr_values)
                    $ret[$attr_values['template_id']][$attr_id] = $attr_values;
                $converted = $ret;

            }

        }

        return $converted;

    }
}

?>