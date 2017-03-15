<?php
CModule::LinkModule('addressing');
$countries = CAddressing::GetAllCountries();
?>
<div id="page-wrapper">

<div class="addressing-label-1"><h3><?= CDictionary::GetKey('addressing-label-55') ?></h3></div>

    <div class="container-fluid">
        <div class="addressing-label-1"><?= CDictionary::GetKey('addressing-label-1') ?></div>
        <div class="form-inline address-main-select-2">
            <div class="form-group address-main-select">
                <select class="form-control" id="main_select">
                    <option value=""><?= CDictionary::GetKey('select') ?></option>
                    <option value="country" data-command="add_country"><?= CDictionary::GetKey('country') ?></option>
                    <option value="state" data-command="add_state"><?= CDictionary::GetKey('state') ?></option>
                    <option value="city" data-command="add_city"><?= CDictionary::GetKey('city') ?></option>
                    <option value="community"
                            data-command="add_community"><?= CDictionary::GetKey('community') ?></option>
                </select>
                <!--                <button type="button" value="Add" class="btn  btn-success" id="add_addressing">add</button>-->
            </div>
            <div id="filters_content" class="address-filter-content">
                <div class="form-group">
                    <select class="form-control" id="country_select" style="display: none">
                        <option value=""><?= CDictionary::GetKey('select') ?></option>
                        <?php foreach ($countries as $c_key => $country) { ?>
                            <option value="<?= $c_key ?>"><?= $country['text'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="state_select" style="display: none">
                        <option value=""><?= CDictionary::GetKey('select') ?></option>

                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="city_select" style="display: none">
                        <option value=""><?= CDictionary::GetKey('select') ?></option>

                    </select>
                </div>
            </div>
        </div>

        <div id="ajax_content">
        </div>
        <div id="view_form">
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#main_select').on('change', function () {
            $('#filters_content select').val('');
            $('#filters_content select').hide();
            var val = $(this).val();
            if (!val) return;
            if (val == 'country') {
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: val,
                        edit_id: page_prop.edit_id
                    },
                    success: function (msg) {
                        $('#ajax_content').html(msg);
                    }
                })
            }
            if (val == 'state') {
                $('#ajax_content').html('');
                $('#country_select').show();
            }
            if (val == 'city') {
                $('#ajax_content').html('');
                $('#country_select').show();
            }
            if (val == 'community') {
                $('#ajax_content').html('');
                $('#country_select').show();
            }

        })
        $('#country_select').on('change', function () {
            if ($('#main_select').val() == 'state') {
                var val = $(this).val();
                if (!val) return;
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'state',
                        country: val,
                        edit_id: page_prop.edit_id
                    },
                    success: function (msg) {
                        $('#ajax_content').html(msg);
                    }
                })
            }
            if ($('#main_select').val() == 'city') {
                var country = $('#country_select').val();
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'get_states',
                        country: country
                    },
                    success: function (msg) {
                        $('#state_select').html(msg)
                        $('#state_select').show();
                        console.log(msg);
                        return;
                        $('#ajax_content').html(msg);
                    }
                })
            }
            if ($('#main_select').val() == 'community') {
                var country = $('#country_select').val();
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'get_states',
                        country: country
                    },
                    success: function (msg) {
                        $('#state_select').html(msg)
                        $('#state_select').show();

                    }
                })
            }
        })
        $('#state_select').on('change', function () {
            if ($('#main_select').val() == 'city') {
                var country = $('#country_select').val();
                var state = $('#state_select').val();
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'city',
                        country: country,
                        state: state,
                        edit_id: page_prop.edit_id
                    },
                    success: function (msg) {
                        $('#ajax_content').html(msg);
                    }
                })
            }
            if ($('#main_select').val() == 'community') {
                var country = $('#country_select').val();
                var state = $('#state_select').val();
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'get_cities',
                        country: country,
                        state: state
                    },
                    success: function (msg) {
                        $('#city_select').html(msg);
                        $('#city_select').show();
                    }
                })
            }
        })

        $('#city_select').on('change', function () {
            if ($('#main_select').val() == 'community') {
                var country = $('#country_select').val();
                var state = $('#state_select').val();
                var city = $('#city_select').val();
                $.ajax({
                    url: 'index.php?module=addressing&submenu=action',
                    type: 'POST',
                    data: {
                        action: 'community',
                        country: country,
                        state: state,
                        city: city,
                        edit_id: page_prop.edit_id
                    },
                    success: function (msg) {
                        $('#ajax_content').html(msg);
                    }
                })
            }
        })
        $(document).on('click', '[data-action="edit-country"]',function () {
            page_prop.edit_id = $(this).data('value');
            $('#main_select').trigger('change');
        })
        $(document).on('click', '[data-action="edit-state"]',function () {
            page_prop.edit_id = $(this).data('value');
            $('#country_select').trigger('change');
        })
        $(document).on('click', '[data-action="edit-city"]',function () {
            page_prop.edit_id = $(this).data('value');
            $('#state_select').trigger('change');
        })
        $(document).on('click', '[data-action="edit-community"]',function () {
            page_prop.edit_id = $(this).data('value');
            $('#city_select').trigger('change');
        })
    })
</script>

