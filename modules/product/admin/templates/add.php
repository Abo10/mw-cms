<?php
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
if (is_numeric($edit_id)) {
    $product_obj = CModule::LoadModule('product');
    $res = $product_obj->GetDatas($edit_id);
//    var_dump($res);
//    die;
    $action = CDictionary::GetKey('edit');
} else {
    $action = CDictionary::GetKey('add');
}
function drowDom($item, $params)
{ ?>
    <div class="">
        <label class="checkbox-inline mycheckbox"">
        <input type="checkbox" value="<?= $item['cid'] ?>"
               data-action="parent-checkbox"
               data-parent-cat-value="<?= $item['cid'] ?>"
               name="<?php if ($params['key'] == 0) echo 'predefines[product_category][]'; ?>" <?php if (key_exists($item['cid'], $params['categories'])) echo 'checked'; ?> >
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
                    <?= CDictionary::GetKey('product') ?>
                    <small><?= $action ?>  <?= CDictionary::GetKey('product') ?></small>
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
            <div class="media-top-1">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs post_leguig" role="tablist">
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
                <button form="product_form" type="submit"
                        class="btn btn-success pull-right product_add_button_top"><?= $action ?></button>
            </div>
            <!-- Tab panes -->
            <form action="index.php?module=product&submenu=action" method="POST" id="product_form">
                <?php if (!empty($edit_id)) { ?>
                    <input type="hidden" name="action" value="edit_product">
                    <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                <?php } else { ?>
                    <input type="hidden" name="action" value="add_product">
                <?php } ?>

                <div class="tab-content">
                    <?php $page_prop_counter = 0; ?>
                    <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                        if ($key == 0) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }
                        if (isset($res)) {

                            $product_title = $res['langs'][$lang['key']]['product_title'];
                            $product_slug = $res['langs'][$lang['key']]['product_slug'];
                            $product_content = $res['langs'][$lang['key']]['product_descr'];
                            $product_descr = $res['langs'][$lang['key']]['product_short_descr'];

                            $product_instock = $res['langs'][$lang['key']]['product_instock'];


//                            if (isset($res['langs']['product_attributes'][$lang['key']])) {
//                                $attributes = $res['langs']['product_attributes'][$lang['key']];
//                            } else {
//                                $attributes = [];
//                            }

                            $categories = $res['predefines']['product_category'];
//                            $product_tags = $res['predefines']['product_tags'];
                            $product_brand = $res['predefines']['brand'];
                            $product_tags = $res['predefines']['tags'];

                            $discount = isset($res['predefines']['discount']) ? $res['predefines']['discount'] : [];

                            $multiprice = isset($res['multyprice']) ? $res['multyprice'] : [];
                            $attributes = isset($res['attributes']) ? $res['attributes'] : [];
                            $empty_attributes = isset($res['empty_attributes']) ? $res['empty_attributes'] : [];

                            $product_img = $res['langs'][$lang['key']]['product_image'];
                            $product_img_title = $res['langs'][$lang['key']]['product_img_title'];

                            $product_code = $res['langs'][$lang['key']]['product_code'];
                            $product_count = $res['langs'][$lang['key']]['product_count'];
                            $product_instock = $res['langs'][$lang['key']]['product_instock'];
                            $product_price = $res['langs'][$lang['key']]['product_price'];
                            $product_old_price = $res['langs'][$lang['key']]['product_old_price'];

                            $product_files = $res['langs'][$lang['key']]['product_attaches'];
                            $product_covers = $res['langs'][$lang['key']]['product_covers'];

                            $product_gallery = $res['langs'][$lang['key']]['product_gallery'];

                            if (!empty($product_gallery)) {
                                $page_prop_counter += max(array_keys($product_gallery));
                            }

                            if (!empty($product_files)) {
                                $page_prop_counter += max(array_keys($product_files));
                            }

                            if (!empty($product_covers)) {
                                $page_prop_counter += max(array_keys($product_covers));
                            }


                            $seo_title = $res['langs'][$lang['key']]['seo_title'];
                            $seo_descr = $res['langs'][$lang['key']]['seo_descr'];
                            $seo_keywords = $res['langs'][$lang['key']]['seo_keywords'];


//                            $cover_gallery = json_decode($cover_gallery, true);

//                            $gallery = json_decode($gallery, true);
                        } else {

                            $product_title = '';
                            $product_slug = '';
                            $product_content = '';
                            $product_descr = '';
                            $product_instock = '';

                            $product_date = '';
                            $discount = [];

                            $categories = [];
                            $product_brand = [];

                            $multiprice = [];
                            $attributes = [];
                            $empty_attributes = [];

//                            $attributes = [];

                            $product_tags = [];

                            $page_prop_counter = 0;

                            $product_img = '';
                            $product_img_title = '';

                            $product_code = '';
                            $product_count = '';
                            $product_instock = '';
                            $product_price = '';
                            $product_old_price = '';

                            $product_img = '';
                            $product_img = '';


                            $seo_title = '';
                            $seo_descr = '';
                            $seo_keywords = '';
                        }
                        ?>

                        <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                            <div class="row">
                                <div class="col-md-9 product_add_left">
                                    <div class="add_page_add">

                                        <input type="hidden" name="lang[<?= $lang['key'] ?>][product_slug]"
                                               value="<?= $product_slug ?>">
                                        <input type="text" class="form-control"
                                               name="lang[<?= $lang['key'] ?>][product_title]"
                                               placeholder="<?= CDictionary::GetKey('title') ?>"
                                               value="<?= $product_title ?>" <?php if ($key == 0) echo 'data-required ' ?>
                                               data-msg="<?= CDictionary::GetKey('post_title_required'); ?>">

                                    <textarea name="lang[<?= $lang['key'] ?>][product_content]"
                                              id="editor_<?= $lang['key'] ?>"
                                              rows="10"
                                              cols="80">
                                        <?= $product_content ?>
                                    </textarea>
                                    </div>
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
                                    <hr>
                                    <div class="form-group">
                                        <textarea class="form-control" name="lang[<?= $lang['key'] ?>][product_descr]"
                                                  rows="5"
                                                  placeholder="<?= CDictionary::GetKey('short_desc') ?>"><?= $product_descr ?></textarea>
                                    </div>
                                    <?php if (!empty($edit_id)) { ?>
                                        <div class="edit_slug" data-action="edit_slug_container"
                                             data-url="index.php?module=product&submenu=action"
                                             data-id="<?= $edit_id ?>"
                                             data-lang="<?= $lang['key'] ?>">
                                            <span
                                                class="edit_slug_label"><?= CUrlManager::GetStaticURL('product', null, $lang['key']) ?></span>
                                            <div class="input-group edit_slug_group">

                                                <input type="text" class="form-control slug-value-input"
                                                       value="<?= $product_slug ?>"
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
                                            <?php if (CModule::HasModule('tags')) { ?>

                                                <div class="form-group product_tags">
                                                    <label for="">
                                                        <?= CDictionary::GetKey('tag') ?>
                                                        <i class="fa fa-info" rel="tooltip" title="Key active"
                                                           id="blah"></i>
                                                    </label>
                                                    <?php
                                                    $tags_obj = CModule::LoadModule('tags');
                                                    $tags = $tags_obj->GetAsArrayList();
                                                    ?>
                                                    <select data-placeholder="<?= CDictionary::GetKey('select') ?>"
                                                        <?php if ($key == 0) { ?>
                                                            <?php echo 'name="predefines[tags][]"'; ?>

                                                        <?php } ?>
                                                            class="form-control tag_select_class"
                                                            data-action="tag_select" multiple>
                                                        <?php foreach ($tags as $tag) { ?>
                                                            <option
                                                                value="<?= $tag[$lang['key']]['pid'] ?>" <?php if (key_exists($tag[$lang['key']]['pid'], $product_tags)) echo 'selected'; ?>>
                                                                <?= $tag[$lang['key']]['tag_name'] ?>
                                                            </option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 product_right">
                                    <div class="form-group product_cat">
                                        <label for="">
                                            <?= CDictionary::GetKey('cat') ?>
                                            <i class="fa fa-info" rel="tooltip" title="Key active" id="blah"></i>
                                        </label>
                                        <div class="product_cat_bottom">

                                            <div class="col-md-12 pr_cat_cat">
                                                <div class="mw-tree">
                                                    <?php $a = CModule::LoadModule('product_category'); ?>

                                                    <?php

                                                    $a->GetCatTree('drowDom', ['categories' => $categories, 'key' => $key]);


                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group product_instock">
                                        <label class="checkbox-inline mycheckbox">
                                            <input type="checkbox" value="1" data-action="in-stock-checkbox"
                                                   name="lang[<?= $lang['key'] ?>][product_instock]" <?php if ($product_instock == 1) echo 'checked'; ?>>
                                            <span class="checkbox_span"></span>
                                            <?= CDictionary::GetKey('in_stock') ?></label>
                                    </div>
                                    <div class="product-right-group1">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('product-right-label') ?>
                                            <span data-action="pr-ac-toggle1"></span></div>
                                        <?php if (CModule::HasModule('brand')) { ?>
                                            <div class="form-group product_brand">
                                                <label for="">
                                                    <?= CDictionary::GetKey('brand') ?>
                                                    <i class="fa fa-info" rel="tooltip" title="Key active" id=""></i>
                                                </label>
                                                <?php
                                                $brand_obj = CModule::LoadModule('brand', $lang['key']);
                                                $brands = $brand_obj->GetBrands();
                                                ?>
                                                <select data-placeholder="<?= CDictionary::GetKey('select') ?>"
                                                        name="<?php if ($key == 0) echo 'predefines[brand][]'; ?>"
                                                        class="form-control "
                                                        data-action="brand-select">
                                                    <option value=""><?= CDictionary::GetKey('select') ?></option>
                                                    <?php foreach ($brands as $brand) { ?>
                                                        <option
                                                            value="<?= $brand['brand_group'] ?>" <?php if (key_exists($brand['brand_group'], $product_brand)) echo 'selected'; ?>>
                                                            <?= $brand['brand_title'] ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        <?php } ?>

                                        <div class="form-group product_price">
                                            <label for="">
                                                <?= CDictionary::GetKey('price') ?>
                                                <i class="fa fa-info" rel="tooltip" title="Key active"></i>
                                            </label>
                                            <input type="number" class="form-control"
                                                   name="lang[<?= $lang['key'] ?>][product_price]"
                                                   value="<?= (float)$product_price ?>" step="0.01"
                                                   data-action="price-input">
                                        </div>

                                        <div class="form-group product_old_price">
                                            <label for="">

                                                <?= CDictionary::GetKey('old_price') ?>

                                                <i class="fa fa-info" rel="tooltip" title="Key active"></i>
                                            </label>
                                            <input type="number" class="form-control"
                                                   name="lang[<?= $lang['key'] ?>][product_old_price]"
                                                   value="<?= (float)$product_old_price ?>" step="0.01"
                                                   data-action="old-price-input">
                                        </div>

                                        <div class="form-group product_count">
                                            <label for="">
                                                <?= CDictionary::GetKey('count') ?>
                                                <i class="fa fa-info" rel="tooltip" title="Key active" id="blah"></i>
                                            </label>
                                            <input type="number" class="form-control"
                                                   name="lang[<?= $lang['key'] ?>][product_count]"
                                                   value="<?= (float)$product_count ?>"
                                                   data-action="count-input">
                                        </div>

                                        <div class="form-group product_code">
                                            <label for="">
                                                <?= CDictionary::GetKey('product_code') ?>
                                                <i class="fa fa-info" rel="tooltip" title="Key active" id=""></i>
                                            </label>
                                            <input type="text" class="form-control"
                                                   name="lang[<?= $lang['key'] ?>][product_code]"
                                                   data-action="code-input"
                                                   value="<?= $product_code ?>"
                                                <?php if ($key == 0) echo 'required' ?>>
                                        </div>
                                    </div>


                                    <div class="pr_media">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('product-right-label') ?>
                                            <span data-action="pr-ac-toggle2"></span>
                                        </div>
                                        <div class="pr_media2">
                                            <div class="form-group product_main_image">
                                                <div
                                                    class="mw_gallery_header_title"><?= CDictionary::GetKey('main_image_title') ?></div>

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
                                                     data-gal-single-name="lang[<?= $lang['key'] ?>][product_img]">
                                                    <?php if (isset($product_img) && !empty($product_img)) { ?>
                                                        <?php $img_src = new CAttach($product_img); ?>
                                                        <div class=" gallery_item gallery_item_product_single">
                                                    <span data-action="gal-delete"><i
                                                            class="fa fa-times gal-img-delete"></i></span>
                                                            <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                 class="img-responsive">
                                                            <input type="hidden"
                                                                   name="lang[<?= $lang['key'] ?>][product_img][id]"
                                                                   value="<?= $product_img ?>">
                                                            <input type="text" class="form-control"
                                                                   name="lang[<?= $lang['key'] ?>][product_img][title]"
                                                                   value="<?= $product_img_title ?>">
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
                                                     data-gallery-name="lang[<?= $lang['key'] ?>][product_gallery]">
                                                    <?php if (isset($product_gallery) && !empty($product_gallery)) { ?>
                                                        <?php foreach ($product_gallery as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][product_gallery][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][product_gallery][<?= $key2; ?>][title]"
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
                                                     data-cover-name="lang[<?= $lang['key'] ?>][product_covers]">
                                                    <?php if (isset($product_covers) && !empty($product_covers)) { ?>
                                                        <?php foreach ($product_covers as $key2 => $item) { ?>
                                                            <?php $img_src = new CAttach($item['id']); ?>
                                                            <div class="gallery_item gallery_item_product">
                                                        <span data-action="gal-delete"><i
                                                                class="fa fa-times gal-img-delete"></i></span>
                                                                <img src="<?= $img_src->GetURL() ?>" alt=""
                                                                     class="img-responsive">
                                                                <input type="hidden"
                                                                       name="lang[<?= $lang['key'] ?>][product_covers][<?= $key2; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control"
                                                                       name="lang[<?= $lang['key'] ?>][product_covers][<?= $key2; ?>][title]"
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
                                                     data-gal-name="lang[<?= $lang['key'] ?>][product_files]">
                                                    <?php if (isset($product_files) && !empty($product_files)) { ?>
                                                        <?php $icon_obj = CIcons::getInstance(); ?>
                                                        <?php foreach ($product_files as $key22 => $item) { ?>
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
                                                                       name="lang[<?= $lang['key'] ?>][product_files][<?= $key22; ?>][id]"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="text" class="form-control product_title"
                                                                       name="lang[<?= $lang['key'] ?>][product_files][<?= $key22; ?>][title]"
                                                                       value="<?= $item['title'] ?>">
                                                            </div>
                                                        <?php }
                                                    } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pr_media">
                                        <div
                                            class="product-right-group-label"><?= CDictionary::GetKey('product-right-label-shipping') ?>
                                            <span data-action="pr-ac-toggle3"></span></div>
                                        <div class="pr_media3">
                                            <div class="form-group pr_shipping_details_size">
                                                <div class="shipping_details_label">
                                                    <?= CDictionary::GetKey('shipping_details_label') ?>
                                                </div>
                                                <select name="" id="" class="form-control">
                                                    <option value="">sm</option>
                                                    <option value="">m</option>
                                                </select>
                                                <input type="text" class="form-control"
                                                       placeholder="<?= CDictionary::GetKey('length') ?>">
                                                <input type="text" class="form-control"
                                                       placeholder="<?= CDictionary::GetKey('width') ?>">
                                                <input type="text" class="form-control"
                                                       placeholder="<?= CDictionary::GetKey('height') ?>">
                                            </div>
                                            <div class="form-group pr_shipping_details_weight">
                                                <div class="shipping_details_label">
                                                    <?= CDictionary::GetKey('shipping_details_label_weight') ?>
                                                </div>
                                                <select name="" id="" class="form-control">
                                                    <option value="">gram</option>
                                                    <option value="">kilogram</option>
                                                </select>
                                                <input type="text" class="form-control"
                                                       placeholder="<?= CDictionary::GetKey('weight') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" id="product_add_button"
                                            class="btn btn-success pull-right"><?= $action ?></button>

                                </div>
                            </div>
                        </div>

                    <?php } ?>


                </div>
                <input type="hidden" value="<?= $page_prop_counter ?>" id="counter_start">

                <?php CModule::LoadTemplate('product_attributes', 'select_attributes', array('multiprice' => $multiprice, 'attributes' => $attributes, 'empty_attributes' => $empty_attributes)) ?>

                <?php CModule::LoadTemplate('discount', 'product_template', ['discount' => $discount]); ?>


            </form>

        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php CModule::LoadTemplate('product_attributes', 'attr_value_modal'); ?>


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
<div>
</div>

<div style="display: none">

    <div class="gallery_item gallery_item_product" id="gallery_item_template">
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

</div>
<script>

</script>
<script>
    $(function () {
        $('.mw-tree input[type=checkbox]:checked').each(function (i, v) {
            $(v).closest('ul').show();
        })
    })
    page_prop.counter = parseInt($('#counter_start').val());
    $("[data-action=pr-ac-toggle1]").on('click', function () {
        $('.product-right-group1 .form-group').toggle()
    })
    $("[data-action=pr-ac-toggle2]").on('click', function () {
        $('.pr_media2').toggle();
    })
    $("[data-action=pr-ac-toggle3]").on('click', function () {
        $('.pr_media3').toggle();
    })
    $('[data-action=show_seo]').on('click', function () {
        $('.seo_tools').toggle()
    })

    $('#product_form').on('submit', function (e) {
        var check = $('[data-action="code-input"]').first().attr('data-success');
        console.log(check);
        if (check == '0') {
            e.preventDefault();
            alert('<?= CDictionary::GetKey('product_code_exists') ?>');
            return false
        }
        var cat_check = $(this).find('[role=tabpanel]').first().find('.mw-tree input[type=checkbox]:checked');
        if (cat_check.length == 0) {
            e.preventDefault();
            alert('<?= CDictionary::GetKey('please_select_category') ?>');
            return false
        }
        return true;

    })

    $('[data-action=tag_select]').on('change', function (e) {
        e.preventDefault();

        var val = $(this).val();
        $('[data-action=tag_select]').val(val)
        $('.tag_select_class').trigger("chosen:updated")
    })

    $('[data-action="code-input"]').on('keyup', function () {
        var elem = $(this);
        var check_val = elem.val();
        $('[data-action="code-input"]').attr('data-success', 0);
        $.ajax({
            url: 'index.php?module=product&submenu=action',
            type: 'POST',
            data: {
                action: 'check_code',
                code: check_val
            },
            success: function (msg) {
                if (msg == 1) {
                    $('[data-action="code-input"]').attr('data-success', 0);
                } else {
                    $('[data-action="code-input"]').attr('data-success', 1);
                }
            }
        })
    });
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

    $('[data-action="brand-select"]').on('change', function (e) {

        var val = $(this).val();
        $('[data-action="brand-select"]').val(val)
    })


    $('[data-action="code-input"]').on('change', function () {
        var val = $(this).val();
        $('[data-action="code-input"]').val(val)
    })
    $('[data-action="count-input"]').on('change', function () {
        var val = $(this).val();
        $('[data-action="count-input"]').val(val)
    })
    $('[data-action="price-input"]').on('change', function () {
        var val = $(this).val();
        $('[data-action="price-input"]').val(val)
    })
    $('[data-action="old-price-input"]').on('change', function () {
        var val = $(this).val();
        $('[data-action="old-price-input"]').val(val)
    })


    $('[data-action=in-stock-checkbox]').on('change', function () {
        var is_check = $(this).prop('checked');
        $('[data-action=in-stock-checkbox]').prop('checked', is_check);
    })

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

</script>

