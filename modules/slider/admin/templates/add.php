<?php

$current_lang = CLanguage::getInstance()->getCurrent();
$current_lang_user = CLanguage::getInstance()->getCurrentUser();

?>

<script type="text/javascript" src="js/nestedSortable-2.0alpha/jquery.mjs.nestedSortable.js"></script>
<script>

    $(document).ready(function () {

        $('#add_slider_item').on('click', function () {
            var elem_clone = $('#slider_item_template').clone().removeAttr('id');
            var uid = guid();
            $(elem_clone).find('a[role="tab"]').each(function (i, v) {
                var old_id = $(v).attr('href');
                var new_id = old_id + uid;
                $(v).attr('href', new_id)
            })
            $(elem_clone).find('[role="tabpanel"]').each(function (i, v) {
                var old_id = $(v).attr('id');
                var new_id = old_id + uid;
                $(v).attr('id', new_id)
            })
            $('#slider_items_container').append(elem_clone);
        })
        $('#slider_items_container').sortable();
        $(document).on('click', '[data-action=delete_menu_img]', function (e) {
            $(this).closest('.img_menu_item_block').find('[data-action=menu_item_attach_id]').val('');
            $(this).closest('.menu_item_img_template').remove()
        })

        $(document).on('click', '[data-action="slider_item_delete_button"]', function () {
            $(this).closest('[data-action="slider_item"]').remove();
        })
        $('#save_slider').on('click', function () {
            if (!$('[data-action="m_group_select"]').val()) {
                alert('select slider');
                return;
            }
            if ($('#slider_items_container').children().length == 0) {
                alert('Add items');
                return;
            }
            $.ajax({
                url: "index.php?module=slider&submenu=action",
                type: "POST",
                data: $('#slider_form').serialize(),
                success: function (msg) {
//                    console.log(msg);
                    alert('Successfully saved');
                }

            })
        })

        $('[data-action=add_menu]').on('click', function () {
            var menu_name = $("[data-action=add_menu_name]").val();
            if (!menu_name) {
                alert("Please fill the name field");
                return;
            }
            ;
            $.ajax({
                type: "POST",
                url: "index.php?module=slider&submenu=action",
                data: {
                    menu_name: menu_name,
                    action: 'add_menu'
                },
                success: function (msg) {

                    var data = JSON.parse(msg);
                    console.log(msg)
                    console.log(data)
                    if (data['status']) {
                        $('[data-action=m_group_select]').append('<option value="' + data.slider_id + '">' + data.slider_name + '</option>');
                        $('[data-action=m_group_select]').val(data.slider_id);
                        $('[data-action=m_group_select]').trigger('change');
                        $("[data-action=add_menu_name]").val('')
                    } else {
                        alert('Menu already exists');
                    }
                }
            })

        })
        $('[data-action="m_group_select"]').on('change', function () {
            $('#slider_form').find('input[name="slider_id"]').val($(this).val());
            var slider_id = $(this).val();
            $.ajax({
                url: 'index.php?module=slider&submenu=action',
                type: 'POST',
                data: {
                    id: slider_id,
                    action: 'get_slider_elements'
                },
                success: function (msg) {
                    $('#slider_items_container').html(msg);

                }
            })

        })


        $(document).on('click', '[data-action=add_slider_second_img]', function () {
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
                        var img_id = page_prop.submited_items[0].attach_id;
                        var img_url = page_prop.submited_items[0].attach_img_src;
                        var img_clone = $('#slider_second_image_template').clone();
                        $(img_clone).removeAttr('id');
                        $(img_clone).find('.menu_item_img').attr('src', img_url);

                        $(button).closest('[data-action="slider_tab"]').find('[data-action="slider_second_img_container"]').html(img_clone)
                        $(button).closest('[data-action="slider_tab"]').find('[data-action="second_attach_id"]').val(img_id)
                    })

                    return

                }
            })
        })
        $(document).on('click', '[data-action=add_slider_first_img]', function () {
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
                        $(document).off('click', '#add_attachment');
                        var img_id = page_prop.submited_items[0].attach_id;
                        var img_url = page_prop.submited_items[0].attach_img_src_original;
                        var img_clone = $('#slider_first_image_template').clone();
                        $(img_clone).removeAttr('id');
                        $(img_clone).find('.menu_item_img').attr('src', img_url);

                        $(button).closest('[data-action="slider_item"]').find('[data-action="slider_first_img_container"]').html(img_clone)
                        $(button).closest('[data-action="slider_item"]').find('[data-action="first_attach_id"]').val(img_id)
                    })

                    return

                }
            })
        })
        $(document).on('click', '[data-action="delete_slide_first_img"]', function () {
            $(this).closest('[data-action="slider_item"]').find('[data-action="first_attach_id"]').val('')
            $(this).parent().remove();
        })
        $(document).on('click', '[data-action="delete_slide_second_img"]', function () {
            $(this).closest('[data-action="slider_tab"]').find('[data-action="second_attach_id"]').val('')
            $(this).parent().remove();
        })

    });
