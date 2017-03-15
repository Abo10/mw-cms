<?php $lang = CLanguage::getInstance(); ?>
<!---->
<li data-action="menu_item" data-m_type="<?= $this->datas[$value]['m_type'] ?>"
    data-m_elem_id="<?= $this->datas[$value]['m_elem_id'] ?>">
    <div class="menu_item">
        <div class="menu_item_head">
            <span data-action="menu-type"><?= CDictionary::GetKey($this->datas[$value]['m_type']) ?></span>
            <span> : </span>

            <span data-action="menu-title" data-title-langs='<?= CStdMenu::GetAnyTitle(['type'=>$this->datas[$value]['m_type'],'id'=>$this->datas[$value]['m_elem_id']])['json'] ?>'><?= CStdMenu::GetAnyTitle(['type'=>$this->datas[$value]['m_type'],'id'=>$this->datas[$value]['m_elem_id']])['title'] ?></span>
            <i class="fa fa-fw fa-caret-down"></i>
                <span class="menu_item_delete_button">
                    <i class="fa fa-times"></i>
                </span>
        </div>
        <div style="display: none" class="menu_item_detailed">
            <div>
                <span><?= CDictionary::GetKey('seo_title') ?></span> 
                <?php $bool_check = true; ?>
                <?php foreach ($lang->get_lang_keys_user() as $key => $item) { ?>
                    <input type="text" data-lang="<?= $item ?>" data-action="menu_item_title" class=" input-sm"
                            <?php if ($bool_check) {
                        $bool_check = false;
                    } else {
                        echo 'style="display: none"';
                    } ?> value="<?= $this->for_admin_use[$value][$item]['menu_text'] ?>">
                <?php } ?>
                <span class="img_menu_item_block">
                        <button type="button" class="btn btn-primary btn-sm" data-action="img_menu_item_button"><?= CDictionary::GetKey('img') ?>
                        </button>
                                <input type="hidden" data-action="menu_item_attach_id"
                                       value="<?= $this->datas[$value]['m_attr'] ?>"/>

                        <span class="menu_img_container">
                            <?php
                                if ($this->datas[$value]['m_attr']) {
                                    $img = new CAttach($this->datas[$value]['m_attr']);
                                    $url = $img->GetURL('original');
                            ?>
                                <div class="menu_item_img_template" id="menu_item_img_template">
                                    <img class="menu_item_img" src="<?= $url ?>" alt="">
                                    <i class="fa fa-times" data-action="delete_menu_img"></i>
                                </div>
                            <?php } ?>
                        </span>

                    </span>
            </div>
            <div
                class="url_tab" <?php if ($this->datas[$value]['m_type'] != 'custom_link') echo 'style="display:none"'; ?>>
                <span><?= CDictionary::GetKey('url') ?></span> 
                <?php $bool_check = true; ?>
                <?php foreach ($lang->get_lang_keys_user() as $key => $item) { ?>
                    <input type="text" data-lang="<?= $item ?>" data-action="menu_item_url" class=" input-sm"
                           placeholder="Title" <?php if ($bool_check) {
                        $bool_check = false;
                    } else {
                        echo 'style="display: none"';
                    } ?> value="<?= $this->for_admin_use[$value][$item]['menu_url'] ?>">
                <?php } ?>
            </div>
            <div>
                <span>Class</span>
                <input type="text" data-action="menu_item_class" class=" input-sm" placeholder="<?= CDictionary::GetKey('seo_title') ?>"
                       value="<?= $this->datas[$value]['m_class'] ?>">
                <span> <?= CDictionary::GetKey('new_tab') ?></span>
                <input type="checkbox"
                       data-action="menu_item_blank_tab" <?php if ($this->datas[$value]['m_tab'] == 1) echo 'checked' ?>>
            </div>
            <div>
                <span><?= CDictionary::GetKey('attributes_menu') ?></span> 
                <?php $bool_check = true; ?>
                <?php foreach ($lang->get_lang_keys_user() as $key => $item) { ?>
                    <input type="text" data-lang="<?= $item ?>" data-action="menu_item_bandle" class=" input-sm"
                           placeholder="<?= CDictionary::GetKey('seo_title') ?>" <?php if ($bool_check) {
                        $bool_check = false;
                    } else {
                        echo 'style="display: none"';
                    } ?> value="<?= $this->for_admin_use[$value][$item]['jq_handle'] ?>">
                <?php } ?>
            </div>

        </div>
    </div>
    <?php
    if (isset($this->all_parents[$value])) {
        echo "<ol>";
        foreach ($this->all_parents[$value] as $any_val)
            $this->GetParentDom_admin($any_val);
        echo "</ol>";
    }

    ?>
</li>
