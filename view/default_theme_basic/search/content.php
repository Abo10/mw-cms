
<!-- creative banner area end -->

<!-- main area start -->
<div class="main-area">
    <div class="container">
        <div class="row">
            <?php include_once __DIR__ . '/filters.php' ?>
            <!--col-md-3-->

            <?php include_once __DIR__ . '/product_list.php' ?>
            <!--col-md-9-->
        </div>
        <!--row-->
    </div>
    <!--container-->
</div>
<!-- main area end -->
<input type="hidden" id="url_base" value="<?= CUrlManager::GetURL(['type'=>'home','id'=>1]) ?>">
<script>


</script>