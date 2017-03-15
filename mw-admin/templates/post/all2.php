<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?= CDictionary::GetKey('post') ?>
                    <small><?= CDictionary::GetKey('all') ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> <?= CDictionary::GetKey('post') ?>
                    </li>
                </ol>
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
        <?php
        $post_obj = new CPost();
        $post = $post_obj->GetList_Title();

        foreach ($post as $key=> $item) {
            ?>
            <div class="row">
                <div class="col-md-3"><?= $item[$current_lang]['post_title'] ?></div>

                <div class="col-md-3"><a href="index.php?menu=post&submenu=add&edit_id=<?= $key ?>"><?= CDictionary::GetKey('edit') ?></a></div>

            </div>

        <?php } ?>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->