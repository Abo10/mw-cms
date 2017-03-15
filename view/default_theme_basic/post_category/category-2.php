<div class="contentBottom ">
    <div class="compRight">
        <span class="forCat" style="font-size:30px !important;margin:0 0 14px !important;"
              itemprop="name"><?= $this->_data['category_title'] ?></span>
        <div class="certContent cat">
            <div class="certContentBlock">
                <?= $this->_data['category_content'] ?>

            </div>
        </div>
    </div>
    <div class="posts">
        <?php foreach ($this->_posts as $item){ ?>
            <?php $item = $item['post'] ?>
            <div class="post_item">
                <a href="<?= CUrlManager::GetStaticURL('post',$item['post_slug']) ?>">
                    <div class="post_img"><img src="<?= CFrontAttach::GetImageUrl($item['post_img'],'original') ?>" alt=""></div>
                    <div class="post_title"><?= $item['post_title'] ?></div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>