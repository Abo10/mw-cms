
<?php $new_arr = [] ?>
<?php if ($this->_data['category_cover_gallery']) {
    foreach (json_decode($this->_data['category_cover_gallery'], true) as $item) {
        $new_arr[] = (int)$item['id'];
    }
} ?>
<div>
    <?= CFrontSlider::RenderSliderByIDs(CFrontAttach::GetImageUrl($new_arr), ['controlNavThumbs' => false, 'pauseTime' => 3000]) ?>
</div>

<?= CFront::RenderBreadcrumb(); ?>

<div class="contentBottom ">
    <div class="compLeft">
        <?php if ($this->child_cats) { ?>
            <ul class="child_cats">
                <?php foreach ($this->child_cats as $item) { ?>
                    <li><a href="<?= CUrlManager::GetURL(['type'=>'post_category','id'=>$item['cid']]) ?>"><?= $item['category_title'] ?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <div class="compRight">
        <span class="forCat" style="font-size:30px !important;margin:0 0 14px !important;"
              itemprop="name"><?= $this->_data['category_title'] ?></span>
        <div class="certContent cat">
            <div class="certContentBlock">
                <?= $this->_data['category_content'] ?>

            </div>
        </div>
    </div>
</div>