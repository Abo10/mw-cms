<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
if (is_numeric($edit_id)) {
    $post = new CPost();
    $res = $post->GetAsArrayPID($edit_id);
//    var_dump($res);
//    die;
    $action = CDictionary::GetKey('edit');
} else {
    $action = CDictionary::GetKey('add');
}
?>
<?php
function drowDom($item, $params)
{ ?>
    <?php //var_dump($params);
    ?>
    <div class="">
        <label class="checkbox-inline mycheckbox"">
        <input type="checkbox" value="<?= $item['cid'] ?>"
               data-action="parent-checkbox"
               data-parent-cat-value="<?= $item['cid'] ?>"
               name="lang[<?= $params['lang']['key'] ?>][post_category][]" <?php if (is_array($params['categories']) && in_array($item['cid'], $params['categories'])) echo 'checked'; ?> >
        <span class="checkbox_span"></span>

        <?= $item['category_title'] ?>
        </label>
    </div>
<?php }

?>


<div id="page-wrapper" style="    background: #FDFDFD;">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('post') ?>
                    <small><?= $action ?>  <?= CDictionary::GetKey('post') ?></small>
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
            <button class="btn" id="media_button"> <?= CDictionary::GetKey('media') ?></button>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs post_leguig" role="tablist">
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
            <form action="index.php?menu=post&submenu=action" method="POST" id="post_form">
                <?php if (!empty($edit_id)) { ?>
                    <input type="hidden" name="action" value="edit_post">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_post">
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

                            $post_uid = $res[$lang['key']]['post_id'];
                            $post_title = $res[$lang['key']]['post_title'];
                            $post_slug = $res[$lang['key']]['post_slug'];
                            $post_content = $res[$lang['key']]['post_content'];
                            $post_descr = $res[$lang['key']]['post_descr'];
                            $post_slug = $res[$lang['key']]['post_slug'];

                            $post_date = date('Y/m/d H:i', $res[$lang['key']]['post_s_date']);

                            $categories = $res['category'];
                            $post_status = $res[$lang['key']]['post_status'];

                            if (isset($res['post_attributes'][$lang['key']])) {
                                $attributes = $res['post_attributes'][$lang['key']];
                            } else {
                                $attributes = [];
                            }

                            $post_tags = $res['post_tags'];
                            $map = $res['map'];

                            $post_img = $res[$lang['key']]['post_img'];
                            $post_img_title = $res[$lang['key']]['post_img_title'];

                            $post_files = $res[$lang['key']]['post_files'];
                            $post_covers = $res[$lang['key']]['post_covers'];

                            $post_gallery = $res[$lang['key']]['post_gallery'];

                            if (!empty($post_gallery)) {
                                $page_prop_counter += max(array_keys($post_gallery));
                            }

                            if (!empty($post_files)) {
                                $page_prop_counter += max(array_keys($post_files));
                            }
                            if (!empty($post_covers)) {
                                $page_prop_counter += max(array_keys($post_covers));
                            }


                            $seo_title = $res[$lang['key']]['seo_title'];
                            $seo_descr = $res[$lang['key']]['seo_descr'];
                            $seo_keywords = $res[$lang['key']]['seo_keywords'];


//                            $cover_gallery = json_decode($cover_gallery, true);

//                            $gallery = json_decode($gallery, true);
                        } else {

                            $post_title = '';
                            $post_slug = '';
                            $post_content = '';
                            $post_descr = '';

                            $post_date = '';

                            $categories = [];
                            $post_status = null;

                            $attributes = [];

                            $post_tags = [];

                            $page_prop_counter = 0;

                            $post_img = '';
                            $post_img_title = '';

                            $post_img = '';
                            $post_img = '';


                            $seo_title = '';
                            $seo_descr = '';
                            $seo_keywords = '';
                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="add_page_add">

                                        <input type="hidden" name="lang[<?= $lang['key'] ?>][post_slug]"
                                               value="<?= $post_slug ?>">
                                        <input type="text" class="form-control"
                                               name="lang[<?= $lang['key'] ?>][post_title]"
                                               placeholder="<?= CDictionary::GetKey('title') ?>"
                                               value="<?= $post_title ?>" <?php if ($key == 0) echo 'data-required ' ?>
                                               data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">

                                    <textarea name="lang[<?= $lang['key'] ?>][post_content]"
                                              id="editor_<?= $lang['key'] ?>"
                                              rows="10"
                                              cols="80">
                                        <?= $post_content ?>
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
                                    <hr>
                                    <div class="form-group">
                                        <textarea class="form-control" name="lang[<?= $lang['key'] ?>][post_descr]"
                                                  rows="5"
                                                  placeholder="<?= CDictionary::GetKey('short_desc') ?>"><?= $post_descr ?></textarea>
                                    </div>

                                    <?php if (!empty($edit_id)) { ?>
                                        <div class="edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?menu=post&submenu=action" data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span
                                                class="edit_slug_label"><?= CUrlManager::GetStaticURL('home', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input"
                                                       value="<?= $post_slug ?>"
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
                                        <div class="pr-tags2">

                                            <div class="form-group product_tags">
                                                <label for="">
                                                    <?= CDictionary::GetKey('tag') ?>
                                                    <i class="fa fa-info" rel="tooltip" title="Key active"
                                                       id="blah"></i>
                                                </label>
                                                <?php
                                                $tags_obj = new CTagsList();
                                                $tags = $tags_obj->GetAsArray();
                                                ?>
                                                <select data-placeholder="<?= CDictionary::GetKey('select') ?>"
                                                        name="lang[<?= $lang['key'] ?>][tag_list][]"
                                                        class="form-control tag_select_class"
                                                        data-action="tag_select" multiple>
                                                    <?php foreach ($tags as $tag) { ?>
                                                        <option
                                                            value="<?= $tag[$lang['key']]['pid'] ?>" <?php if (in_array($tag[$lang['key']]['pid'], $post_tags)) echo 'selected'; ?>>
                                                            <?= $tag[$lang['key']]['tag_name'] ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-3 post_right">
                                    <div class="form-group">
                                        <label for="">
                                            <?= CDictionary::GetKey('date') ?>
                                            <i class="fa fa-info" rel="tooltip" title="Key active" id="blah"></i>
                                        </label>
                                        <input class="form-control" name="lang[<?= $lang['key'] ?>][post_date]"
                                               type="text"
                                               id="datepicker" data-action="date_input"
                                               placeholder="  <?= CDictionary::GetKey('date_show') ?>"
                                               value="<?= $post_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="1" data-action="status-checkbox"
                                                   name="lang[<?= $lang['key'] ?>][post_status]"
                                                <?php if ($post_status) echo 'checked'; ?>
                                            ><?= CDictionary::GetKey('publicate') ?></label>
                                    </div>

                                    <div class="form-group post_cat">
                                        <label for="">
                                            <?= CDictionary::GetKey('cat') ?>
                                            <i class="fa fa-info" rel="tooltip" title="Key active" id="blah"></i>
                                        </label>

                                        <div>
                                            <div class="mw-tree">
                                                <?php $a = new CCategoryPost(); ?>
                                                <?php
                                                $a->GetCatTree('drowDom', ['categories' => $categories, 'key' => $key, 'lang' => $lang]);
                                                ?>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="pr_media">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('post-right-label') ?>
                                            <span data-action="pr-ac-toggle2"></span></div>
                                        <div class="pr_media2">

                                            <div class="form-group product_main_image">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('main_image_title') ?>
                                                </div>
                                                <button type="button" class="btn btn-default"
                                                        data-action="main_image_button"
                                                        data-lang="<?= $lang['key'] ?>"><?= CDictionary::GetKey('main') ?> <?= CDictionary::GetKey('image') ?>
                                                </button>
                                                <?php if ($key != 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-default"
                                                            data-action="copy_main_image_button"
                                                            data-lang="<?= $lang['key'] ?>"
                                                            style="display: none"><?= CDictionary::GetKey('use') ?> <?= CDictionary::GetKey('main') ?> <?= CDictionary::GetKey('image') ?>
                                                    </button>
                                                <?php } ?>
                                                <div class="" data-action="gallery_single_content"
                                                     data-lang="<?= $lang['key'] ?>"
                                                     data-gal-single-name="lang[<?= $lang['key'] ?>][post_img]">
                                                    <?php if (isset($post_img) && !empty($post_img)) { ?>
                                                        <?php $img_src = new CAttach($post_img); ?>
                                                        <div class="gallery_item gallery_item_product_single">
                                                            <span data-action="gal-delete"><i
                                                                    class="fa fa-times gal-img-delete"></i></span>
                                                            <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                 class="img-responsive">
                                                            <input type="hidden"
                                                                   name="lang[<?= $lang['key'] ?>][post_img][id]"
                                                                   value="<?= $post_img ?>">
                                                            <input type="text" class="form-control"
                                                                   name="lang[<?= $lang['key'] ?>][post_img][title]"
                                                                   value="<?= $post_img_title ?>">
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
                                                     data-gallery-name="lang[<?= $lang['key'] ?>][post_gallery]">
                                                    <?php if (isset($post_gallery) && !empty($post_gallery)) { ?>
                                                        <?php foreach ($post_gallery as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times gal-img-delete"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][post_gallery][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][post_gallery][<?= $key2; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

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
                                                     data-cover-name="lang[<?= $lang['key'] ?>][post_covers]">
                                                    <?php if (isset($post_covers) && !empty($post_covers)) { ?>
                                                        <?php foreach ($post_covers as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times gal-img-delete"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][post_covers][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][post_covers][<?= $key2; ?>][title]"
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
                                                     data-gal-name="lang[<?= $lang['key'] ?>][post_files]">
                                                    <?php if (isset($post_files) && !empty($post_files)) { ?>
                                                        <?php $icon_obj = CIcons::getInstance(); ?>
                                                        <?php foreach ($post_files as $key22 => $item) { ?>
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
                                                                       name="lang[<?= $lang['key'] ?>][post_files][<?= $key22; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control post_title"
                                                                       name="lang[<?= $lang['key'] ?>][post_files][<?= $key22; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php CModule::LoadTemplate('map', 'button'); ?>
                                    <div class="post-attr-container">
                                        <div class="post-attr-label"><?= CDictionary::GetKey('post-attr-label') ?></div>
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
                                    <button type="submit" id="post_add_button"
                                            class="btn btn-success pull-right"><?= $action ?></button>

                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <input type="hidden" value="<?= $page_prop_counter ?>" id="counter_start">

                    <?php CModule::LoadTemplate('map', 'map_modal'); ?>

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
    <div class=" gallery_item gallery_item_product_single" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="map_icon" id="map_single_image_template">
        <span data-action="map-icon-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="map-elem-item" id="map-elem-template">
        <input type="hidden" data-action="lat" name="lang[map][lat][]">
        <input type="hidden" data-action="lng" name="lang[map][lng][]">
        <?php foreach ($user_langs as $key => $lang) {
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
    page_prop.counter = parseInt($('#counter_start').val());
    page_prop.map_status = false;
    var markers = [];
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: {lat: 40.172730, lng: 44.515971}
        });


        //marker.addListener('click', toggleBounce);
        //marker.addListener('drag', c_log);
    }
    $(function () {
        $('.mw-tree input[type=checkbox]:checked').each(function (i, v) {
            $(v).closest('ul').show();
        })
    })
    $('[data-action=show_seo]').on('click', function () {
        $('.seo_tools').toggle()
    })
    $('#map_modal').on('shown.bs.modal', function () {

        console.log(google);
        if (!page_prop.map_status) {
            initMap();
            page_prop.map_status = true;
            if ($('#map_modal .map-tab-content').children().length > 0) {
                $('#map_modal .map-elem-item').each(function (i, v) {

                    var lat = $(v).find('[data-action="lat"]').val();
                    var lng = $(v).find('[data-action="lng"]').val();
                    console.log(lat);
                    console.log(lat);
                    var unique_id = $(v).data('u-id');
                    var marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        position: {lat: parseFloat(lat), lng: parseFloat(lng)}
                    });
                    marker.addListener('drag', function (e) {
                        var lat = marker.getPosition().lat()
                        var lng = marker.getPosition().lng()
                        var unique_id = marker.u_attr;
                        $("[data-u-id=" + unique_id + "]").find("[data-action=lat]").val(lat);
                        $("[data-u-id=" + unique_id + "]").find("[data-action=lng]").val(lng);
                        console.log(marker.u_attr);
                    });
                    marker.addListener('click', function (e) {
                        $("[data-u-id=" + unique_id + "]").find("button").focus();
                    });

                    marker.u_attr = unique_id;
                    markers.push(marker);
                })
            }
            console.log(markers)
        }
    })
    $('[data-action=show_map]').on('click', function () {
        //$('.map_tools').toggle()
        $('#map_modal').modal('show')

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


    $('[data-action=tag_select]').on('change', function (e) {
        e.preventDefault();

        var val = $(this).val();
        $('[data-action=tag_select]').val(val)
        $('.tag_select_class').trigger("chosen:updated")
    })

    //    $('[data-action=status-checkbox]').on('change', function () {
    //        var is_check = $(this).prop('checked');
    //        $('[data-action=status-checkbox]').prop('checked', is_check);
    //    })

    $('[data-parent-cat-value]').on('change', function () {
        var is_check = $(this).prop('checked');
        var value = $(this).data('parent-cat-value');
        $('[data-parent-cat-value=' + value + ']').prop('checked', is_check);
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
    $('[data-action=map_image_button]').on('click', function () {
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
                    handle_map_single_image();
                })

                return

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
    $('.tag_select_class').chosen({
        width: '100%'
    })

    $('#post_form').on('submit', function (e) {
        if (!$('[data-required]').val()) {
            e.preventDefault();
            alert($('[data-required]').data('msg'))
            $('.nav-tabs a:first').tab('show');
            $('[data-required]').focus();
        }
    })

    $('[data-action=add_mark]').on('click', function () {

        var unique_id = guid();

        var template = $("#map-elem-template").clone();
        console.log(template);
//
        $(template).removeAttr('id');
        $(template).attr('data-u-id', unique_id);


        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: {lat: 40.172730, lng: 44.515971}
        });

        marker.u_attr = unique_id;
        markers.push(marker);

        $(template).find("[data-action=lat]").val(marker.getPosition().lat());
        $(template).find("[data-action=lng]").val(marker.getPosition().lng());

        $('#map_modal .map-tab-content').append(template)

        marker.addListener('drag', function (e) {
            var lat = marker.getPosition().lat()
            var lng = marker.getPosition().lng()
            var unique_id = marker.u_attr;
            $("[data-u-id=" + unique_id + "]").find("[data-action=lat]").val(lat);
            $("[data-u-id=" + unique_id + "]").find("[data-action=lng]").val(lng);
            console.log(marker.u_attr);
        });
        marker.addListener('click', function (e) {
            $("[data-u-id=" + unique_id + "]").find("button").focus();
        });


        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
        function c_log() {
            //var in_input = $(active_tab).find('.map_tools [data-action="map_input_container"]').children().last();
            //$('.map_tools [data-action="map_input_container"]').lastChild().val(marker.getPosition().lat())
            //$(in_input).val(marker.getPosition().lat())
            console.log();

        }
    })
</script>
<script>


    function toggleBounce() {
//        console.log(marker.getPosition().lat());
//        if (marker.getAnimation() !== null) {
//            marker.setAnimation(null);
//        } else {
//            marker.setAnimation(google.maps.Animation.BOUNCE);
//        }
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqH7TXy83BKtYIKRaUTcMH6EGwGtL_Mf0"
        type="text/javascript"></script>
<style>
    .map {
        height: 350px;
    }
</style>