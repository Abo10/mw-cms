<?php
$product_obj = CModule::LoadModule('product');
//$post = $post_obj->GetList_Title();
$css_check_class = 'fa-check';
$css_uncheck_class = 'fa-times';

$search = [];

$counter = 0;

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
}

if (isset($_GET['cat_id']) && $_GET['cat_id'] != 0) {
    $cat_id = [$_GET['cat_id']];
} else {
    $cat_id = null;
}
if (isset($_GET['brand_id']) && $_GET['brand_id'] != 0) {
    $brand_id = [$_GET['brand_id']];
} else {
    $brand_id = null;
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
} else {
    $limit = 50;
}
if (isset($_GET['product-title'])) {
    $search['product_title'] = $_GET['product-title'];
} else {
    $search['product_title'] = '';
}
if (isset($_GET['product-code'])) {
    $search['product_code'] = $_GET['product-code'];
} else {
    $search['product_code'] = '';
}
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = 2;
}
//$search['product_title'] = 'adasdasda';
//$search['product_code'] = 'asda';
$predefines = ['product_category' => $cat_id, 'brand' => $brand_id];
$product = $product_obj->GetProducts($page, $limit, $lang, $search, $predefines, $status);
//var_dump($product);

?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('product')?>
                    <small><?= CDictionary::GetKey('all') ?></small>
                </h1>
                
            </div>
        </div>
        <!-- /.row -->

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
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-inline post_public_class">
                            <?php $counts = $product_obj->GetProductCounts(); ?>
                            <span>
                            <a href="" data-action="status-link"
                                                                 data-value="2"><?= CDictionary::GetKey('all') ?> (<?= (int)$counts['all'] ?>)</a></span>
                            <span>
                            <a href="" data-action="status-link"
                                                                        data-value="1"> <?= CDictionary::GetKey('publicated') ?> (<?= (int)$counts['active'] ?>)</a></span>
                            <span>
                           <a href="" data-action="status-link" data-value="0"> <?= CDictionary::GetKey('passive55') ?> (<?= (int)$counts['passive'] ?>)</a></span>
                        </div>
                        <div class="form-inline post-button-panel">
                            <button class="btn btn-danger"
                                    data-action="delete-all"><?= CDictionary::GetKey('delete') ?></button>

                            <div class="form-group">


                            </div>
                            <div class="form-group">


                            </div>
                            <div class="form-group">
                                <select id="post_limit" class="form-control">
                                    <option value="5" <?php if ($limit == 5) echo 'selected' ?>>5</option>
                                    <option value="10" <?php if ($limit == 10) echo 'selected' ?>>10</option>
                                    <option value="50" <?php if ($limit == 50) echo 'selected' ?>>50</option>
                                </select>


                            </div>

                            <!--                            <div class="form-group">-->
                            <!--                                <div class="input-group">-->
                            <!--                                    <input type="text" class="form-control input-sm" name="search" placeholder="Փնտրել"-->
                            <!--                                           data-action="search" value="-->
                            <? //= $search ?><!--">-->
                            <!--                    <span class="input-group-addon" id="search_but">-->
                            <!--                            <span class="fa fa-search"></span>-->
                            <!--                    </span>-->
                            <!--                                </div>-->
                            <!---->
                            <!---->
                            <!--                            </div>-->
                            <div class="form-group">
                                <?php foreach (CLanguage::get_langsUser() as $key => $item) {
                                    if ($key == 0) {
                                        $active_class = 'active';
                                    } else {
                                        $active_class = '';
                                    }
                                    ?>
                                    <button class="btn btn-primary" data-action="lang_button"
                                            data-value="<?= $item['key'] ?>">
                                        <?= $item['title'] ?>
                                    </button>
                                <?php } ?>
                            </div>

