<?php


//$post = $post_obj->GetList_Title();
$css_check_class = 'fa-check';
$css_uncheck_class = 'fa-times';

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
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
//Cmwdb::$db->orWhere('uid',1);
//Cmwdb::$db->orWhere('uid',2);
$users = Cmwdb::$db->get(CStd_user::$tbl_name);
//var_dump($users);
//var_dump($pages);
CModule::LinkModule('user');
CModule::LinkModule('addressing');
$users = CUserExt::FindUsers($search, ['us_login', 'user_mail'], null, $page, $limit);
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('page') ?>
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

                        <div class="form-inline post-button-panel">



                            <div class="form-group" style="float: right;">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="search" placeholder="Փնտրել"
                                           data-action="search" value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                                </div>


                            </div>


                        </div>


                    </div>
                </div>
                <table class="table table-bordered table-striped table-responsive" id="all_posts">

                    <tr>
                        <th width="2%"><input type="checkbox" data-action="check-all"></th>
                        <th width="2%">ID</th>
                        <th><?= CDictionaryUser::GetKey('firstname') ?></th>
                        <th><?= CDictionaryUser::GetKey('lastname') ?></th>
                        <th><?= CDictionaryUser::GetKey('email') ?></th>
                        <th><?= CDictionaryUser::GetKey('phone') ?></th>
                        <th><?= CDictionary::GetKey('reg_date') ?></th>
                        <th><?= CDictionary::GetKey('login_date') ?></th>
                        <th><?= CDictionary::GetKey('address') ?></th>
                        <th><?= CDictionary::GetKey('status') ?></th>
                        <th width="7%"><?= CDictionary::GetKey('actions') ?></th>

                    </tr>
                    <tbody>
                    <?php foreach ($users['result'] as $key => $item) { ?>
                        <tr>
                            <td><input type="checkbox" data-action="post-checkbox" data-value="<?= $item['pid'] ?>">
                            </td>
                            <th width="2%"><?= $item['uid'] ?></th>
                            <th><?= $item['user_name'] ?></th>
                            <th><?= $item['userl_name'] ?></th>
                            <th><?= $item['us_login'] ?></th>
                            <th><?= $item['tel_code'] ?> <?= $item['user_tel'] ?></th>
                            <th><?= $item['register_date'] ?></th>
                            <th><?= $item['login_date'] ?></th>
                            <th><?= CDictionary::GetKey('address') ?></th>


                            <td>
                                <button
                                    class="btn btn-default post-active" <?php if ($item['us_status'] == 1) echo 'style="display:none"'; ?>
                                    data-action="make_active"
                                    data-value="<?= $item['uid'] ?>"><?= CDictionary::GetKey('activate'); ?></button>
                                <button
                                    class="btn btn-default post-passive" <?php if ($item['us_status'] == 2) echo 'style="display:none"'; ?>
                                    data-action="make_passive"
                                    data-value="<?= $item['uid'] ?>"><?= CDictionary::GetKey('passive'); ?></button>

                            </td>
                            <th>
                                <button class="btn btn-danger " data-action="delete-user"
                                        data-value="<?= $item['uid'] ?>"><i class="fa fa-times"></i></button>
                            </th>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($users['page_count'] > 1) { ?>
            <div class="row">
                <div class="col-md-12">
                    <ul class="pagination pull-right" data-action="pagination">
                        <?php for ($i = 1; $i <= $users['page_count']; $i++) { ?>
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
        $('[data-action="post-checkbox"]').on('change', function () {
            $('#all_posts').find('[data-action=check-all]').prop('checked', false)
        })
        $('[data-action=pagination] a').on('click', function (e) {
            e.preventDefault();
            replace_url_param('page', $(this).data('value'))
        })
        $('#post_cat').on('change', function () {
            replace_url_param('cat_id', $(this).val())
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
        $('[ data-action=delete-user]').on('click', function () {
            if (!confirm('Are you sure?')) return;
            $.ajax({
                type: "POST",
                url: 'index.php?module=user&submenu=action',
                data: {
                    action: 'delete_user',
                    delete_id: $(this).data('value')
                },
                success: function (msg) {
                    console.log(msg)
                    //location.reload();
                }
            })
        })
        $('[data-action=make_active]').on('click', function () {
            var button = $(this);

            $.ajax({
                type: "POST",
                url: 'index.php?module=user&submenu=action',
                data: {
                    action: 'user_activate',
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
                url: 'index.php?module=user&submenu=action',
                data: {
                    action: 'user_block',
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

    })
</script>
<!-- /#page-wrapper -->

