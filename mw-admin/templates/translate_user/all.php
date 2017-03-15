<?php
CDictionaryUser::Initialise();
$translates = CDictionaryUser::GetAllDict();
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('translate') ?>
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
                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>Code</th>
                        <?php foreach ($user_langs as $item) { ?>
                            <th><?= $item['title'] ?></th>
                        <?php } ?>
                        <th></th>
                    </tr>

                    <tr>
                        <td><input type="text" class="form-control" data-lang-key></td>
                        <?php foreach ($user_langs as $item) { ?>
                            <td><input  type="text" class="form-control" data-lang="<?= $item['key'] ?>" ></td>
                        <?php } ?>
                        <td>
                            <button class="btn btn-success" data-action="add">+</button>
                        </td>

                    </tr>

                </table>

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>Code</th>
                        <?php foreach ($user_langs as $item) { ?>
                            <th><?= $item['title'] ?></th>
                        <?php } ?>
                        <th></th>
                    </tr>
                    <?php foreach ($translates as $key => $item2) { ?>

                        <tr>
                            <td><span data-lang-key="<?= $key ?>"><?= $key ?></span></td>
                            <?php foreach ($user_langs as $item) { ?>
                                <td><input type="text" class="form-control" data-lang="<?= $item['key'] ?>"
                                           value="<?php if(isset($item2[$item['key']])) echo $item2[$item['key']]; ?>" readonly></td>
                            <?php } ?>
                            <td>
                                <button class="btn btn-primary" data-action="save" style="display: none;"><i
                                        class="fa fa-check"></i></button>
                                <button class="btn btn-success" data-action="activate"><i class="fa fa-pencil"></i>
                                </button>
                            </td>

                        </tr>
                    <?php } ?>

                </table>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    <script>
        $('[data-action=activate]').on('click', function () {
            $(this).hide().closest('tr').addClass('success').find('input').prop('readonly', false);
            $(this).closest('td').find('[data-action=save]').show();
        })
        $('[data-action=save]').on('click', function () {
            var inputs = $(this).hide().closest('tr').removeClass('success').find('input');
            var key_tr =  $(this).closest('tr').find('[data-lang-key]').data('lang-key')
            var post_data = {key:key_tr}
            var inp_data = {};
            $(inputs).each(function (i,v) {
                var key = $(v).data('lang');
                var val = $(v).val();
                inp_data[key] = val
            });
            post_data.values = inp_data;
            $.ajax({
                type:"post",
                url: 'index.php?menu=translate_user&submenu=action',
                data:{
                    data:post_data,
                    action:'edit_translate'
                },
                success: function (msg) {
                    console.log(msg)
                    inputs.prop('readonly', true);
                    $(inputs).closest('tr').find('[data-action=activate]').show();

                }
            })
            console.log(post_data);
//            $(this).closest('td').find('[data-action=activate]').show();
        })

        $('[data-action=add]').on('click', function () {
            var inputs = $(this).closest('tr').removeClass('success').find('[data-lang]');
            var key_tr =  $(this).closest('tr').find('[data-lang-key]').val()
            var post_data = {key:key_tr}
            var inp_data = {};
            $(inputs).each(function (i,v) {
                var key = $(v).data('lang');
                var val = $(v).val();
                inp_data[key] = val
            });
            post_data.values = inp_data;
            $.ajax({
                type:"post",
                url: 'index.php?menu=translate_user&submenu=action',
                data:{
                    data:post_data,
                    action:'add_translate'
                },
                success: function (msg) {
                    location.reload();
                    return;

                }
            })
            console.log(post_data);
//            $(this).closest('td').find('[data-action=activate]').show();
        })


    </script>
</div>
<!-- /#page-wrapper -->