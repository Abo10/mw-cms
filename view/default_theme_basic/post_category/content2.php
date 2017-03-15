<?= CFront::RenderBreadcrumb(); ?>

<div class='post_cat_title'>
    <div><?= $this->_data['category_title'] ?></div>
</div>
<?php foreach ($this->_posts as $item) { ?>
    <div class='post_item'>
        <div class='post_title'><?= $item['post']['post_title'] ?></div>
        <div class='post_content'><?= $item['post']['post_content'] ?></div>
        <div class='post_image'><img src="<?= CFrontAttach::GetImageUrl($item['post']['post_img'], 'original') ?>"
                                     alt=""></div>
        <div class='post_date'><span><?= date('Y-m-d H:i:s', $item['post']['post_s_date']) ?></span></div>
        <div class='post_read_more'><a
                href="<?= CUrlManager::GetStaticURL('post', $item['post']['post_slug']) ?>"><?= CDictionaryUser::GetKey('read_more') ?></a>
        </div>
    </div>
<?php } ?>
<?php $new_arr = [] ?>
<?php if ($this->_data['category_cover_gallery']) {
    foreach (json_decode($this->_data['category_cover_gallery'], true) as $item) {
        $new_arr[] = (int)$item['id'];
    }
} ?>
<div>
    <?= CFrontSlider::RenderSliderByIDs(CFrontAttach::GetImageUrl($new_arr),['controlNavThumbs'=>false,'pauseTime'=>3000]) ?>
</div>

