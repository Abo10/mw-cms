<?php
$brand_obj = CModule::LoadModule('brand');
//$post = $post_obj->GetList_Title();
$css_check_class = 'fa-check';
$css_uncheck_class = 'fa-times';

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
}
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
} else {
    $cat_id = null;
}
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
} else {
    $cat_id = null;
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
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = null;
}
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = 2;
}
$brand = $brand_obj->GetElementsPage($lang, $limit, $page, $search);

?>
<div class="brand-main">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= CDictionary::GetKey('brand') ?>
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

                    <div class="form-inline brand-button-panel">
                        <button class="btn btn-danger"
                                data-action="delete-all"><?= CDictionary::GetKey('delete') ?></button>


                        <div class="form-group">
                            <select id="brand_limit" class="form-control">
                                <option value="5" <?php if ($limit == 5) echo 'selected' ?>>5</option>
                                <option value="10" <?php if ($limit == 10) echo 'selected' ?>>10</option>
                                <option value="50" <?php if ($limit == 50) echo 'selected' ?>>50</option>
                            </select>


                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" name="search" placeholder="Փնտրել"
                                       data-action="search" value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                            </div>


                        </div>
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


                    </div>


                </div>
            </div>
            <table class="table table-bordered table-striped table-responsive" id="all_brands">

                <tr>
                    <th width="2%"><input type="checkbox" data-action="check-all"></th>
                    <th width="2%">N</th>
                    <th><?= CDictionary::GetKey('title') ?></th>
                    <th><?= CDictionary::GetKey('main_image_title') ?></th>
                    <th>ID</th>
                    <th width="7%"><?= CDictionary::GetKey('actions') ?></th>

                </tr>
                <tbody>
                <?php foreach ($brand['data'] as $key => $item) { ?>
                    <tr>
                        <td><input type="checkbox" data-action="brand-checkbox"
                                   data-value="<?= $item['brand_group'] ?>"></td>
                        <td><?= ($page - 1) * $limit + $key + 1 ?></td>
                        <td><?= $item['brand_title'] ?><?php if (!$item['is_translated']) echo CDictionary::GetKey('not_translated'); ?></td>

                        <td>
                            <?php if ($item['brand_img']): ?>
                                <?php $link = new CAttach($item['brand_img']) ?>
                                <div class="all-brand-image">
                                    <img src="<?= $link->GetURL() ?>" alt="">
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['brand_group'] ?></td>
                        <td>
                            <a href="index.php?module=brand&submenu=add&edit_id=<?= $item['brand_group'] ?>"
                               class="btn btn-default" ><i class="fa fa-pencil-square-o"></i></a>

                            <button class="btn btn-default " data-action="delete-brand-item"
                                    data-value="<?= $item['brand_group'] ?>"><i class="fa fa-times"></i></button>
                        </td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($brand['total_pages'] > 1) { ?>
        <div class="row">
            <div class="col-md-12">
                <ul class="pagination pull-right" data-action="pagination">
                    <?php for ($i = 1; $i <= $brand['total_pages']; $i++) { ?>
                        <li><a href="#" data-value="<?= $i ?>"><?= $i ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    //$('#all_brands tbody').sortable({});
    $(function () {
        $('[data-action=check-all]').on('change', function () {
            $('#all_brands').find('input[type=checkbox]').prop('checked', $(this).prop('checked'))
        })
        $('[data-action=pagination] a').on('click', function (e) {
            e.preventDefault();
            replace_url_param('page', $(this).data('value'))
        })
        $('#brand_limit').on('change', function () {
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
        $('[ data-action=delete-brand-item]').on('click', function () {
            if (!confirm('Are you sure?')) return;
            $.ajax({
                type: "POST",
                url: 'index.php?module=brand&submenu=action',
                data: {
                    action: 'delete_brand_item',
                    delete_id: $(this).data('value')
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })
        })

        $('[data-action=delete-all]').on('click', function () {


            var checked = $('[data-action=brand-checkbox]:checked');
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
                url: 'index.php?module=brand&submenu=action',
                data: {
                    action: 'delete_brand_item',
                    delete_id: ret_data
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })

            console.log(ret_data)
        })
    })
</script>
<!-- /#page-wrapper -->