<a class='btn btn-success pull-right' href='index.php?module=product&submenu=add'><?= CDictionary::GetKey('add')?> <?= CDictionary::GetKey('product')?></a>
                        </div>


                    </div>
                </div>
                <table class="table table-bordered table-striped table-responsive" id="all_products">

                    <tr>
                        <th width="2%"><input type="checkbox" data-action="check-all"></th>
                        <th width="2%">N</th>
                        <th><?= CDictionary::GetKey('title') ?></th>
                        <th width="6%"><?= CDictionary::GetKey('image') ?></th>

                        <th><?= CDictionary::GetKey('cat') ?></th>
                        <?php if (CModule::HasModule('brand')) { ?>
                            <th><?= CDictionary::GetKey('brand') ?></th>
                        <?php } ?>
                        <th width="10%"><?= CDictionary::GetKey('translate') ?></th>
                        <th><?= CDictionary::GetKey('id') ?></th>
                        <th width="8%"><?= CDictionary::GetKey('order') ?></th>
                        <th     width="7%"><?= CDictionary::GetKey('product_code') ?></th>
                        <th><?= CDictionary::GetKey('price') ?></th>
                        <th><?= CDictionary::GetKey('old_price') ?></th>
                        <th><?= CDictionary::GetKey('count') ?></th>
                        <th width="7%"><?= CDictionary::GetKey('actions') ?></th>
                        <th><?= CDictionary::GetKey('status') ?></th>

                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th><input type="text" class="form-control" data-action="search-product-title"
                                   value="<?= $search['product_title'] ?>">
                        </th>
                        <th></th>

                        <th>
                            <select id="product_cat" class="form-control">
                                <option value="0"><?= CDictionary::GetKey('all') ?></option>
                                <?php
                                $a = CModule::LoadModule('product_category');
                                $cat = $a->GetAllCats();
                                foreach ($cat as $item) {
                                    ?>
                                    <option
                                        value="<?= $item['value']['cid'] ?>" <?php if ($item['value']['cid'] == $cat_id[0]) echo 'selected'; ?>><?= $a->GetTree($item['level']) ?><?= $item['value']['category_title'] ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <?php if (CModule::HasModule('brand')) { ?>
                            <th>
                                <select id="product_brand" class="form-control">
                                    <option value="0"><?= CDictionary::GetKey('all') ?></option>
                                    <?php
                                    $brand_obj = CModule::LoadModule('brand');
                                    $brand = $brand_obj->GetBrands();
                                    foreach ($brand as $item) {
                                        ?>
                                        <option
                                            value="<?= $item['brand_group'] ?>" <?php if (is_array($brand_id) && in_array($item['brand_group'], $brand_id)) echo 'selected'; ?>><?= $item['brand_title'] ?></option>
                                    <?php } ?>
                                </select>
                            </th>
                        <?php } ?>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><input type="text" class="form-control" data-action="search-product-code"
                                   value="<?= $search['product_code'] ?>"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tbody>
                    <?php foreach ($product['products'] as $key => $item) { ?>
                        <tr>
                            <td><input type="checkbox" data-action="post-checkbox" data-value="<?= $key ?>"></td>
                            <td><?= ($page - 1) * $limit + $counter++ + 1 ?></td>
                            <td><?= $item[$lang]['product_title'] ?></td>
                            <td>
                                <img src="<?= $item[$lang]['product_image_url'] ?>" class="product_all_image" alt="">
                            </td>
                            <td>
                                <?php foreach ($product['product_category'][$key] as $key2 => $item2) { ?>
                                    <span><?= $item2 ?></span>
                                    <span>, </span>
                                <?php } ?>
                            </td>
                            <?php if (CModule::HasModule('brand')) { ?>
                                <td>
                                    <?php foreach ($product['brand'][$key] as $key2 => $item2) { ?>
                                        <?php if (!is_array($item2)) { ?>
                                            <span><?= $item2 ?></span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <td>
                                <ul class="">
                                    <?php foreach ($item as $item2) { ?>
                                        <li><span><?= $item2['product_lang'] ?></span> <i
                                                class="fa <?php if ($item2['is_translated']) echo $css_check_class; else echo $css_uncheck_class; ?>"></i>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </td>
                            <td><?= $key ?></td>

                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" disabled data-id="<?= $key ?>"
                                           value="<?= $item[$lang]['product_order'] ?>">
                                    <span class="input-group-btn">
                                    <button class="btn btn-primary" data-action="save" style="display: none;">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-success" data-action="activate">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    </span>
                                </div>

                            </td>
                            <td><?= $item[$lang]['product_code'] ?></td>
                            <td><?= $item[$lang]['product_price'] ?></td>
                            <td><?= $item[$lang]['product_old_price'] ?></td>
                            <td><?= $item[$lang]['product_count'] ?></td>
                            <td>
                                <a href="index.php?module=product&submenu=add&edit_id=<?= $key ?>"
                                   class="btn btn-default" target="_blank"><i class="fa fa-pencil-square-o"></i></a>

                                <button class="btn btn-default " data-action="delete-post-item"
                                        data-value="<?= $key ?>"><i class="fa fa-times"></i></button>
                            </td>
                            <td>
                                <button
                                    class="btn btn-default post-active" <?php if ($item2['product_isactive'] == 1) echo 'style="display:none"'; ?>
                                    data-action="make_active"
                                    data-value="<?= $key ?>"><?= CDictionary::GetKey('activate'); ?></button>
                                <button
                                    class="btn btn-default post-passive" <?php if ($item2['product_isactive'] == 0) echo 'style="display:none"'; ?>
                                    data-action="make_passive"
                                    data-value="<?= $key ?>"><?= CDictionary::GetKey('passive'); ?></button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($product['page_count'] > 1) { ?>
            <div class="row">
                <div class="col-md-12">
                    <ul class="pagination pull-right" data-action="pagination">
                        <?php for ($i = 1; $i <= $product['page_count']; $i++) { ?>
                            <li><a href="#" data-value="<?= $i ?>"><?= $i ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>

    </div>
    <!-- /.container-fluid -->

</div>
<script>
    //$('#all_posts tbody').sortable({});
    $(function () {
        $('[data-action=check-all]').on('change', function () {
            $('#all_posts').find('input[type=checkbox]').prop('checked', $(this).prop('checked'))
        })
        $('[data-action=pagination] a').on('click', function (e) {
            e.preventDefault();
            replace_url_param('page', $(this).data('value'))
        })
        $('#product_cat').on('change', function () {
            replace_url_param('cat_id', $(this).val())
        })
        $('#product_brand').on('change', function () {
            replace_url_param('brand_id', $(this).val())
        })
        $('#post_limit').on('change', function () {
            replace_url_param('limit', $(this).val())
        })
        $('#search_but').on('click', function () {
            replace_url_param('search', $('[data-action=search]').val())
        })
        $('[data-action=lang_button]').on('click', function () {
            replace_url_param('lang', $(this).data('value'))
        })
        $('[data-action=status-link]').on('click', function (e) {
            e.preventDefault();
            replace_url_param('status', $(this).data('value'))
        })
        $('[data-action="search-product-title"]').on('change', function () {
            replace_url_param('product-title', $(this).val())
        })
        $('[data-action="search-product-code"]').on('change', function () {
            replace_url_param('product-code', $(this).val())
        })

        $('[ data-action=delete-post-item]').on('click', function () {
            if (!confirm('Are you sure?')) return;
            $.ajax({
                type: "POST",
                url: 'index.php?module=product&submenu=action',
                data: {
                    action: 'delete_product_item',
                    delete_id: $(this).data('value')
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })
        })
        $('[data-action=make_active]').on('click', function () {
            var button = $(this);

            $.ajax({
                type: "POST",
                url: 'index.php?module=product&submenu=action',
                data: {
                    action: 'product_activate',
                    id: $(this).data('value')
                },
                success: function (msg) {
                    $(button).hide();
                    $(button).siblings().show();
                }
            })
        })
        $('[data-action=make_passive]').on('click', function () {
            var button = $(this);
            $.ajax({
                type: "POST",
                url: 'index.php?module=product&submenu=action',
                data: {
                    action: 'product_passivate',
                    id: $(this).data('value')
                },
                success: function (msg) {
                    console.log(msg)
                    //location.reload();
                    $(button).hide();
                    $(button).siblings().show();
                }
            })
        })
        $('[data-action=delete-all]').on('click', function () {


            var checked = $('[data-action=post-checkbox]:checked');
            if (checked.length > 0) {
                if (!confirm('Are you sure?')) return;
            } else {
                return;
            }
            var ret_data = [];
            $.each(checked, function (i, v) {
                ret_data.push($(v).data('value'))
            })
            $.ajax({
                type: "POST",
                url: 'index.php?module=product&submenu=action',
                data: {
                    action: 'delete_product_item',
                    delete_id: ret_data
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })

            console.log(ret_data)
        })
        $('[data-action="activate"]').on('click', function () {
            $(this).closest('div').find('input').prop('disabled', false);
            $(this).closest('div').find('[data-action="save"]').show();
            $(this).hide()
        })

        $('[data-action="save"]').on('click', function () {
            var button = $(this);
            var p_id = $(this).closest('div').find('input').data('id');
            var order_val = $(this).closest('div').find('input').val();
            $.ajax({
                type: "POST",
                url: 'index.php?module=product&submenu=action',
                data: {
                    action: 'update_product_order',
                    id: p_id,
                    order: order_val
                },
                success: function (msg) {
                    console.log(msg)
                    //location.reload();
                    $(button).closest('div').find('input').val(parseInt(msg));
                    $(button).closest('div').find('input').prop('disabled', true);
                    $(button).closest('div').find('[data-action="activate"]').show();
                    $(button).hide()
                }

            })

        })
    })
</script>
<!-- /#page-wrapper -->