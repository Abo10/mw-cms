<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
if (is_numeric($edit_id)) {
    $category = new CCategoryPost();
    $res = $category->GetAsArrayPID($edit_id);
    $action = CDictionary::GetKey('edit');
} else {
    $action = CDictionary::GetKey('add');
}
//var_dump($res);
?>
<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('cat_post') ?>
                    <small><?= $action ?> </small>

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
        <div>
            <button class="btn" id="media_button"><?= CDictionary::GetKey('media') ?></button>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs add_page55" role="tablist">
                <?php foreach ($user_langs as $key => $lang) {
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
            <button form="post_cat_form" type="submit"
                    class="btn btn-success pull-right"><?= $action ?></button>

            <!-- Tab panes -->
            <form action="index.php?menu=post_category&submenu=action" method="POST" id="post_cat_form">
                <?php if (!empty($edit_id)) { ?>
                    <input type="hidden" name="action" value="edit_category">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_category">
                <?php } ?>

                <div class="tab-content">
                    <?php $page_prop_counter = 0; ?>

                    <?php foreach ($user_langs as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($res)) {

                            $input = $res[$lang['key']]['category_title'];
                            $cat_slug = $res[$lang['key']]['slugs'];
                            $content = $res[$lang['key']]['category_content'];

                            $cat_parent = $res[$lang['key']]['category_parent'];

                            $cat_img = $res[$lang['key']]['category_img'];
                            $cat_img_title = $res[$lang['key']]['category_img_title'];

                            if (isset($res['attributes'][$lang['key']])) {
                                $attributes = $res['attributes'][$lang['key']];
                            } else {
                                $attributes = [];
                            }

                            $seo_title = $res[$lang['key']]['seo_title'];
                            $seo_descr = $res[$lang['key']]['seo_descr'];
                            $seo_keywords = $res[$lang['key']]['seo_keywords'];


                            $cover_gallery = $res[$lang['key']]['category_cover_gallery'];
//                            $cover_gallery = json_decode($cover_gallery, true);

                            $gallery = $res[$lang['key']]['category_gallery'];

                            if (!empty($gallery)) {
                                $page_prop_counter += max(array_keys($gallery));
                            }
                            if (!empty($cover_gallery)) {
                                $page_prop_counter += max(array_keys($cover_gallery));
                            }
                            if (!empty($gallery)) {
                                $page_prop_counter += max(array_keys($gallery));
                            }
//                            $gallery = json_decode($gallery, true);
                        } else {

                            $input = '';
                            $content = '';
                            $cat_parent = null;

                            $attributes = [];

                            $cat_img = '';

                            $seo_title = '';
                            $seo_descr = '';
                            $seo_keywords = '';
                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row">
                                <div class="col-md-9 post_cat_sidebar_left">
                                    <div class="add_page_add">

                                        <input type="text" class="form-control"
                                               name="lang[<?= $lang['key'] ?>][category_title]"
                                               placeholder="<?= CDictionary::GetKey('title') ?>"
                                               value="<?= $input ?>" <?php if ($key == 0) echo 'data-required ' ?>
                                               data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">

                            <textarea name="lang[<?= $lang['key'] ?>][category_content]" id="editor_<?= $lang['key'] ?>"
                                      rows="10"
                                      cols="80">
                                <?= $content ?>
                            </textarea>
                                    </div>
                                    <?php if(false){ ?>
                                        <script>
                                            // Replace the <textarea id="editor1"> with a CKEditor
                                            // instance, using default configuration.
                                            var a = CKEDITOR.replace('editor_<?= $lang['key'] ?>', {
//                                    "extraPlugins" : 'imagebrowser,video,mwplugin',
                                                    cextraPlugins: 'imageresize',
                                                    height: '400px',
//                                    "imageBrowser_listUrl" : "/mastershop.local/image_list.json"
                                                }
                                            );

                                        </script>
                                    <?php } ?>
                                    <script>
                                        tinymce.init({
                                            selector: '#editor_<?= $lang['key'] ?>',
                                            height: 500,
                                            relative_urls: false,
                                            plugins: [
                                                'advlist autolink lists link image charmap print preview anchor',
                                                'searchreplace visualblocks code fullscreen',
                                                'insertdatetime media table contextmenu paste code textcolor media'
                                            ],
                                            toolbar: 'insertfile undo redo | styleselect | bold italic |  bullist numlist outdent indent | link image | forecolor backcolor | sizeselect | bold italic | fontselect |  fontsizeselect | media',

                                        });
                                    </script>
                                    <hr>
                                    <?php if (!empty($edit_id)) { ?>
                                        <div class="edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?menu=post_category&submenu=action"
                                             data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span
                                                class="edit_slug_label"><?= CUrlManager::GetStaticURL('post_category', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input"
                                                       value="<?= $cat_slug ?>"
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
                                        <div class="product-seo2" style="width:100%">
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
                                <div class="col-md-3 post_cat_sidebar_right">
                                    <div class="form-group post_cat987">
                                        <div class="post_cat_parent_label">
                                            <?= CDictionary::GetKey('post_cat_parent_label') ?>
                                        </div>
                                        <select name="lang[<?= $lang['key'] ?>][category_parent]" id=""
                                                class="form-control"
                                                data-action="parent_cat_select">
                                            <option value="0"> <?= CDictionary::GetKey('select') ?></option>
                                            <?php
                                            $a = new CCategoryPost();
                                            if ($edit_id) {
                                                $cat = $a->GetAllCatsExcept($edit_id);
                                                foreach ($cat as $item) {
                                                    if ($item['value']['cid'] == $edit_id) continue;
                                                    ?>
                                                    <option
                                                        value="<?= $item['value']['cid'] ?>" <?php if ($item['value']['cid'] == $cat_parent) echo 'selected'; ?>><?= $a->GetTree($item['level']) ?><?= $item['value']['category_title'] ?></option>
                                                <?php }
                                            } else {
                                                $cat = $a->GetAllCats();
                                                foreach ($cat as $item) {
                                                    ?>
                                                    <option
                                                        value="<?= $item['value']['cid'] ?>" <?php if ($item['value']['cid'] == $cat_parent) echo 'selected'; ?>><?= $a->GetTree($item['level']) ?><?= $item['value']['category_title'] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="pr_media">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('post-right-label') ?>
                                            <span data-action="pr-ac-toggle2"></span></div>
                                        <div class="pr_media2">

                                            <div class="form-group product_main_image">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('main_image_title') ?></div>
                                                <button type="button" class="btn btn-default"
                                                        data-action="main_image_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('select') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_main_image_button"
                                                            data-lang="<?= $lang['key'] ?>" style="display: none">Use
                                                        Main image
                                                    </button>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-md-12" data-action="gallery_single_content"
                                                         data-lang="<?= $lang['key'] ?>"
                                                         data-gal-single-name="lang[<?= $lang['key'] ?>][category_img]">

                                                        <?php if (isset($cat_img) && !empty($cat_img)) { ?>
                                                            <?php $img_src = new CAttach($cat_img); ?>
                                                            <div class="gallery_item gallery_item_product_single">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][category_img][id]"
                                                                       value="<?= $cat_img ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][category_img][title]"
                                                                       value="<?= $cat_img_title ?>">
                                                            </div>
                                                            <?php
                                                        } ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mw_product_covers">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('cover_images_title') ?>
                                                </div>
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
                                                     data-cover-name="lang[<?= $lang['key'] ?>][category_cover_gallery]">
                                                    <?php if (isset($cover_gallery) && !empty($cover_gallery)) { ?>
                                                        <?php foreach ($cover_gallery as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][category_cover_gallery][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][category_cover_gallery][<?= $key2; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>
                                            <div class="form-group mw_product_attaches">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('attaches_title') ?>
                                                </div>
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
                                                     data-gal-name="lang[<?= $lang['key'] ?>][category_gallery]">
                                                    <?php if (isset($gallery) && !empty($gallery)) { ?>
                                                        <?php $icon_obj = CIcons::getInstance(); ?>
                                                        <?php foreach ($gallery as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <?php if ($img_src->GetType() == 'document') {
                                                                $img_src2 = $icon_obj->GetIcon($img_src->GetExtention());
                                                            } else {
                                                                $img_src2 = $img_src->GetURL();
                                                                ?>
                                                            <?php } ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times"></i></span>
                                                                <img src="<?= $img_src2 ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][category_gallery][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][category_gallery][<?= $key2; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="form-group post-attr-group" data-action="post-attr-group">
                                        <?php $attr_obj = new CAttrTemplateList() ?>
                                        <?php if (!empty($attributes)) { ?>
                                            <?php $step = true; ?>
                                            <?php foreach ($attributes as $key22 => $attr_item) { ?>
                                                <div class="form-inline post-attr-group"
                                                     data-action="post-attr-item"
                                                     data-token="<?= $key22 ?>">
                                                    <div class="form-group">
                                                        <select class="form-control"
                                                                name="lang[<?= $lang['key'] ?>][post_attr][]">
                                                            <option
                                                                value=''><?= CDictionary::GetKey('select') ?></option>
                                                            <?php foreach ($attr_obj->GetDatas() as $key1 => $item2) { ?>
                                                                <option
                                                                    value="<?= $key1 ?>" <?php if ($attr_item['t_id'] == $key1) echo 'selected'; ?>><?= $item2['attr'][$lang['key']] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="text" class="form-control"
                                                               name="lang[<?= $lang['key'] ?>][post_attr_title][]"
                                                               value="<?= $attr_item['attr_values'] ?>">
                                                        <?php if ($step) { ?>
                                                            <?php $step = false; ?>
                                                            <button type="button" class="btn btn-success"
                                                                    data-action="add">
                                                                +
                                                            </button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-danger"
                                                                    data-action="delete">
                                                                x
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="form-inline post-attr-group" data-action="post-attr-item"
                                                 data-token="qqq">
                                                <div class="form-group">
                                                    <select class="form-control"
                                                            name="lang[<?= $lang['key'] ?>][post_attr][]">
                                                        <option
                                                            value=''><?= CDictionary::GetKey('select') ?></option>
                                                        <?php foreach ($attr_obj->GetDatas() as $key1 => $item2) { ?>
                                                            <option
                                                                value="<?= $key1 ?>"><?= $item2['attr'][$lang['key']] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="text" class="form-control"
                                                           name="lang[<?= $lang['key'] ?>][post_attr_title][]">
                                                    <button type="button" class="btn btn-success" data-action="add">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <input type="hidden" value="<?= $page_prop_counter ?>" id="counter_start">


                </div>

            </form>

        </div>


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
                <h4 class="modal-title" id="exampleModalLabel"><?= $action ?> <?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none">
    <div class="gallery_item  gallery_item_product" id="gallery_item_template">
        <span data-action="gal-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="gallery_item gallery_item_product_single" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
</div>
<script>

    page_prop.counter = parseInt($('#counter_start').val());

    $('[data-action=show_seo]').on('click', function () {
        $('.seo_tools').toggle()
    })
    $('#media_button').on('click', function () {
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
                    handle_editor_attaches();
                })


            }
        })
    })
    $('[data-action=gallery_content]').sortable()
    $('[data-action=gallery_content]').disableSelection()

    $('[data-action="cover_content"]').sortable()
    $('[data-action="cover_content"]').disableSelection()

    $('[data-action="cover_gallery_content"]').sortable()
    $('[data-action="cover_gallery_content"]').disableSelection()


    $('[data-action=parent_cat_select]').on('change', function (e) {
        e.preventDefault();
        var val = $(this).val();
        $('[data-action=parent_cat_select]').val(val)
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
    $('#post_cat_form').on('submit', function (e) {
        if (!$('[data-required]').val()) {
            e.preventDefault();
            alert($('[data-required]').data('msg'))
            $('.nav-tabs a:first').tab('show');
            $('[data-required]').focus();
        }
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
    $("[data-action=pr-ac-toggle2]").on('click', function () {
        $('.pr_media2').toggle();
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

    $('[data-action=copy_cover_button]').on('click', function () {
        var items = $('[role=tabpanel]').first().find('[data-action=cover_content]').find('.gallery_item').clone()
        console.log(items);
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

    $('[data-action=post-attr-item] [data-action=add]').on('click', function () {
        var token = guid();
        $('[data-action=post-attr-item] [data-action=add]').each(function (i, v) {
            var clone = $(v).closest('[data-action=post-attr-item]').clone();
            clone.attr('data-token', token);
            clone.find('input[type=text]').val('');
            clone.find('[data-action=add]').removeAttr('data-action').addClass('btn-danger').html('x').attr('data-action', 'delete');
            var container = $(v).closest('[data-action=post-attr-group]').append(clone);
        })


    })
    $(document).on('click', '[data-action=delete]', function () {
        var token = $(this).closest('[data-action=post-attr-item]').data('token')
        $('[data-token=' + token + ']').remove()
    })
    $(document).on('change', '[data-action=post-attr-item] select', function () {
        var value = $(this).val();
        console.log(value);
        var token = $(this).closest('[data-action=post-attr-item]').data('token');
        $('[data-token=' + token + ']').find('select').val(value)
    })


</script>