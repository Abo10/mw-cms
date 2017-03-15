<?php global $map ; ?>
<div id="map_modal" class="modal fade" role="dialog">
    <div class="modal-dialog mapmap55 modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
                <h4 class="modal-title"> <?= CDictionary::GetKey('post-map-label') ?></h4>   
            </div>
            <div class="modal-body">
                <div class="map_tools">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <div id="map" class="map col-md-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <ul class="nav nav-tabs maps-tab" data-action="map-nav-panel"
                                role="tablist">
                                <?php foreach (CLanguage::getInstance()->get_langsUser() as $key => $lang) {
                                    if ($key == 0) {
                                        $active_class = 'active';
                                    } else {
                                        $active_class = '';
                                    }
                                    ?>
                                    <li role="presentation" class="<?= $active_class ?>">
                                        <a href="#<?= $lang['key'] ?>_tabnav_map"
                                           data-href="<?= $lang['key'] ?>" aria-controls="home"
                                           role="tab"
                                           data-toggle="tab"><?= $lang['title'] ?></a>
                                    </li>

                                <?php } ?>
                                <li><button type="button" class="map_metka" data-action="add_mark"><?= CDictionary::GetKey('map_metka') ?></button>
                                    <div data-action="map_input_container"></div>
                                </li>
                            </ul>
                            <div class="map-tab-content">
                                <?php if (isset($map)) { ?>
                                    <?php foreach ($map as $map_item) { ?>
                                        <?php $map_titles = json_decode($map_item['map_title'], true) ?>
                                        <div class="map-elem-item" data-u-id="<?= uniqid() ?>">
                                            <input type="hidden" data-action="lat"
                                                   name="lang[map][lat][]"
                                                   value="<?= $map_item['lat'] ?>">
                                            <input type="hidden" data-action="lng"
                                                   name="lang[map][lng][]"
                                                   value="<?= $map_item['lng'] ?>">
                                            <?php foreach (CLanguage::getInstance()->get_langsUser() as $key => $lang) {
                                                if ($key == 0) {
                                                    $style = '';
                                                } else {
                                                    $style = 'style="display:none"';
                                                }
                                                ?>
                                                <span data-action="map-lang-item" <?= $style ?>
                                                      data-value="<?= $lang['key'] ?>">
                                                                        <input type="text" placeholder="<?= CDictionary::GetKey('seo_title') ?> "
                                                                               name="lang[map][title][<?= $lang['key'] ?>][]"
                                                                               value="<?= $map_titles[$lang['key']] ?>"/>
                                                                    </span>
                                            <?php } ?>

                                            <button class="btn btn-danger map_close" type="button"
                                                    data-action="delete-map-item">x
                                            </button>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" data-action="map_image_button"><?= CDictionary::GetKey('image_map') ?></button> 
                            <div data-action="map_image_container">
                                <?php if (isset($map)) { ?>
                                    <?php foreach ($map as $item) { ?>
                                        <?php if ($item['img_id']) { ?>

                                            <?php if (isset($item['img_id']) && !empty($item['img_id'])) { ?>
                                                <?php $img_src = new CAttach($item['img_id']); ?>
                                                <div class="map_icon">
                                                                            <span data-action="map-icon-delete"><i
                                                                                    class="fa fa-times"></i></span>
                                                    <img src="<?= $img_src->GetURL() ?>" alt=""
                                                         class="img-responsive">
                                                    <input type="hidden"
                                                           name="lang[map][img_id]"
                                                           value="<?= $item['img_id'] ?>">

                                                </div>
                                                <?php
                                            } ?>

                                        <?php } ?>
                                        <?php break; ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= CDictionary::GetKey('select') ?></button>
            </div>
        </div>

    </div>
</div>
