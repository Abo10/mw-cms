<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#product" ><i class="fa fa-shopping-cart" ></i> <?= CDictionary::GetKey('products') ?> <i class="fa fa-angle-left pull-right"></i></a>
    <ul id="product"  <?php if ($module != 'product_category') echo 'class="collapse"'; ?>>
        <li <?php if ($sub_menu == 'add') echo 'class="active"'; ?>>
            <a href="index.php?module=product&submenu=add"><?= CDictionary::GetKey('add') ?> </a>
        </li>
        <li <?php if ($sub_menu == 'all') echo 'class="active"'; ?>>
            <a href="index.php?module=product&submenu=all"><?= CDictionary::GetKey('all') ?> </a>
        </li>
    </ul>
</li>