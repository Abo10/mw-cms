<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li <?php if ($menu == 'home') echo 'class="active"'; ?>>
            <a href="index.php?menu=home"><i class="fa fa-fw fa-dashboard"></i> <?= CDictionary::GetKey('home') ?></a>

        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo" ><i class="fa fa-file-text-o" ></i> <?= CDictionary::GetKey('page') ?> <i class="fa fa-angle-left pull-right"></i></a>
            <ul id="demo"  <?php if ($menu != 'page') echo 'class="collapse"'; ?>>
                <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
                    <a href="index.php?menu=page&submenu=add"><?= CDictionary::GetKey('add') ?></a>
                </li>
                <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
                    <a href="index.php?menu=page&submenu=all"><?= CDictionary::GetKey('all') ?></a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#category" ><i class="fa fa-list-ul" ></i> <?= CDictionary::GetKey('cat_post') ?> <i class="fa fa-angle-left pull-right"></i></a>
            <ul id="category"  <?php if ($menu != 'post_category') echo 'class="collapse"'; ?>>
                <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
                    <a href="index.php?menu=post_category&submenu=add"><?= CDictionary::GetKey('add') ?> </a>
                </li>
                <li <?php if ($sub_menu == 'add_page') echo 'class="active"'; ?>>
                    <a href="index.php?menu=post_category&submenu=all2"><?= CDictionary::GetKey('all') ?> </a>
                </li>
            </ul>
        </li>


        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#post" ><i class="fa fa-file-o" ></i> <?= CDictionary::GetKey('post') ?><i class="fa fa-angle-left pull-right"></i></a>
            <ul id="post"  <?php if ($menu != 'post') echo 'class="collapse"'; ?>>
                <li <?php if ($sub_menu == 'add') echo 'class="active"'; ?>>
                    <a href="index.php?menu=post&submenu=add"><?= CDictionary::GetKey('add') ?> </a>
                </li>
                <li <?php if ($sub_menu == 'all') echo 'class="active"'; ?>>
                    <a href="index.php?menu=post&submenu=all"><?= CDictionary::GetKey('all') ?> </a>
                </li>
            </ul>
        </li>
        <li <?php if ($menu == 'post_attributes') echo 'class="active"'; ?>>
            <a href="index.php?menu=post_attributes&submenu=all"><i class="fa fa-code-fork"></i> <?= CDictionary::GetKey('post_attributes') ?> </a>
        </li>

        <li <?php if ($menu == 'tag') echo 'class="active"'; ?>>
            <a href="index.php?menu=tag&submenu=add"><i class="fa fa-code-fork"></i> <?= CDictionary::GetKey('tag') ?> </a>
        </li>


        <li <?php if ($menu == 'slider') echo 'class="active"'; ?>>
            <a href="index.php?module=slider&submenu=add"><i class="fa fa-align-justify"></i> <?= CDictionary::GetKey('slider') ?> </a>
        </li>

        <?php CModule::LoadTemplate('product_category','menu_button',['module'=>$module, 'sub_menu'=>$sub_menu]); ?>
        
        <?php CModule::LoadTemplate('product','menu_button',['module'=>$module, 'sub_menu'=>$sub_menu]); ?>

        <?php CModule::LoadTemplate('product_attributes','menu_button',['module'=>$module, 'sub_menu'=>$sub_menu]); ?>

        <?php CModule::LoadTemplate('brand','menu_button',['module'=>$module,'sub_menu'=>$sub_menu]); ?>

        <?php CModule::LoadTemplate('tags','menu_button',['module'=>$module,'sub_menu'=>$sub_menu]); ?>



        <li <?php if ($menu == 'menu') echo 'class="active"'; ?>>
            <a href="index.php?menu=menu&submenu=add"><i class="fa fa-align-justify"></i> <?= CDictionary::GetKey('menu') ?> </a>
        </li>
        <li <?php if ($menu == 'options') echo 'class="active"'; ?>>
            <a href="index.php?menu=options"><i class="fa fa-cogs"></i> <?= CDictionary::GetKey('options') ?> </a>
        </li>
        <li <?php if ($menu == 'translate') echo 'class="active"'; ?>>
            <a href="index.php?menu=translate&submenu=all"><i class="fa fa-flag-o"></i> <?= CDictionary::GetKey('translate') ?><?= CDictionary::GetKey('asdasdasd') ?> </a>
        </li>
        <li <?php if ($menu == 'translate_user') echo 'class="active"'; ?>>
            <a href="index.php?menu=translate_user&submenu=all"><i class="fa fa-flag"></i> <?= CDictionary::GetKey('translate_user') ?><?= CDictionary::GetKey('asdasdasd') ?> </a> 
        </li>
        <li <?php if ($menu == 'media') echo 'class="active"'; ?>>
            <a href="index.php?menu=media&submenu=media"><i class="fa fa-picture-o"></i> <?= CDictionary::GetKey('media') ?></a>
        </li>

        <?php CModule::LoadTemplate('checkout','menu_button',['module'=>$module,'sub_menu'=>$sub_menu]); ?>

        <?php CModule::LoadTemplate('user','menu_button',['module'=>$module,'sub_menu'=>$sub_menu]); ?>

        <li>
            <a href="index.php?menu=subscriptions"><i class="fa fa-fw fa-table"></i> Subscriptions</a>
        </li>
       <?php 
       		CModule::LoadTemplate('addressing', 'menu_button');
       ?>
    </ul>
</div>