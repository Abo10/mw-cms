<?php
if (isset($_POST['edit_id']) && is_numeric($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $action = 'edit';
    CModule::LinkModule('addressing');
    $res = CAddressing::GetCityAllLangs($edit_id);
} else {
    $action = 'add';
    $edit_id = null;
    $res = [];
}
CModule::LinkModule('addressing');
$cities = CAddressing::GetCitiesAllLangs($state);
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
        <form action="index.php?menu=post&submenu=action" method="POST" id="add_city_form">
            <?php if (!empty($edit_id)) { ?>
                <input type="hidden" name="action" value="edit_city">
                <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
            <?php } else { ?>
                <input type="hidden" name="action" value="add_city">
            <?php } ?>

            <input type="hidden" name="country" value="<?= $country ?>">
            <input type="hidden" name="state" value="<?= $state ?>">
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
                                   placeholder="<?= CDictionary::GetKey('city_name') ?>" value="<?php if($res) echo $res['text'][$lang['key']] ?>">
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
            <button type="button" class="btn btn-success" id="add_city"><?= CDictionary::GetKey($action) ?></button>
        </form>
    </div>
</div>

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
            <?php
            	if(!is_array($cities))$cities = [];
            ?>
            <?php foreach ($cities as $key => $city) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach (CLanguage::get_lang_keys_user() as $item) { ?>
                        <td><?= $city['text'][$item] ?></td>
                    <?php } ?>
                    <td><?= $city['addr_time'] ?></td>
                    <td><?= $city['addr_price'] ?></td>
                    <td><?= $city['addr_tax'] ?></td>
                    <td><?= $city['addr_tel_code'] ?></td>
                    <td><?= $city['addr_zip'] ?></td>
                    <td><?= $city['addr_order'] ?></td>
                    <td>
                        <button class="btn btn-default" data-action="edit-city" data-value="<?= $key ?>"><i
                                class="fa fa-pencil-square-o"></i></button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<script>
    $('#add_city').on('click', function () {
        page_prop.edit_id = null;
        var f_data = $('#add_city_form').serialize();
        $.ajax({
            url: 'index.php?module=addressing&submenu=action',
            type: 'POST',
            data: f_data,
            success: function (msg) {
                $('#state_select').trigger('change')
                if (msg == 1) {

                } else {
                    alert(msg)
                }
            }
        })
    })
</script>