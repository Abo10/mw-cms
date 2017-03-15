<?php
$page_prop = new CPageProp();
$res = $page_prop->GetCoreProps();
$action = CDictionary::GetKey('edit');
//var_dump($res);die;
?>
<div id="page-wrapper">

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('options') ?>
                    <small><?= $action ?></small>
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
                <li class="pull-right">
                    <button class="btn btn-info" data-action="update-timestamp">
                        <?php if (CSitemap::GetLastUpdate()) { ?>
                            <?= CDictionary::GetKey('update_sitemap') ?>
                            (<?= CDictionary::GetKey('last_update') ?>: <?= date('Y:m:d H:i:s', CSitemap::GetLastUpdate()) ?>)
                        <?php } else { ?>
                            <?= CDictionary::GetKey('generate_sitemap') ?>
                        <?php } ?>
                    </button>
                </li>
            </ul>


            <!-- Tab panes -->
            <form action="index.php?menu=options&submenu=action" method="POST">

                <input type="hidden" name="action" value="edit_options">

                <div class="tab-content">

                    <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($res['lang'])) {

                            $s_title = isset($res['lang'][$lang['key']]['s_title']) ? $res['lang'][$lang['key']]['s_title'] : null;
                            $s_descr = isset($res['lang'][$lang['key']]['s_descr']) ? $res['lang'][$lang['key']]['s_descr'] : null;
                            $s_keywords = isset($res['lang'][$lang['key']]['s_keywords']) ? $res['lang'][$lang['key']]['s_keywords'] : null;
                            $s_img = isset($res['lang'][$lang['key']]['simg']) ? $res['lang'][$lang['key']]['simg'] : null;
                            $m_img = isset($res['lang'][$lang['key']]['mimg']) ? $res['lang'][$lang['key']]['mimg'] : null;
                        } else {
                            $s_title = '';
                            $s_descr = '';
                            $s_img = '';
                            $m_img = '';
                        }
                        if (isset($res['slang'])) {

                            $fb_link = isset($res['slang']['fb_link']) ? $res['slang']['fb_link'] : null;
                            $gg_link = isset($res['slang']['gg_link']) ? $res['slang']['gg_link'] : null;
                            $ok_link = isset($res['slang']['ok_link']) ? $res['slang']['ok_link'] : null;
                            $vk_link = isset($res['slang']['vk_link']) ? $res['slang']['vk_link'] : null;
                            $tw_link = isset($res['slang']['tw_link']) ? $res['slang']['tw_link'] : null;
                            $yt_link = isset($res['slang']['yt_link']) ? $res['slang']['yt_link'] : null;

                            $contact_phone1 = isset($res['slang']['contact_phone1']) ? $res['slang']['contact_phone1'] : null;
                            $contact_phone2 = isset($res['slang']['contact_phone2']) ? $res['slang']['contact_phone2'] : null;
                            $contact_mail = isset($res['slang']['contact_mail']) ? $res['slang']['contact_mail'] : null;
                            $contact_skype = isset($res['slang']['contact_skype']) ? $res['slang']['contact_skype'] : null;
                        } else {
                            $fb_link = '';
                            $gg_link = '';
                            $ok_link = '';
                            $vk_link = '';
                            $tw_link = '';
                            $yt_link = '';

                            $contact_phone1 = '';
                            $contact_phone2 = '';
                            $contact_mail = '';
                            $contact_skype = '';
                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">


                            <div class="seo_tools1">
                                <input type="text" class="form-control" name="lang[<?= $lang['key'] ?>][s_title]"
                                       placeholder="<?= CDictionary::GetKey('title') ?>" value="<?= $s_title ?>">
                                <textarea class="form-control" name="lang[<?= $lang['key'] ?>][s_descr]" rows="5"
                                          placeholder="<?= CDictionary::GetKey('seo_desc') ?>"><?= $s_descr ?></textarea>
                                 <textarea class="form-control" name="lang[<?= $lang['key'] ?>][s_keywords]" rows="5"
                                           placeholder="<?= CDictionary::GetKey('seo_keywords') ?>"><?= $s_keywords ?></textarea>


                            </div>
                            <div class="settings55">
                                <button type="button" class="btn" data-action="gallery_button"
                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('icon') ?>
                                </button>

                                <?php if ($key != 0) {
                                    ?>
                                    <button type="button" class="btn" data-action="copy_main_image_button"
                                            data-lang="<?= $lang['key'] ?>"
                                            style="display: none"><?= CDictionary::GetKey('use_icon') ?>
                                    </button>
                                <?php } ?>
                                <div class="row" data-action=gallery_single_content data-lang="<?= $lang['key'] ?>"
                                     data-gal-single-name="lang[<?= $lang['key'] ?>][simg]">
                                    <?php if (isset($s_img)) { ?>
                                        <?php $img_src = new CAttach($s_img); ?>
                                        <div class="col-md-1 gallery_item">
                                            <span data-action="gal-delete"><i class="fa fa-times"></i></span>
                                            <img src="<?= $img_src->GetURL() ?>" alt="" class="img-responsive">
                                            <input type="hidden"
                                                   name="lang[<?= $lang['key'] ?>][simg][id]"
                                                   value="<?= $s_img ?>">

                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                            <div class="settings55">
                                <button type="button" class="btn" data-action="gallery_share_button"
                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('icon') ?>
                                </button>

                                <?php if ($key != 0) {
                                    ?>
                                    <button type="button" class="btn" data-action="copy_main_image_button"
                                            data-lang="<?= $lang['key'] ?>"
                                            style="display: none"><?= CDictionary::GetKey('use_icon') ?>
                                    </button>
                                <?php } ?>
                                <div class="row" data-action=gallery_single_share_content
                                     data-lang="<?= $lang['key'] ?>"
                                     data-gal-single-share-name="lang[<?= $lang['key'] ?>][mimg]">
                                    <?php if (isset($m_img)) { ?>
                                        <?php $img_src = new CAttach($m_img); ?>
                                        <div class="col-md-1 gallery_item">
                                            <span data-action="gal-delete"><i class="fa fa-times"></i></span>
                                            <img src="<?= $img_src->GetURL() ?>" alt="" class="img-responsive">
                                            <input type="hidden"
                                                   name="lang[<?= $lang['key'] ?>][mimg][id]"
                                                   value="<?= $m_img ?>">

                                        </div>
                                    <?php } ?>

                                </div>
                            </div>


                        </div>


                    <?php } ?>

                    <div class="siciallinks55">
                        <h3><?= CDictionary::GetKey('social_links') ?></h3>
                        <div class="social_links_bottom">
                            <input type="url" class="form-control" name="link[fb_link]" placeholder="facebook link"
                                   value="<?= $fb_link ?>"/>
                            <input type="url" class="form-control" name="link[gg_link]" placeholder="Google + link"
                                   value="<?= $gg_link ?>"/>
                            <input type="url" class="form-control" name="link[vk_link]" placeholder="Vk link"
                                   value="<?= $vk_link ?>"/>
                            <input type="url" class="form-control" name="link[ok_link]" placeholder="Ok link"
                                   value="<?= $ok_link ?>"/>
                            <input type="url" class="form-control" name="link[tw_link]" placeholder="Twitter link"
                                   value="<?= $tw_link ?>"/>
                            <input type="url" class="form-control" name="link[yt_link]" placeholder="Youtube link"
                                   value="<?= $yt_link ?>"/>
                        </div>
                    </div>
                    <div class="siciallinks55">
                        <h3><?= CDictionary::GetKey('contact_info') ?></h3>
                        <div class="social_links_bottom">
                            <input type="text" class="form-control" name="link[contact_phone1]" placeholder="<?= CDictionary::GetKey('contact_phone1') ?>"
                                   value="<?= $contact_phone1 ?>"/>
                            <input type="text" class="form-control" name="link[contact_phone2]" placeholder="<?= CDictionary::GetKey('contact_phone2') ?>"
                                   value="<?= $contact_phone2 ?>"/>
                            <input type="email" class="form-control" name="link[contact_mail]" placeholder="<?= CDictionary::GetKey('contact_mail') ?>"
                                   value="<?= $contact_mail ?>"/>
                            <input type="text" class="form-control" name="link[contact_skype]"
                                   placeholder="<?= CDictionary::GetKey('contact_skype') ?>"
                                   value="<?= $contact_skype ?>"/>

                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><?= $action ?></button>
            </form>
            <div class="site_favicon">
                <?php if ($page_prop->GetFavicon()) { ?>
                    <img src="<?= $page_prop->GetFavicon() ?>" alt="">
                <?php } ?>

            </div>
            <form action="index.php?menu=options&submenu=action" enctype="multipart/form-data"
                  method="post">
                <input type="hidden" name="action" value="add_favicon">
                <input type="file" name="favicon" accept="image/*" required>
                <button type="submit" value=""><?= CDictionary::GetKey('edit') ?></button>
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
    <div class="col-md-1 gallery_item" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
    </div>
</div>
<script>
    $('[data-action="update-timestamp"]').on('click', function () {
        $.ajax({
            url: 'index.php?menu=options&submenu=action',
            type: 'POST',
            data: {
                action: 'update_sitemap',
            },
            success: function (msg) {
                $('[data-action="update-timestamp"]').html(msg)
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

            }
        })
    })

    $('[data-action=gallery_share_button]').on('click', function () {
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
                    handle_share_single_share_image();
                })

            }
        })
    })

    $('[data-action=gallery_content]').sortable()
    $('[data-action=gallery_content]').disableSelection()

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


</script>