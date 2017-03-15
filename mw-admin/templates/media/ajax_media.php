<?php
if (isset($_POST['page'])) {
    $page = $_POST['page'];
} else {
    $page = 1;
}
if (isset($_POST['type'])) {
    $type = $_POST['type'];
} else {
    $type = 'all';
}
if (isset($_POST['allow_change_type'])) {
    $allow_change_type = $_POST['allow_change_type'];
} else {
    $allow_change_type = 1;
}
if (isset($_POST['search'])) {
    $search = $_POST['search'];
} else {
    $search = '';
}
if (isset($_POST['selected_limit'])) {
    if ($_POST['selected_limit'] == 1) {

        $selected_limit = 1; // 1  means limit 1

    } else {
        $selected_limit = 0; // 0  means unlimited
    }
} else {
    $selected_limit = 0; // 0  means unlimited
}
//$selected_limit = 1; // 0  means unlimited


$gallery = new CAttachGallery($type);

$gallery->SetPage($page);

$gallery->SetSearchWord($search);


$items = $gallery->GetPageRacional();

$pages = $gallery->GetPageCountRacional();

$icon_obj = CIcons::getInstance();
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <div id="media_container">

            <input type="hidden" name="type" data-name="attach_type" value="<?= $type ?>">
            <input type="hidden" name="allow_change_type" data-name="allow_change_type" value="<?= $allow_change_type ?>">
            <input type="hidden" name="page" data-name="cur_page" value="<?= $page ?>">
            <input type="hidden"  data-name="search" value="<?= $search ?>">
            <input type="hidden" name="page" data-name="selected_limit" value="<?= $selected_limit ?>">

            <div class="row">
                <div class="col-lg-5 ">
                    <div class="media-500">
                       <span class="btn btn-default btn-file">
                    <?= CDictionary::GetKey('browse') ?>
                           <input type="file" id="media_file" multiple>
                    <span id="ajax_load" style="display: none">
                        <img src="<?= ASSETS_BASE ?>/res/ajax-loader.gif" alt="">
                    </span>

                    </span>
                    <div class="media-selected"><?= CDictionary::GetKey('selected') ?></div>
                     <span id="selected_attachment">0</span>
                    </div>

                </div>

                <div class="col-lg-3 col-lg-offset-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="<?= CDictionary::GetKey('search') ?>" id="search"
                               value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                    </div>
                </div>
            </div>

            <?php if ($allow_change_type) { ?>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" data-action="media_tablist">
                    <li role="presentation" <?php if ($type == 'all') echo 'class="active"' ?>>
                        <a href="#tabnavall" aria-controls="home" role="tab" data-toggle="tab" data-value="all"><?= CDictionary::GetKey('all') ?></a>
                    </li>
                    <li role="presentation" <?php if ($type == 'images') echo 'class="active"' ?>>
                        <a href="#tabnavimage" aria-controls="home" role="tab" data-toggle="tab"
                           data-value="images"><?= CDictionary::GetKey('images') ?></a>
                    </li>
                    <li role="presentation" <?php if ($type == 'documents') echo 'class="active"' ?>>
                        <a href="#tabnavdocs" aria-controls="home" role="tab" data-toggle="tab"
                           data-value="documents"><?= CDictionary::GetKey('documents') ?></a>
                    </li>
                </ul>
            <?php } ?>
            <?php if ($pages > 1) { ?>
                <!--pagination-->
                <div class="row">
                    <div class="col-lg-6">
                        <nav id="media_pagination">
                            <ul class="pagination">
                                <!--<li><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>-->
                                <?php for ($i = 1;
                                           $i <= $pages;
                                           $i++) { ?>
                                    <li <?php if ($i == $page) echo 'class="active"' ?>><a href="#"
                                                                                           data-value="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                                <!--<li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>-->
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php } ?>
            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="tabnavall">
                    <?php if (!$items) { ?>
                        <div class="gallery_not_found_modal">
                            <?= CDictionary::GetKey('gallery_not_found') ?>
                        </div>
                    <?php } ?>
                    <?php
                    foreach ($items as $value) {
                        ?>
                        <div class="col-md-2 attachment_item" data-attach-id="<?= $value['id'] ?>"
                             data-attach-title="<?= $value['title'] ?>" data-attach-type="<?= $value['type'] ?>"
                             data-image-src="<?php if (isset($value['url_thumb'])) echo $value['url_thumb'] ?>"
                             data-image-src-original="<?php if (isset($value['url_original'])) echo $value['url_original'] ?>"
                             data-document-src="<?php if (isset($value['url'])) echo $value['url'] ?>"
                             data-document-icon-src="<?= $icon_obj->GetIcon($value['ext']) ?>">
                            <div class="gal_toolbar">
                                <input type="checkbox" data-action="attachment-checkbox"
                                       name="checked[<?= $value['id'] ?>]"
                                       style="display: none;">
                            </div>
                            <?php if ($value['type'] == 'document') { ?>
                                <img src="<?= $icon_obj->GetIcon($value['ext']) ?>"
                                     class="img-responsive"
                                     alt="">
                                <a href="<?= $value['url'] ?>"><span><?= $value['title'] ?></span></a>

                            <?php } ?>
                            <?php if ($value['type'] == 'image') { ?>
                                <img src="<?= $value['url_thumb'] ?>"
                                     class="img-responsive"
                                     alt="">
                                <span><?= $value['title'] ?></span>

                            <?php } ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-default pull-right add_attach_button_media" id="add_attachment" type="button"> <?= CDictionary::GetKey('add') ?> <?= CDictionary::GetKey('media') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>

<script>

    $('[data-action="media_tablist"] a').on('click', function () {
        var type = $(this).data('value');

        $.ajax({
            type: 'POST',
            url: 'index.php?menu=media&submenu=action',
            data: {
                action: 'show_media',
                type: type
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                update_selected()
            }

        })

    })


    $('#media_pagination a').on('click', function (e) {
        e.preventDefault();
        var type = $('[data-name=attach_type]').val();
        var allow_change_type = $('[data-name=allow_change_type]').val();
        var search = $('[data-name=search]').val();
        var selected_limit = $('[data-name=selected_limit]').val();
        var page = $(this).data('value');
        var selected_items = $('[data-name=selected_vals]').val();

        $.ajax({
            type: 'POST',
            url: 'index.php?menu=media&submenu=action',
            data: {
                action: 'show_media',
                type: type,
                allow_change_type: allow_change_type,
                page: page,
                search: search,
                selected_limit: selected_limit,

            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                update_selected()

            }

        })
    })
    $('#search_but').on('click', function (e) {
        var type = $('[data-name=attach_type]').val();
        var allow_change_type = $('[data-name=allow_change_type]').val();
        var page = $('[data-name=cur_page]').val();
        var search = $('[name=search]').val();
        var selected_items = $('[data-name=selected_vals]').val();
        $.ajax({
            type: 'POST',
            url: 'index.php?menu=media&submenu=action',
            data: {
                action: 'show_media',
                type: type,
                allow_change_type: allow_change_type,
                page: 1,
                search: search,
                selected_items: selected_items
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                update_selected()
            }

        })
    })
    $('#media_file').on('change', function () {
        var formdata = new FormData();
        var files = $('#media_file')[0].files;
        $(files).each(function (index, value) {
            formdata.append('file' + index, value);
        })
        formdata.append('action', 'file_upload');
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            data: formdata,
            processData: false,
            contentType: false,
            type: 'POST',
            beforeSend: function () {
                $('#ajax_load').show();
            },
            success: function (data) {
                console.log($.parseJSON(data))
                var ret = $.parseJSON(data)

                var selected_items = $('[data-name=selected_limit]').val();

                var type = $('[data-name=attach_type]').val();
                var page = $('[data-name=cur_page]').val();
                var search = $('[name=search]').val();
                $.ajax({
                    type: 'POST',
                    url: 'index.php?menu=media&submenu=action',
                    data: {
                        action: 'show_media',
                        type: type,
                        page: 1,
                        search: search,
                        selected_items: selected_items
                    },
                    success: function (msg) {
                        $('#img_modal .modal-body').html(msg);

                        $.each(ret, function (index, value) {
                            $('[data-attach-id=' + value + ']').trigger('click')
                        })
                        if (selected_items == 1) {
                            $('#add_attachment').trigger('click');
                            return
                        }
                        update_selected()
                    }

                })
            }
        });
    })

    $('#media_container .attachment_item').on('click', function () {
        var attach_id = $(this).closest('[data-attach-id]').data('attach-id');
        var attach_type = $(this).closest('[data-attach-type]').data('attach-type');
        var attach_title = $(this).closest('[data-attach-title]').data('attach-title');
        var attach_img_src = $(this).closest('[data-image-src]').data('image-src');
        var attach_img_src_original = $(this).closest('[data-image-src-original]').data('image-src-original');
        var attach_doc_src = $(this).closest('[data-document-src]').data('document-src');
        var attach_doc_icon_src = $(this).closest('[data-document-icon-src]').data('document-icon-src');

        var attach_item = {
            attach_id: attach_id,
            attach_type: attach_type,
            attach_title: attach_title,
            attach_img_src: attach_img_src,
            attach_img_src_original: attach_img_src_original,
            attach_doc_src: attach_doc_src,
            attach_doc_icon_src: attach_doc_icon_src
        }

        checkBoxes = $(this).find("[data-action=attachment-checkbox]");
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        if (checkBoxes.prop('checked')) {
            page_prop.selected_items.push(attach_item)
        } else {
            var att_index;
            $.each(page_prop.selected_items, function (index, value) {
                if (attach_id == value.attach_id) {
                    att_index = index;
                    return;
                }
            })
            if (att_index >= 0) {
                page_prop.selected_items.splice(att_index, 1);
            }
        }
        var selected_items = $('[data-name=selected_limit]').val();
        if (selected_items == 1) {
            $('#add_attachment').trigger('click');
            return
        }
        update_selected()

    })

    function update_selected() {
        var selected = page_prop.selected_items;
        $('[data-attach-id]').find('img').removeClass('bordered')
        $.each(selected, function (index, value) {
            $('[data-attach-id=' + value.attach_id + ']').find('img').addClass('bordered')
            $('[data-attach-id=' + value.attach_id + ']').find('input[type=checkbox]').prop('checked', true);

            selected_count = page_prop.selected_items.length;
            $('#selected_attachment').html(selected_count);

        })
    }

    $('#img_modal').on('hidden.bs.modal', function () {
        page_prop.selected_items = []
    })
    $('#add_attachment').on('click', function () {
        page_prop.submited_items = page_prop.selected_items;
//        console.log(page_prop.submited_items)
        $('#img_modal').modal('hide')
    })


</script>

