<?php
if (isset($_POST['edit_id']) && is_numeric($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $action = 'edit';
    CModule::LinkModule('addressing');
    $res = CAddressing::GetCountryAllLangs($edit_id);
} else {
    $action = 'add';
    $edit_id = null;
    $res = [];
}
?>
<div class="row">
    <div class="col-md-10">
        <ul class="nav nav-tabs post_leguig" role="tablist">
            <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                if ($key == 0) {
                    $active_class = 'active';
                } else {
                    $active_class = '';
                }
                ?>
                <li role="presentation" class="<?= $active_class ?>">
                    <a href="#<?= $lang['key'] ?>_tabnav" aria-controls="home" role="tab"
                       data-toggle="tab"><?= $lang['title'] ?></a>
                </li>
            <?php } ?>
        </ul>
        <form action="index.php?menu=post&submenu=action" method="POST" id="add_country_form">
            <?php if (!empty($edit_id)) { ?>
                <input type="hidden" name="action" value="edit_country">
                <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
            <?php } else { ?>
                <input type="hidden" name="action" value="add_country">
            <?php } ?>

            <div class="form-inline" style="display: none">
                <input type="text" name="shortkey" class="form-control" placeholder="shortkey">
            </div>
            <div class="tab-content">
                <?php $page_prop_counter = 0; ?>
                <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                    if ($key == 0) {
                        $active_class = 'active';
                    } else {
                        $active_class = '';
                    }
                    ?>
                    <div role="tabpanel" class="tab-pane <?= $active_class ?>" id="<?= $lang['key'] ?>_tabnav">
                        <div class="form-inline address-name-input">
                            <input type="text" name="lang[<?= $lang['key'] ?>]" class="form-control"
                                   placeholder="<?= CDictionary::GetKey('country_name') ?>" value="<?php if ($res) echo $res['text'][$lang['key']] ?>">
                        </div>
                    </div>
                <?php } ?>
                <div class="addressing-label-2">
                    <?= CDictionary::GetKey('addressing-label-2');'' ?>
                </div>
                <div class="form-inline address-details-group">
                    <input type="text" class="form-control" name="addr_time" value="<?php if($res) echo $res['addr_time']; ?>" placeholder="<?= CDictionary::GetKey('c_shipping_time') ?>">
                    <input type="text" class="form-control" name="addr_price" value="<?php if($res) echo $res['addr_price']; ?>" placeholder="<?= CDictionary::GetKey('c_shipping_price') ?>">
                    <input type="text" class="form-control" name="addr_tax" value="<?php if($res) echo $res['addr_tax']; ?>" placeholder="<?= CDictionary::GetKey('c_shipping_tax') ?>">
                    <input type="text" class="form-control" name="addr_tel_code" value="<?php if($res) echo $res['addr_tel_code']; ?>" placeholder="<?= CDictionary::GetKey('c_tel_code') ?>">
                    <input type="text" class="form-control" name="addr_zip" value="<?php if($res) echo $res['addr_zip']; ?>" placeholder="<?= CDictionary::GetKey('c_zip') ?>">
                    <input type="text" class="form-control" name="addr_order" value="<?php if($res) echo $res['addr_order']; ?>" placeholder="<?= CDictionary::GetKey('order') ?>">
                </div>


            </div>
            <button type="button" class="btn btn-success" id="add_country"><?= CDictionary::GetKey($action) ?></button>
        </form>
    </div>
</div>
<?php
CModule::LinkModule('addressing');
$countries = CAddressing::GetCountriesAllLangs();
?>


<div class="row">
    <div class="col-md-10">
        <table class="table table-bordered table-striped table-responsive">
            <tr>
                <th>key</th>
                <?php foreach (CLanguage::get_langsUser() as $item) { ?>
                    <th><?= $item['title'] ?></th>
                <?php } ?>

                <th><?= CDictionary::GetKey('c_shipping_time') ?></th>
                <th><?= CDictionary::GetKey('c_shipping_price') ?></th>
                <th><?= CDictionary::GetKey('c_shipping_tax') ?></th>
                <th><?= CDictionary::GetKey('c_tel_code') ?></th>
                <th><?= CDictionary::GetKey('c_zip') ?></th>
                <th><?= CDictionary::GetKey('order') ?></th>

                <th></th>
            </tr>
            <?php foreach ($countries as $key => $country) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach (CLanguage::get_lang_keys_user() as $item) { ?>
                        <td><?= $country['text'][$item] ?></td>
                    <?php } ?>
                    <td><?= $country['addr_time'] ?></td>
                    <td><?= $country['addr_price'] ?></td>
                    <td><?= $country['addr_tax'] ?></td>
                    <td><?= $country['addr_tel_code'] ?></td>
                    <td><?= $country['addr_zip'] ?></td>
                    <td><?= $country['addr_order'] ?></td>
                    <td>
                        <button class="btn btn-default" data-action="edit-country" data-value="<?= $key ?>"><i
                                class="fa fa-pencil-square-o"></i></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<script>
    $(function () {
        $('#add_country').on('click', function () {
            page_prop.edit_id = null;
            var f_data = $('#add_country_form').serialize();
            $.ajax({
                url: 'index.php?module=addressing&submenu=action',
                type: 'POST',
                data: f_data,
                success: function (msg) {
                    if (msg == 1) {
                        $('#main_select').trigger('change')
                    } else {
                        alert(msg)
                    }
                    console.log(msg);
                }
            })
        })

    })
</script>