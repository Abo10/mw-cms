<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;
if (is_numeric($edit_id)) {
    $page = new CPage();
    $res = $page->GetAsArrayPID($edit_id);
    //var_dump($res);die;
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
                    <?= CDictionary::GetKey('page') ?>
                    <small><?= $action ?></small>
                    <div class="page-submit">
                        <button form="page-form" type="submit" class="btn btn-success"><?= $action ?></button>
                    </div>
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
        <div class="">

            <button class="btn" id="media_button"> <?= CDictionary::GetKey('media') ?> </button>

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

            <!-- Tab panes -->
            <form action="index.php?menu=page&submenu=action" method="POST" id="page-form">

                <?php if (isset($edit_id)) { ?>
                    <input type="hidden" value="<?= $page_prop_counter ?>" id="counter_start">
                    <input type="hidden" name="action" value="edit_page">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                    <input type="hidden" data-action="page_prop_start_counter"
                           value="<?= $page->GetMaxID_Gallery(); ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_page">
                <?php } ?>

                <div class="tab-content">
                    <?php $page_prop_counter = 0; ?>
                    <?php foreach ($user_langs as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($res) && isset($res[$lang['key']])) {

                            $input = $res[$lang['key']]['page_title'];
                            $page_slug = $res[$lang['key']]['page_slug'];
                            $content = $res[$lang['key']]['page_content'];

                            $seo_title = $res[$lang['key']]['seo_title'];
                            $seo_descr = $res[$lang['key']]['seo_descr'];
                            $seo_keywords = $res[$lang['key']]['seo_keywords'];

                            $gallery = $res[$lang['key']]['page_gallery'];
                            $gallery = json_decode($gallery, true);

                            if ($gallery) {
                                $page_prop_counter += max(array_keys($gallery));;
                            }

                        } else {

                            $input = '';
                            $content = '';

                            $seo_title = '';
                            $seo_descr = '';
                            $seo_keywords = '';
                        }
                        ?>
                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row">
                                <div class="col-md-9 ">
                                    <div class="add_page_add">
                                        <input type="text" class="form-control" name="lang[<?= $lang['key'] ?>][title]"
                                               placeholder="<?= CDictionary::GetKey('title') ?>"
                                               value="<?= $input ?>" <?php if ($key == 0) echo 'data-required ' ?>
                                               data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">

                                    <textarea name="lang[<?= $lang['key'] ?>][content]" id="editor_<?= $lang['key'] ?>"
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
                                                'insertdatetime media table contextmenu paste code textcolor'
                                            ],
                                            toolbar: 'insertfile undo redo | styleselect | bold italic |  bullist numlist outdent indent | link image | forecolor backcolor | sizeselect | bold italic | fontselect |  fontsizeselect',

                                        });
                                    </script>
                                    <?php if (!empty($edit_id)) { ?>

                                        <div class="edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?menu=page&submenu=action" data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span
                                                class="edit_slug_label"><?= CUrlManager::GetStaticURL('page', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input"
                                                       value="<?= $page_slug ?>"
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
                                    <hr>

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
                                    <textarea class="form-control" name="lang[<?= $lang['key'] ?>][seo_desc]" rows="5"
                                              placeholder="<?= CDictionary::GetKey('seo_desc') ?>"><?= $seo_descr ?></textarea>

                                                <input type="text" class="form-control"
                                                       name="lang[<?= $lang['key'] ?>][seo_keywords]"
                                                       placeholder="<?= CDictionary::GetKey('seo_keywords') ?>"
                                                       value="<?= $seo_keywords ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">

                                    <div class="form-group mw_product_gallery">
                                        <div
                                            class="mw_gallery_header_title"><?= CDictionary::GetKey('gallery_title') ?>
                                        </div>
                                        <button type="button" class="btn" data-action="gallery_button"
                                                data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('gallery') ?>
                                        </button>
                                        <?php if ($key != 0) {
                                            ?>
                                            <button type="button" class="btn" data-action="copy_gallery_button"
                                                    data-lang="<?= $lang['key'] ?>"
                                                    style="display: none"><?= CDictionary::GetKey('use_gallery') ?>
                                            </button>
                                        <?php } ?>
                                        <div class="row" data-action="gallery_content" data-lang="<?= $lang['key'] ?>"
                                             data-gal-name="lang[<?= $lang['key'] ?>][gallery]">
                                            <?php if (isset($gallery)) { ?>
                                                <?php foreach ($gallery as $key => $item) { ?>
                                                    <?php $img_src = new CAttach($item['id']); ?>
                                                    <div class="col-md-1 gallery_item">
                                                <span data-action="gal-delete"><i
                                                        class="fa fa-times gal-img-delete"></i></span>
                                                        <img src="<?= $img_src->GetURL() ?>" alt=""
                                                             class="img-responsive">
                                                        <input type="hidden"
                                                               name="lang[<?= $lang['key'] ?>][gallery][<?= $key; ?>][id]"
                                                               value="<?= $item['id'] ?>">
                                                        <input type="text" class="form-control"
                                                               name="lang[<?= $lang['key'] ?>][gallery][<?= $key; ?>][title]"
                                                               value="<?= $item['title'] ?>">
                                                    </div>
                                                <?php }
                                            } ?>

                                        </div>
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

    $('[data-action=gallery_button]').on('click', function () {
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

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_galery_multiple_images();
                })

            }
        })
    })

    $('[data-action=gallery_content]').sortable()
    $('[data-action=gallery_content]').disableSelection()

    $(document).on('click', '[data-action=gal-delete]', function () {
        $(this).closest('.gallery_item').remove()
    })


    $('[data-action=copy_gallery_button]').on('click', function () {
        var items = $('[role=tabpanel]').first().find('.gallery_item').clone()
        var content = $(this);
        var active_tab = $('.tab-content').find('.active');
        var name = $(active_tab).find('[data-gal-name]').data('gal-name');

        $.each(items, function (index, value) {

            var img_src = $(value).find('img').attr('src');

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
    $('#page-form').on('submit', function (e) {
        if (!$('[data-required]').val()) {
            e.preventDefault();
            alert($('[data-required]').data('msg'))
            $('.nav-tabs a:first').tab('show');
            $('[data-required]').focus();
        }
    })


</script>