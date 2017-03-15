<?php
$page_obj = new CAllPages();

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
$pages = $page_obj->GetElementsPage($lang, $limit, $page,  $search, $status);
//var_dump($pages);
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
                        <div class="form-inline post_public_class">
                            <span><?= CDictionary::GetKey('all') ?>(<a href="" data-action="status-link" data-value="2"><?= $pages['total_all'] ?></a>)</span>
                            <span><?= CDictionary::GetKey('publicated') ?>(<a href="" data-action="status-link" data-value="1"><?= $pages['total_active'] ?></a>)</span>
                            <span><?= CDictionary::GetKey('passive') ?>(<a href="" data-action="status-link" data-value="0"><?= $pages['total_passive'] ?></a>)</span>
                        </div>
                        <div class="form-inline post-button-panel">
                            <button class="btn btn-danger" data-action="delete-all"><?= CDictionary::GetKey('delete') ?></button>


                            <div class="form-group">
                                <select id="post_limit" class="form-control">
                                    <option value="5" <?php if ($limit == 5) echo 'selected' ?>>5</option>
                                    <option value="10" <?php if ($limit == 10) echo 'selected' ?>>10</option>
                                    <option value="50" <?php if ($limit == 50) echo 'selected' ?>>50</option>
                                </select>


                            </div>

                            <div class="form-group" style="float: right;">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="search" placeholder="Փնտրել"
                                           data-action="search" value="<?= $search ?>">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                                </div>


                            </div>
                            <div class="form-group">
                                <?php foreach ($user_langs as $key => $item) {
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


                        </div>



                    </div>
                </div>
                <table class="table table-bordered table-striped table-responsive" id="all_posts">

                    <tr>
                        <th width="2%"><input type="checkbox" data-action="check-all"></th>
                        <th width="2%">N</th>
                        <th><?= CDictionary::GetKey('title') ?></th>
                        <th>ID</th>
                        <th width="7%"><?= CDictionary::GetKey('actions') ?></th>
                        <th><?= CDictionary::GetKey('status') ?></th>

                    </tr>
                    <tbody>
                    <?php foreach ($pages['data'] as $key => $item) { ?>
                        <tr>
                            <td><input type="checkbox" data-action="post-checkbox" data-value="<?= $item['pid'] ?>"></td>
                            <td><?= ($page-1)*$limit+$key + 1 ?></td>
                            <td><span <?php if(!$item['is_translated']) echo "class='page_not_translate'" ?>><?= $item['page_title'] ?></span> <span><?php if(!$item['is_translated']) echo CDictionary::GetKey('not_translated',$lang) ?></span></td>


                            <td><?= $item['pid'] ?></td>
                            <td>
                                <a href="index.php?menu=page&submenu=add&edit_id=<?= $item['pid'] ?>"
                                   class="btn btn-default" target="_blank"><i class="fa fa-pencil-square-o"></i></a>

                                <button class="btn btn-default " data-action="delete-post-item" data-value="<?= $item['pid'] ?>"><i class="fa fa-times"></i></button>
                            </td>
                            <td>
                                <button class="btn btn-default post-active" <?php if($item['page_isactive']==1) echo 'style="display:none"'; ?> data-action="make_active" data-value="<?= $item['pid'] ?>"><?= CDictionary::GetKey('activate'); ?></button>
                                <button class="btn btn-default post-passive" <?php if($item['page_isactive']==0) echo 'style="display:none"'; ?>  data-action="make_passive" data-value="<?= $item['pid'] ?>"><?= CDictionary::GetKey('passive'); ?></button>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($pages['total_pages'] > 1) { ?>
            <div class="row">
                <div class="col-md-12">
                    <ul class="pagination pull-right" data-action="pagination">
                        <?php for ($i = 1; $i <= $pages['total_pages']; $i++) { ?>
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
            $('#all_posts').find('input[type=checkbox]').prop('checked',$(this).prop('checked'))
        })
        $('[data-action="post-checkbox"]').on('change', function () {
            $('#all_posts').find('[data-action=check-all]').prop('checked',false)
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
        $('[ data-action=delete-post-item]').on('click', function () {
            if(!confirm('Are you sure?')) return;
            $.ajax({
                type:"POST",
                url:'index.php?menu=page&submenu=action',
                data:{
                    action:'delete_page_item',
                    delete_id:$(this).data('value')
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
                type:"POST",
                url:'index.php?menu=page&submenu=action',
                data:{
                    action:'page_activate',
                    id:$(this).data('value')
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
                type:"POST",
                url:'index.php?menu=page&submenu=action',
                data:{
                    action:'page_passivate',
                    id:$(this).data('value')
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
            if(checked.length>0){
                if(!confirm('Are you sure?')) return;
            }else{
                return;
            }            var ret_data = [];
            $.each(checked, function (i,v) {
                ret_data.push($(v).data('value'))
            })
            $.ajax({
                type:"POST",
                url:'index.php?menu=page&submenu=action',
                data:{
                    action:'delete_page_item',
                    delete_id:ret_data
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

