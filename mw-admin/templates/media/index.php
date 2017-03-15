<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= $menu ?>
                    <small></small>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Galery
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-info-circle"></i> <strong>Like SB Admin?</strong> Try out <a
                        href="http://startbootstrap.com/template-overviews/sb-admin-2" class="alert-link">SB Admin
                        2</a> for additional features!
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div>
            <div class="row">
                <div class="col-lg-1 ">
                       <span class="btn btn-default btn-file">
                    Browse <input type="file" id="file" multiple>
                    <span id="ajax_load" style="display: none">
                        <img src="<?= URL_BASE ?>web/res/ajax-loader.gif" alt="">
                    </span>
                </span>
                </div>
                <div class="col-lg-1 ">
                    selected files <span id="selected_attachment">0</span>
                </div>

                <div class="col-lg-3 col-lg-offset-7">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" id="search">
                    <span class="input-group-addon">
                            <span class="fa fa-search" id="search_but"></span>
                    </span>
                    </div>
                </div>
            </div>


            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tabnavall" aria-controls="home" role="tab" data-toggle="tab">All</a>
                </li>
                <li role="presentation">
                    <a href="#tabnavimage" aria-controls="home" role="tab" data-toggle="tab">Image</a>
                </li>
                <li role="presentation" class="">
                    <a href="#tabnavdocs" aria-controls="home" role="tab" data-toggle="tab">Docs</a>
                </li>
                <li role="presentation" class="">
                    <a href="#_tabnav" aria-controls="home" role="tab" data-toggle="tab">Video</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="tabnavall">
                    <?php
                    $gallery = new CAttachGallery();
                    $items = $gallery->GetGallery();
                    $icon_obj = CIcons::getInstance();
                    foreach ($items as $value) {
                        ?>
                        <div class="col-md-2 gal_img_item" data-attach-id="<?= $value['id'] ?> "
                             data-attach-title="<?= $value['title'] ?>" data-attach-type="<?= $value['type'] ?>">
                            <div class="gal_toolbar">
                                <span class="add_img_details"><i
                                        class="fa fa-pencil-square-o"></i></span>
                                <span class="delete_attachment"><i
                                        class="fa fa-times"></i></span>
                                <input type="checkbox" data-action="attachment-checkbox">
                            </div>
                            <?php if ($value['type'] == 'document') { ?>
                                <img src="<?= $icon_obj->GetIcon($value['ext']) ?>" class="img-responsive" alt="">
                                <a href="<?= $value['url'] ?>"><span><?= $value['title'] ?></span></a>

                            <?php } ?>
                            <?php if ($value['type'] == 'image') { ?>
                                <img src="<?= $value['url_thumb'] ?>" class="img-responsive" alt="">
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
<!-- /#page-wrapper -->

<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Attachment attributes</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Title</label>
                        <input type="text" class="form-control" id="img-title">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Description</label>
                        <input type="text" class="form-control" id="img-desc">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="update_image">Edit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#file').on('change', function () {
            var formdata = new FormData();
            var files = $('#file')[0].files;
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
//                    alert(data);
//                    console.log(data)
                    location.reload();
                }
            });
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
        $('.delete_attachment').on('click', function () {
            if (!confirm('Are You Sure? '))
                return
            var gal_id = $(this).closest("[data-attach-id]").data('attach-id')
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
        $('#search').on('keyup', function () {
            var search_query = $(this).val();
            if (search_query) {
                console.log(1)
                $('[data-attach-title]').hide()
                $('[data-attach-title*="' + search_query + '"]').show()
            } else {
                $('[data-attach-title]').show()
            }
        })
        $('[role=tablist] a').on('click', function () {
            if ($(this).attr('href') == '#tabnavall') {
                $('[data-attach-type]').show()
            }
            if ($(this).attr('href') == '#tabnavimage') {
                $('[data-attach-type]').hide()
                $('[data-attach-type=image]').show()
            }
            if ($(this).attr('href') == '#tabnavdocs') {
                $('[data-attach-type]').hide()
                $('[data-attach-type=document]').show()
            }
        })
        $("[data-action=attachment-checkbox]").on('change', function () {
            var selected_count = $("[data-action=attachment-checkbox]:checked").length;
            $('#selected_attachment').html(selected_count);
            console.log(selected_count);
        })

    })

</script>