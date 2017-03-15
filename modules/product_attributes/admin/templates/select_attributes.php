<!--class counter select_attrs55-->
<div class="select_attrs52">
    <div class="select_attrs53"><?= CDictionary::GetKey('pr_attrs_label'); ?></div>
    <div class="select_attrs58"><?= CDictionary::GetKey('pr_attrs_label_2'); ?></div>

    <div class="col-md-12 select_attrs1">
        <div class="row select_attrs2" data-action="attr_group_main_container">
            <?php foreach ($attributes as $key => $item) { ?>
                <div class="col-md-3 select_attrs17" data-action="attr_group_container" data-attr-id="<?= $key ?>">
                    <div class="row select_attrs18" data-action="attr_name"><?= $item['text'] ?></div>
                    <div class="row select_attrs19">
                        <label class="checkbox-inline mycheckbox">
                            <input type="checkbox"
                                   data-action="price_attr_checkbox" <?php if (isset($item['checked']) && $item['checked'] == 1) echo 'checked'; ?>>
                            <span class="checkbox_span"></span>
                            <span><?= CDictionary::GetKey('multiprice') ?></span>
                        </label>
                    </div>
                    <div class="row select_attrs20">
                        <div class="input-group select_attrs21">
                            <input type="text" class="form-control" placeholder="<?= CDictionary::GetKey('search') ?>"
                                   value="" data-action="product-attrs-search">
                <span class="input-group-addon select_attrs22">
                    <span class="fa fa-search"></span>
                </span>
                        </div>
                    </div>
                    <div data-action="labels_container" class="select_attrs23">
                        <?php foreach ($item['vals'] as $key2 => $item2) { ?>
                            <div class="row select_attrs25" data-action="attr_item_value_container"
                                 data-attr-value-id="<?= $key2 ?>">
                                <div class="checkbox-group select_attrs26">
                                    <div class="select_attrs30">
                                        <div class="row nomargin select_attrs27">
                                            <div class="col-md-11 nopadding select_attrs28">
                                                <label class="checkbox-inline mycheckbox">
                                                    <input type="checkbox"
                                                           data-action="attr_value_checkbox" <?php if ($item2['checked'] == 1) echo 'checked'; ?>>
                                                    <span class="checkbox_span"></span>
                                                 <span class="select_attrs29"
                                                       data-action="attr_value_text"><?= $item2['unit_value'] ?></span>
                                                </label>


                                                <input type="hidden"
                                                       data-action="attr_value_id_hidden" <?php if ($item2['checked'] == 1) echo 'name="attr_vals[' . $key . '][' . $key2 . '][id]"'; ?>
                                                       value="<?= $key2 ?>">

                                            </div>
                                            <div class="col-md-1 nopadding select_attrs31">
                                                <div class="product_attr_img_container select_attrs32"
                                                     data-action="product_attr_img_container">
                                                    <img src="<?= $item2['unit_image_url'] ?>" alt=""
                                                         class="img-responsive select_attrs33">

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>

                    <div class="row text-center select_attrs24">
                        <button type="button" data-action="add-attr-val-button"
                                class="btn btn-sm btn-success "><?= CDictionary::GetKey('add') ?></button>
                    </div>
                </div>
            <?php } ?>
            <?php foreach ($empty_attributes as $key => $item) { ?>
                <div class="col-md-3 select_attrs17" data-action="attr_group_container" data-attr-id="<?= $key ?>">
                    <div class="row select_attrs18" data-action="attr_name"><?= $item['attr_name'] ?></div>
                    <div class="row select_attrs19">
                        <!--                    multiprice(<input type="checkbox"-->
                        <!--                                                                  data-action="price_attr_checkbox">)-->
                        <label class="checkbox-inline mycheckbox">
                            <input type="checkbox" data-action="price_attr_checkbox">
                            <span class="checkbox_span"></span>
                            <span><?= CDictionary::GetKey('multiprice') ?></span>
                        </label>
                    </div>
                    <div class="row select_attrs20">
                        <div class="input-group select_attrs21">
                            <input type="text" class="form-control" placeholder="<?= CDictionary::GetKey('search') ?>"
                                   value="" data-action="product-attrs-search">
                <span class="input-group-addon select_attrs22">
                    <span class="fa fa-search"></span>
                </span>
                        </div>
                    </div>
                    <div data-action="labels_container" class="select_attrs23">
                        <?php foreach ($item['values'] as $key2 => $item2) { ?>
                            <div class="row select_attrs25" data-action="attr_item_value_container"
                                 data-attr-value-id="<?= $key2 ?>">
                                <div class="checkbox-group select_attrs26">
                                    <div class="select_attrs30">
                                        <div class="row nomargin select_attrs27">
                                            <div class="col-md-11 nopadding select_attrs28">
                                                <label class="checkbox-inline mycheckbox">
                                                    <input type="checkbox"
                                                           data-action="attr_value_checkbox"> <span
                                                        class="checkbox_span"></span>
                                                 <span class="select_attrs29"
                                                       data-action="attr_value_text"><?= $item2['unit_value'] ?></span>
                                                </label>


                                                <input type="hidden"
                                                       data-action="attr_value_id_hidden"
                                                       value="<?= $key2 ?>">

                                            </div>

                                            <div class="col-md-1 nopadding select_attrs31">
                                                <div class="product_attr_img_container select_attrs32"
                                                     data-action="product_attr_img_container">
                                                    <img src="<?= $item2['unit_image'] ?>" alt=""
                                                         class="img-responsive select_attrs33">

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>

                    <div class="row text-center select_attrs24">
                        <button type="button" data-action="add-attr-val-button"
                                class="btn btn-sm btn-success "><?= CDictionary::GetKey('add') ?></button>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
</div>

<div class="select_attrs54">
    <div class="select_attrs55"><?= CDictionary::GetKey('pr_multiprice_label'); ?></div>
    <div class="select_attrs57"><?= CDictionary::GetKey('pr_multiprice_label_2'); ?></div>

    <div data-action="multiprice_container" class="select_attrs3">
    <?php if ($multiprice) { ?>
        <?php foreach ($multiprice as $key => $item) { ?>
            <?php
            $arr_json = [];
            if ($item['attr_group1']) {
                $arr_json[$item['attr_group1']] = $item['attr_value1'];
            }
            if ($item['attr_group2']) {
                $arr_json[$item['attr_group2']] = $item['attr_value2'];
            }
            if (isset($item['attr1_text'])) {
                $attr1_text = $item['attr1_text'];
            } else {
                $attr1_text = '';
            }
            if (isset($item['attr2_text'])) {
                $attr2_text = $item['attr2_text'];
            } else {
                $attr2_text = '';
            }
            ?>
            <div data-action="multiprice_item" class="select_attrs4 form-inline">
                <div style="display: none">
                    <input type="hidden" name="multiprice[attr_multiprice][]" value='<?= json_encode($arr_json); ?>'
                           data-action="attr_multiprice_mixed_val">
                    <input type="hidden" name="multiprice[edit_id][]" value="<?= $key ?>">
                    <input type="hidden" placeholder="img" name="multiprice[attach_id][]" value="<?= $item['o_img'] ?>"
                           data-action="multiprice-attach-id-input">
                    <input type="hidden" name="multiprice[order][]" value="0" data-action="multiprice-as-main-input">
                    <input type="hidden" name="multiprice[in_stock][]" value="0"
                           data-action="multiprice-in-stock-input">
                </div>
                <span data-action="multiprice_val_text"><?= $attr1_text . ", " . $attr2_text ?></span>
                <input type="text" class="form-control select_attrs48" placeholder="<?= CDictionary::GetKey('price') ?>" value="<?= $item['price'] ?>"
                       name="multiprice[price][]">
                <button class="btn btn-xs" type="button" data-action="choose_product_multiprice_img"><i
                        class="fa fa-picture-o"></i>
                </button>

                <div class="product_multiprice_img_container select_attrs45 form-group"
                     data-action="product_attr_img_container">
                    <?php if (isset($item['image_url'])) { ?>
                        <div class="gallery_item select_attrs6" id="attr_multiprice_img_template">
                            <span data-action="gal-multiprice-delete"><i class="fa fa-times"></i></span>
                            <img src="<?= $item['image_url'] ?>" alt="" class="img-responsive">
                            <input type="hidden">
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group select_attrs46">
                    <input type="text" class="form-control select_attrs47 " value="<?= $item['o_count'] ?>"
                           placeholder="<?= CDictionary::GetKey('count') ?>" name="multiprice[count][]">
                </div>
                <div class="form-group select_attrs43">
                    <label class="checkbox-inline mycheckbox">
                        <input type="checkbox"
                               data-action="multiprice-in-stock-checkbox" <?php if ($item['instock']) echo 'checked' ?>>
                        <span class="checkbox_span"></span>
                        <span><?= CDictionary::GetKey('in_stock') ?></span>
                    </label>
                </div>
                <div class="form-group select_attrs44">
                    <label class="checkbox-inline mycheckbox">
                        <input type="checkbox"
                               data-action="multiprice-as-main-checkbox" <?php if ($item['order']) echo 'checked' ?>>
                        <span class="checkbox_span"></span>
                        <span><?= CDictionary::GetKey('as_main') ?></span>
                    </label>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

</div>
</div>

<div style="display: none;">

    <div data-action="multiprice_item" id="multiprice_template" class="select_attrs4  form-inline">
        <div style="display: none">
            <input type="hidden" name="multiprice[attr_multiprice][]" data-action="attr_multiprice_mixed_val">
            <input type="hidden" name="multiprice[edit_id][]" value="0">

            <input type="hidden" placeholder="img" name="multiprice[attach_id][]"
                   data-action="multiprice-attach-id-input">
            <input type="hidden" name="multiprice[order][]" value="0" data-action="multiprice-as-main-input">
            <input type="hidden" name="multiprice[in_stock][]" value="1" data-action="multiprice-in-stock-input">
        </div>
        <span data-action="multiprice_val_text">text, 10</span>
        <input type="text" class="form-control select_attrs48" placeholder="<?= CDictionary::GetKey('price') ?>" name="multiprice[price][]">
        <button class="btn btn-xs" type="button" data-action="choose_product_multiprice_img"><i
                class="fa fa-picture-o"></i>
        </button>

        <div class="product_multiprice_img_container select_attrs45 form-group"
             data-action="product_attr_img_container">
        </div>
        <div class="form-group select_attrs46">
            <input type="text" class="form-control select_attrs47 " placeholder="<?= CDictionary::GetKey('count') ?>" name="multiprice[count][]">
        </div>
        <div class="form-group select_attrs43">
            <label class="checkbox-inline mycheckbox">
                <input type="checkbox" data-action="multiprice-in-stock-checkbox" checked>
                <span class="checkbox_span"></span>
                <span><?= CDictionary::GetKey('in_stock') ?></span>
            </label>
        </div>
        <div class="form-group select_attrs44">
            <label class="checkbox-inline mycheckbox">
                <input type="checkbox" data-action="multiprice-as-main-checkbox">
                <span class="checkbox_span"></span>
                <span><?= CDictionary::GetKey('as_main') ?></span>
            </label>
        </div>
    </div>

    <div class="gallery_item select_attrs5" id="attr_val_img_template">
        <span data-action="gal-delete"><i class="fa fa-times gal-img-delete"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
    </div>

    <div class="gallery_item select_attrs6" id="attr_multiprice_img_template">
        <span data-action="gal-multiprice-delete"><i class="fa fa-times"></i></span>
        <img src="" alt="" class="img-responsive">
        <input type="hidden">
    </div>
    <div class="form-group select_attrs7" data-action="attr_value_item_sec" id="attr_value_item_sec">
        <label class="control-label col-sm-1 select_attrs8"
               for="email"><?= CDictionary::GetKey('value') ?></label>
        <div class="col-sm-2 select_attrs9">
            <input type="hidden" data-action="attach-hidden">
            <input type="text" class="form-control select_attrs10"
                   placeholder="">
        </div>
        <div class="col-sm-3 select_attrs11">
            <select name="" class="form-control select_attrs12">
                <option value=""><?= CDictionary::GetKey('select') ?></option>
            </select>
        </div>
        <div class="col-sm-1 select_attrs13">
            <button class="btn btn-secondary btn-sm" type="button" data-action="attr_val_img_sec"><i class="fa fa-picture-o"></i>
            </button>
        </div>
        <div class="col-sm-1 select_attrs14">
            <div data-action="attr_val_img_container" class="select_attrs15">

            </div>
        </div>
        <div class=" col-sm-1 select_attrs16">
            <button class="btn btn-danger" type="button" data-action="delete_attr_value_item_sec">-
            </button>
        </div>
    </div>
    <div class="col-md-3 select_attrs17" data-action="attr_group_container" data-attr-id="1"
         id="attr_group_container_template">
        <div class="row select_attrs18" data-action="attr_name"></div>
        <div class="row select_attrs19">
            <label class="checkbox-inline mycheckbox">
                <input type="checkbox" data-action="price_attr_checkbox">
                <span class="checkbox_span"></span>
                <span><?= CDictionary::GetKey('multiprice') ?></span>
            </label>
        </div>
        <div class="row select_attrs20">
            <div class="input-group select_attrs21">
                <input type="text" class="form-control" placeholder="<?= CDictionary::GetKey('search') ?>" value=""
                       data-action="product-attrs-search">
                <span class="input-group-addon select_attrs22">
                    <span class="fa fa-search"></span>
                </span>
            </div>
        </div>
        <div data-action="labels_container" class="select_attrs23">
        </div>

        <div class="row text-center select_attrs24">
            <button type="button" data-action="add-attr-val-button"
                    class="btn btn-sm btn-success "><?= CDictionary::GetKey('add') ?></button>
        </div>
    </div>

    <div class="row select_attrs25" data-action="attr_item_value_container" data-attr-value-id="9"
         id="attr_item_value_container_template">
        <div class="checkbox-group select_attrs26">
            <div class="select_attrs30">
                <div class="row nomargin select_attrs27">
                    <div class="col-md-11 nopadding select_attrs28">
                        <label class="checkbox-inline mycheckbox">
                            <input type="checkbox"
                                   data-action="attr_value_checkbox"> <span class="checkbox_span"></span>
                                                 <span class="select_attrs29"
                                                       data-action="attr_value_text"></span>
                        </label>


                        <input type="hidden"
                               data-action="attr_value_id_hidden"
                               value="">

                    </div>
                    <div class="col-md-1 nopadding select_attrs31">
                        <div class="product_attr_img_container select_attrs32"
                             data-action="product_attr_img_container">
                            <img src="" alt=""
                                 class="img-responsive select_attrs33">

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="form-group select_attrs34"
         data-action="attr_value_item_sec" id="attr_value_item_sec_first_row">
        <label class="control-label col-sm-1 select_attrs35"
               for="email"><?= CDictionary::GetKey('value') ?></label>
        <div class="col-sm-2 select_attrs36">
            <input type="hidden" data-action="attach-hidden">
            <input type="text" class="form-control"
                   placeholder="">
        </div>
        <div class="col-sm-3 select_attrs37">
            <select name="" class="form-control select_attrs38">
                <option value=""><?= CDictionary::GetKey('select') ?></option>
            </select>
        </div>
        <div class="col-sm-1 select_attrs39">
            <button class="btn btn-secondary btn-sm" type="button"
                    data-action="attr_val_img_sec"><i class="fa fa-picture-o"></i>
            </button>

        </div>
        <div class="col-sm-1 select_attrs40">
            <div data-action="attr_val_img_container" class="select_attrs41">

            </div>
        </div>
        <div class=" col-sm-1 select_attrs42">
            <button class="btn btn-success" type="button"
                    data-action="add_attr_value_item_sec">+
            </button>
        </div>
    </div>

</div>

<script>
    $(function () {
        $(document).on('keyup', '[data-action="product-attrs-search"]', function (e) {

            var val = $(this).val();
            var regex = new RegExp(val, 'g');
            $(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_item_value_container"]').show()
            var values = $(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_text"]');
            console.log(values);

            $.each(values, function (i, v) {
                if (!$(v).html().match(regex)) {
                    $(v).closest('[data-action="attr_item_value_container"]').hide();
                }
            })
        })
        $(document).on('keydown', '[data-action="product-attrs-search"]', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        })
        function array_equal(arr1, arr2) {
            if (arr1.length != arr2.length)
                return false;
            //todo
        }

        $(document).on('click', '[data-action="price_attr_checkbox"]', function (e) {

            var checked = $(this).prop('checked');
            var has_other_checked = $('[data-action="price_attr_checkbox"]:checked').not(this);
            if (has_other_checked.length > 1) {
                e.preventDefault();
                //alert(1);
                return false;
            }
            if ($(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked').length == 0) {
                return;

            }
            //console.log(has_other_checked);
            var json_part = {};
            var selected_items = []
            if (has_other_checked.length == 1) {
                var first = $('[data-action="price_attr_checkbox"]:checked').eq(0);
                var first_attr_id = $(first).closest('[data-action="attr_group_container"]').data('attr-id');

                var second = $('[data-action="price_attr_checkbox"]:checked').eq(1)
                var second_attr_id = $(second).closest('[data-action="attr_group_container"]').data('attr-id');

                var checked_first_values = $(first).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked')
                var checked_second_values = $(second).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked')

                if (checked_first_values.length > 0) {
                    $.each(checked_first_values, function (i, v) {
                        var value_id = $(v).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                        var json_part = {};
                        json_part[first_attr_id] = value_id;
                        if (checked_second_values.length > 0) {
                            $.each(checked_second_values, function (i2, v2) {
                                var value_id2 = $(v2).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                                var new_json_part = jQuery.extend(true, {}, json_part);
                                new_json_part[second_attr_id] = value_id2;

                                selected_items.push(new_json_part);

                            })
                        } else {
                            selected_items.push(json_part);
                        }

                    })
                } else {
                    if (checked_second_values.length > 0) {
                        $.each(checked_second_values, function (i2, v2) {
                            var value_id2 = $(v2).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                            var json_part = {};
                            json_part[second_attr_id] = value_id2;
                            selected_items.push(json_part);
                        })
                    }
                }
            } else {
                if ($('[data-action="price_attr_checkbox"]:checked').length == 1) {
                    var first_attr_id = $('[data-action="price_attr_checkbox"]:checked').closest('[data-action="attr_group_container"]').data('attr-id');
                    var checked_first_values = $('[data-action="price_attr_checkbox"]:checked').closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked')
                    $.each(checked_first_values, function (i, v) {
                        var value_id = $(v).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                        var json_part = {};
                        json_part[first_attr_id] = value_id;
                        selected_items.push(json_part);

                    })

                }
            }
            ///// allll selected items here !!!!!!!!!!!!!!
            $('[data-action="multiprice_container"]').find('[data-action="attr_multiprice_mixed_val"]').attr('data-exist', '0');
            var existing_indexes = []
            $.each(selected_items, function (i, v) {
                var str = JSON.stringify(v);
                var exists = false;
                $('[data-action="multiprice_container"] [data-action="multiprice_item"]').each(function (i, v) {
                    if ($(v).find('[data-action="attr_multiprice_mixed_val"]').val() == str) {
                        $(v).find('[data-action="attr_multiprice_mixed_val"]').attr('data-exist', '1');
                        existing_indexes.push(i);
                    }
                })

            })
            $('[data-action="multiprice_container"]').find('[data-exist="0"]').closest('[data-action="multiprice_item"]').remove();

            $.each(selected_items, function (i, v) {
                if (existing_indexes[i]) {
                    return false;
                }

                var str = JSON.stringify(v);
                var inner_text = '';
                $.each(v, function (i2, v2) {
                    var tmp_str = $('[data-attr-id="' + i2 + '"] [data-attr-value-id="' + v2 + '"]').find('[data-action="attr_value_text"]').html();
                    inner_text = inner_text + tmp_str + ', ';
                })
                var clone = $('#multiprice_template').clone().removeAttr('id');
                $(clone).find('[data-action="attr_multiprice_mixed_val"]').val(str);
                $(clone).find('[data-action="multiprice_val_text"]').html(inner_text)
                $('[data-action="multiprice_container"]').append(clone);

            })

        })

        $(document).on('click', '[data-action="attr_value_checkbox"]', function () {
            var parent_checked = $(this).closest('[data-action="attr_group_container"]').find('[data-action="price_attr_checkbox"]').prop('checked');
            if (!parent_checked)
                return;
            if ($(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked').length == 0) {
                $('[data-action="price_attr_checkbox"]').closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]').prop('checked', false);
            }
            console.log($(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked').length);
            if ($(this).closest('[data-action="attr_group_container"]').find('[data-action="attr_value_checkbox"]:checked').length == 1) {
                $('[data-action="multiprice_container"] [data-action="multiprice_item"]').remove();
            }


            var attr_id = $(this).closest('[data-action="attr_group_container"]').data('attr-id');
            var attr_value_id = $(this).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
            if (!$(this).prop('checked')) {
                $('[data-action="multiprice_container"] [data-action="multiprice_item"]').each(function (i, v) {
                    var inner_data = $(v).find('[data-action="attr_multiprice_mixed_val"]').val();
                    inner_data = JSON.parse(inner_data)
                    if (inner_data[attr_id] == attr_value_id) {
                        $(v).remove();
                    }

                })
            } else {
                var has_other_checked = $('[data-action="price_attr_checkbox"]:checked').not($(this).closest('[data-action="attr_group_container"]').find('[data-action="price_attr_checkbox"]'));
                var selected_items = [];
                var json_part = {};
                json_part[attr_id] = attr_value_id;
                if (has_other_checked.length > 0) {
                    var second = $(has_other_checked).closest('[data-action="attr_group_container"]');
                    var second_attr_id = $(second).data('attr-id');
                    var checked_second_values = $(second).find('[data-action="attr_value_checkbox"]:checked');

                    $.each(checked_second_values, function (i, v) {
                        var value_id = $(v).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                        var new_json_part = jQuery.extend(true, {}, json_part);
                        new_json_part[second_attr_id] = value_id;

                        selected_items.push(new_json_part);

                    })
                } else {
                    selected_items.push(json_part);

                }

                $.each(selected_items, function (i, v) {
                    var str = JSON.stringify(v);
                    var inner_text = '';
                    $.each(v, function (i2, v2) {
                        var tmp_str = $('[data-attr-id="' + i2 + '"] [data-attr-value-id="' + v2 + '"]').find('[data-action="attr_value_text"]').html();
                        inner_text = inner_text + tmp_str + ', ';
                    })
                    var clone = $('#multiprice_template').clone().removeAttr('id');
                    $(clone).find('[data-action="attr_multiprice_mixed_val"]').val(str);
                    $(clone).find('[data-action="multiprice_val_text"]').html(inner_text)
                    $('[data-action="multiprice_container"]').append(clone);

                })


            }

        })

        $(document).on('click', '[data-action="add-attr-val-button"]', function () {
            var id = $(this).closest('[data-action="attr_group_container"]').data('attr-id');
            $('#add_attr_value_modal').find('input[name="attr_group"]').val(id);
            $('#add_attr_value_modal').modal();
            $.ajax({
                url: 'index.php?module=product_attributes&submenu=action',
                type: 'post',
                data: {
                    action: 'get_attr_values_units',
                    id: id
                },
                success: function (msg) {
                    page_prop.unit_values = JSON.parse(msg).units;
                    page_prop.attr_values = JSON.parse(msg).values;
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


                }
            })
        })

        $(document).on('click', '#add_attr_value_modal [data-action="delete_attr_value_item_sec"]', function () {
            if (!confirm('are you sure?')) {
                return;
            }
            var _index = $(this).closest('[data-action="attr_value_item_sec"]').index();
            $('#add_attr_value_modal .tab-content [data-action="attr_value_item_sec"]:nth-child(' + (_index + 1) + ')').remove();
            //$(this).closest('[data-action="unit-template"]').remove();
        })

        $(document).on('change', '#add_attr_value_modal select', function () {
            var sel_val = $(this).val();
            var _index = $(this).closest('[data-action="attr_value_item_sec"]').index();
            $('#add_attr_value_modal .tab-content [data-action="attr_value_item_sec"]:nth-child(' + (_index + 1) + ')').find('select').val(sel_val);
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

                if (options_str) {
                    $(elem_clone).find('select').attr('name', 'values[' + lang + '][' + uniqiu_index + '][unit]');
                    $(elem_clone).find('select').append(options_str);
                } else {
                    $(elem_clone).find('select').parent().remove()
                }


                $(v).append(elem_clone);
            })
        })

        $('#attr_val_modal_form [data-action=submit-form]').on('click', function () {
            var f_data = $('#attr_val_modal_form').serialize();
            var group_id = $('#attr_val_modal_form').find('input[name="attr_group"]').val();
            var button = $(this);
//            console.log(f_data)
            $.ajax({
                url: 'index.php?module=product_attributes&submenu=action',
                type: 'POST',
                data: f_data,
                success: function (msg) {
                    console.log(msg)
                    var r_data = JSON.parse(msg)
                    console.log(r_data)
                    $.each(r_data, function (i, v) {
                        var item_clone = $('#attr_item_value_container_template').clone().removeAttr('id');
                        $(item_clone).attr('data-attr-value-id', i);
                        $(item_clone).find('[data-action="attr_value_text"]').html(v.value);
                        $(item_clone).find('[data-action="product_attr_img_container"]').find('img').attr('src', v.unit_image_url);
                        $('[data-action="attr_group_container"][data-attr-id=' + group_id + ']').find('[data-action="labels_container"]').append(item_clone);
                    })
                    $('#add_attr_value_modal').modal('hide')

                }
            })

        })

        $('[data-action="parent-checkbox"]').on('change', function () {
            var active_tab = $(this).closest('.tab-content').find('.active');
            var checked_vals = [];
            $(active_tab).find('[data-action="parent-checkbox"]:checked').each(function (i, v) {
                checked_vals.push($(v).data('parent-cat-value'))
            });
            $.ajax({
                type: 'POST',
                url: 'index.php?module=product_attributes&submenu=action',
                data: {
                    action: 'get_subjects',
                    data: checked_vals
                },
                success: function (msg) {
                    var data = JSON.parse(msg);
                    console.log(data)
                    $('[data-action="attr_group_main_container"] [data-action="attr_group_container"]').each(function (i, v) {
                        var group_id = parseInt($(v).data('attr-id'));
                        var has_elem = false;

                        $.each(data, function (i2, v2) {

                            if (group_id == parseInt(i2)) {
                                has_elem = true
                                return
                            }
                        })
                        if (!has_elem) {
                            $('[data-action="attr_group_main_container"] [data-action="attr_group_container"][data-attr-id=' + group_id + ']').remove()
                        }
                    })

                    $.each(data, function (i, v) {
                        if ($('[data-action="attr_group_main_container"]').find('[data-attr-id=' + i + ']').length == 0) {
                            var attr_group_clone = $('#attr_group_container_template').clone().removeAttr('id');
                            $(attr_group_clone).attr('data-attr-id', i)
                            $(attr_group_clone).find('[data-action=attr_name]').html(v.attr_name)
                            $.each(v.values, function (i2, v2) {
                                var attr_val_item_clone = $('#attr_item_value_container_template').clone().removeAttr('id');
                                $(attr_val_item_clone).attr('data-attr-value-id', i2);
                                $(attr_val_item_clone).find('[data-action="attr_value_text"]').html(v2.unit_value);
                                $(attr_val_item_clone).find('[data-action="product_attr_img_container"]').find('img').attr('src', v2.unit_image);
                                $(attr_group_clone).find('[data-action="labels_container"]').append(attr_val_item_clone);
                            })
                            $('[data-action="attr_group_main_container"]').append(attr_group_clone)
                        }
                    })
                }
            })

        })

        $(document).on('change', '[data-action="multiprice-in-stock-checkbox"]', function () {
            var value;
            if ($(this).prop('checked')) {
                value = 1;
            } else {
                value = 0;
            }
            $(this).closest('[data-action="multiprice_item"]').find('[data-action="multiprice-in-stock-input"]').val(value)
        })

        $(document).on('change', '[data-action="multiprice-as-main-checkbox"]', function (e) {
            if ($('[data-action="multiprice_container"] [data-action="multiprice-as-main-checkbox"]:checked').length > 1) {
                $('[data-action="multiprice_container"] [data-action="multiprice-as-main-checkbox"]').prop('checked',false);
                $('[data-action="multiprice_container"] [data-action="multiprice-as-main-input"]').val(0);
                $(this).prop('checked', true)
//                return false;
            }
            var value;
            if ($(this).prop('checked')) {
                value = 1;
            } else {
                value = 0;
            }
            $(this).closest('[data-action="multiprice_item"]').find('[data-action="multiprice-as-main-input"]').val(value)
        })

        $(document).on('click', '[data-action="choose_product_multiprice_img"]', function () {
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
                        handle_product_multiprice_image(button);
                    })

                    return

                }
            })

        })


        $(document).on('click', '[data-action=gal-product-delete]', function () {
            $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_attach_id_hidden"]').val('');
            $(this).closest('.gallery_item').remove();
        })

        $(document).on('click', '[data-action=gal-multiprice-delete]', function () {
            $(this).closest('[data-action="multiprice_item"]').find('[data-action="multiprice-attach-id-input"]').val('');
            $(this).closest('.gallery_item').remove();
        })


        $(document).on('change', '[data-action="attr_value_checkbox"]', function () {
            if ($(this).prop('checked')) {
                var attr_group = $(this).closest('[data-action="attr_group_container"]').data('attr-id');
                var value_id = $(this).closest('[data-action="attr_item_value_container"]').data('attr-value-id');
                var value_name = 'attr_vals[' + attr_group + '][' + value_id + '][id]';
//                var attach_name = 'attr_vals[' + attr_group + '][' + value_id + '][attach_id]';
                $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_value_id_hidden"]').attr('name', value_name);
                $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_value_id_hidden"]').val(value_id);
//                $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_attach_id_hidden"]').attr('name', attach_name);
            } else {
                $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_value_id_hidden"]').removeAttr('name');
//                $(this).closest('[data-action="attr_item_value_container"]').find('[data-action="attr_attach_id_hidden"]').removeAttr('name');
            }
        })
    })
    //    function handle_attr_val_image(button) {
    //        var active_tab = $(button).closest('.tab-content').find('.active');
    //
    //        $.each(page_prop.submited_items, function (index, value) {
    //            //var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');
    //            var tmp = $('#attr_val_img_template').clone().removeAttr('id');
    //            var image_src = value.attach_img_src;
    //            var image_id = value.attach_id;
    //
    //            $(tmp).find('img').attr('src', image_src);
    //            $(tmp).find('input[type=hidden]').attr({
    //                name: name + '[id]',
    //                value: image_id
    //            });
    //            $(tmp).find('input[type=text]').attr({
    //                name: name + '[title]'
    //            });
    //            $(button).closest('[data-action="attr_value_item_sec"]').find('[data-action="attach-hidden"]').val(image_id)
    //            $(button).parent().next().find('[data-action="attr_val_img_container"]').html(tmp);
    //            console.log(tmp);
    //
    //        })
    //        page_prop.submited_items = []
    //
    //        if ($(active_tab).first().is(':first-child')) {
    //            $('[data-action=copy_main_image_button]').show();
    //        }
    //
    //    }

    function handle_attr_val_image(button) {
        var active_tab = $(button).closest('.tab-content').find('.active');
        var _index = $(button).closest('[data-action="attr_value_item_sec"]').index();
        $.each(page_prop.submited_items, function (index, value) {
            //var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');
            $('#add_attr_value_modal [data-action="attr_group_sec"]').each(function (i, v) {
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


    function handle_product_multiprice_image(button) {

        $.each(page_prop.submited_items, function (index, value) {
            //var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');
            var tmp = $('#attr_multiprice_img_template').clone().removeAttr('id');
            var image_src = value.attach_img_src;
            var image_id = value.attach_id;

            $(tmp).find('img').attr('src', image_src);

            $(button).closest('[data-action="multiprice_item"]').find('[data-action="multiprice-attach-id-input"]').val(image_id);
            $(button).closest('[data-action="multiprice_item"]').find('[data-action="product_attr_img_container"]').html(tmp);
            console.log(tmp);

        })
        page_prop.submited_items = []
    }

</script>