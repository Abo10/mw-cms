<?php
$attr_edit_id = isset($_GET['attr_edit_id']) ? $_GET['attr_edit_id'] : '';
$all_attrs_obj = CModule::LoadModule('attributika');
$lang2 = (isset($_GET['lang'])) ? $_GET['lang'] : CLanguage::getDefaultUser();
$all_attrs = $all_attrs_obj->GetAttribute($lang2, null, ['product_category']);
if (is_numeric($attr_edit_id)) {
    $attr_ = CModule::LoadModule('product');
    $attr_res = $all_attrs_obj->GetAttributeAllLangs($attr_edit_id, ['product_category']);
//    var_dump($attr_res);
//    die;
    $attr_action = CDictionary::GetKey('edit');
} else {
    $attr_action = CDictionary::GetKey('add');
}
//var_dump($all_attrs);
?>

<?php echo CModule::LoadCSS('product_attributes', 'style'); ?>
<div id="page-wrapper" style="    background: #FDFDFD;">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">
                    <?= CDictionary::GetKey('post_ad_attr') ?>

                </h1>

            </div>
        </div>

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
            <div class="col-md-6 attr_container">
                <div class="row at777">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs post_leguig 555" role="tablist">
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

                    <!-- Tab panes -->
					 
                    <form action="index.php?module=product_attributes&submenu=action" method="POST"
                          id="product_attr_form">
                 <h3><?= CDictionary::GetKey('attname1') ?></h3>
                        <?php if (!empty($attr_edit_id)) { ?>
                            <input type="hidden" name="action" value="edit_product_attribute">
                            <input type="hidden" name="edit_id" value="<?= $attr_edit_id; ?>">
                        <?php } else { ?>
                            <input type="hidden" name="action" value="add_product_attribute">
                        <?php } ?>

                        <div class="tab-content">
                           
                            <div class="alert alert-warning" data-action="attr-warning-msg" style="display: none">
                                <strong data-action="msg-text"></strong>
                            </div>

                            <?php foreach (CLanguage::get_langsUser() as $key => $lang) {
                                if ($key == 0) {
                                    $active_class = 'active';
                                } else {
                                    $active_class = '';
                                }
                                if ($attr_edit_id) {
                                    $attr_name = $attr_res[$lang['key']][$attr_edit_id]['attr_name'];
                                    $attr_order = $attr_res[$lang['key']][$attr_edit_id]['template_order'];
                                    if (isset($attr_res[$lang['key']][$attr_edit_id]['units'])) {
                                        $attr_units = $attr_res[$lang['key']][$attr_edit_id]['units'];
                                    } else {
                                        $attr_units = [];
                                    }

                                    $attr_categories = $attr_res[$lang['key']][$attr_edit_id]['product_category'];
                                } else {
                                    $attr_name = '';
                                    $attr_order = '';
                                    $attr_units = [];
                                    $attr_categories = [];
                                }
                                ?>
                                <div role="tabpanel" class="tab-pane <?= $active_class ?>"
                                     id="<?= $lang['key'] ?>_tabnav">
                                    <div class="row">
                                        <div class="master78">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="master88"
                                                           for="email"><?= CDictionary::GetKey('attname') ?></label>
                                                    <div class="col-sm-9">
                                                        <input name="lang[<?= $lang['key'] ?>][attr_name]" type="text"
                                                               class="form-control" id="email"
                                                               placeholder="" data-required="name"
                                                               value="<?= $attr_name ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="master88"
                                                           for="pwd"><?= CDictionary::GetKey('cats') ?></label>
                                                    <div class="col-sm-9">
                                                        <div class="row">
                                                            <div class="master99">
                                                                <?= CDictionary::GetKey('all_categories') ?>
                                                                <input type="checkbox" data-action="check_all_cats"
                                                                       name="lang[obj_all]">
                                                                <input type="hidden" name="lang[obj_type]"
                                                                       value="product_category">
                                                            </div>
                                                            <div class="master991">
                                                                <select <?php if ($key == 0) {
                                                                    echo 'name="lang[obj_ids][]"';
                                                                } ?> id="" class="categories-select"
                                                                     data-action="categories-select"
                                                                     data-placeholder="<?= CDictionary::GetKey('select') ?>"
                                                                     data-required="category"
                                                                     multiple>
                                                                    <?php $cat_obj = CModule::LoadModule('product_category'); ?>
                                                                    <?php foreach ($cat_obj->GetAllCats() as $value) { ?>
                                                                        <option
                                                                            value="<?= $value['value']['cid'] ?>" <?php if (in_array($value['value']['cid'], $attr_categories)) echo 'selected' ?>><?= $cat_obj->GetTree($value['level']) ?><?= $value['value']['category_title'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="master88"><?= CDictionary::GetKey('order') ?></label>
                                                    <div class="col-sm-9">
                                                        <input <?php if ($key == 0) {
                                                            echo "name=lang[order]";
                                                        } ?> type="number" class="form-control" data-action="order"
                                                             placeholder="<?= CDictionary::GetKey('order') ?>"
                                                             value="<?= $attr_order ?>">
                                                    </div>
                                                </div>
                                                <button type="submit"
                                                        class="btn btn-success pull-right mw99"><?= $attr_action ?></button>
                                            </div>
                                        </div>
                                        <div class="master73" data-action="unit-group" data-lang="<?= $lang['key'] ?>">
                                            <span><?= CDictionary::GetKey('units') ?></span>
                                            <div class="form-horizontal mw45" data-action="unit-template">
                                                <div class="form-group">

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control product-order-input"
                                                               placeholder=""
                                                               data-required="unit"
                                                               name="units[<?= $lang['key'] ?>][name][]">
                                                        <input type="hidden" name="units[<?= $lang['key'] ?>][group][]"
                                                               value="0">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control product-order-input"
                                                               placeholder="<?= CDictionary::GetKey('order') ?>"
                                                               name="units[<?= $lang['key'] ?>][order][]">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-secondary" type="button"
                                                                data-action="add-unit">+
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php foreach ($attr_units as $unit_group => $attr_item) { ?>
                                                <div class="form-horizontal" data-action="unit-template">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <input type="text"
                                                                   class="form-control product-order-input"
                                                                   placeholder="" value="<?= $attr_item['unit'] ?>"
                                                                   data-required="unit"
                                                                   name="units[<?= $lang['key'] ?>][name][]">
                                                            <input type="hidden"
                                                                   name="units[<?= $lang['key'] ?>][group][]"
                                                                   value="<?= $unit_group ?>">

                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number"
                                                                   class="form-control product-order-input"
                                                                   placeholder="<?= CDictionary::GetKey('order') ?>"
                                                                   name="units[<?= $lang['key'] ?>][order][]">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button class="btn btn-danger" type="button"
                                                                    data-action="delete-unit">-
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            <div class="col-md-2"></div>

                        </div>
                    </form>

                </div>

            </div>
            <div class="col-md-6 attr_val_container at776">
                <div>
                    <h3><?= CDictionary::GetKey('add_attr_value') ?></h3>
                    <div class="attr_val_required_lags2"><?= CDictionary::GetKey('attr_val_required_lags') ?></div>

                    <div class="form-horizontal attr_val_container_1">
                        <form action="index.php?module=product_attributes&submenu=action" method="POST"
                              id="product_attr_val_form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" style="    font-size: 12px;"
                                               for="email"><?= CDictionary::GetKey('attname') ?></label>
                                        <div class="col-sm-9">
                                            <select name="attr_group" id="attr_cat_select" class="form-control"
                                                    data-action="attr-name-select">
                                                <option value=""><?= CDictionary::GetKey('select') ?></option>
                                                <?php foreach ($all_attrs as $index => $all_attr) { ?>
                                                    <?php
                                                    if (isset($all_attr['product_category']) && is_array($all_attr['product_category'])) {
                                                        $cat_text = ' (';
                                                        foreach ($all_attr['product_category'] as $item) {
                                                            $cat_text .= $item['category_title'] . ', ';
                                                        }
                                                        $cat_text = substr_replace($cat_text, '', strlen($cat_text) - 2);
                                                        $cat_text .= ')';
                                                    } else {
                                                        $cat_text = '';
                                                    }
                                                    ?>
                                                    <option
                                                        value="<?= $index ?>"><?= $all_attr['attr_name'] . $cat_text ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" data-action="attr-val-full-block" style="display: none">
                                <div class="col-md-12">
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
                                        <?php if (!empty($edit_id)) { ?>
                                            <input type="hidden" name="action" value="edit_product_attribute_value">
                                            <input type="hidden" name="edit_id" value="<?= $edit_id; ?>">
                                        <?php } else { ?>
                                            <input type="hidden" name="action" value="add_product_attribute_value">
                                        <?php } ?>

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
                                                        <div class="col-md-12 fg55">
                                                            <div class="form-horizontal" data-action="attr_group_sec">


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                            <button type="submit"
                                                    class="btn btn-success pull-right"
                                                    data-action="attr_val_add_submit"><?= CDictionary::GetKey('add') ?>1</button>
                                            <button type="submit"
                                                    class="btn btn-success pull-right" style="display: none"
                                                    data-action="attr_val_edit_submit"><?= CDictionary::GetKey('edit') ?>2</button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 fg44">
                <table class="table table-striped table-hover table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= CDictionary::GetKey('name') ?></th>
                        <th><?= CDictionary::GetKey('add_attr_value') ?></th>
                        <th><?= CDictionary::GetKey('order') ?></th>
                        <th><?= CDictionary::GetKey('units') ?></th>
                        <th><?= CDictionary::GetKey('cat') ?></th>
                        <th><?= CDictionary::GetKey('actiond') ?></th>
                        <th><?= CDictionary::GetKey('status') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($all_attrs as $index => $item) {
                        ?>
                        <tr>
                            <td><?= $index ?></td>
                            <td>
                                <a href="?module=product_attributes&submenu=add&attr_edit_id=<?= $index ?>"><?= $item['attr_name'] ?></a>
                            </td>
                            <!--                        <td>--><?php //var_dump($item[$lang2]['units']); ?><!--</td>-->
                            <td>
                                <?php $tmp_unit_arr = []; ?>
                                <?php
                                foreach ($item['values'] as $i => $item2) {
                                    $unit_name = isset($item['units'][$item2['unit_group']]['unit']) ? $item['units'][$item2['unit_group']]['unit'] : '';
                                    $tmp_unit_arr[] = $item2['unit_value'] . ' ' . $unit_name;
                                }
                                echo implode(', ', $tmp_unit_arr);
                                ?>
                            </td>
                            <td><?= $item['template_order'] ?></td>
                            <td>
                                <?php $tmp_arr = []; ?>
                                <?php
                                foreach ($item['units'] as $i => $item2) {
                                    $tmp_arr[] = $item2['unit'];
//                                    $tmp_cats_arr[] = $item2['ca'];
                                }

                                echo implode(', ', $tmp_arr);
                                ?>
                            </td>
                            <td>
                                <?php $tmp_unit_arr = []; ?>
                                <?php
                                foreach ($item['product_category'] as $i => $item2) {
//                                    $unit_name = isset($item['units'][$item2['unit_group']]['unit'])?$item['units'][$item2['unit_group']]['unit']:'';
                                    if (is_numeric($i)) {

                                        $tmp_unit_arr[] = $item2['category_title'];
                                    }
                                }
                                echo implode(', ', $tmp_unit_arr);
                                ?>
                            </td>
                            <td>
                                <a href="?module=product_attributes&submenu=add&attr_edit_id=<?= $index ?>"
                                   class="btn btn-default"><i class="fa fa-pencil-square-o"></i></a>

                                <button class="btn btn-default " data-action="delete-attr-item"
                                        data-value="<?= $index ?>"><i class="fa fa-times"></i></button>
                            </td>
                            <td>
                                <button
                                    class="btn btn-default post-active" <?php if ($item['is_active'] == 1) echo 'style="display:none"'; ?>
                                    data-action="make_active"
                                    data-value="<?= $index ?>"><?= CDictionary::GetKey('activate'); ?></button>
                                <button
                                    class="btn btn-default post-passive" <?php if ($item['is_active'] == 0) echo 'style="display:none"'; ?>
                                    data-action="make_passive"
                                    data-value="<?= $index ?>"><?= CDictionary::GetKey('passive'); ?></button>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"> <?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none">
    <div class="col-md-1 gallery_item" id="gallery_item_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class=" gallery_item" id="gallery_single_item_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
        <input type="text" class="form-control">
    </div>
    <div class="gallery_item" id="attr_val_img_template">
        <span data-action="gal-product-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
    </div>


    <div class="form-horizontal" id="unit_template" data-action="unit-template">
        <div class="form-group">
            <div class="col-md-4">
                <input type="text" class="form-control product-order-input" placeholder="" data-required="unit">
                <input type="hidden" class="form-control product-order-input" placeholder="" value="0">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control product-order-input"
                       placeholder="<?= CDictionary::GetKey('order') ?>">
            </div>
            <div class="col-md-4">
                <button class="btn btn-danger" type="button" data-action="delete-unit">-</button>

            </div>
        </div>
    </div>

    <div class="form-group" data-action="attr_value_item_sec" id="attr_value_item_sec">
        <label class="control-label col-sm-1"
               for="email"><?= CDictionary::GetKey('value') ?></label>
        <div class="col-sm-2">
            <input type="hidden" data-action="attach-hidden">
            <input type="text" class="form-control"
                   placeholder="">
        </div>
        <div class="col-sm-3">
            <select name="" class="form-control">
                <option value=""><?= CDictionary::GetKey('select') ?></option>
            </select>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-secondary" type="button"
                    data-action="attr_val_img_sec"><i class="fa fa-picture-o"></i>
            </button>
        </div>
        <div class="col-sm-1">
            <div data-action="attr_val_img_container">

            </div>
        </div>
        <div class=" col-sm-1">
            <button class="btn btn-danger" type="button" data-action="delete_attr_value_item_sec">-
            </button>
        </div>
    </div>

    <div class="form-group"
         data-action="attr_value_item_sec" id="attr_value_item_sec_first_row">
        <label class="control-label col-sm-1"
               for="email"><?= CDictionary::GetKey('value') ?></label>
        <div class="col-sm-2">
            <input type="hidden" data-action="attach-hidden">
            <input type="text" class="form-control"
                   placeholder="">
        </div>
        <div class="col-sm-3">
            <select name="" class="form-control">
                <option value=""><?= CDictionary::GetKey('select') ?></option>
            </select>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-secondary" type="button"
                    data-action="attr_val_img_sec"><i class="fa fa-picture-o"></i>
            </button>

        </div>
        <div class="col-sm-1">
            <div data-action="attr_val_img_container">

            </div>
        </div>
        <div class=" col-sm-1">
            <button class="btn btn-success" type="button"
                    data-action="add_attr_value_item_sec">+
            </button>
        </div>
    </div>

</div>
<script>
    $(document).on('click', '[data-action="gal-product-delete"]', function () {
        $(this).closest('[data-action="attr_value_item_sec"]').find('[data-action="attach-hidden"]').val('');
        $(this).closest('.gallery_item').remove();
    })

    $('[data-action=categories-select]').on('change', function (e) {
        e.preventDefault();

        var val = $(this).val();
        $('[data-action=categories-select]').val(val)
        $('.categories-select').trigger("chosen:updated")
    })

    $('.categories-select').chosen({
        width: '100%'
    })
    $('[data-action="attr-name-select"]').chosen({
        width: '100%'
    })
    $('[data-action="check_all_cats"]').on('change', function () {
        $('[data-action="check_all_cats"]').prop('checked', $(this).prop('checked'));
        $('[data-action=categories-select]').prop('disabled', $(this).prop('checked'));
        $('.categories-select').trigger("chosen:updated");

    })
    $(document).on('click', '[data-action="delete-unit"]', function () {
        if (!confirm('are you sure?')) {
            return;
        }
        var _index = $(this).closest('[data-action="unit-template"]').index();
        $('.tab-content [data-action="unit-template"]:nth-child(' + (_index + 1) + ')').remove();
        //$(this).closest('[data-action="unit-template"]').remove();
    })

    $(document).on('change', '[data-action="attr_value_item_sec"] select', function () {
        var _val = $(this).val();
        var _index = $(this).closest('[data-action="attr_value_item_sec"]').index();
        $('.tab-content [data-action="attr_value_item_sec"]:nth-child(' + (_index + 1) + ')').find('select').val(_val);
        //$(this).closest('[data-action="unit-template"]').remove();
    })
    $('[data-action="add-unit"]').on('click', function () {

        $('[data-action=unit-group]').each(function () {
            var lang = $(this).closest('[data-lang]').data('lang');
            var elem_clone = $('#unit_template').clone().removeAttr('id');
            $(elem_clone).find('input[type=text]').attr('name', 'units[' + lang + '][name][]');
            $(elem_clone).find('input[type=number]').attr('name', 'units[' + lang + '][order][]');
            $(elem_clone).find('input[type=hidden]').attr('name', 'units[' + lang + '][group][]');
            $(this).append(elem_clone);
        });
    })
    $('[data-action="order"]').on('change', function () {
        $('[data-action="order"]').val($(this).val())
    })


    $(document).on('click', '[data-action="add_attr_value_item_sec"]', function () {
        var uniqiu_index = guid();

        $('[data-action="attr_group_sec"]').each(function (i, v) {
            var lang = $(v).closest('[data-lang]').data('lang');
            var elem_clone = $('#attr_value_item_sec').clone().removeAttr('id');
            var options_str = '';
            if (page_prop.unit_values.length !== 0) {

                $.each(page_prop.unit_values[lang], function (i2, v2) {
                    options_str = options_str + '<option value="' + i2 + '">' + v2.unit + '</option>';
                })
            }
            $(elem_clone).find('input[type=text]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][value]');
            $(elem_clone).find('[data-action="attach-hidden"]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][attach_id]');
            $(elem_clone).find('select').attr('name', 'values[' + lang + '][' + uniqiu_index + '][unit]');
            $(elem_clone).find('select').append(options_str);
            $(v).append(elem_clone);
        })
    })


    $('#attr_cat_select').on('change', function () {
        var id = $(this).val();
        var button = $(this);
        if(!id){
            $('[data-action="attr-val-full-block"]').hide();
            return;
        }else{
            $('[data-action="attr-val-full-block"]').show();

        }
        $.ajax({
            url: 'index.php?module=product_attributes&submenu=action',
            type: 'post',
            data: {
                action: 'get_attr_values_units',
                id: id
            },
            success: function (msg) {
                console.log(msg);
                page_prop.unit_values = JSON.parse(msg).units;
                page_prop.attr_values = JSON.parse(msg).values;
                console.log(page_prop)
                var uniqiu_index = guid();

                $('[data-action="attr_group_sec"]').each(function (i, v) {
                    var lang = $(v).closest('[data-lang]').data('lang');
                    var elem_clone = $('#attr_value_item_sec_first_row').clone().removeAttr('id');
                    var options_str = '';
                    if (page_prop.unit_values.length !== 0) {

                        $.each(page_prop.unit_values[lang], function (i2, v2) {
                            options_str = options_str + '<option value="' + i2 + '">' + v2.unit + '</option>';
                        })
                    }
                    $(elem_clone).find('input[type=text]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][value]');
                    $(elem_clone).find('input[type=text]').prop('required', false);
                    $(elem_clone).find('[data-action="attach-hidden"]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][attach_id]');

                    if (options_str) {
                        $(elem_clone).find('select').attr('name', 'values[' + lang + '][' + uniqiu_index + '][unit]');
                        $(elem_clone).find('select').append(options_str);
                    } else {
                        $(elem_clone).find('select').parent().remove()
                    }


                    $(v).html(elem_clone);
                })

                if (page_prop.attr_values.length !== 0) {
                    $('[data-action="attr_val_add_submit"]').hide();
                    $('[data-action="attr_val_edit_submit"]').show();
                    $(button).closest('form').find('input[name=action]').val('edit_product_attribute_value');
                    $('[data-action="attr_group_sec"]').each(function (i, v) {
                        var lang = $(v).closest('[data-lang]').data('lang');

                        $.each(page_prop.attr_values[lang], function (i2, v2) {
                            var elem_clone = $('#attr_value_item_sec').clone().removeAttr('id');
                            var options_str = '';
                            if (v2.unit_image) {
                                var tmp_img = $('#attr_val_img_template').clone().removeAttr('id');
                                var image_src = v2.unit_image;
                                var image_id = v2.unit_image_id;
                                $(elem_clone).find('[data-action="attach-hidden"]').val(image_id)
                                $(tmp_img).find('img').attr('src', image_src);

                                $(elem_clone).find('[data-action="attr_val_img_container"]').html(tmp_img);
                            }
                            if (page_prop.unit_values.length !== 0) {
                                $.each(page_prop.unit_values[lang], function (i3, v3) {
                                    options_str = options_str + '<option value="' + i3 + '">' + v3.unit + '</option>';
                                })
                            }
                            var uniqiu_index = i2;
                            $(elem_clone).find('input[type=text]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][value]');
                            $(elem_clone).find('[data-action="attach-hidden"]').attr('name', 'values[' + lang + '][' + uniqiu_index + '][attach_id]');
                            $(elem_clone).find('select').attr('name', 'values[' + lang + '][' + uniqiu_index + '][unit]');
                            if (options_str) {
                                $(elem_clone).find('select').append(options_str);
                                $(elem_clone).find('select').parent().show();
                                if (v2.unit_group) {
                                    $(elem_clone).find('select').val(v2.unit_group);
                                }
                            } else {
                                $(elem_clone).find('select').parent().remove()
                                $('#attr_value_item_sec').find('select').parent().hide()
                            }
                            $(elem_clone).find('input[type=text]').val(v2.unit_value);
                            $(v).append(elem_clone);
                        })
                    })
                } else {
                    $(button).closest('form').find('input[name=action]').val('add_product_attribute_value');
                    $('[data-action="attr_val_add_submit"]').show()
                    $('[data-action="attr_val_edit_submit"]').hide()
                }

            }
        })
        //$('[data-action="attr_group_sec"]').html($('#attr_value_item_sec_first_row').clone().removeAttr('id'));
    })

    $(document).on('click', '[data-action="delete_attr_value_item_sec"]', function () {
        if (!confirm('are you sure?')) {
            return;
        }
        var _index = $(this).closest('[data-action="attr_value_item_sec"]').index();
        console.log(_index);
        $('.tab-content [data-action="attr_value_item_sec"]:nth-child(' + (_index + 1) + ')').remove();
        //$(this).closest('[data-action="unit-template"]').remove();
    })

    $(document).on('click', '[data-action="attr_val_img_sec"]', function () {
        var button = $(this);
        $.ajax({
            url: 'index.php?menu=media&submenu=action',
            type: 'POST',
            data: {
                action: 'show_media',
                type: 'images',
                allow_change_type: 0,
                selected_limit: 1
            },
            success: function (msg) {
                $('#img_modal .modal-body').html(msg);
                $('#img_modal').modal();

                $(document).on('click', '#add_attachment', function () {
                    $(document).off('click', '#add_attachment')
                    handle_attr_val_image(button);
                })

                return

            }
        })

    })

    $('#product_attr_form').on('submit', function (e) {
        var check = true;


        $(this).find('[role="tabpanel"]').each(function (i, v) {


            if (!$(v).find('[data-required=name]').val()) {
                check = false;
                var tab = $(v).attr('id');
                $('a[href=#' + tab + ']').trigger('click');
                $(v).find('[data-required=name]').focus();
                $('[data-action="attr-warning-msg"]').show();
                $('[data-action="attr-warning-msg"] [data-action=msg-text]').html('<?= CDictionary::GetKey('name_required') ?>')

                return false;
            }
            if (!$(v).find('[data-required="category"]').val()) {
                if (!$(v).find('[data-action="check_all_cats"]').prop('checked')) {
                    check = false;
                    var tab = $(v).attr('id');
                    $('a[href=#' + tab + ']').trigger('click');
                    $(v).find('[data-required="category"]').focus();
                    $('[data-action="attr-warning-msg"]').show();
                    $('[data-action="attr-warning-msg"] [data-action=msg-text]').html('<?= CDictionary::GetKey('category_required') ?>')

                    return false;
                }
            }

        })
        /*        var has_unit_value = true;
         var has_unit_object;
         var has_unit_index;
         $(this).find('[data-required=unit]').each(function (i, v) {
         console.log();
         if ($(v).val()) {
         has_unit_value = false;
         has_unit_object = $(v).closest('[data-action="unit-template"]');
         has_unit_index = $(v).closest('[data-action="unit-template"]').index();
         return false;
         }
         })
         if(!has_unit_value){
         var tab = $(has_unit_object).closest('[role="tabpanel"]').attr('id');
         $('a[href=#' + tab + ']').trigger('click');
         $(tab).find('[data-action="unit-template"]').eq(has_unit_index).find('[data-required=unit]').focus();
         $('[data-action="attr-warning-msg"]').show();
         $('[data-action="attr-warning-msg"] [data-action=msg-text]').html('Unit is required');
         check = false;

         }*/
        if (check) {
            $(this).submit()

        } else {
            e.preventDefault();
        }

    })

    $('[data-action=make_active]').on('click', function () {
        var button = $(this);

        $.ajax({
            type: "POST",
            url: 'index.php?module=product_attributes&submenu=action',
            data: {
                action: 'attribute_activate',
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
            url: 'index.php?module=product_attributes&submenu=action',
            data: {
                action: 'attribute_passivate',
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
    $('[ data-action=delete-attr-item]').on('click', function () {
        if (!confirm('Are you sure?')) return;
        $.ajax({
            type: "POST",
            url: 'index.php?module=product_attributes&submenu=action',
            data: {
                action: 'delete_attr_item',
                id: $(this).data('value')
            },
            success: function (msg) {
                console.log(msg)
                location.reload();
            }
        })
    })

    function handle_attr_val_image(button) {
        console.log(page_prop)
        var active_tab = $(button).closest('.tab-content').find('.active');
        var _index = $(button).closest('[data-action="attr_value_item_sec"]').index();
        $.each(page_prop.submited_items, function (index, value) {
            //var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');
            $('#product_attr_val_form [data-action="attr_group_sec"]').each(function (i, v) {
                var tmp = $('#attr_val_img_template').clone().removeAttr('id');
                var image_src = value.attach_img_src;
                var image_id = value.attach_id;

                $(tmp).find('img').attr('src', image_src);
                $(tmp).find('input[type=hidden]').attr({
                    name: name + '[id]',
                    value: image_id
                });
                $(tmp).find('input[type=text]').attr({
                    name: name + '[title]'
                });
                $(v).find('[data-action="attr_value_item_sec"]').eq(_index).find('[data-action="attach-hidden"]').val(image_id)
                $(v).find('[data-action="attr_value_item_sec"]').eq(_index).find('[data-action="attr_val_img_container"]').html(tmp);
            });


        })
        page_prop.submited_items = []


    }

</script>

