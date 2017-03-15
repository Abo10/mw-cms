<?= CFront::RenderBreadcrumb(); ?>
<div class="contentBottom ">
    <div class='post_single'>
        <div class='single_post_title'><?= $this->_data['post_title'] ?></div>
        <div class='single_post_content'><?= $this->_data['post_content'] ?></div>
        <div class='single_post_image'><img src="<?= CFrontAttach::GetImageUrl($this->_data['post_img'], 'original') ?>" alt=""></div>
        <div class='single_post_date'><span><?= date('Y-m-d H:i:s', $this->_data['post_s_date']) ?></span></div>
    </div>
</div>

