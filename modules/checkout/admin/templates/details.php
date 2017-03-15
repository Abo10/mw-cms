<?php

//$post = $post_obj->GetList_Title();
$css_check_class = 'fa-check';
$css_uncheck_class = 'fa-times';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
} else {
    die('id_error');
}
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = CLanguage::getInstance()->getDefaultUser();
}
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
} else {
    $cat_id = null;
}
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
} else {
    $cat_id = null;
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
} else {
    $limit = 50;
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = null;
}
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = 2;
}
$order_data = CFrontOrder::GetOrder($order_id);
//var_dump($post);
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->

        <!-- /.row -->

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
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-inline post-button-panel">

                            <div class="page-header" style="display:inline-block; width:100%">
                                <h3 style="float:left;    margin-top: 0px;">Order N <?= $order_data["result"]['order_details']['id'] ?> Details</h3>
                                <p style="    float: left;margin: 3px 0px 0px 20px;">Payment Method:<b>VISA, MASTER</b>
                                </p>
                                <div class="form-group" style="float: right;margin-left: 20px;">
                                    <label>Order Status</label>
                                    <select class="form-control" style="    width: 200px;">
                                        <option>Pending</option>
                                        <option>Delivery</option>
                                        <option>Deliverd</option>
                                        <option>Cancel</option>

                                    </select>
                                    <button type="submit" class="btn btn-default">Save</button>
                                </div>
                                <p style="float:right">
                                    <button class="btn btn-default" id="print_single_button"><i class="fa fa-print"
                                                                                                aria-hidden="true"></i>
                                    </button>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <div class="list-group">
                                    <p class="list-group-item active">Ship to</p>
                                    <p class="list-group-item"><b>Order Date:</b> <?= $order_data["result"]['order_details']['order_data'] ?></p>
                                    <p class="list-group-item"><b>Ship Date:</b> -----------</p>

                                    <p class="list-group-item">
                                        <b>Name:</b> <?= $order_data["result"]['shipping_cart']['shipping_datas']['first_name'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Phone:</b> <?= $order_data["result"]['shipping_cart']['shipping_datas']['shipping_phone'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Country:</b> <?= $order_data["result"]['shipping_cart']['address_ext']['country'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Region:</b> <?= $order_data["result"]['shipping_cart']['address_ext']['state'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>City:</b> <?= $order_data["result"]['shipping_cart']['address_ext']['city'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Adress:</b> <?= $order_data["result"]['shipping_cart']['address_ext']['address'] ?>
                                    </p>
                                    <p class="list-group-item"><b>Shiping
                                            metod:</b> <?= $order_data["result"]['shipping_ext']['shipping_method'] ?>
                                    </p>


                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="list-group">
                                    <p class="list-group-item active">Customer ID</p>
                                    <p class="list-group-item">
                                        <b>Name:</b> <?= $order_data["result"]['shipping_cart']['billing_datas']['first_name'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Phone:</b> <?= $order_data["result"]['shipping_cart']['billing_datas']['billing_phone'] ?>
                                    </p>
                                    <p class="list-group-item">
                                        <b>Email: <?= $order_data["result"]['shipping_cart']['billing_datas']['email'] ?></b>
                                    </p>
                                    <p class="list-group-item"><b>Country:</b> -----------</p>
                                    <p class="list-group-item"><b>Region:</b> -----------</p>
                                    <p class="list-group-item"><b>City:</b> -----------</p>
                                    <p class="list-group-item"><b>Adress:</b> -----------</p>

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="list-group">
                                    <p class="list-group-item "><b>Order notes</b></p>
                                    <p class="list-group-item"><?= $order_data['result']['order_details']['order_notes'] ?></p>

                                </div>
                            </div>


                        </div>

                        <div class="col-lg-12 form-inline post-button-panel">
                            <h2>Products</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php CModule::LinkModule('product') ?>
                                    <?php $product_obj = new CProduct() ?>
                                    <?php foreach ($order_data['result']['order_products'] as $item) { ?>
                                        <?php $product_data = $product_obj->GetDatas($item['product_id']); ?>
                                        <tr>
                                            <td><img src="<?= CFrontAttach::GetImageUrl($product_data['langs'][CLanguage::getCurrentUser()]['product_image'],'thumb') ?>">
                                            </td>
                                            <td><?= $product_data['langs'][CLanguage::getCurrentUser()]['product_title'] ?></td>
                                            <td><?= $product_data['langs'][CLanguage::getCurrentUser()]['product_code'] ?></td>
                                            <td><?= floatval($item['product_count']) ?></td>
                                            <td>$<?= floatval($item['product_price']) ?></td>
                                            <td>$<?= $item['product_count'] * $item['product_price'] ?></td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <td class="text-right" colspan="5"><b>Sub-Total</b></td>
                                        <td class="text-right">$<?= $order_data["result"]['order_price'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="5"><b>Flat Shipping Rate</b></td>
                                        <td class="text-right">$<?= $order_data["result"]['shipping_price'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="5"><b>Total</b></td>
                                        <td class="text-right"><b>$<?= $order_data["result"]['calculated_price'] ?></b></td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<script>
    //$('#all_posts tbody').sortable({});
    $(function () {
        $('[data-action=check-all]').on('change', function () {
            $('#all_posts').find('input[type=checkbox]').prop('checked', $(this).prop('checked'))
        })
        $('[data-action="post-checkbox"]').on('change', function () {
            $('#all_posts').find('[data-action=check-all]').prop('checked', false)
        })
        $('[data-action=pagination] a').on('click', function (e) {
            e.preventDefault();
            replace_url_param('page', $(this).data('value'))
        })
        $('#post_cat').on('change', function () {
            replace_url_param('cat_id', $(this).val())
        })
        $('#post_limit').on('change', function () {
            replace_url_param('limit', $(this).val())
        })
        $('#search_but').on('click', function () {
            replace_url_param('search', $('[data-action=search]').val())
        })
        $('[data-action=lang_button]').on('click', function () {
            replace_url_param('lang', $(this).data('value'))
        })
        $('[data-action=status-link]').on('click', function (e) {
            e.preventDefault();
            replace_url_param('status', $(this).data('value'))
        })
        $('[ data-action=delete-post-item]').on('click', function () {
            if (!confirm('Are you sure?')) return;
            $.ajax({
                type: "POST",
                url: 'index.php?menu=post&submenu=action',
                data: {
                    action: 'delete_post_item',
                    delete_id: $(this).data('value')
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })
        })
        $('[data-action=make_active]').on('click', function () {
            var button = $(this);

            $.ajax({
                type: "POST",
                url: 'index.php?menu=post&submenu=action',
                data: {
                    action: 'post_activate',
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
                url: 'index.php?menu=post&submenu=action',
                data: {
                    action: 'post_passivate',
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
        $('[data-action=delete-all]').on('click', function () {


            var checked = $('[data-action=post-checkbox]:checked');
            if (checked.length > 0) {
                if (!confirm('Are you sure?')) return;
            } else {
                return;
            }
            var ret_data = [];
            $.each(checked, function (i, v) {
                ret_data.push($(v).data('value'))
            })
            $.ajax({
                type: "POST",
                url: 'index.php?menu=post&submenu=action',
                data: {
                    action: 'delete_post_item',
                    delete_id: ret_data
                },
                success: function (msg) {
                    console.log(msg)
                    location.reload();
                }
            })

            console.log(ret_data)
        })
    })
</script>
<!-- /#page-wrapper -->


<div class="container" id="print_order">
    <div style="page-break-after: always;">
        <h1>Invoice #2803</h1>
        <img src="http://masterweb.am/images/masterweb.png" alt="" widht="120"
             style="    float: right;  margin-top: -20px;margin-bottom:10px">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td colspan="2">Order Details</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 50%;">
                    <address>

                        <b>Name:</b> Last name first name
                    </address>
                    <b>Telephone:</b> 123456789<br>
                    <b>E-Mail:</b> demo@demo.com<br>
                </td>
                <td style="width: 50%;">
                    <b>Date Added:</b> 08/06/2016<br>
                    <b>Delivery Date:</b> 08/06/2016<br>
                    <b>Order ID:</b> 2803<br>
                    <b>Payment Method:</b> Cash On Delivery<br>
                    <b>Shipping Method:</b> Flat Shipping Rate<br>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td style="width: 50%;"><b>Shipping Detials</b></td>
                <td style="width: 50%;"><b>Billing Detials</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <address>
                        Country: Armenia<br>Region: Yerevan<br>City: Yerevan<br>Adress: Kievyan 16<br></address>
                </td>
                <td>
                    <address>
                        Country: Armenia<br>Region: Yerevan<br>City: Yerevan<br>Adress: Kievyan 16<br></address>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td><b>Product</b></td>

                <td class="text-right"><b>Quantity</b></td>
                <td class="text-right"><b>Unit Price</b></td>
                <td class="text-right"><b>Total</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>HP LP3065 <br>

                </td>

                <td class="text-right">1</td>
                <td class="text-right">$100.00</td>
                <td class="text-right">$100.00</td>
            </tr>
            <tr>
                <td class="text-right" colspan="3"><b>Sub-Total</b></td>
                <td class="text-right">$100.00</td>
            </tr>
            <tr>
                <td class="text-right" colspan="3"><b>Flat Shipping Rate</b></td>
                <td class="text-right">$5.00</td>
            </tr>
            <tr>
                <td class="text-right" colspan="3"><b>Total</b></td>
                <td class="text-right">$105.00</td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td><b>Comment</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>flkdfjlskdfjlskdjf</td>
            </tr>
            </tbody>
        </table>
        <br>
        Signature ______________________________________ Date ____ ________ _______
    </div>

</div>
<script>
    $(function () {
        $('#print_single_button').on('click', function () {
            window.print();
        })
    })
</script>
 




