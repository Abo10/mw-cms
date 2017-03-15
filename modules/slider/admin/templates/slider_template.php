<!--<div class="slider_first_image_template" id="slider_first_image_template">-->
<!--    <img class="menu_item_img" src="" alt="">-->
<!--    <i class="fa fa-times gal-img-delete delete_slide_first_img" data-action="delete_slide_first_img"></i>-->
<!--</div>-->
<!--<div class="slider_second_image_template" id="slider_second_image_template">-->
<!--    <img class="menu_item_img" src="" alt="">-->
<!--    <i class="fa fa-times gal-img-delete delete_slide_second_img" data-action="delete_slide_second_img"></i>-->
<!--</div>-->
<?php foreach ($items['main']['first_attach_id'] as $key3 => $item) { ?>
    <div class="mw_slider_item" data-action="slider_item">
        <div class="row">
            <div class="col-md-4">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        ?>
                        <li role="presentation" class="<?= $active_class ?>">
                            <a href="#<?= $lang['key'] ?>_tabnav<?= $key3 ?>" aria-controls="home" role="tab"
                               data-toggle="tab"><?= $lang['title'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">

                    <?php foreach (CLanguage::get_langsUser() as $key => $lang) { ?>
                        <?php
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        ?>
                        <div role="tabpanel" data-action="slider_tab" class="tab-pane <?= $active_class ?>"
                             id="<?= $lang['key'] ?>_tabnav<?= $key3 ?>">
                            <button style="position: absolute;  top: -66px; left: 314px;" type="button" class="btn btn-default" data-action="add_slider_second_img"
                                    data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('slider_image1') ?>
                            </button>
							 <div class="slider_second_img_container1">
                            <div data-action="slider_second_img_container">
                                <?php if ($items['lang'][$lang['key']]['attach_url'][$key3]) { ?>
                                    <div class="slider_second_image_template" id="slider_second_image_template">
                                        <img class="menu_item_img"
                                             src="<?= $items['lang'][$lang['key']]['attach_url'][$key3] ?>"
                                             alt="">
                                        <i class="fa fa-times gal-img-delete delete_slide_second_img"
                                           data-action="delete_slide_second_img"></i>
                                    </div>
                                <?php } ?>

                            </div>
                            <input type="text" name="lang[<?= $lang['key'] ?>][url][]" class="form-control mw-slider10"
                                   placeholder="URL" value="<?= $items['lang'][$lang['key']]['url'][$key3] ?>">
                            <input type="hidden" name="lang[<?= $lang['key'] ?>][second_attach_id][]"
                                   value="<?= $items['lang'][$lang['key']]['second_attach_id'][$key3] ?>"
                                   data-action="second_attach_id">
								   </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="col-md-8">
    <span class="menu_item_delete_button" data-action="slider_item_delete_button">
                    <i class="fa fa-times"></i>
                </span>
                <button type="button" class="btn btn-default" data-action="add_slider_first_img"
                        data-lang="<?= $lang['key'] ?>"> <?= CDictionary::GetKey('slider_image2') ?>
                </button>
                <input type="hidden" data-action="first_attach_id" name="main[first_attach_id][]" value="<?= $item ?>">
                <input type="hidden" data-action="is_active_item" name="main[is_active][]" value="1">

                <div data-action="slider_first_img_container">
                    <?php if ($items['main']['attach_url'][$key3]) { ?>
                        <div class="slider_first_image_template" id="slider_first_image_template">
                            <img class="menu_item_img" src="<?= $items['main']['attach_url'][$key3] ?>" alt="">
                            <i class="fa fa-times gal-img-delete delete_slide_first_img" data-action="delete_slide_first_img"></i>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>