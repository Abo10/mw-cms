<?php

class CCategoryProduct extends CCategory
{

    function __construct($slug = null)
    {
        $this->tbl_name = "std_category_product";
        $this->edit_url = "product_category";
        $this->comm_url = "module";
        $this->configs = CModule::TakeConfigs('product_category');
// 		var_dump($this->configs);

    }

    function CreateCategory($argv)
    {

        if (isset($argv['predefines'])) {
            foreach ($argv['predefines'] as $subject => $unneed)
                $argv['predefines'][$subject]['from_subject'] = 'product_category';
        }
        return parent::CreateCategory($argv);
    }

    function GetYourType()
    {
        return get_class();
    }

    function AddLinks($obj_id, $obj_type, $s_links, $remove_exists = true)
    {
        $links = CModule::LoadComponent('product_category', 'product_links');
        if (is_object($links)) {
            return $links->AddLinks($obj_id, $obj_type, $s_links, $remove_exists);
        }
        return false;
    }

    function GetLinks($obj_id = null, $obj_type = null, $s_link = null, $group_by_mlink = false)
    {
        $links = CModule::LoadComponent('product_category', 'product_links');
        if (is_object($links)) {
            return $links->GetLinks($obj_id, $obj_type, $s_link, $group_by_mlink);
        }
        return false;

    }

    function GetLinksCompaq($obj_id = null, $obj_type = null, $s_link = null, $group_by_mlink = false)
    {
        $links = CModule::LoadComponent('product_category', 'product_links');
        if (is_object($links)) {
            $tmp = $links->GetLinks($obj_id, $obj_type, $s_link, $group_by_mlink);

            $ret = array();
            foreach ($tmp as $key => $unneed) $ret[] = $key;
            return $ret;

        }
        return array();

    }

    function RemoveLinks($m_id, $obj_type)
    {
        $links = CModule::LoadComponent('product_category', 'product_links');
        if (is_object($links)) {
            return $links->RemoveMultyLinks($m_id, $obj_type);
        }
        return false;

    }

    function GetDatasByObj($obj_id, $obj_type, $lang = null)
    {
        if (!$lang) $lang = CLanguage::getInstance()->getDefaultUser();
        $links = CModule::LoadComponent('product_category', 'product_links');
        if (is_object($links)) {
            $res = $links->GetLinks($obj_id, $obj_type, null, false);
            if (!empty($res)) {
                $tmp = array();
                foreach ($res as $s_id => $unneed) {
                    $tmp[] = $s_id;
                }
                Cmwdb::$db->where('category_lang', $lang);
                Cmwdb::$db->where('cid', $tmp, "in");
                $ret = Cmwdb::$db->get($this->tbl_name);
                foreach ($ret as $values) {
                    $res[$values['cid']] = $values['category_title'];
                }
                return $res;
            }
        }
        return array();
    }

    function GetAllGroups()
    {
//		echo 'Lang is: '.CLanguage::getInstance()->getDefaultUser().'<br>';
        Cmwdb::$db->where('category_lang', CLanguage::getInstance()->getDefaultUser());
        $res = Cmwdb::$db->get($this->tbl_name, null, ['cid']);
// 		var_dump($res);
        $ret = array();
        foreach ($res as $cids)
            $ret[] = $cids['cid'];
// 		var_dump($ret);
        return $ret;
    }

    function GetDatas($cid, $lang = null, $fields = null)
    {
        if (!$lang) $lang = CLanguage::getInstance()->getDefaultUser();
        if (!$fields) {
            $fields[] = 'cid';
            $fields[] = 'category_title';
            $fields[] = 'slugs';
            $fields[] = 'category_parent';
            $fields[] = 'is_active';
            $fields[] = 'is_complated';
        }
        if (is_array($cid)) Cmwdb::$db->where('cid', $cid, "in");
        if (is_numeric($cid)) Cmwdb::$db->where('cid', $cid);
        Cmwdb::$db->where('category_lang', $lang);
        $res = Cmwdb::$db->get($this->tbl_name, null, $fields);
        $ret = array();
        foreach ($res as $values) $ret[$values['cid']] = $values;
        if ($this->configs) {
            if (isset($this->configs['predefines'])) {
                foreach ($this->configs['predefines'] as $module_name) {
                    $obj = CModule::LoadModule($module_name);
                    if (is_object($obj)) {
                        $ret['predefines'][$module_name] = $obj->GetLinks($cid, 'product_category');
                    }
                }
            }
        }

        return $ret;
    }

