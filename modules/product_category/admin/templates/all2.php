<?php
$cat_post = new CCategoryPost();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = null;
}

if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
} else {
    $limit = 10;
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['brand'])) {
    $cur_brand = $_GET['brand'];
} else {
    $cur_brand = null;
}
CFrontProductCategory::Initial();
$cats = CFrontProductCategory::GetFiltered($search, null, $lang, $page, $limit,'category_order',['brand'=>$cur_brand]);
$page_count = $cats['page_count'];
$current_page = $cats['current_page'];
unset($cats['page_count']);
unset($cats['current_page']);
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('cat_product') ?>
                    <small><?= CDictionary::GetKey('all') ?></small>
                    <button class="btn btn-success pull-right"><a
                            href="index.php?menu=post_category&submenu=add"><?= CDictionary::GetKey('add') ?> <?= CDictionary::GetKey('cat') ?></a>
                    </button>
                </h1>

            </div>
        </div>
        <!-- /.row -->
        <div class="form-inline post-cat-button-panel">


            <div class="form-group">
                <?php foreach (CLanguage::get_langsUser() as $key => $item) {
                    if ($key == 0) {
                        $active_class = 'active';
                    } else {
                        $active_class = '';
                    }
                    ?>
                    <button class="btn btn-primary" data-action="lang_button" data-value="<?= $item['key'] ?>">
                        <?= $item['title'] ?>
                    </button>
                <?php } ?>

            </div>
            <div class="form-group ">
                <select name="" id="show-count" class="form-control ">
                    <option value="5" <?php if ($limit == 5) echo 'selected' ?>>5</option>
                    <option value="10" <?php if ($limit == 10) echo 'selected' ?>>10</option>
                    <option value="50" <?php if ($limit == 50) echo 'selected' ?>>50</option>
                </select>
            </div>
            <?php if (CModule::HasModule('brand')) { ?>
                <?php
                $brand_obj = CModule::LoadModule('brand');
                $brands = $brand_obj->GetBrands();
                if ($brands) {
                    ?>
                    <div class="form-group ">
                        <select name="" id="brand-filter" class="form-control ">
                            <option value=""><?= CDictionary::GetKey('all_brands') ?></option>
                            <?php foreach ($brands as $one_brand) { ?>
                                <option value="<?= $one_brand['brand_group'] ?>" <?php if ($cur_brand == $one_brand['brand_group']) echo 'selected' ?>><?= $one_brand['brand_title'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php }
            } ?>
            <div class="form-group pull-right">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="search"
                           placeholder="<?= CDictionary::GetKey('search') ?>"
                           data-action="search" value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                </div>


            </div>
        </div>

        <div id="post_cat_sortable">
            <table class="table table-bordered table-striped table-responsive product_cat_table">
                <tr>
                    <th class="all_cat_head1">ID</th>
                    <th class="all_cat_head2"><?= CDictionary::GetKey('title') ?></th>
                    <th class="all_cat_head3"><?= CDictionary::GetKey('parent_cat') ?></th>
                    <th class="all_cat_head4"><?= CDictionary::GetKey('image') ?></th>
                    <th class="all_cat_head4"><?= CDictionary::GetKey('brand') ?></th>
                    <th class="all_cat_head5"><?= CDictionary::GetKey('language') ?></th>
                    <th class="all_cat_head6"><?= CDictionary::GetKey('products') ?></th>
                    <th class="all_cat_head7" width="10%"><?= CDictionary::GetKey('order') ?></th>
                    <?php if (CModule::HasModule('discount')) { ?>
                        <th class="all_cat_head100"><?= CDictionary::GetKey('apply_discount') ?></th>
                    <?php } ?>
                    <th class="all_cat_head8"><?= CDictionary::GetKey('status') ?></th>
                    <th class="all_cat_head9"><?= CDictionary::GetKey('actions') ?></th>
                </tr>
                <?php foreach ($cats as $key => $item) { ?>
                    <tr data-action="post_cat_item" data-value="<?= $key ?>">
                        <td><?= $key ?></td>
                        <td>
                            <?= $cat_post->GetTree($item['product_category']['category_level']) ?>
                            <?= $item['product_category']['category_title'] ?>
                            <?php if (!$item['product_category']['is_translated']) { ?>
                                <div class="post-not-translated">
                                    <?= CDictionary::GetKey('not_translated') ?>
                                </div>
                            <?php } ?>
                        </td>
                        <td>
                            <?= $item['product_category']['category_parent'] ?>

                            <?php if ($item['product_category']['parent_is_translated']) { ?>
                                <div class="post-not-translated">
                                    <?= CDictionary::GetKey('not_translated') ?>
                                </div>
                            <?php } ?></td>

                        <td>
                            <?php if ($item['product_category']['category_img']) { ?>
                                <div class="post-all-img"><img src="<?= $item['product_category']['category_img'] ?>"
                                                               alt=""></div>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if ($item['brand']) {
                                $brand_arr = array();
                                foreach ($item['brand'] as $brand_item) {
                                    $brand_arr[] = $brand_item['brand_title'];
                                }
                                echo implode(' , ', $brand_arr);
                            }
                            ?>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($item['product_category']['is_active_langs'] as $p_lang_key => $is_active_lang) { ?>
                                    <li>
                                        <span><?= $p_lang_key ?></span>
                                        <?php if ($is_active_lang): ?>
                                            <i class="fa fa-check"></i>
                                        <?php else: ?>

                                            <i class="fa fa-times"></i>
                                        <?php endif; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td><?= $item['product_category']['product_count'] ?></td>
                        <td>
                            <div class="input-group">
                                <input type="number" class="form-control" disabled data-id="<?= $key ?>"
                                       value="<?= $item['product_category']['category_order'] ?>">
                                    <span class="input-group-btn">
                                    <button class="btn btn-primary" data-action="save" style="display: none;">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-success" data-action="activate_order">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    </span>
                            </div>
                        </td>
                        <?php if (CModule::HasModule('discount')) { ?>
                            <td>
                                <div>

                                </div>
                                <div class="input-group" data-action="apply-discount-container">
                                    <span class="input-group-btn">
                                        <select name="" id="" data-action="apply-discount-select1" class="form-control apply-discount-select-type1">
                                            <option value="fixed"><?= CDictionary::GetKey('Fixed') ?></option>
                                            <option value="percent"><?= CDictionary::GetKey('Procent') ?> (%)</option>
                                        </select>
                                    </span>
                                <span class="input-group-btn">
                                    <select name="" id="" data-action="apply-discount-select2" class="form-control apply-discount-select-type2">
                                        <option value="plus">+</option>
                                        <option value="minus">-</option>
                                    </select>
                                </span>
                                    <input type="number" class="form-control"
                                           value="" data-action="apply-discount-value" min="0">
                                    <span class="input-group-btn">
                                    <button class="btn btn-primary" data-action="save-discount"  data-id="<?= $key ?>">
                                        <i class="fa fa-check"></i>
                                    </button>

                                    </span>
                                </div>
                                <span>min: <?php echo isset($item['product_category']['price_range']['min'])?$item['product_category']['price_range']['min']:0 ?></span>
                                <span> max: <?php echo isset($item['product_category']['price_range']['min'])?$item['product_category']['price_range']['max']:0 ?></span>
                            </td>
                        <?php } ?>
                        <td>
                            <button
                                class="btn btn-default post-active" <?php if ($item['product_category']['is_active'] == 1) echo 'style="display:none"'; ?>
                                data-action="activate"
                                data-value="<?= $key ?>"><?= CDictionary::GetKey('activate') ?></button>
                            <button
                                class="btn btn-default post-passive" <?php if ($item['product_category']['is_active'] == 0) echo 'style="display:none"'; ?>
                                data-action="passive"
                                data-value="<?= $key ?>"><?= CDictionary::GetKey('passive') ?></button>
                        </td>
                        <td>
                            <a class="btn btn-default"
                               href="index.php?module=product_category&submenu=add&edit_id=<?= $key ?>"><i
                                    class="fa fa-pencil-square-o"></i></a>
                            <button class="btn btn-default" data-action="delete"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php if ($page_count > 1) { ?>
                <div class="p-cat-pagination">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="pagination pull-right" data-action="pagination">
                                <?php for ($i = 1; $i <= $page_count; $i++) { ?>
                                    <li <?php if ($page == $i) echo 'class="active"' ?>><a href="#"
                                                                                           data-value="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>


        </div>

    </div>

    <script>

        $(function () {

            $('[data-action="activate_order"]').on('click', function () {
                $(this).closest('div').find('input').prop('disabled', false);
                $(this).closest('div').find('[data-action="save"]').show();
                $(this).hide()
            })
            $('[data-action=lang_button]').on('click', function () {
                replace_url_param('lang', $(this).data('value'))
            })
            $('#search_but').on('click', function () {
                replace_url_param('search', $('[data-action="search"]').val())
            })
            $('#show-count').on('change', function () {
                replace_url_param('limit', $(this).val())
            })
            $('#brand-filter').on('change', function () {
                replace_url_param('brand', $(this).val())
            })
            $('[data-action="pagination"] a').on('click', function (e) {
                e.preventDefault();
                replace_url_param('page', $(this).data('value'))
            })
            $('[data-action="save"]').on('click', function () {
                var button = $(this);
                var p_id = $(this).closest('div').find('input').data('id');
                var order_val = $(this).closest('div').find('input').val();
                $.ajax({
                    type: "POST",
                    url: 'index.php?module=product_category&submenu=action',
                    data: {
                        action: 'update_post_order',
                        id: p_id,
                        order: order_val
                    },
                    success: function (msg) {
                        console.log(msg)
                        //location.reload();
                        $(button).closest('div').find('input').val(parseInt(msg));
                        $(button).closest('div').find('input').prop('disabled', true);
                        $(button).closest('div').find('[data-action="activate_order"]').show();
                        $(button).hide()
                    }

                })

            })

            $('[data-action="delete"]').on('click', function () {
                if (!confirm('Are you sure?')) return;
                $.ajax({
                    type: "POST",
                    url: 'index.php?module=product_category&submenu=action',
                    data: {
                        action: 'delete_cat_item',
                        delete_id: $(this).closest('[data-action="post_cat_item"]').data('value')
                    },
                    success: function (msg) {
                        console.log(msg)
                        location.reload();
                    }
                })
            })
            $('[data-action="activate"]').on('click', function () {
                //if(!confirm('Are you sure?')) return;
                var button = $(this);
                $.ajax({
                    type:"POST",
                    url:'index.php?module=product_category&submenu=action',
                    data:{
                        action:'cat_activate',
                        id:button.closest('[data-action="post_cat_item"]').data('value')
                    },
                    success: function (msg) {
                        button.closest('[data-action="post_cat_item"]').find('[data-action="activate"]').hide()
                        button.closest('[data-action="post_cat_item"]').find('[data-action="passive"]').show()
                        console.log(msg)
//                        //location.reload();
                    }
                })
            })
            $('[data-action="passive"]').on('click', function () {
                //if(!confirm('Are you sure?')) return;
                var button = $(this);
                $.ajax({
                    type:"POST",
                    url:'index.php?module=product_category&submenu=action',
                    data:{
                        action:'cat_passivate',
                        id:button.closest('[data-action="post_cat_item"]').data('value')
                    },
                    success: function (msg) {
                        button.closest('[data-action="post_cat_item"]').find('[data-action="activate"]').show()
                        button.closest('[data-action="post_cat_item"]').find('[data-action="passive"]').hide()
                        console.log(msg)
//                        //location.reload();
                    }
                })
            })
            $('[data-action="save-discount"]').on('click', function () {
                var cat_id = $(this).data('id');
                var select_1 = $(this).closest('[data-action="apply-discount-container"]').find('[data-action="apply-discount-select1"]').val();
                var select_2 = $(this).closest('[data-action="apply-discount-container"]').find('[data-action="apply-discount-select2"]').val();
                var value = $(this).closest('[data-action="apply-discount-container"]').find('[data-action="apply-discount-value"]').val();
                if(!value){
                    alert('please fill value');
                    return false;
                }

                var data = {
                    cat_id:cat_id,
                    select_1:select_1,
                    select_2:select_2,
                    value:value
                };
                $.ajax({
                    type: "POST",
                    url: 'index.php?module=product_category&submenu=action',
                    data: {
                        action: 'cat_apply_discount',
                        data: data
                    },
                    success: function (msg) {

                        console.log(msg)
//                        //location.reload();
                    }
                })
            })
        })

    </script>
    <!-- /#page-wrapper -->