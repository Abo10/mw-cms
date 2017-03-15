<div class="row">
    <div class="col-md-12">
        <h2>step2</h2>
        <form action="" class="form-inline" id="step2_form">
            <input type="hidden" name="action" value="step2">

            <?php foreach ($config_data['lang_list'] as $key => $item) { ?>
                <div class="form-group">
                    <label class="checkbox-inline"><input type="radio" name="default_lang" value="<?= $item['key'] ?>" <?php if ($key == 0) echo 'checked' ?>><?= CDictionary::GetKey('default') ?>
                    </label>
                    <label class="checkbox-inline"><input type="checkbox" name="selected_langs[]" value="<?= $item['key'] ?>"><?= $item['title'] ?></label>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-3">
                    <span>simple</span>
                    <span><input type="radio" name="site_type" value="simple" data-action="choose-site-type"
                                 checked></span>

                </div>
                <div class="col-md-3" data-action="site-type-container">
                    <span>catalog</span>
                    <span><input type="radio" name="site_type" value="catalog" data-action="choose-site-type"></span>


                    <div class="type_dropdown" data-action="site-type-content" style="display: none">
                        choose currency
                        <div>
                            <ul class="list-group">
                                <?php $counter = 0; ?>
                                <?php foreach ($config_data['currency'] as $key => $item) { ?>
                                    <li class="list-group-item">
                                        <label class="checkbox-inline"><input type="radio" value="<?= $key ?>" name="default_currency_catalog" <?php if ($counter++ == 0) echo 'checked' ?>><?= CDictionary::GetKey('default') ?>
                                        </label>
                                        <label class="checkbox-inline"><input type="checkbox" name="currency_list[]" value="<?= $key ?>"><?= $key ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-action="site-type-container">
                    <span>catalog</span>
                    <span><input type="radio" name="site_type" value="shop" data-action="choose-site-type"></span>
                    <div class="type_dropdown" data-action="site-type-content" style="display: none">
                        choose currency
                        <div>
                            <ul class="list-group">
                                <?php $counter = 0; ?>
                                <?php foreach ($config_data['currency'] as $key => $item) { ?>
                                    <li class="list-group-item">
                                        <label class="checkbox-inline"><input type="radio" value="<?= $key ?>" name="default_currency_shop" <?php if ($counter++ == 0) echo 'checked' ?>><?= CDictionary::GetKey('default') ?>
                                        </label>
                                        <label class="checkbox-inline"><input type="checkbox" name="currency_list[]" value="<?= $key ?>"><?= $key ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <form action="?menu=action" method="post">
            <input type="hidden" name="action" value="back_to_step1">
            <button type="submit" class="btn btn-primary">back</button>
        </form>
        <form action="?menu=action" method="post">
            <button type="button" id="submit_step2" class="btn btn-primary pull-right">next</button>
        </form>
    </div>
</div>
<script>
    $(function () {
        $('#submit_step2').on('click', function () {
            $('#submit_step2').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: '?menu=action',
                data: $('#step2_form').serialize(),
                success: function (msg) {
                    console.log(msg);
                    location.reload();
                },
                beforeSend: function () {
                    $('#submit_step2').html('Wait...')
                }
            })
        })
        $('[data-action="choose-site-type"]').on('click', function () {
            $('[data-action="site-type-content"]').hide();
            if ($(this).prop('checked')) {
                $(this).closest('[data-action="site-type-container"]').find('[data-action="site-type-content"]').show();
            }
        })
    })
</script>