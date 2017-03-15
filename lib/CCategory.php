<?php

class CCategory
{
    protected $category_id = null;
    protected $cid = null;
    protected $category_lang = "";
    protected $category_title = "";
    protected $category_descr = "";
    protected $category_img = null;
    protected $category_cover_gallery = array();
    protected $category_gallery = array();
    protected $slugs = "";
    protected $category_seo = null;
    protected $category_order = null;
    protected $category_parent = null;
    protected $tbl_name = "std_categories";
    protected $all_categories = array();

    protected $edit_url = "post_category";
    protected $comm_url = "menu";
    protected $configs = null;

    function __construct($slug = null)
    {

    }

    function CreateCategory($argv)
    {
        Cmwdb::$db->startTransaction();
//         var_dump($argv);die;
        $predefines = null;
        if (isset($argv['predefines'])) {
            $predefines = $argv['predefines'];
            unset($argv['predefines']);
        }
//         var_dump($predefines);die;
        $cid = Cmwdb::$db->getOne($this->tbl_name, 'max(cid) cid');
        if ($cid['cid']) $cid['cid']++;
        else $cid['cid'] = 1;
        $lang = CLanguage::getInstance();
        $cid = $cid['cid'];
        $slugs = array();
        while ($cur_slug = current($argv)) {
            $slugs[key($argv)] = CSlug::GetSlug($cur_slug['category_title']);
            next($argv);
        }

        reset($argv);
        $slugs = CSlug::GetVerifiedSlugs($slugs, $this->tbl_name, "slugs");
        while ($current = current($argv)) {
            $queryData = array();
            //verify important datas
            if (!isset($current['category_title']) && key($current) === $lang->getDefault()) {
                $this->db->rollback();
                return false;
            }
            $queryData['category_title'] = CSecurity::FilterString($current['category_title']);
            $cur_seo = null;
            //creating seo datas
            if (empty($current['seo_title'])) $cur_seo['seo_title'] = $current['category_title'];
            else $cur_seo['seo_title'] = $current['seo_title'];
            if (!empty($current['seo_descr'])) $cur_seo['seo_descr'] = $current['seo_descr'];
            else {
                $temp_str = strip_tags($current['category_content']);
                $temp_str = preg_replace("/&#?[a-z0-9]+;/i", "", $temp_str);
                $temp_str = substr($temp_str, 0, 155);
                $cur_seo['seo_descr'] = $temp_str;
            }
            $cur_seo['seo_keywords'] = $current['seo_keywords'];
            //creating seo
            $temp_seo = new CSeo();
            if (!$queryData['category_seo'] = $temp_seo->CreateSeo($cur_seo['seo_title'], $cur_seo['seo_descr'], $cur_seo['seo_keywords'])) {
                Cmwdb::$db->rollback();
                return false;
            }
            //creating slug
            $queryData['slugs'] = $slugs[key($argv)];
//             var_dump(key($argv));
            /*
             * FIXME: seo must be verified in base
             */
            $queryData['cid'] = $cid;
            $queryData['category_lang'] = key($argv);
            $queryData['category_content'] = $current['category_content'];
            if ((!isset($current['category_img'])) || $current['category_img'] === "") $current['category_img'] = 0;
            $queryData['category_img'] = $current['category_img']['id'];
            $queryData['category_img_title'] = CSecurity::FilterString($current['category_img']['title']);
            if ((!isset($current['category_cover_gallery'])) || $current['category_cover_gallery'] === "") $current['category_cover_gallery'] = array();
            $queryData['category_cover_gallery'] = json_encode($current['category_cover_gallery']);
            if ((!isset($current['category_gallery'])) || $current['category_gallery'] === "") $current['category_gallery'] = array();
            $queryData['category_gallery'] = json_encode($current['category_gallery']);
            if ((!isset($current['category_order'])) || $current['category_order'] === "") $current['category_order'] = 0;
            $queryData['category_order'] = $current['category_order'];
            $queryData['category_parent'] = $current['category_parent'];
            if (!Cmwdb::$db->insert($this->tbl_name, $queryData)) {
                Cmwdb::$db->rollback();
                return false;
            }
            if (!empty($current['post_attr_title'])) $ATTR_VALUES[key($argv)] = $current['post_attr_title'];
            if (!empty($current['post_attr'])) $attr_templates[key($argv)] = $current['post_attr'];
            next($argv);
        }
        $values = $attr_templates[CLanguage::getInstance()->getDefaultUser()];
        foreach ($values as $index => $tmpl_id) {
            $temp_array = array();
            $temp_array['template_id'] = $tmpl_id;
            foreach (CLanguage::get_lang_keys_user() as $lang_codes) {
                $temp_array['attr_values'][$lang_codes] = $ATTR_VALUES[$lang_codes][$index];
            }
            $temp_array['obj_id'] = $cid;
            $temp_array['obj_type'] = "post_category";
            $links = new CAttrLinkL();

            $links->CreateLink($temp_array);
        }
        if ($predefines) {
            foreach ($predefines as $mod_name => $arguments) {
                $from_subject = $arguments['from_subject'];
                unset($arguments['from_subject']);
                $obj = CModule::LoadModule($mod_name);
                if (is_object($obj)) {
                    $obj->AddLinks($cid, $from_subject, $arguments, false);
                }
            }
        }
        Cmwdb::$db->commit();
        return true;
    }


