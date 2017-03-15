<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#category2" ><i class="fa fa-list-ul" ></i> <?= CDictionary::GetKey('cat_product') ?> <i class="fa fa-angle-left pull-right"></i></a>
    <ul id="category2"  <?php if ($module != 'product_category') echo 'class="collapse"'; ?>>
        <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
            <a href="index.php?module=product_category&submenu=add"><?= CDictionary::GetKey('add') ?> </a>
        </li>
        <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
            <a href="index.php?module=product_category&submenu=all2"><?= CDictionary::GetKey('all') ?> </a>
        </li>
    </ul>
</li>