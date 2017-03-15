<div>
    <?php if (CMessage::hasFlash('err_msg')) { ?>
        <?= CMessage::getFlash('err_msg'); ?>
    <?php } ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <form action="?menu=action" method="post" class="form-horizontal">
            <input type="hidden" name="action" value="step1">
            <div class="form-group">
                <label for="inputEmail5" class="col-sm-4 control-label"><?= CDictionary::GetKey('db_host') ?></label>
                <div class="col-sm-8">
                    <input type="text" name="db_host" class="form-control" id="inputEmail5">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label"><?= CDictionary::GetKey('db_name') ?></label>
                <div class="col-sm-8">
                    <input type="text" name="db_name" class="form-control" id="inputEmail3">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail4" class="col-sm-4 control-label"><?= CDictionary::GetKey('db_user') ?></label>
                <div class="col-sm-8">
                    <input type="text" name="db_user" class="form-control" id="inputEmail4">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail6" class="col-sm-4 control-label"><?= CDictionary::GetKey('db_pass') ?></label>
                <div class="col-sm-8">
                    <input type="text" name="db_pass" class="form-control" id="inputEmail6">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default pull-right"><?= CDictionary::GetKey('next') ?></button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-6">
        text
    </div>
</div>