    protected function GetAllCatsRecursive($parent_id = 0, $step = 0)
    {

        Cmwdb::$db->where('category_parent', $parent_id);
        Cmwdb::$db->orderBy('category_order');
        $lang = CLanguage::getInstance();
        Cmwdb::$db->where('category_lang', $lang->getDefaultUser());
        $res = Cmwdb::$db->get($this->tbl_name);

        if (!empty($res)) {
            $step++;
            foreach ($res as $value) {
                $tmp = array('value' => $value, 'level' => $step - 1);
                $this->all_categories[] = $tmp;
                $this->GetAllCatsRecursive($value['cid'], $step);
            }

        } else {
            return;
        }

    }

    protected function GetAllCatsRecursiveExcept($exception_id, $parent_id = 0, $step = 0)
    {
        Cmwdb::$db->where('category_parent', $parent_id);
        Cmwdb::$db->orderBy('category_order');
        $lang = CLanguage::getInstance();
        Cmwdb::$db->where('category_lang', $lang->getDefaultUser());
        $res = Cmwdb::$db->get($this->tbl_name);

        if (!empty($res)) {
            $step++;
            foreach ($res as $value) {
                if ($value['cid'] == $exception_id) continue;

                $tmp = array('value' => $value, 'level' => $step - 1);
                $this->all_categories[] = $tmp;
                $this->GetAllCatsRecursive($value['cid'], $step);
            }

        } else {
            return;
        }

    }


    public function GetCatsByParent($parent_id = 0, $step = 0)
    {
        Cmwdb::$db->where('category_parent', $parent_id);
        Cmwdb::$db->orderBy('category_order');
        Cmwdb::$db->where('category_lang', CLanguage::getDefaultUser());
        $res = Cmwdb::$db->get($this->tbl_name);

        return $res;
    }

