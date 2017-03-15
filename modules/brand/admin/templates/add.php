<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
if (is_numeric($edit_id)) {
    $brand = CModule::LoadModule('brand');
    $res = $brand->GetDatas($edit_id);
    //var_dump($res);
    //die;
    $action = CDictionary::GetKey('edit');
} else {
    $action = CDictionary::GetKey('add');
}
?>

<div id="page-wrapper" style="    background: #FDFDFD;">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('brand') ?>
                    <small><?= $action ?>  <?= CDictionary::GetKey('brand') ?></small>
                </h1>

            </div>
        </div>

        <!-- /.row -->
        <?php if (CMessage::hasFlash('message')) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= CMessage::getFlash('message') ?>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        <?php } ?>
        <div class="add-brand-4">
<!--            <button class="btn" id="media_button"> --><?//= CDictionary::GetKey('media') ?><!--</button>-->

            <!-- Nav tabs -->
            <ul class="nav nav-tabs post_leguig" role="tablist">
                <?php foreach (CLanguage::getInstance()->get_langsUser() as $key => $lang) {
                    if ($key == 0) {
                        $active_class = 'active';
                    } else {
                        $active_class = '';
                    }
                    ?>
                    <li role="presentation" class="<?= $active_class ?>">
                        <a href="#<?= $lang['key'] ?>_tabnav" aria-controls="home" role="tab"
                           data-toggle="tab"><?= $lang['title'] ?></a>
                    </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <form action="index.php?module=brand&submenu=action" method="POST" id="brand_form">
                <?php if (!empty($edit_id)) { ?>
                    <input type="hidden" name="action" value="edit_brand">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_brand">
                <?php } ?>

                <div class="tab-content">
                    <?php $page_prop_counter = 0; ?>
                    <?php foreach (CLanguage::getInstance()->get_langsUser() as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($res)) {

                            $brand_title = $res[$lang['key']]['brand_title'];
                            $brand_slug = $res[$lang['key']]['brand_slug'];
                            $brand_descr = $res[$lang['key']]['brand_descr'];


                            $brand_img = $res[$lang['key']]['brand_img'];
                            $brand_img_title = $res[$lang['key']]['brand_img_title'];

                            $brand_files = $res[$lang['key']]['brand_files'];
                            $brand_covers = $res[$lang['key']]['brand_covers'];

                            $brand_gallery = $res[$lang['key']]['brand_gallery'];

                            if (!empty($brand_files)) {
                                $page_prop_counter += max(array_keys($brand_files));
                            }
                            if (!empty($brand_covers)) {
                                $page_prop_counter += max(array_keys($brand_covers));
                            }

                            $seo_title = $res[$lang['key']]['seo_title'];
                            $seo_descr = $res[$lang['key']]['seo_descr'];
                            $seo_keywords = $res[$lang['key']]['seo_keywords'];


//                            $cover_gallery = json_decode($cover_gallery, true);

//                            $gallery = json_decode($gallery, true);
                        } else {

                            $brand_title = '';
                            $brand_slug = '';
                            $brand_content = '';
                            $brand_descr = '';

                            $brand_date = '';

                            $categories = [];

                            $attributes = [];

                            $brand_tags = [];

                            $page_prop_counter = 0;

                            $brand_img = '';
                            $brand_img_title = '';

                            $brand_img = '';
                            $brand_img = '';


                            $seo_title = '';
                            $seo_descr = '';
                            $seo_keywords = '';
                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row add-brand-1">
                                <div class="col-md-9 add-brand-2">
                                    <input type="hidden" value="<?= $page_prop_counter ?>" id="counter_start">

                                    <input type="hidden" name="lang[<?= $lang['key'] ?>][brand_slug]"
                                           value="<?= $brand_slug ?>">
                                    <input type="text" class="form-control" name="lang[<?= $lang['key'] ?>][brand_title]"
                                           placeholder="<?= CDictionary::GetKey('title') ?>"
                                           value="<?= $brand_title ?>" <?php if ($key == 0) echo 'data-required ' ?> data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">


                                    <div class="form-group">
                                        <textarea class="form-control" name="lang[<?= $lang['key'] ?>][brand_descr]"
                                                  rows="5" placeholder="<?= CDictionary::GetKey('short_desc') ?>"><?= $brand_descr ?></textarea>
                                    </div>

                                    <?php if (!empty($edit_id)) { ?>
                                        <div class="form-group edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?module=brand&submenu=action" data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span class="edit_slug_label"><?= CUrlManager::GetStaticURL('brand', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input" value="<?= $brand_slug ?>"
                                                       readonly data-action="slug-value">
                                                    <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"
                                                            data-action="save_edited_slug" style="display: none;">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-success"
                                                            data-action="edit_slug">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group product-seo">
                                        <div class="product-seo2">
                                            <div
                                                class="pr-seo-title2"><?= CDictionary::GetKey('pr-seo-title2'); ?></div>
                                            <button class="btn btn-default" type="button"
                                                    data-action="show_seo"><?= CDictionary::GetKey('seo_tools') ?></button>
                                            <div class="seo_tools" style="display: none">
                                                <input type="text" class="form-control"
                                                       name="lang[<?= $lang['key'] ?>][seo_title]"
                                                       placeholder="<?= CDictionary::GetKey('seo_title') ?>"
                                                       value="<?= $seo_title ?>">
                                            <textarea class="form-control" name="lang[<?= $lang['key'] ?>][seo_descr]"
                                                      rows="5"
                                                      placeholder="<?= CDictionary::GetKey('seo_desc') ?>"><?= $seo_descr ?></textarea>

                                                <input type="text" class="form-control"
                                                       name="lang[<?= $lang['key'] ?>][seo_keywords]"
                                                       placeholder="<?= CDictionary::GetKey('seo_keywords') ?>"
                                                       value="<?= $seo_keywords ?>">
                                            </div>
                                        </div>

                                    </div>



                                </div>
                                <div class="col-md-3 post_right">



                                    <div class="pr_media">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('brand-right-label') ?>
                                            <span data-action="pr-ac-toggle2"></span></div>
                                        <div class="pr_media2">
                                            <div class="form-group product_main_image">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('main_image_title') ?>
                                                </div>

                                                <button type="button" class="btn btn-default"
                                                        data-action="main_image_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('main_image') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_main_image_button"
                                                            data-lang="<?= $lang['key'] ?>"
                                                            style="display: none"><?= CDictionary::GetKey('use') ?> <?= CDictionary::GetKey('main_image') ?>
                                                    </button>
                                                <?php } ?>
                                                <div data-action="gallery_single_content"
                                                     data-lang="<?= $lang['key'] ?>"
                                                     data-gal-single-name="lang[<?= $lang['key'] ?>][brand_img]">
                                                    <?php if (isset($brand_img) && !empty($brand_img)) { ?>
                                                        <?php $img_src = new CAttach($brand_img); ?>
                                                        <div class=" gallery_item gallery_item_product_single">
                                                    <span data-action="gal-delete"><i
                                                            class="fa fa-times gal-img-delete"></i></span>
                                                            <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                 class="img-responsive">
                                                            <input type="hidden"
                                                                   name="lang[<?= $lang['key'] ?>][brand_img][id]"
                                                                   value="<?= $brand_img ?>">
                                                            <input type="text" class="form-control"
                                                                   name="lang[<?= $lang['key'] ?>][brand_img][title]"
                                                                   value="<?= $brand_img_title ?>">
                                                        </div>
                                                        <?php
                                                    } ?>

                                                </div>
                                            </div>

                                            <div class="form-group mw_product_gallery">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('gallery_title') ?>
                                                </div>

                                                <button type="button" class="btn btn-default"
                                                        data-action="cover_gallery_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('gallery') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_cover_gallery_button"
                                                            data-lang="<?= $lang['key'] ?>"
                                                            style="display: none"><?= CDictionary::GetKey('use') ?> <?= CDictionary::GetKey('gallery') ?>
                                                    </button>
                                                <?php } ?>
                                                <div class="row" data-action="cover_gallery_content"
                                                     data-lang="<?= $lang['key'] ?>"
                                                     data-gallery-name="lang[<?= $lang['key'] ?>][brand_gallery]">
                                                    <?php if (isset($brand_gallery) && !empty($brand_gallery)) { ?>
                                                        <?php foreach ($brand_gallery as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][brand_gallery][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][brand_gallery][<?= $key2; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>

                                            <div class="form-group mw_product_covers">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('cover_images_title') ?></div>

                                                <button type="button" class="btn btn-default" data-action="cover_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('cover_images') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_cover_button"
                                                            data-lang="<?= $lang['key'] ?>"
                                                            style="display: none"><?= CDictionary::GetKey('use') ?> <?= CDictionary::GetKey('cover_images') ?>
                                                    </button>
                                                <?php } ?>
                                                <div class="row" data-action="cover_content"
                                                     data-lang="<?= $lang['key'] ?>"
                                                     data-cover-name="lang[<?= $lang['key'] ?>][brand_covers]">
                                                    <?php if (isset($brand_covers) && !empty($brand_covers)) { ?>
                                                        <?php foreach ($brand_covers as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times gal-img-delete"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][brand_covers][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][brand_covers][<?= $key2; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>

                                            <div class="form-group mw_product_attaches">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('attaches_title') ?></div>

                                                <button type="button" class="btn btn-default"
                                                        data-action="attach_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('attaches') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_gallery_button"
                                                            data-lang="<?= $lang['key'] ?>"
                                                            style="display: none"><?= CDictionary::GetKey('use') ?> <?= CDictionary::GetKey('attaches') ?>
                                                    </button>
                                                <?php } ?>
                                                <div class="row" data-action="gallery_content"
                                                     data-lang="<?= $lang['key'] ?>"
                                                     data-gal-name="lang[<?= $lang['key'] ?>][brand_files]">
                                                    <?php if (isset($brand_files) && !empty($brand_files)) { ?>
                                                        <?php $icon_obj = CIcons::getInstance(); ?>
                                                        <?php foreach ($brand_files as $key22 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <?php if ($img_src->GetType() == 'document') {
                                                                $img_src2 = $icon_obj->GetIcon($img_src->GetExtention());
                                                            } else {
                                                                $img_src2 = $img_src->GetURL();
                                                                ?>
                                                            <?php } ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times gal-img-delete"></i></span>
                                                                <img src="<?= $img_src2 ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][brand_files][<?= $key22; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control post_title"
                                                                       name="lang[<?= $lang['key'] ?>][brand_files][<?= $key22; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" id="post_add_button"
                                            class="btn btn-success pull-right"><?= $action ?></button>

                                </div>
                            </div>
                        </div>

                    <?php } ?>


                    <?php CModule::LoadTemplate('map', 'map_modal'); ?>

                </div>
            </form>

        </div>
<?php include 'all.php'?>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"> <?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none">
    <div class="col-md-1 gallery_item gallery_item_product" id="gallery_item_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class=" gallery_item gallery_item_product" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="map_icon" id="map_single_image_template">
        <span data-action="map-icon-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="map-elem-item" id="map-elem-template">
        <input type="hidden" data-action="lat" name="lang[map][lat][]">
        <input type="hidden" data-action="lng" name="lang[map][lng][]">
        <?php foreach (CLanguage::getInstance()->get_langsUser() as $key => $lang) {
            if ($key == 0) {
                $style = '';
            } else {
                $style = 'style="display:none"';
            }
            ?>
            <span data-action="map-lang-item" <?= $style ?> data-value="<?= $lang['key'] ?>">
                <input type="text" name="lang[map][title][<?= $lang['key'] ?>][]">
            </span>
        <?php } ?>

        <button class="btn btn-danger" type="button" data-action="delete-map-item">x</button>
    </div>
</div>
<script>

</script>
<script>


    $('[data-action=gallery_content]').sortable()
    $('[data-action=gallery_content]').disableSelection()

    $('[data-action="cover_content"]').sortable()
    $('[data-action="cover_content"]').disableSelection()

    $('[data-action="cover_gallery_content"]').sortable()
    $('[data-action="cover_gallery_content"]').disableSelection()

    $("[data-action=pr-ac-toggle2]").on('click', function () {
        $('.pr_media2').toggle();
    })

    $('[data-action=main_image_button]').on('click', function () {
        var button = $(this);
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media',
                type: 'images',
                allow_change_type: 0,
                selected_limit: 1
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_single_image();
                })

                return

            }
        })
    })
    $('[data-action=gallery_button]').on('click', function () {
        console.log(1);
        var button = $(this);
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media',
                type: 'images',
                allow_change_type: 0
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();
                //attaching events for modal

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_multiple_attaches();
                })

            }
        })
    })

    $('[data-action=cover_gallery_button]').on('click', function () {
        console.log(1);
        var button = $(this);
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media',
                type: 'images',
                allow_change_type: 0
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();
                //attaching events for modal

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_multiple_gallery_covers();
                })

            }
        })
    })

    $('[data-action=cover_button]').on('click', function () {
        console.log(1);
        var button = $(this);
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media',
                type: 'images',
                allow_change_type: 0
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();
                //attaching events for modal

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_multiple_covers();
                })

            }
        })
    })
    $('[data-action=attach_button]').on('click', function () {
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media'
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();


                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_multiple_attaches();
                })

                //attaching events for modal


            }
        })
    })

    $(document).on('click', '[data-action=gal-delete]', function () {
        $(this).closest('.gallery_item').remove()
    })

    $('[data-action=copy_main_image_button]').on('click', function () {
        var item = $('[role=tabpanel]').first().find('[data-action=gallery_single_content]').find('.gallery_item').clone()
        var content = $(this);
        var active_tab = $('.tab-content').find('.active');
        var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');

        $(item).find('input[type=hidden]').attr({
            name: name + '[id]',
        });
        $(item).find('input[type=text]').attr({
            name: name + '[title]',

        }).val('');

        $(content).closest('[role=tabpanel]').find('[data-action=gallery_single_content]').html(item)

    })

    $('[data-action=copy_cover_gallery_button]').on('click', function () {
        var items = $('[role=tabpanel]').first().find('[data-action=cover_gallery_content]').find('.gallery_item').clone()
        console.log(items);
        var content = $(this);
        var active_tab = $('.tab-content').find('.active');
        var name = $(active_tab).find('[data-gallery-name]').data('gallery-name');

        $.each(items, function (index, value) {
            var img_src = $(value).find('img').attr('src');


//            console.log($(content).closest('[role=tabpanel]').find('[data-action=cover_content]').find('[src="' + img_src + '"]'))
            if ($(content).closest('[role=tabpanel]').find('[data-action=cover_content]').find('[src="' + img_src + '"]').length) {
                //$(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').append(value)

            } else {
                page_prop.counter++;
                $(value).find('input[type=hidden]').attr({
                    name: name + '[' + page_prop.counter + '][id]',
                });
                $(value).find('input[type=text]').attr({
                    name: name + '[' + page_prop.counter + '][title]',

                }).val('');

                $(content).closest('[role=tabpanel]').find('[data-action=cover_gallery_content]').append(value)
            }
        })
    })

    $('[data-action=copy_cover_button]').on('click', function () {
        var items = $('[role=tabpanel]').first().find('[data-action=cover_content]').find('.gallery_item').clone()
        var content = $(this);
        var active_tab = $('.tab-content').find('.active');
        var name = $(active_tab).find('[data-cover-name]').data('cover-name');

        $.each(items, function (index, value) {
            var img_src = $(value).find('img').attr('src');


//            console.log($(content).closest('[role=tabpanel]').find('[data-action=cover_content]').find('[src="' + img_src + '"]'))
            if ($(content).closest('[role=tabpanel]').find('[data-action=cover_content]').find('[src="' + img_src + '"]').length) {
                //$(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').append(value)

            } else {
                page_prop.counter++;
                $(value).find('input[type=hidden]').attr({
                    name: name + '[' + page_prop.counter + '][id]',
                });
                $(value).find('input[type=text]').attr({
                    name: name + '[' + page_prop.counter + '][title]',

                }).val('');

                $(content).closest('[role=tabpanel]').find('[data-action=cover_content]').append(value)

            }
        })
    })

    $('[data-action=show_seo]').on('click', function () {
        $('.seo_tools').toggle()
    })

    $('[data-action=copy_gallery_button]').on('click', function () {
        var items = $('[role=tabpanel]').first().find('[data-action=gallery_content]').find('.gallery_item').clone()
        var content = $(this);
        var active_tab = $('.tab-content').find('.active');
        var name = $(active_tab).find('[data-gal-name]').data('gal-name');

        $.each(items, function (index, value) {
            var img_src = $(value).find('img').attr('src');


//            console.log($(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').find('[src="' + img_src + '"]'))
            if ($(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').find('[src="' + img_src + '"]').length) {
                //$(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').append(value)

            } else {
                page_prop.counter++;
                $(value).find('input[type=hidden]').attr({
                    name: name + '[' + page_prop.counter + '][id]',
                });
                $(value).find('input[type=text]').attr({
                    name: name + '[' + page_prop.counter + '][title]',

                }).val('');

                $(content).closest('[role=tabpanel]').find('[data-action=gallery_content]').append(value)
            }
        })
    })

    $('#brand_form').on('submit', function (e) {
        if(!$('[data-required]').val()){
            e.preventDefault();
            alert($('[data-required]').data('msg'))
            $('.nav-tabs a:first').tab('show');
            $('[data-required]').focus();
        }
    })

    $(document).on('click', '[data-action=delete]', function () {
        var token = $(this).closest('[data-action=post-attr-item]').data('token')
        $('[data-token=' + token + ']').remove()
    })

</script>

