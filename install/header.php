<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">Masterweb Admin</a>
</div>
<!-- Top Menu Items -->
<ul class="nav navbar-right top-nav">
    <li>
        <a href="<?= URL_BASE ?>" target="_blank">Site</a>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">lang<b
                class="caret"></b></a>
        <ul class="dropdown-menu ">
            <?php foreach(CLanguage::getInstance()->get_lang_keys() as $lang){ ?>
            <li>
                <a href="index.php?change_lang=<?= $lang ?>"><?= $lang ?> </a>
            </li>
            <?php } ?>
        </ul>
    </li>
     
     
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                class="fa fa-user"></i> <?= CUserAdmin::GetLogin() ?><b
                class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="login.php?logout=yes"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
            </li>
        </ul>
    </li>
</ul>