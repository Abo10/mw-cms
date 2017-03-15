<div class="col-md-3 col-sm-3 nopadding-right">
    <aside>
        <div class="box_manufacrurer">
            <div class="area-title">
                <h3>Shop By</h3>
            </div>
            <div class="">
                <div class="panel-group" id="accordion">
                    <?php foreach (CFrontProductCategory::GetCategoriesByParent(0) as $item) { ?>
                        <?php $childs = CFrontProductCategory::GetCategoriesByParent($item['cid']) ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="<?= URL_BASE . 'product-category/' . $item['slugs'] ?>">
                                        <?= $item['category_title'] ?>
                                    </a>
                                    <?php if ($childs) { ?>
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse<?= $item['cid'] ?>"
                                           class="pull-right arrow-toggle">
                                            <span class="icon-arrow-down"><i class="fa fa-angle-up"
                                                                             aria-hidden="true"></i></span>
                                            <span class="icon-arrow-up"><i class="fa fa-angle-down"
                                                                           aria-hidden="true"></i></span></a>
                                    <?php } ?>
                                </h4>
                            </div>
                            <?php if ($childs) { ?>
                                <div id="collapse<?= $item['cid'] ?>"
                                     class="panel-collapse collapse ">
                                    <div class="panel-body">
                                        <ul>
                                            <?php foreach ($childs as $item2) { ?>
                                                <li>
                                                    <a href="<?= URL_BASE . 'product-category/' . $item2['slugs'] ?>"><?= $item2['category_title'] ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div>

            </div>


        </div>
    </aside>
    <!--aside 1 end-->

    <aside class="shop-filter aside-padd">
        <h3 class="price-title">Price Filter</h3>
        <div class="price_filter">
            <form action="#" method="get">
                <div id="slider-range"
                     class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
                <input type="text" id="amount" readonly="" data-min="<?= $this->_data['price_range']['min'] ?>"
                       data-max="<?= $this->_data['price_range']['max'] ?>">
                <input type="hidden" value="<?= $this->cat_id; ?>" id="product_cat_id">
                <input type="submit" value="Filter" id="price_filter">
            </form>
        </div>
    </aside>
    <!--aside 2 end-->

    <?php $brand_obj = CModule::LoadModule('brand'); ?>
    <?php $brands = $brand_obj->GetBrands() ?>
<!--    --><?php //var_dump($brands); ?>
    <!--aside 3 end-->
    <aside>
        <div class="tag-area">
            <div class="area-title">
                <h3>Brands</h3>
            </div>
            <div class="categori" style="    width: 100%;">
                <form id="select-categoris" method="post" class="form-horizontal">
                    <select name="brands" id="cat_brands" class="orderby" style="display: none;">
                        <option value="">Brands</option>
                        <?php foreach ($brands as $brand) { ?>
                            <option value="<?= $brand['brand_group'] ?>"><?= $brand['brand_title'] ?></option>
                        <?php } ?>
                    </select>
                </form>
            </div>
        </div>
    </aside>
    <!--aside 4 end-->
    <aside>
        <div class="newsletter-area">
            <div class="area-title">
                <h3>Newsletter</h3>
            </div>
            <div class="aside-padd">
                <div class="vina-newsletter">
                    <form method="post" action="#">
                        <div class="input-box">
                            <label>Sign Up for Our Newsletter:</label>
                            <input type="email" placeholder="Email" name="email">
                        </div>
                        <div class="input-box">
                            <input type="submit" class="submit-btn" name="submit" value="Subscribe">
                        </div>
                    </form>
                </div>
                <div class="web-links">
                    <ul>
                        <li><a href="#" class="rss"><i class="fa fa-rss"></i>
                            </a></li>
                        <li><a href="#" class="ldin"><i class="fa fa-linkedin"></i>
                            </a></li>
                        <li><a href="#" class="face"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" class="google"><i class="fa fa-google-plus"></i>
                            </a></li>
                        <li><a href="#" class="twitter"><i class="fa fa-twitter"></i>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
    <!--aside 5 end-->
</div>
<script>
    $(function () {
        var url_base = $('#url_base').val();
        var collect_data = function () {
            var ret_data = {}
            var price_str = $('#amount').val();
            var min_price = price_str.split('-')[0].replace(/\D/g, '');
            var max_price = price_str.split('-')[1].replace(/\D/g, '');

            var cat_id = $('#product_cat_id').val();
            ret_data.price_range = {min: min_price, max: max_price}
            ret_data.product_category = [];
            if ($('#cat_brands').val()) {

                ret_data.brand = [$('#cat_brands').val()]
            }
            if ($('#main_search_input').val()) {

                ret_data.search = $('#main_search_input').val()
            }
            var order = $('#order').val();
            var limit = $('#items_limit').val()
            var page = $('#page').val()
            console.log(ret_data);
            var attrs = {};
            $('[data-attr-group]').each(function (i, v) {
                var checked = $(v).find('input[type=checkbox]:checked');
                var group_id = parseInt($(v).data('attr-group'));
                if ($(checked).length > 0) {
                    var inner_arr = [];
                    $(checked).each(function (i2, v2) {
                        inner_arr.push($(v2).val())
                    })
                    attrs[group_id] = inner_arr;
                }
            })
            ret_data.attributika = attrs;

            var url = url_base + 'ajax/';
            $.ajax({
                url: url,
                type: 'post',
                data: {
                    action: 'get_product_list',
                    data: ret_data,
                    limit:limit,
                    order:order,
                    page:page
                },
                success: function (msg) {
                    //console.log(msg)
                    $('#products_container').html(msg)
                }
            })
            return ret_data;
            //console.log(attrs);

        }
        $('#price_filter').on('click', function (e) {
            e.preventDefault();
            collect_data();


        })
        $('[data-attr-group] input[type=checkbox]').on('change', function (e) {
            $('#page').val(1)
            collect_data();
        })
        $('#cat_brands').on('change', function (e) {
            $('#page').val(1)
            collect_data();
        })
        $('#items_limit').on('change', function (e) {
            $('#page').val(1)
            collect_data();
        })
        $('#order').on('change', function (e) {
            $('#page').val(1)
            collect_data();
        })
        $(document).on('click','[data-action="pagination"]', function (e) {
            $('#page').val($(this).data('value'));
            e.preventDefault();
            collect_data();
        })
    })
</script>