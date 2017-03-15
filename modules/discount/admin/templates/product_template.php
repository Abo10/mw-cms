<div style="margin-top: 20px" class="col-md-12"></div>
<div class="col-md-12 product-discount">
    <div class="row">
        <div class="mw-has-discount" data-action="has-discount">
            <input type="hidden" data-action="has-discount-hidden" name="predefines[discount][has_discount]" value="0">
            <label class="checkbox-inline mycheckbox">
                <input type="checkbox" data-action="has-discount-checkbox" <?php if(isset($discount['has_discount']) && $discount['has_discount']) echo 'checked'; ?>>
                <span class="checkbox_span"></span>
                <span><?= CDictionary::GetKey('discount'); ?></span>
            </label>
        </div>
        <div class="discount-content" data-action="discount-content" <?php if(isset($discount['has_discount']) && !$discount['has_discount']) echo 'style="display: none"'; ?>>
            <div class="form-inline discount-itme" data-action="discount-item">
                <div class="form-group">
                    <input type="number" class="form-control" name="predefines[discount][count][]"
                           placeholder="<?= CDictionary::GetKey('count'); ?>"/>

                    <input type="number" class="form-control" name="predefines[discount][percent][]"
                           placeholder="<?= CDictionary::GetKey('discount2') ?> %">
                    <button type="button" class="btn btn-success" data-action="add-discount-item">+</button>
                </div>
            </div>
            <?php if (isset($discount['has_discount']) && $discount['has_discount']) { ?>
                <?php foreach ($discount['count'] as $key =>  $item) { ?>
                    <div class="form-inline discount-itme" data-action="discount-item" >
                        <div class="form-group">
                            <input type="number" class="form-control" name="predefines[discount][count][]"
                                   placeholder="<?= CDictionary::GetKey('count'); ?>" value="<?= $item ?>"/>

                            <input type="number" class="form-control" name="predefines[discount][percent][]"
                                   placeholder="<?= CDictionary::GetKey('discount') ?> %" value="<?= $discount['percent'][$key] ?>">

                            <button type="button" class="btn btn-danger" data-action="delete-discount-item">-</button>

                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<div style="display: none">
    <div class="form-inline discount-itme" data-action="discount-item" id="discount-item-template">
        <div class="form-group">
            <input type="number" class="form-control" name="predefines[discount][count][]"
                   placeholder="<?= CDictionary::GetKey('count'); ?>"/>

            <input type="number" class="form-control" name="predefines[discount][percent][]"
                   placeholder="<?= CDictionary::GetKey('discount2') ?> %">

            <button type="button" class="btn btn-danger" data-action="delete-discount-item">-</button>

        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-action="has-discount-checkbox"]').on('change', function () {
            if ($(this).prop('checked')) {
                $('[data-action="has-discount-hidden"]').val(1);
                $('[data-action="discount-content"]').show();
//                $("html, body").animate({scrollTop: $(document).height()}, 1000);

            } else {
                $('[data-action="has-discount-hidden"]').val(0);
                $('[data-action="discount-content"]').hide();
            }
        })
        $('[data-action="add-discount-item"]').on('click', function () {
            var elem_clone = $('#discount-item-template').clone().removeAttr('id');
            $('[data-action="discount-content"]').append(elem_clone);
            $("html, body").animate({scrollTop: $(document).height()}, 1000);
        })

        $(document).on('click', '[data-action="delete-discount-item"]', function () {
            $(this).closest('[data-action="discount-item"]').remove();
        })
        $('[data-action="has-discount-checkbox"]').trigger('change');
    })
</script>