    public function GetCatTree($callback = '', $params = [], $check_res = null, $parent = 0)
    {
        $check_res = $this->GetCatsByParent($parent);
        if ($check_res) {
            $style = '';
            if ($parent !== 0) {
                $style = 'style="display:none;"';
            }
            echo '<ul ' . $style . '>';
            foreach ($check_res as $check_re) {
                $check_inner = $this->GetCatsByParent($check_re['cid']);

                if ($check_inner) {
                    echo '<li>';
                    //drowDom($check_re, $params);
                    $callback($check_re, $params);
                    $this->GetCatTree($callback, $params, $check_inner, $check_re['cid']);
                    echo '</li>';

                } else {
                    echo '<li>';
                    $callback($check_re, $params);
                    //drowDom($check_re, $params);
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
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

    public function GetAllCats()
    {
        $this->GetAllCatsRecursive();
        return $this->all_categories;
    }

    public function GetAllCatsExcept($exception_id)
    {
        $this->GetAllCatsRecursiveExcept($exception_id);
        return $this->all_categories;
    }


    function GetAsArrayPID($pid)
    {
        $this->cid = $pid;
        return $this->GetAsArray();
    }

    function GetAsArray()
    {
        if ($this->cid) {
            Cmwdb::$db->where('cid', $this->cid);
            $res = Cmwdb::$db->get($this->tbl_name);
            $ret = array();
            $cur_pos = 0;
            $exists_langs = array();
            $langs = CLanguage::getInstance()->get_lang_keys_user();

            while ($current = current($res)) {
                $exists_langs[] = $current['category_lang'];

                $ret[$current['category_lang']]['category_id'] = $res[$cur_pos]['category_id'];
                $ret[$current['category_lang']]['cid'] = $res[$cur_pos]['cid'];
                $ret[$current['category_lang']]['slugs'] = $res[$cur_pos]['slugs'];
                $ret[$current['category_lang']]['category_title'] = $res[$cur_pos]['category_title'];
                $ret[$current['category_lang']]['category_content'] = $res[$cur_pos]['category_content'];
                $temp_seo = new CSeo($current['category_seo']);
                $ret[$current['category_lang']]['seo_title'] = $temp_seo->GetTitle();
                $ret[$current['category_lang']]['seo_descr'] = $temp_seo->GetDescr();
                $ret[$current['category_lang']]['seo_keywords'] = $temp_seo->GetKeywords();
                $ret[$current['category_lang']]['category_img'] = $res[$cur_pos]['category_img'];
                $ret[$current['category_lang']]['category_img_title'] = $res[$cur_pos]['category_img_title'];
                $ret[$current['category_lang']]['category_title'] = $res[$cur_pos]['category_title'];
                $ret[$current['category_lang']]['category_cover_gallery'] = json_decode($res[$cur_pos]['category_cover_gallery'], true);
                $ret[$current['category_lang']]['category_gallery'] = json_decode($res[$cur_pos]['category_gallery'], true);
                $ret[$current['category_lang']]['category_order'] = $res[$cur_pos]['category_order'];
                $ret[$current['category_lang']]['category_parent'] = $res[$cur_pos]['category_parent'];
                $cur_pos++;
                next($res);
            }
            $attr_list = new CAttrLinkList($this->cid, "post_category");
            $attr_mas = $attr_list->GetDatas();
            $ret['attributes'] = $attr_mas;

            foreach ($langs as $cur_lang) {
                if (in_array($cur_lang, $exists_langs)) continue;
                //  				var_dump($cur_lang);die;
                $ret[$cur_lang]['cid'] = $this->cid;
                $ret[$cur_lang]['category_lang'] = $cur_lang;
                $ret[$cur_lang]['is_active'] = 1;


            }

            return $ret;
        }
        return false;
    }

    function EditPage($argv, $pid)
    {
//     	var_dump($argv);die;
        $langs = CLanguage::getInstance()->get_lang_keys_user();
        Cmwdb::$db->where('cid', $pid);
        $exists_langs = Cmwdb::$db->get($this->tbl_name, null, ['category_lang']);
        $tmp = [];
        foreach ($exists_langs as $vals) $tmp[] = $vals['category_lang'];
        $exists_langs = $tmp;
        $missing_langs = [];
        foreach ($langs as $compaer_lang) {
            $tmp_seo = new CSeo();
            if (!in_array($compaer_lang, $exists_langs))
                $missing_langs[] = $compaer_lang;
        }
        $tmp_query = [];
        foreach ($missing_langs as $adding_lang) {
            $tmp_query = [];
            $tmp_query['category_seo'] = $tmp_seo->CreateSeo("", "");
            $tmp_query['cid'] = $pid;
            $tmp_query['category_lang'] = $adding_lang;
            if (isset($argv[$adding_lang]) && isset($argv[$adding_lang]['category_title']) && $argv[$adding_lang]['category_title'] !== "") {
                $converted_title = CSlug::GetSlug($argv[$adding_lang]['category_title']);
                $converted_title = CSlug::GetVerifiedSlugs([$converted_title], $this->tbl_name, 'slugs');
                $tmp_query['slugs'] = $converted_title[0];
            }
            Cmwdb::$db->insert($this->tbl_name, $tmp_query);
        }
        $ATTR_VALUES = array();
        $attr_templates = array();

        Cmwdb::$db->startTransaction();
        while ($current = current($argv)) {
            $queryData['category_title'] = CSecurity::FilterString($current['category_title']);
            $queryData['category_content'] = $current['category_content'];
            Cmwdb::$db->where('cid', $pid);
            Cmwdb::$db->where("category_lang", key($argv));
            $res = Cmwdb::$db->getOne($this->tbl_name, 'category_seo');
            if (empty($res)) return false;
            $temp_seo = new CSeo($res['category_seo']);
            if (!$temp_seo->UpdateDatas($current['seo_title'], $current['seo_descr'], $current['seo_keywords'])) {
                Cmwdb::$db->rollback();
                return false;
            }
            if (isset($current['category_img'])) $queryData['category_img'] = $current['category_img']['id'];
            if (isset($current['category_img'])) $queryData['category_img_title'] = CSecurity::FilterString($current['category_img']['title']);
            if (isset($current['category_gallery'])) $queryData['category_gallery'] = json_encode($current['category_gallery']);
            else $queryData['category_gallery'] = json_encode(array());
            if (isset($current['category_cover_gallery'])) $queryData['category_cover_gallery'] = json_encode($current['category_cover_gallery']);
            else $queryData['category_cover_gallery'] = json_encode(array());
            $queryData['category_parent'] = $current['category_parent'];
            if (isset($current['category_order'])) $queryData['category_order'] = $current['category_order'];
            else $queryData['category_order'] = 0;
            Cmwdb::$db->where('cid', $pid);
            Cmwdb::$db->where("category_lang", key($argv));
            if (!Cmwdb::$db->update($this->tbl_name, $queryData)) {
                Cmwdb::$db->rollback();
                return false;
            }
            if (isset($current['post_attr_title'])) $ATTR_VALUES[key($argv)] = $current['post_attr_title'];
            if (isset($current['post_attr'])) $attr_templates[key($argv)] = $current['post_attr'];
            next($argv);
        }
        $atr_list = new CAttrLinkList();
        $atr_list->DeleteAssociations($pid, "post_category");
        if ($attr_templates) {
            $values = $attr_templates[CLanguage::getInstance()->getDefaultUser()];
            foreach ($values as $index => $tmpl_id) {
                $temp_array = array();
                $temp_array['template_id'] = $tmpl_id;
                foreach (CLanguage::get_lang_keys_user() as $lang_codes) {
                    $temp_array['attr_values'][$lang_codes] = $ATTR_VALUES[$lang_codes][$index];
                }
                $temp_array['obj_id'] = $pid;
                $temp_array['obj_type'] = "post_category";
                $links = new CAttrLinkL();
                $links->CreateLink($temp_array);
            }
        }
        Cmwdb::$db->commit();
        return true;
    }

    function GetTableName()
    {
        return $this->tbl_name;
    }

    function GetYourType()
    {
        return get_class();
    }

    function GetList_Title()
    {
        $res = Cmwdb::$db->get($this->tbl_name, null, array("cid", "category_lang", "category_title", "category_parent"));
        $ret = array();
        foreach ($res as $key => $value) {
            $ret[$value['cid']][$value['category_lang']] = $value['category_title'];
            $ret[$value['cid']]['category_parent'] = $value['category_parent'];
        }
        foreach ($ret as $key => $value) {
            $level = 0;

            $this->GetLevel($value['category_parent'], $ret, $level);
            $ret[$key]['level'] = $level;
        }

        $temp_array = array();
        foreach ($ret as $cid => $values) {
            $temp_array[$cid] = $values;
            foreach ($ret as $child_cid => $child_values) {
                if ($child_values['category_parent'] === $cid) {
                    $temp_array[$child_cid] = $child_values;
                    unset($ret[$child_cid]);
                }
            }
        }

        return $temp_array;

    }

    protected function GetLevel($cat_id, &$in_array, &$level = 0)
    {
        if (!$cat_id) return false;
        if (!isset($in_array[$cat_id])) return false;
        $next_parent = $in_array[$cat_id]['category_parent'];
        $level++;
        if ($next_parent) {
            if ($this->GetLevel($next_parent, $in_array, $level))
                return true;
        }
        return false;
    }

    function GetDOM($lang = null)
    {
        if (!$lang) $lang = CLanguage::getInstance()->getDefaultUser();
        $parents = array();
        Cmwdb::$db->orderBy('category_order', 'asc');
        $res = Cmwdb::$db->get($this->tbl_name);
        $ret = "";
        $coll = [];
        if (!empty($res)) {
            foreach ($res as $value) {
                $coll[$value['cid']][$value['category_lang']] = $value;
            }
            foreach ($coll as $key => $value) {
                $parents[$value[$lang]['category_parent']][] = $value[$lang]['cid'];
            }
            $level = 0;
            $ret .= '<ul class="cat_order_ul">';
            foreach ($parents[0] as $value) {
                $ret .= '<li class="cat_order_ul_li">';
                $ret .= '<div data-action="post_cat_item" data-value="' . $coll[$value][$lang]['cid'] . '">';
                $ret .= "<span data-action='order_index' class='post-cat-order'></span>";
                if ($coll[$value][$lang]['category_title'] == "") {
                    $ret .= '<span class="cat_ul_title" data-is_translated="0">';
                    $ret .= $coll[$value][CLanguage::getInstance()->getDefaultUser()]['category_title'];
                    $ret .= "<span>" . CDictionary::GetKey("not_translated", $lang) . "<span>";
                } else {
                    $ret .= '<span class="cat_ul_title" data-is_translated="1">';
                    $ret .= $coll[$value][$lang]['category_title'];
                }
                $ret .= '</span>';
                Cmwdb::$db->where('cid', $coll[$value][$lang]['cid']);
                Cmwdb::$db->where('category_lang', $lang);
                $img = Cmwdb::$db->getOne($this->tbl_name, array('category_img'));
                if (is_array($img) && $img['category_img'] !== null) {
                    $attach = new CAttach($img['category_img']);
                    $ret .= '<span class="cat_ul_images"><img src="' . $attach->GetURL("thumb") . '" alt="" style="width: 50px"></span>';
                } else
                    $ret .= '<span class="cat_ul_images"><img src="http://watchfit.com/images/no-photo.gif" alt="" style="width: 50px"></span>';
//   				$ret.='<span class="cat_ul_count">fffffff</span>';
                $ret .= '<span class="cat_ul_count">';
                $post_count = new CPostToCatPost();

                $post_count->LoadValues($coll[$value][$lang]['cid']);
                $ret .= $post_count->GetCount_SLink($coll[$value][$lang]['cid']) . '</span>';
                $ret .= '<span class="cat_action_button">';
                $ret .= '<button class="btn btn-default">view</button>';
                $ret .= '<a class="btn btn-default" href="index.php?' . $this->comm_url . '=' . $this->edit_url . '&submenu=add&edit_id=' . $coll[$value][$lang]['cid'] . '">edit</a>';
                $ret .= '<button class="btn btn-default" data-action="delete">' . CDictionary::GetKey("delete") . '</button>';
                if ($coll[$value][$lang]['is_active']) {
                    $style2 = 'style="display:none"';
                    $style1 = '';

                } else {
                    $style1 = 'style="display:none"';
                    $style2 = '';
                }
                $ret .= '<button class="btn btn-default" data-action="activate" ' . $style2 . '>' . CDictionary::GetKey("activate") . '</button>';
                $ret .= '<button class="btn btn-default" data-action="passive" ' . $style1 . '>' . CDictionary::GetKey("passive") . '</button>';
                $ret .= '</span>';
                $ret .= '</div>';
                $coll[$value][$lang]["cid"];
                $this->RunOnElements($value, $parents, $coll, $ret, $lang, $level);
                $ret .= "</li>";
            }
            $ret .= "</ul>";
            return $ret;
        }
        return "<ul></ul>";
    }

    protected function RunOnElements($current_parent, &$parents, &$coll, &$ret, $lang, $level)
    {
        $level++;
        if (isset($parents[$current_parent])) {
            $ret .= '<ul class="sub_cat_order_ul">';
            foreach ($parents[$current_parent] as $value) {
                if (isset($parents[$value])) {
                    $ret .= '<li>';
                    $ret .= '<div data-action="post_cat_item" data-value="' . $coll[$value][$lang]['cid'] . '">';
                    $ret .= "<span data-action='order_index' class='post-cat-order'></span>";
                    if ($coll[$value][$lang]['category_title'] == "") {
                        $ret .= '<span data-is_translated="0">';
                        for ($i = 0; $i < $level; $i++) $ret .= "-";
                        $ret .= $coll[$value][CLanguage::getInstance()->getDefaultUser()]['category_title'];
                        $ret .= "<span>" . CDictionary::GetKey("not_translated", $lang) . "</span>";
                    } else {
                        $ret .= '<span data-is_translated="1">';
                        for ($i = 0; $i < $level; $i++) $ret .= "-";
                        $ret .= $coll[$value][$lang]['category_title'];
                    }
                    $ret .= '</span>';
                    Cmwdb::$db->where('cid', $coll[$value][$lang]['cid']);
                    Cmwdb::$db->where('category_lang', $lang);
                    $img = Cmwdb::$db->getOne($this->tbl_name, array('category_img'));
                    if (is_array($img) && $img['category_img'] !== null) {
                        $attach = new CAttach($img['category_img']);
                        $ret .= '<span class="cat_ul_images"><img src="' . $attach->GetURL("thumb") . '" alt="" style="width: 50px"></span>';
                    } else
                        $ret .= '<span class="cat_ul_images"><img src="http://watchfit.com/images/no-photo.gif" alt="" style="width: 50px"></span>';
                    $ret .= '<span>';
                    $post_count = new CPostToCatPost();
                    $post_count->LoadValues($coll[$value][$lang]['cid']);
                    $ret .= $post_count->GetCount_SLink($coll[$value][$lang]['cid']) . '</span>';
                    $ret .= '<button class="btn btn-default">view</button>';
                    $ret .= '<a class="btn btn-default" href="index.php?' . $this->comm_url . '=' . $this->edit_url . '&submenu=add&edit_id=' . $coll[$value][$lang]['cid'] . '">edit</a>';
                    $ret .= '<button class="btn btn-default" data-action="delete">' . CDictionary::GetKey("delete") . '</button>';
                    if ($coll[$value][$lang]['is_active']) {
                        $style2 = 'style="display:none"';
                        $style1 = '';

                    } else {
                        $style1 = 'style="display:none"';
                        $style2 = '';
                    }
                    $ret .= '<button class="btn btn-default" data-action="activate" ' . $style2 . '>' . CDictionary::GetKey("activate") . '</button>';
                    $ret .= '<button class="btn btn-default" data-action="passive" ' . $style1 . '>' . CDictionary::GetKey("passive") . '</button>';
                    $ret .= '</div>';
                    $this->RunOnElements($value, $parents, $coll, $ret, $lang, $level);
                    $ret .= '</li>';
                } else {
                    $ret .= '<li>';
                    $ret .= '<div data-action="post_cat_item" data-value="' . $coll[$value][$lang]['cid'] . '">';
                    $ret .= "<span data-action='order_index' class='post-cat-order'></span>";
                    if ($coll[$value][$lang]['category_title'] == "") {
                        $ret .= '<span data-is_translated="0">';
                        for ($i = 0; $i < $level; $i++) $ret .= "-";
                        $ret .= $coll[$value][CLanguage::getInstance()->getDefaultUser()]['category_title'];
                        $ret .= "<span>" . CDictionary::GetKey("not_translated", $lang) . "</span>";
                    } else {
                        $ret .= '<span data-is_translated="1">';
                        for ($i = 0; $i < $level; $i++) $ret .= "-";
                        $ret .= $coll[$value][$lang]['category_title'];
                    }
                    $ret .= '</span>';
                    Cmwdb::$db->where('cid', $coll[$value][$lang]['cid']);
                    Cmwdb::$db->where('category_lang', $lang);
                    $img = Cmwdb::$db->getOne($this->tbl_name, array('category_img'));
                    if (is_array($img) && $img['category_img'] !== null) {
                        $attach = new CAttach($img['category_img']);
                        $ret .= '<span class="cat_ul_images"><img src="' . $attach->GetURL("thumb") . '" alt="" style="width: 50px"></span>';
                    } else
                        $ret .= '<span class="cat_ul_images"><img src="http://watchfit.com/images/no-photo.gif" alt="" style="width: 50px"></span>';
                    $ret .= '<span>';
                    $post_count = new CPostToCatPost();
                    $post_count->LoadValues($coll[$value][$lang]['cid']);
                    $ret .= $post_count->GetCount_SLink($coll[$value][$lang]['cid']) . '</span>';
                    $ret .= '<button class="btn btn-default">view</button>';
                    $ret .= '<a class="btn btn-default" href="index.php?' . $this->comm_url . '=' . $this->edit_url . '&submenu=add&edit_id=' . $coll[$value][$lang]['cid'] . '">edit</a>';
                    $ret .= '<button class="btn btn-default" data-action="delete">' . CDictionary::GetKey("delete") . '</button>';
                    if ($coll[$value][$lang]['is_active']) {
                        $style2 = 'style="display:none"';
                        $style1 = '';

                    } else {
                        $style1 = 'style="display:none"';
                        $style2 = '';
                    }
                    $ret .= '<button class="btn btn-default" data-action="activate" ' . $style2 . '>' . CDictionary::GetKey("activate") . '</button>';
                    $ret .= '<button class="btn btn-default" data-action="passive" ' . $style1 . '>' . CDictionary::GetKey("passive") . '</button>';
                    $ret .= '</div>';
                    $ret .= '</li>';
                }
            }
            $ret .= "</ul>";
        } else {
            if (!$level) {
                $ret .= '<li>';
                $ret .= '<div data-action="post_cat_item" data-value="' . $coll[$current_parent][$lang]['cid'] . '">';
                $ret .= "<span data-action='order_index' class='post-cat-order'></span>";
                if ($coll[$current_parent][$lang]['category_title'] == "") {
                    $ret .= '<span data-is_translated="0">';
                    for ($i = 0; $i < $level; $i++) $ret .= "-";
                    $ret .= $coll[$current_parent][CLanguage::getInstance()->getDefaultUser()]['category_title'];
                    $ret .= "<span>" . CDictionary::GetKey("not_translated", $lang) . "</span>";
                } else {
                    $ret .= '<span data-is_translated="1">';
                    for ($i = 0; $i < $level; $i++) $ret .= "-";
                    $ret .= $coll[$current_parent][$lang]['category_title'];
                }
                $ret .= '</span>';
                Cmwdb::$db->where('cid', $coll[$current_parent][$lang]['cid']);
                Cmwdb::$db->where('category_lang', $lang);
                $img = Cmwdb::$db->getOne($this->tbl_name, array('category_img'));
                if (is_array($img) && $img['category_img'] !== null) {
                    $attach = new CAttach($img['category_img']);
                    $ret .= '<span class="cat_ul_images"><img src="' . $attach->GetURL("thumb") . '" alt="" style="width: 50px"></span>';
                } else
                    $ret .= '<span class="cat_ul_images"><img src="http://watchfit.com/images/no-photo.gif" alt="" style="width: 50px"></span>';
                $ret .= '<span>';
                $post_count = new CPostToCatPost();
                $post_count->LoadValues($coll[$current_parent][$lang]['cid']);
                $ret .= $post_count->GetCount_SLink($coll[$value][$lang]['cid']) . '</span>';
                $ret .= '<a class="btn btn-default" href="#">view</a>';
                $ret .= '<a class="btn btn-default" href="index.php?' . $this->comm_url . '=' . $this->edit_url . '&submenu=add&edit_id=' . $coll[$current_parent][$lang]['cid'] . '">edit</a>';
                $ret .= '<button class="btn btn-default" data-action="delete">' . CDictionary::GetKey("delete") . '</button>';
                if ($coll[$value][$lang]['is_active']) {
                    $style2 = 'style="display:none"';
                    $style1 = '';

                } else {
                    $style1 = 'style="display:none"';
                    $style2 = '';
                }
                $ret .= '<button class="btn btn-default" data-action="activate" ' . $style2 . '>' . CDictionary::GetKey("activate") . '</button>';
                $ret .= '<button class="btn btn-default" data-action="passive" ' . $style1 . '>' . CDictionary::GetKey("passive") . '</button>';
                $ret .= '</div>';
                $ret .= '</li>';
            }
        }
    }

    function UpdateOrders($args)
    {
        if (is_array($args)) {
            foreach ($args as $index => $cid) {
                Cmwdb::$db->where('cid', $cid);
                Cmwdb::$db->update($this->tbl_name, array("category_order" => $index));
            }
            return true;
        }
        return false;
    }

    function DeleteCategory($cid)
    {
        Cmwdb::$db->where('cid', $cid);
        return Cmwdb::$db->delete($this->tbl_name);
    }

    function getTblName()
    {
        return $this->tbl_name;
    }

    function Activate($cid)
    {
        Cmwdb::$db->where('cid', $cid);
        return Cmwdb::$db->update($this->tbl_name, array("is_active" => 1));
    }

    function Disable($cid)
    {
        Cmwdb::$db->where('cid', $cid);
        return Cmwdb::$db->update($this->tbl_name, array("is_active" => 0));
    }

    function GetCountByActiveStatus($status)
    {
        Cmwdb::$db->where('is_active', $status);
        return Cmwdb::$db->getValue($this->tbl_name, "count(*)");
    }

    function getParentPostCats()
    {
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getCurrentUser());
        Cmwdb::$db->where('category_parent', 0);

        return Cmwdb::$db->get($this->tbl_name);


    }

    function GetFiltered($search_word = null, $in_fields = null, $lang = null, $page = 1, $count = 20, $order = "cid")
    {
        if (!$in_fields) $in_fields = ['category_title'];
        else {
            if (!in_array("category_title", $in_fields)) $in_fields[] = 'category_title';
        }
        if ($search_word) {
            foreach ($in_fields as $field_name)
                Cmwdb::$db->orWhere($field_name, '%' . $search_word . '%', "like");
        }
        if (!$lang) $lang = CLanguage::getInstance()->getCurrentUser();
        Cmwdb::$db->where('category_lang', $lang);
        Cmwdb::$db->pageLimit = $count;
        $res = Cmwdb::$db->arraybuilder()->paginate($this->tbl_name, $page, ['cid', 'category_title', 'category_img', 'category_parent', 'category_order', 'is_active', 'is_complated']);
//     	$res = Cmwdb::$db->get($this->tbl_name);
        $reconvert = array();
        $needs = array();
        foreach ($res as $values) {
            $reconvert[$values['cid']] = $values;
            $needs[] = $values['cid'];
        }

        $ret = $this->GetNeeds($needs, $lang);
        $ret['page_count'] = Cmwdb::$db->totalPages;
        $ret['current_page'] = $page;
        return $ret;
    }

    function GetNeeds(array $oids, $lang = null)
    {
        if (!$lang) $lang = CLanguage::getInstance()->getCurrentUser();
        if (empty($oids)) return [];
        Cmwdb::$db->where('cid', $oids, "in");
        $res = Cmwdb::$db->get($this->tbl_name);
        $ret = array();
        foreach ($res as $vals) $ret[$vals['cid']][$vals['category_lang']] = $vals;
        $forret = array();
        foreach ($ret as $cid => $values) {
            foreach ($values as $cur_lang => $details) {

                if ($details['category_title'] != "")
                    $forret[$cid]['is_active_langs'][$cur_lang] = true;
                else $forret[$cid]['is_active_langs'][$cur_lang] = false;
                if ($lang === $cur_lang) {
                    if ($details['category_title']) {
                        $forret[$cid]['category_title'] = $details['category_title'];
                        $forret[$cid]['is_translated'] = true;

                    } else {
                        $forret[$cid]['category_title'] = $values[CLanguage::getInstance()->getDefaultUser()]['category_title'];
                        $forret[$cid]['is_translated'] = false;

                    }
                    if ($details['category_parent']) {
                        Cmwdb::$db->where('cid', $details['category_parent']);
                        Cmwdb::$db->where('category_lang', $lang);
                        $forret[$cid]['category_parent'] = Cmwdb::$db->getValue($this->tbl_name, 'category_title');
                        $tmp_cid = $details['category_parent'];
                        $level = 0;
                        do {
//     						echo "Bro: step to one<br>";
                            $level++;
                            Cmwdb::$db->where('cid', $tmp_cid);
                        } while ($tmp_cid = Cmwdb::$db->getValue($this->tbl_name, 'category_parent'));
                        $forret[$cid]['category_level'] = $level;
                    } else {
                        $forret[$cid]['category_parent'] = "";
                        $forret[$cid]['category_level'] = 0;
                    }
                    if ($details['category_img']) {
                        $at = new CAttach($details['category_img']);
                        $forret[$cid]['category_img'] = $at->GetURL();
                    } else $forret[$cid]['category_img'] = "";
//     				$forret[$cid]['is_active_langs'][$cur_lang] = true;
                    $forret[$cid]['category_order'] = $details['category_order'];
                    $forret[$cid]['is_active'] = $details['is_active'];
                } else {
//     				$forret[$cid]['is_active_langs'][$cur_lang] = $details['is_active'];
                }
            }
            $post_links = new CPostToCatPost();
            $posts = $post_links->GetBySLink($cid);
            $forret[$cid]['posts_count'] = count($posts);
        }

        return $forret;
    }

    function UpdateOrder($oid, $order)
    {
        $order = (int)$order;
        Cmwdb::$db->where('cid', $oid);
        if (Cmwdb::$db->update($this->tbl_name, ['category_order' => $order])) {
            Cmwdb::$db->where('cid', $oid);
            return Cmwdb::$db->getValue($this->tbl_name, 'category_order');
        }
        return false;
    }


}