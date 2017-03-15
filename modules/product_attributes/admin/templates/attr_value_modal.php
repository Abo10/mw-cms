<div class="modal fade" id="add_attr_value_modal" tabindex="+1" role="dialog" aria-labelledby="exampleModalLabel" >
    <input type="hidden" data-id>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"> <?= CDictionary::GetKey('add_attr_value') ?></h4>
                <div class="attr_val_required_lags"><?= CDictionary::GetKey('attr_val_required_lags') ?></h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10">
                        <div>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs post_leguig" role="tablist">
                                <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                                    if ($key == 0) {
                                        $active_class = 'active';
                                    } else {
                                        $active_class = '';
                                    }
                                    ?>
                                    <li role="presentation" class="<?= $active_class ?>">
                                        <a href="#<?= $lang['key'] ?>_tabnav_sec" aria-controls="home"
                                           role="tab"
                                           data-toggle="tab"><?= $lang['title'] ?></a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <!-- Tab panes -->
                            <form action="" id="attr_val_modal_form">
                                <input type="hidden" name="action" value="edit_product_attribute_value_modal">
                                <input type="hidden" name="attr_group" value="4">
                            <div class="tab-content">
                                <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                                    if ($key == 0) {
                                        $active_class = 'active';
                                    } else {
                                        $active_class = '';
                                    }
                                    ?>
                                    <div role="tabpanel" class="tab-pane <?= $active_class ?>"
                                         id="<?= $lang['key'] ?>_tabnav_sec"
                                         data-lang="<?= $lang['key'] ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-horizontal" data-action="attr_group_sec">


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <button type="button"
                                        class="btn btn-success pull-right" data-action="submit-form"><?= CDictionary::GetKey('add') ?></button>
                            </div>
                            </form>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>
