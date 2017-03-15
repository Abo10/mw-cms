<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
if (is_numeric($edit_id)) {
    $tag_obj = CModule::LoadModule('tags',$edit_id);
    $tag = $tag_obj->GetAsArray();
    $action = CDictionary::GetKey('edit');
} else {
    $action = CDictionary::GetKey('add');
}
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('tag') ?>
                    <small><?= $action ?>  <?= CDictionary::GetKey('tag') ?></small>
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
        <div class="tag-1">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tag-2" role="tablist">
                <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
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
            <form class="tag-3" action="index.php?module=tags&submenu=action" method="POST" id="product_tag_form">
                <?php if (!empty($edit_id)) { ?>
                    <input type="hidden" name="action" value="edit_tag">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_tag">
                <?php } ?>

                <div class="tab-content">

                    <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($tag)) {

                            $input = $tag[$lang['key']]['tag_name'];
                            $tag_slug = $tag[$lang['key']]['tag_slug'];
                            $content = $tag[$lang['key']]['tag_descr'];


                        } else {

                            $input = '';
                            $content = '';

                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="lang[<?= $lang['key'] ?>][tag_name]"
                                           placeholder="<?= CDictionary::GetKey('tag_name') ?>" value="<?= $input ?>" <?php if ($key == 0) echo 'data-required ' ?> data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">

                                    <div class="form-group">
                                    <textarea class="form-control" name="lang[<?= $lang['key'] ?>][tag_descr]" rows="5"
                                              placeholder="<?= CDictionary::GetKey('tag_desc') ?>"><?= $content ?></textarea>
                                    </div>
                                    <?php if (!empty($edit_id)) { ?>
                                        <div class="edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?module=tags&submenu=action" data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span class="edit_slug_label"><?= CUrlManager::GetStaticURL('product_tag', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input" value="<?= $tag_slug ?>"
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
                                </div>
                                <!--<div class="col-md-2">
                                    <div class="form-group">
                                        <button class="btn btn-default" type="button" data-action="show_seo">Seo Tools
                                        </button>
                                        <div class="seo_tools" style="display: none">
                                            <input type="text" class="form-control"
                                                   name="lang[<? /*= $lang['key'] */ ?>][seo_title]"
                                                   placeholder="Seo Title" value="<? /*= $seo_title */ ?>">
                                            <input type="text" class="form-control"
                                                   name="lang[<? /*= $lang['key'] */ ?>][seo_descr]"
                                                   placeholder="Seo Description" value="<? /*= $seo_descr */ ?>">
                                            <input type="text" class="form-control"
                                                   name="lang[<? /*= $lang['key'] */ ?>][seo_keywords]"
                                                   placeholder="Seo Keywords" value="<? /*= $seo_keywords */ ?>">
                                        </div>
                                    </div>

                                </div>-->
                            </div>
                        </div>

                    <?php } ?>


                </div>
                <button type="submit" class="btn btn-success"><?= $action ?></button>
            </form>

        </div>
        <div class="tag-5">
            <?php include 'all.php'?>
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
                <h4 class="modal-title" id="exampleModalLabel"> <?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none">
    <div class="col-md-1 gallery_item" id="gallery_item_template">
        <span data-action="gal-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class=" gallery_item" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
</div>
<script>
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

    $('[data-action=parent_cat_select]').on('change', function (e) {
        e.preventDefault();

        var val = $(this).val();
        $('[data-action=parent_cat_select]').val(val)
        $('.cat_select_class').trigger("chosen:updated")
    })

    $('[data-action=status-checkbox]').on('change', function () {
        var is_check = $(this).prop('checked');
        $('[data-action=status-checkbox]').prop('checked', is_check);
    })

    $('[data-action=date_input]').datetimepicker();
    $('[data-action=date_input]').on('change', function (e) {
        e.preventDefault();
        var val = $(this).val();
        $('[data-action=date_input]').val(val)
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
    $('.cat_select_class').chosen({
        width: '100%'
    })
    $('#product_tag_form').on('submit', function (e) {
        if(!$('[data-required]').val()){
            e.preventDefault();
            alert($('[data-required]').data('msg'))
            $('.nav-tabs a:first').tab('show');
            $('[data-required]').focus();
        }
    })


</script>