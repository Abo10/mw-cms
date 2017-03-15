<?php
$cat_post = new CCategoryPost();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
}
$cats = $cat_post->GetDOM($lang);

?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('cat_post') ?>
                    <small><?= CDictionary::GetKey('all') ?></small>
                </h1>

            </div>
        </div>
        <!-- /.row -->
        <div class="form-inline post-button-panel">


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

            <div class="form-group pull-right">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="search" placeholder="Փնտրել"
                           data-action="search" value="">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                </div>


            </div>
        </div>

        <div id="post_cat_sortable">
            <table class="cat_table">

                <th class="cat_title2">title</th>
                <th class="cat_title3">images</th>
                <th class="cat_title2">Leguige</th>
                <th class="cat_title4">count</th>
                <th class="cat_title5">action</th>

            </table>
            <?= $cats ?>

        </div>

    </div>

    <script>
        function order_cat() {
            $('[data-action="post_cat_item"]').each(function (i, v) {
                $(this).find('[data-action="order_index"]').html(i + 1)
            })
        }
        $(function () {

            order_cat();

            $('[data-action=lang_button]').on('click', function () {
                replace_url_param('lang', $(this).data('value'))
            })
            $('#post_cat_sortable ul').sortable({
                update: function (event, ui) {
                    var ret_data = []
                    $('[data-action=post_cat_item]').each(function (i, v) {
                        ret_data.push($(v).data('value'))
                    })
                    $.ajax({
                        url: 'index.php?menu=post_category&submenu=action',
                        type: 'POST',
                        data: {
                            action: 'order_category',
                            data: ret_data
                        },
                        success: function (msg) {
                            console.log(msg)
                            order_cat();

                        }
                    })
                    console.log(ret_data);
                }
            });

            $('[data-action="search"]').on('keyup', function () {
                var val = $(this).val();

                console.log(val);
                var regex = new RegExp(val, 'i');
                if(val) {
                    $('[data-is_translated]').each(function (i, v) {
                        if ($(this).html().match(regex)) {
                            console.log($(this).html());
                            $(this).addClass('cat_title_search_active')
                            $(this).closest('[data-action="post_cat_item"]').addClass('cat_row_search_active')
                        } else {
                            $(this).removeClass('cat_title_search_active')
                            $(this).closest('[data-action="post_cat_item"]').removeClass('cat_row_search_active')

                        }
                    })
                }else{
                    $('[data-is_translated]').each(function (i, v) {
                        $(this).removeClass('cat_title_search_active')
                        $(this).closest('[data-action="post_cat_item"]').removeClass('cat_row_search_active')


                    })
                }
            })
            $('[data-action="delete"]').on('click', function () {
                if(!confirm('Are you sure?')) return;
                $.ajax({
                    type:"POST",
                    url:'index.php?menu=post_category&submenu=action',
                    data:{
                        action:'delete_cat_item',
                        delete_id:$(this).closest('[data-action="post_cat_item"]').data('value')
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
                    url:'index.php?menu=post_category&submenu=action',
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
                    url:'index.php?menu=post_category&submenu=action',
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
        })

    </script>
    <!-- /#page-wrapper -->