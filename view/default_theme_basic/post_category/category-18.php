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
                    <li>
                        <a href="<?= CUrlManager::GetURL(['type' => 'post_category', 'id' => $item['cid']]) ?>"><?= $item['category_title'] ?></a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <div class="compRight">
        <span class="forCat" style="font-size:30px !important;margin:0 0 14px !important;"
              itemprop="name"><?= $this->_data['category_title'] ?></span>
        <div class="certContent cat">
            <div class="certContentBlock">
                <div>
                    <?= $this->_data['category_content'] ?>
                </div>
                <div class="our_images_container">
                    <?php foreach ($this->files as $item) { ?>
                        <div class="our_images_item">
                            <img src="<?= $item['url']['original'] ?>" alt="">
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <video controls>
                        <source src="<?= URL_BASE ?>uploads/videos/1.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <video controls>
                        <source src="<?= URL_BASE ?>uploads/videos/2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .our_images_container {

    }

    .our_images_item {

    }
    .our_images_item img{
        width: 100%;
    }

    .compRight {
        width: 100% !important;
    }
</style>