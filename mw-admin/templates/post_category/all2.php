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
$cats = $cat_post->GetFiltered($search, null, $lang, $page, $limit);

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
                    <?= CDictionary::GetKey('cat_post') ?>
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
                <?php foreach ($langs as $key => $item) {
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
            <table class="table table-bordered table-striped table-responsive cat_table">
                <tr>
                    <th class="all_cat_head1">ID</th>
                    <th class="all_cat_head2"><?= CDictionary::GetKey('title') ?></th>
                    <th class="all_cat_head3"><?= CDictionary::GetKey('parent_cat') ?></th>
                    <th class="all_cat_head4"><?= CDictionary::GetKey('image') ?></th>
                    <th class="all_cat_head5"><?= CDictionary::GetKey('language') ?></th>
                    <th class="all_cat_head6"><?= CDictionary::GetKey('posts') ?></th>
                    <th class="all_cat_head7" width="12%"><?= CDictionary::GetKey('order') ?></th>
                    <th class="all_cat_head8"><?= CDictionary::GetKey('status') ?></th>
                    <th class="all_cat_head9"><?= CDictionary::GetKey('actions') ?></th>
                </tr>
                <?php foreach ($cats as $key => $item) { ?>
                    <tr data-action="post_cat_item" data-value="<?= $key ?>">
                        <td><?= $key ?></td>
                        <td>
                            <?= $cat_post->GetTree($item['category_level']) ?>
                            <?= $item['category_title'] ?>
                            <?php if (!$item['is_translated']) { ?>
                                <div class="post-not-translated">
                                    <?= CDictionary::GetKey('not_translated') ?>
                                </div>
                            <?php } ?>
                        </td>
                        <td><?= $item['category_parent'] ?></td>
                        <td>
                            <?php if ($item['category_img']) { ?>
                                <div class="post-all-img"><img src="<?= $item['category_img'] ?>" alt=""></div>
                            <?php } ?>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($item['is_active_langs'] as $p_lang_key => $is_active_lang) { ?>
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
                        <td><?= $item['posts_count'] ?></td>
                        <td>
                            <div class="input-group">
                                <input type="number" class="form-control" disabled data-id="<?= $key ?>"
                                       value="<?= $item['category_order'] ?>">
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
                        <td>
                            <button
                                class="btn btn-default post-active" <?php if ($item['is_active'] == 1) echo 'style="display:none"'; ?>
                                data-action="activate"
                                data-value="<?= $key ?>"><?= CDictionary::GetKey('activate') ?></button>
                            <button
                                class="btn btn-default post-passive" <?php if ($item['is_active'] == 0) echo 'style="display:none"'; ?>
                                data-action="passive"
                                data-value="<?= $key ?>"><?= CDictionary::GetKey('passive') ?></button>
                        </td>
                        <td>
                            <a class="btn btn-default"
                               href="index.php?menu=post_category&submenu=add&edit_id=<?= $key ?>"><i
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
                    url: 'index.php?menu=post_category&submenu=action',
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
                    url: 'index.php?menu=post_category&submenu=action',
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
                    type: "POST",
                    url: 'index.php?menu=post_category&submenu=action',
                    data: {
                        action: 'cat_activate',
                        id: button.closest('[data-action="post_cat_item"]').data('value')
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
                    type: "POST",
                    url: 'index.php?menu=post_category&submenu=action',
                    data: {
                        action: 'cat_passivate',
                        id: button.closest('[data-action="post_cat_item"]').data('value')
                    },
                    success: function (msg) {
                        button.closest('[data-action="post_cat_item"]').find('[data-action="activate"]').show()
                        button.closest('[data-action="post_cat_item"]').find('[data-action="passive"]').hide()
                        console.log(msg)
//                        //location.reload();
                    }
                })
            })
        })

    </script>
    <!-- /#page-wrapper -->