</script>
<style>
    .mw_slider_item {
        margin: 2px;
        border: 1px solid black;
    }
</style>
<div id="page-wrapper" class="slider55">
    <div class="row mw-slider5">
        <div class="col-md-12 mw-slider4">
            <div class="form-inline mw-slider3">
                <div class="form-group mw-slider2">
				
				<h4 style="    font-size: 16px;"><?= CDictionary::GetKey('slider_title'); ?></h4>
				
                    <select name="" id="" class="form-control mw-slider-select" data-action="m_group_select">
                        <option value=""><?= CDictionary::GetKey('browse'); ?></option>

                        <?php
                        $sliders = CModule::LoadModule('slider');
                        $sliders_list = $sliders->GetSlidersNames();
                        foreach ($sliders_list as $item) {
                            ?>
                            <option value="<?= $item['slider_id'] ?>"><?= $item['slider_name'] ?></option>

                        <?php } ?>
                    </select>
                    <input type="text" style="    margin-left: 16px;" data-action="add_menu_name" class="form-control" placeholder="<?= CDictionary::GetKey('name'); ?>">
                    <button data-action="add_menu" class="btn btn-success"><?= CDictionary::GetKey('add') ?></button>

                </div>

                <button type="button" class="btn btn-success" id="save_slider"
                        form="slider_form"><?= CDictionary::GetKey('save') ?></button>
            </div>

        </div>
    </div>

    <div class="row mw-slider6" id="slider_container">

        <div class="col-md-12 mw-slider7">
            <div class="slider_all_desc">
                <h4> <?= CDictionary::GetKey('slider_desc_big') ?></h4>
                <p> <?= CDictionary::GetKey('slider_desc_small') ?></p>
            </div>
            <button class="btn btn-default add-slider-item" id="add_slider_item">
                <?= CDictionary::GetKey('add_slider_item'); ?>
            </button>
            <form action="?module=slider&submenu=action" method="post" id="slider_form">
                <input type="hidden" name="action" value="add_slider_elements">
                <input type="hidden" name="slider_id" value="1">

                <section id="slider_items_container">


                </section>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none;">
    <div class="slider_first_image_template" id="slider_first_image_template">
        <img class="menu_item_img" src="" alt="">
        <i class="fa fa-times gal-img-delete delete_slide_first_img" data-action="delete_slide_first_img"></i>
    </div>
    <div class="slider_second_image_template" id="slider_second_image_template">
        <img class="menu_item_img" src="" alt="">
        <i class="fa fa-times gal-img-delete delete_slide_second_img" data-action="delete_slide_second_img"></i>
    </div>
    <div id="slider_item_template" class="mw_slider_item" data-action="slider_item">
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
                            <a href="#<?= $lang['key'] ?>_tabnav" aria-controls="home" role="tab"
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
                             id="<?= $lang['key'] ?>_tabnav">
                            <button style="position: absolute;  top: -66px; left: 314px;" type="button" class="btn btn-default" data-action="add_slider_second_img"
                                    data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('slider_image1') ?>
                            </button>
							<div class="slider_second_img_container1">
                            <div data-action="slider_second_img_container">

                            </div>
                            <input type="text" name="lang[<?= $lang['key'] ?>][url][]" class="form-control mw-slider10"
                                   placeholder="URL">
                            <input type="hidden" name="lang[<?= $lang['key'] ?>][second_attach_id][]"
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
                <input type="hidden" data-action="first_attach_id" name="main[first_attach_id][]">
                <input type="hidden" data-action="is_active_item" name="main[is_active][]" value="1">

                <div data-action="slider_first_img_container">

                </div>
            </div>
        </div>
    </div>
    <div/>