    function EditCategory($argv, $pid)
    {
        $predefines = null;
        if (isset($argv['predefines'])) {
            $predefines = $argv['predefines'];
            unset($argv['predefines']);
        }
        if (parent::EditPage($argv, $pid)) {
            if (!$predefines) return true;
            foreach ($predefines as $mod_name => $values) {
                $obj = CModule::LoadModule($mod_name);
                if (is_object($obj)) {
                    $obj->Addlinks($pid, 'product_category', $values, false);
                }
            }
            return true;
        }
        return false;

    }

    function GetAsArrayPID($pid)
    {
        $ret = array();
        $ret = parent::GetAsArrayPID($pid);
        if ($this->configs) {
            if (isset($this->configs['predefines'])) {
                foreach ($this->configs['predefines'] as $module_name) {
                    $obj = CModule::LoadModule($module_name);
                    if (is_object($obj)) {
                        $ret['predefines'][$module_name] = $obj->GetLinks($pid, 'product_category');
                    }
                }
            }
        }
        return $ret;
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

// 			$posts = $post_links->GetBySLink($cid);
// 			$forret[$cid]['posts_count'] = count($posts);
        }

        return $forret;
    }

    function ApplyDiscount($id, $value, $action = "plus", $type = "fixed")
    {
        try {
            Cmwdb::$db->startTransaction();
            $obj_links = CModule::LoadComponent('product_category', 'product_links');
            $value = abs($value);
            if (is_object($obj_links)) {
                $links = $obj_links->GetLinksCompaq(null, 'product', $id, true);
                $product = CModule::LoadModule('product');
                if (!is_object($product)) {
                    Cmwdb::$db->rollback();
                    throw new Exception('The module product is miss', 1);
                }
                foreach ($links as $prod_id) {
                    $res = $product->ApplyDiscount($prod_id, $value, $action, $type);
                    if (is_bool($res)) continue;
                    Cmwdb::$db->rollback();
                    throw new Exception("Returned error - " . $res);
                }
                Cmwdb::$db->commit();
                return true;
            }
            throw new Exception('Cant find links, maybe not products in this category');
        } catch (Exception $error) {
            return $error->getMessage();
        }
        return true;
    }

    function UpdateSlug($id, $lang, $new_slug, $type = null)
    {
        try {
            Cmwdb::$db->where('cid', $id);
            Cmwdb::$db->where('category_lang', $lang);
            if (!$type) $type = 'product_category';
            $old_slug = Cmwdb::$db->getValue($this->tbl_name, 'slugs');

            $new_slug = CSlug::ConvertToEnglish($new_slug);
            if (is_numeric($new_slug)) $new_slug = 'stdslug-' . $new_slug;
            Cmwdb::$db->where('slugs', $new_slug);
            if (Cmwdb::$db->getValue($this->tbl_name, 'slugs')) throw new Exception('The new slug exists in db.', 1);
            if ($old_slug) {

                Cmwdb::$db->startTransaction();
                if (!CStdRedirects::AddRedirect($type, $id, $old_slug, $new_slug)) {
                    Cmwdb::$db->rollback();
                    throw new Exception("Cant create redirect", 2);
                }
                Cmwdb::$db->where('cid', $id);
                Cmwdb::$db->where('category_lang', $lang);
                if (!Cmwdb::$db->update($this->tbl_name, ['slugs' => $new_slug])) {
                    Cmwdb::$db->rollback();
                    throw new Exception("Error, cant insert new slug into post table", 3);
                }
                Cmwdb::$db->commit();
                return ['status' => 1, 'message' => $new_slug];
            } else {
                Cmwdb::$db->where('cid', $id);
                Cmwdb::$db->where('category_lang', $lang);
                if (!Cmwdb::$db->update($this->tbl_name, ['slugs' => $new_slug])) throw new Exception('Error: Cant insert new slug into post table');
                return ['status' => 1, 'message' => $new_slug];
            }
        } catch (Exception $error) {
            return ['status' => 0, 'message' => $error->getMessage()];
        }
    }
}

?>