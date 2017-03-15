<div class="col-md-9 col-sm-9 nopadding-left">
    <div class="ambit-key">
        <div class="col-md-12 pt40">
            <ol class="breadcrumb">
                <li class="home"><a href="index.html" title="Go to Home Page">Home</a></li>
                <li class="active">Search</li>
            </ol>
            <div class="shop-banner mt20 mb20">
                <?php
                if ($this->covers) {
                    $attach = new CAttach(array_shift($this->covers)['id']);
                    ?>

                    <a href="javascript::"><img src="<?= $attach->GetURL('original') ?>" alt=""></a>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="shop-product-area">
            <!-- area title start -->
            <div class="col-sm-12">
                <div class="area-title bdr">
                    <h2><?= $this->cat_data['category_title'] ?></h2>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- area title end -->
            <div class="short-area mt20">
                <form id="sort_count" name="sort_count" method="post" action="#">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="sort-by">
                            <label>Sort by:</label>
                            <div class="select-sort-by">
                                <select class="inputbox" name="order" id="order">
                                    <option selected="selected" value="product_order">Predefined</option>
                                    <option value="product_title">Name</option>
                                    <option value="product_price"">Price</option>
                                    <!--                                    <option value="3">Date</option>-->
                                    <!--                                    <option value="5">Rating</option>-->
                                    <!--                                    <option value="6">Popular</option>-->
                                </select>
<!--                                <a href="#"><i class="fa fa-long-arrow-up"></i></a>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                        <div class="limiter">
                            <label>Show:</label>
                            <div class="select-limiter">
                                <select class="inputbox" name="limit" id="items_limit">
                                    <option value="99999">All</option>
                                    <option value="1">1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option selected="selected" value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <input type="hidden" id="page" value="1">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- product area start-->
            <div class="row row-margin2" id="products_container">
                <?php foreach ($this->products as $product) { ?>
                    <?php $attach = new CAttach($product['product_image']); ?>
                    <div class="col-lg-3 col-md-4  col-sm-6  col-xs-12  col-padd">
                        <div class="single-product">
                            
                            <div class="product-img">
                                <a href="<?= CUrlManager::GetURL(['type'=>'product','id'=>$product['product_group']]) ?>">
                                    <img class="primary-image" src="<?= $attach->GetURL(); ?>" alt="">
                                </a>
                            </div>
                            <div class="product-content">
                                <h2 class="product-name"><a href="#"><?= $product['product_title'] ?></a></h2>
                                
                                <div class="price-box">
                                    <span class="new-price"><?= CFrontCurrency::GetSymbol(); ?><?= $product['product_price'] ?></span>
                                </div>
                            </div>
                            <div class="product-content2">
                                <h2 class="product-name"><a href="#"><?= $product['product_title'] ?></a></h2>
                               
                                <div class="price-box">
                                    <span class="new-price"><?= CFrontCurrency::GetSymbol(); ?><?= $product['product_price'] ?></span>
                                </div>
                                <div class="button-container">
                                    <a title="Add to Cart" href="#" class="button cart_button">
                                        <span>Add to Cart</span>
                                    </a>
                                </div>
                            </div>
                            <ul class="add-to-links">
                                <li>
                                    <div class="wishlist">
                                        <a title="" href="#" data-toggle="tooltip"
                                           data-original-title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="view-products">
                                        <a title="" href="#" data-toggle="tooltip" data-placement="top"
                                           data-original-title="view pordcuts"><i class="fa fa-arrows-alt"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php if (isset($this->_data['page_count']) && $this->_data['page_count'] > 1) { ?>
                    <!--pagination-->
                    <div class="col-md-12">
                        <div class="pagination pt30">
                            <ul>
                                <?php for ($i = 1; $i <= $this->_data['page_count']; $i++) { ?>
                                    <li class="<?php if($i == 1 ) echo 'pagination-active' ?>"><a href="javascript" data-value="<?= $i ?>" data-action="pagination"><?= $i ?></a></li>
                                <?php } ?>
                                <!--                <li><a title="2" href="#" class="">2</a></li>-->
                                <!--                <li><a title="»" href="#" class="next">»</a></li>-->
                                <!--                <li><a title="End" href="#" class="">End</a></li>-->
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!--shop product area end-->

        <!--brand crasoule-area-start-->
    </div>
    <!--ambit-key-->
</div>
