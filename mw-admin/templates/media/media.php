<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = 'all';
}
if (isset($_GET['allow_change_type'])) {
    $allow_change_type = $_GET['allow_change_type'];
} else {
    $allow_change_type = 1;
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = '';
}
if (isset($_GET['selected_limit'])) {
    if ($_GET['selected_limit'] == 1) {

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
        <div id="media_container" class="media-container-1">

            <input type="hidden" name="type" data-name="attach_type" value="<?= $type ?>">
            <input type="hidden" name="allow_change_type" data-name="allow_change_type"
                   value="<?= $allow_change_type ?>">
            <input type="hidden" name="page" data-name="cur_page" value="<?= $page ?>">
            <input type="hidden" data-name="search" value="<?= $search ?>">
            <input type="hidden" name="page" data-name="selected_limit" value="<?= $selected_limit ?>">

            <div class="row">
                <div class="col-lg-5 ">
                    <div class="media-500">
                       <span class="btn btn-default btn-file">
                    <?= CDictionary::GetKey('browse') ?>
                           <input type="file" id="media_file" multiple>
                    <span id="ajax_load" style="display: none">
                        <img src="<?= URL_BASE ?>web/res/ajax-loader.gif" alt="">
                    </span>

                    </span>
                        <div class="media-selected"><?= CDictionary::GetKey('selected') ?></div>
                        <span id="selected_attachment">0</span>
                    </div>

                </div>

                <div class="col-lg-3 col-lg-offset-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search"
                               placeholder="<?= CDictionary::GetKey('search') ?>" id="search"
                               value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                    </div>
                </div>
            </div>

            <?php if ($allow_change_type) { ?>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs98" data-action="media_tablist">
                    <li role="presentation" <?php if ($type == 'all') echo 'class="active"' ?>>
                        <a href="#tabnavall" aria-controls="home" role="tab" data-toggle="tab"
                           data-value="all"><?= CDictionary::GetKey('all') ?></a>
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
                <div class="row row_menia88">
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
            <div class="tab-content tab-content55">

                <div role="tabpanel" class="tab-pane active media-tabs-2" id="tabnavall">
                    <?php if (!$items) { ?>
                        <div class="gallery_not_found">
                            <?= CDictionary::GetKey('gallery_not_found') ?>
                        </div>
                    <?php } ?>
                    <?php
                    foreach ($items as $value) {
                        ?>
                        <div class="col-md-2 media_item" data-attach-id="<?= $value['id'] ?>"
                             data-attach-title="<?= $value['title'] ?>" data-attach-type="<?= $value['type'] ?>"
                             data-image-src="<?php if (isset($value['url_thumb'])) echo $value['url_thumb'] ?>"
                             data-document-src="<?php if (isset($value['url'])) echo $value['url'] ?>"
                             data-document-icon-src="<?= $icon_obj->GetIcon($value['ext']) ?>">
                            <span class="media-delete"><span data-action="media-delete"
                                                             data-value="<?= $value['id'] ?>"><i
                                        class="fa fa-times gal-img-delete"></i></span></span>
                            <span class="media-edit"><span class="add_img_details"><i class="fa fa-pencil-square-o"></i></span></span>
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
                                <span class="media_title55"><?= $value['title'] ?></span>

                            <?php } ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"> <?= CDictionary::GetKey('edit_media') ?></h4>
            </div>
            <div class="modal-body" style="padding: 21px;">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label"><?= CDictionary::GetKey('name') ?></label>
                        <input type="text" class="form-control" id="img-title">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label"><?= CDictionary::GetKey('tag_desc') ?></label>
                        <input type="text" class="form-control" id="img-desc">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                        id="update_image"><?= CDictionary::GetKey('edit_media') ?></button>
            </div>
        </div>
    </div>
</div>
<script>

    $('[data-action="media_tablist"] a').on('click', function () {
        var type = $(this).data('value');
        var string = updateQueryStringParameter(location.href, 'type', type)
        var string = updateQueryStringParameter(string, 'search', '');
        location.replace(string)

    })


    $('#media_pagination a').on('click', function (e) {

        e.preventDefault();
        var page = $(this).data('value');

        replace_url_param('page', page);

    })
    $('#search_but').on('click', function (e) {

        var search = $('[name=search]').val();
        replace_url_param('search', search);

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
                location.reload();
            }
        });
    })

    $('[data-action="media-delete"]').on('click', function () {
        if (!confirm('Are You Sure? '))
            return
        var gal_id = $(this).data('value')
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: "POST",
            data: {
                action: 'delete_attachment',
                id: gal_id
            },
            success: function (msg) {
                location.reload()
            }
        })
    })
    $('#update_image').on('click', function () {
        var gal_id = $("#img_modal [data-id]").val()
        var title = $('#img_modal #img-title').val()
        var descr = $('#img_modal #img-desc').val()
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: "POST",
            data: {
                action: 'update_img_details',
                id: gal_id,
                title: title,
                descr: descr
            },
            success: function (msg) {
                if (msg == 1) {
                    $('#img_modal').modal('hide');
                } else {
                    alert(1)
                }
            }
        })

    })

    $('.add_img_details').on('click', function () {
        var gal_id = $(this).closest("[data-attach-id]").data('attach-id')
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: "POST",
            data: {
                action: 'get_img_details',
                id: gal_id
            },
            success: function (msg) {
                var data = $.parseJSON(msg)
                $('#img_modal [data-id]').val(gal_id)
                $('#img_modal #img-title').val(data.title)
                $('#img_modal #img-desc').val(data.descr)
            }
        })
        $('#img_modal').modal()
    })


</script>

