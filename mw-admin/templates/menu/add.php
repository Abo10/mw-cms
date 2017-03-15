<?php
ini_set('max_execution_time', 5);
ini_set('display_errors', true);
error_reporting(E_ALL);
$current_lang = CLanguage::getInstance()->getCurrent();
$current_lang_user = CLanguage::getInstance()->getDefaultUser();

$pages = new CAllPages();
$page = $pages->GetAsArray();

$post_category_obj = new CCategoryPost();
$post_category = $post_category_obj->GetList_Title();

//var_dump($post_category);


$post = new CPost();
$posts = $post->GetList_Title();

?>

<script type="text/javascript" src="js/nestedSortable-2.0alpha/jquery.mjs.nestedSortable.js"></script>
<script>

    $(document).ready(function () {

        $('.sortable').nestedSortable({
//            listType: 'ul',
//            handle: 'div',
            items: 'li',
            isTree: true,
            toleranceElement: '> div',
            //maxLevels: 3
        });

        $(document).on('click', '.menu_item_head', function () {
            $(this).parent().find('.menu_item_detailed').toggle();

        })
        $(document).on('click', '.menu_item_delete_button', function (e) {
            e.stopPropagation();
            $(this).closest('li').remove();

        })
        $(document).on('click', '[data-action=delete_menu_img]', function (e) {
            $(this).closest('.img_menu_item_block').find('[data-action=menu_item_attach_id]').val('');
            $(this).closest('.menu_item_img_template').remove()

        })
        $('[data-action=add_menu]').on('click', function () {
            var menu_name = $("[data-action=add_menu_name]").val();
            if (!menu_name) {
                alert("Please fill the name field");
                return;
            }
            ;
            $.ajax({
                type: "POST",
                url: "index.php?menu=menu&submenu=action",
                data: {
                    menu_name: menu_name,
                    action: 'add_menu'
                },
                success: function (msg) {
                    var data = JSON.parse(msg);
                    //console.log(data);
                    if (data['status']) {
                        $('[data-action=m_group_select]').append('<option value="' + data.menu_id + '">' + data.menu_name + '</option>');
                        $('[data-action=m_group_select]').val(data.menu_id);
                        $('[data-action=m_group_select]').trigger('change');
                        $("[data-action=add_menu_name]").val('')
                    } else {
                        alert('Menu already exists');
                    }
                }
            })

        })
        $('[data-action=add_custom_menu_item]').on('click', function () {
            var elem = $("#menu_item_template").clone();
            $(elem).attr('data-m_type', 'custom_link');
            $(elem).attr('data-m_elem_id', '');
            $(elem).removeAttr('id');
            $(elem).find('.menu_item_detailed').show();
            $(elem).find('.url_tab').show();
            $(elem).find('[data-action=menu-type]').html('Custom link');
            $('.sortable').append(elem);
//            console.log('asdasdads')
        })
        $("[data-action=add_to_menu]").on('click', function () {
            var selected_items = $(this).parent().find('ul').find('input[type=checkbox]:checked')
            var default_lang = $('#default_lang').val();
//            console.log(default_lang)
            selected_items.each(function (i, v) {
                var m_type = $(v).data('m_type');
                var m_type_label = $(v).data('m_type_label');
                var m_elem_id = $(v).data('m_elem_id');
                var titles = $(v).data('titles');
//                console.log(titles['am']);
                var elem = $("#menu_item_template").clone();
                $(elem).attr('data-m_type', m_type);
                $(elem).attr('data-m_elem_id', m_elem_id);
                $(elem).find('[data-action=menu-title]').attr('data-title-langs', JSON.stringify(titles));
                $(elem).find('[data-action=menu-title]').html(titles[default_lang]);
                $.each(titles, function (i2, v2) {
                    //$(elem).find('[data-action=menu_item_title][data-lang=' + i2 + ']').val(v2);
                })
                $(elem).removeAttr('id');
                $(elem).find('[data-action=menu-type]').html(m_type_label)


                $('.sortable').append(elem);
            })
            $(this).closest('[data-action="menu-container"]').find('input[type=checkbox]').prop('checked',false)

        })
        $(document).on('click', '[data-action=img_menu_item_button]', function () {
            var button = $(this);
            $.ajax({
                url: 'index.php?menu=media&submenu=action',
                type: 'POST',
                data: {
                    action: 'show_media',
                    type: 'images',
                    allow_change_type: 0,
                    selected_limit: 1
                },
                success: function (msg) {
                    $('#img_modal .modal-body').html(msg);
                    $('#img_modal').modal();

                    $(document).on('click', '#add_attachment', function () {
                        $(document).off('click', '#add_attachment')
                        var img_id = page_prop.submited_items[0].attach_id;
                        var img_url = page_prop.submited_items[0].attach_img_src;
                        var img_clone = $('#menu_item_img_template').clone();
                        $(img_clone).removeAttr('id');
                        $(img_clone).find('.menu_item_img').attr('src', img_url);

                        $(button).parent().find('.menu_img_container').html(img_clone)
                        $(button).parent().find('[data-action=menu_item_attach_id]').val(img_id);
                    })

                    return

                }
            })
        })
        $('#save_menu').on('click', function () {

            var selected = $("[data-action=m_group_select]").val();
            //console.log(selected);
            if (!selected) {
                alert("Please select menu");
                return;
            }
            ;

            var elements = $('#sortable').find('[data-action=menu_item]')
            //console.log(elements)
            $.each(elements, function (i, v) {
                $(v).attr('data-elem_id', ++i);
                var find_parent = $(v).parents('[data-action=menu_item]')
                //console.log(find_parent)
                if (find_parent.length > 0) {
                    var parent_id = find_parent.data('elem_id')
                    $(v).attr('data-menu_pid', parent_id)
                } else {
                    $(v).attr('data-menu_pid', 0)
                }

            })
            var lang_keys = $.parseJSON($('#langs').val());
            //console.log(lang_keys);
            var ret_array = {};

            var m_group = $('[data-action=m_group_select]').val();
            $.each(elements, function (i, v) {

                var m_type = $(v).data('m_type');
                var m_type_label = $(v).data('m_type_label');
                var m_elem_id = $(v).data('m_elem_id');
                var menu_pid = $(v).data('menu_pid');
                var elem_id = $(v).data('elem_id');
                var inner_obj = {
                    menu_text: "",
                    menu_pid: menu_pid,
                    menu_url: null,
                    m_group: m_group,
                    m_tab: false,
                    m_attr: null,
                    m_type: m_type,
                    m_class: null,
                    m_elem_id: m_elem_id,
                    jq_handle: null
                };

                var langs_arr = {}
                $.each(lang_keys, function (i2, v2) {
                    var menu_text = $(v).find(' > .menu_item').find('[data-action=menu_item_title][data-lang=' + v2 + ']').val();
                    var m_class = $(v).find(' > .menu_item').find('[data-action=menu_item_class]').val();
                    var jq_handle = $(v).find(' > .menu_item').find('[data-action=menu_item_bandle][data-lang=' + v2 + ']').val();
                    var menu_url = $(v).find(' > .menu_item').find('[data-action=menu_item_url][data-lang=' + v2 + ']').val();
                    var m_attr = $(v).find(' > .menu_item').find('[data-action=menu_item_attach_id]').val();
                    var m_tab = $(v).find(' > .menu_item').find('[data-action=menu_item_blank_tab]').prop('checked') ? 1 : 0;
                    inner_obj.menu_text = menu_text;
                    inner_obj.m_class = m_class;
                    inner_obj.jq_handle = jq_handle;
                    inner_obj.m_attr = m_attr;
                    inner_obj.menu_url = menu_url;
                    inner_obj.m_tab = m_tab;

                    var new_inner_obj = jQuery.extend({}, inner_obj);
                    //console.log(new_inner_obj);
                    langs_arr[v2] = new_inner_obj;
                })
                ret_array[elem_id] = langs_arr;


            })
            //console.log(ret_array);
            //return;
            var ret_arr_json = JSON.stringify(ret_array)
            $.ajax({
                type: "POST",
                url: "index.php?menu=menu&submenu=action",
                data: {
                    data: ret_arr_json,
                    action: 'add_menu_elements'
                },
                success: function (msg) {
                    alert('Successfully added')
                    //console.log(msg)
                }
            })
            //console.log(ret_array);
        })
        $('[data-action=m_group_select]').on('change', function () {
            var id = $(this).val();
            if (id) {
                $('#menu_container').find('.col-md-9').show();
            } else {
                $('#menu_container').find('.col-md-9').hide();
            }
            $.ajax({
                type: "POST",
                url: "index.php?menu=menu&submenu=action",
                data: {
                    id: id,
                    action: 'get_menu_elements'
                },
                success: function (msg) {
                    console.log(msg)
                    if (msg.length > 3) {
                    }
                    $('#sortable').html(msg);
                }
            })
        })
        $('[data-action=lang_button]').on('click', function () {
            $('[data-action=lang_button]').removeClass('active_menu');
            $(this).addClass('active_menu');
            var lang = $(this).data('value');
            var default_lang = $('#default_lang').val();
            $('[data-action=item_labels_by_langs]').each(function (i, v) {
                json_data = $(this).data('value');
                //var json_data = JSON.parse();

                if (json_data[lang]) {
                    $(v).html(json_data[lang])
                    $(v).closest('li').find('[data-action=translate_status]').hide();
                    $(v).removeClass('menu-error')
                } else {
                    json_data[default_lang]
                    $(v).closest('li').find('[data-action=translate_status]').show();
                    $(v).addClass('menu-error')

                }

                //for inner menu elements
                $('[data-lang]').hide()
                $('[data-lang=' + lang + ']').show()


            });
            $('#menu_items_container [data-action=menu_item] > .menu_item').each(function (i, v) {
                var lang_data = $(v).find('[data-title-langs]').data('title-langs');
                var val = lang_data[lang];
                console.log(v)
                if (val) {
                    $(v).find('[data-action=menu-title]').removeClass('menu-error')
                    $(v).find('[data-action=menu-title]').html(val)
                } else {
                    $(v).find('[data-action=menu-title]').html(lang_data[default_lang])
                    $(v).find('[data-action=menu-title]').addClass('menu-error')
                }
            })

        })
        $('[data-action=search]').on('keyup', function () {
            var inp_val = $(this).val()
            var li_elems = $(this).closest('[data-action=menu-container]').find('ul').find('[data-action=item_labels_by_langs]')
            $.each(li_elems, function (i, v) {
                var data = $(v).data('value');
                var data_str = '';
                for (var a in data) {
                    data_str += data[a];
                }
                var re = new RegExp(inp_val, "i");
                if (data_str.match(re)) {
                    $(v).closest('li').show();
                } else {
                    $(v).closest('li').hide();
                }

            })
        })
        $('[data-action=m_group_select]').val(1)
        $('[data-action=m_group_select]').trigger('change')


    });
</script>
<style>
    .menu_item {
    / / border: 1 px solid black;
        width: 400px;
        margin: 3px;
    }

    .menu_item_head {
        border: 2px solid black;
        width: 400px;
    }

    .menu_item_detailed {
        border: 1px dotted black;
    }

    #menu_items_container ol {
    / / list-style: none;
    }

    [data-action=menu-title] {
    / / display: inline-block;
    / / width: 82 %;
    }

    .menu_item_img {
        width: 40px;
    }

    .menu_item_img_template {
        display: inline;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="form-group" style="    position: absolute;left: 27%;top: 56px;z-index: 99;">
                    <select name="" id="" class="form-control" data-action="m_group_select">
                        <option value=""><?= CDictionary::GetKey('browse'); ?></option>

                        <?php
                        $menu = new CStdMenu();
                        $m_list = $menu->GetMenusList();
                        foreach ($m_list as $item) {
                            ?>
                            <option value="<?= $item['menu_id'] ?>"><?= $item['menu_name'] ?></option>

                        <?php } ?>
                    </select>
                    <input type="text" data-action="add_menu_name" class="form-control">
                    <button data-action="add_menu" class="btn btn-success"><?= CDictionary::GetKey('add') ?></button>

                </div>
                <?php foreach ($user_langs as $key => $item) {
                    if ($key == 0) {
                        $active_class = 'active_menu';
                    } else {
                        $active_class = '';
                    }
                    ?>
                    <button class="btn btn-primary <?= $active_class ?>" data-action="lang_button" data-value="<?= $item['key'] ?>">
                        <?= $item['title'] ?>
                    </button>
                <?php } ?>
                <button class="btn btn-success" id="save_menu"><?= CDictionary::GetKey('save') ?></button>
            </div>

        </div>
    </div>

    <div class="row" id="menu_container">
        <div class="col-md-3 mw_menu">

            <div data-action="menu-container" class="mw_menu_child">
                <input type="hidden" id="langs"
                       value='<?= json_encode(CLanguage::getInstance()->get_lang_keys_user()); ?>'>
                <input type="hidden" id="default_lang" value='<?= $current_lang_user ?>'>
                <div class="mw_menu_child_title"><?= CDictionary::GetKey('pages') ?></div>
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="search"
                           placeholder="<?= CDictionary::GetKey('search') ?>" data-action="search"
                           value="">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                </div>
                <ul class="list-unstyled" style="max-height: 200px;overflow: auto">
                    <?php foreach ($page as $item) { ?>
                        <?php
                        $json_array = [];
                        foreach ($item as $key2 => $item2) {
                            $json_array[$key2] = $item2['page_title'];
                        }
                        ?>
                        <li>
                            <label class="mycheckbox">
                                <input type="checkbox" data-m_elem_id="<?= $item[$current_lang_user]['pid'] ?>"
                                       data-m_type="page"
                                       data-m_type_label="<?= CDictionary::GetKey('page') ?>"
                                       data-titles='<?= json_encode($json_array) ?>'>
                                <span class="checkbox_span"></span>
                                <span data-action="item_labels_by_langs"
                                      data-value='<?= json_encode($json_array) ?>'><?= $item[$current_lang_user]['page_title'] ?></span>
                                <span data-action="translate_status" style="display:none"><a
                                        href="<?= ADMIN_URL ?>index.php?menu=page&submenu=add&edit_id=<?= $item[$current_lang_user]['pid'] ?>"
                                        target="_blank"><?= CDictionary::GetKey('not_translated') ?></a></span>
                            </label>
                        </li>
                    <?php } ?>

                </ul>
                <button class="btn btn-sm btn-success"
                        data-action="add_to_menu"><?= CDictionary::GetKey('add') ?></button>
            </div>
            <!--            Cat post-->
            <div data-action="menu-container" class="mw_menu_child">
                <div class="mw_menu_child_title"><?= CDictionary::GetKey('cat_post') ?></div>
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="search"
                           placeholder="<?= CDictionary::GetKey('search') ?>" data-action="search"
                           value="">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                </div>
                <ul class="list-unstyled" style="max-height: 200px;overflow: auto">
                    <?php foreach ($post_category as $key => $item) { ?>
                        <?php //var_dump($item); ?>
                        <li>
                            <label class="mycheckbox">
                                <input type="checkbox" data-m_elem_id="<?= $key ?>"
                                       data-m_type="post_category"
                                       data-m_type_label="<?= CDictionary::GetKey('cat_post') ?>"
                                       data-titles='<?= json_encode($item) ?>'><?= $post_category_obj->GetTree($item['level']) ?>
                                <span class="checkbox_span"></span>

                                <span data-action="item_labels_by_langs"
                                      data-value='<?= json_encode($item) ?>'><?= $item[$current_lang_user] ?></span>
                                <span data-action="translate_status" style="display:none"><a
                                        href="<?= ADMIN_URL ?>index.php?menu=post_category&submenu=add&edit_id=<?= $key ?>"
                                        target="_blank"><?= CDictionary::GetKey('not_translated') ?></a></span>


                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <button class="btn btn-sm btn-success"
                        data-action="add_to_menu"><?= CDictionary::GetKey('add') ?>
                </button>
            </div>
            <!--            Post-->
            <div data-action="menu-container" class="mw_menu_child">
                <div class="mw_menu_child_title"><?= CDictionary::GetKey('post') ?></div>
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="search"
                           placeholder="<?= CDictionary::GetKey('search') ?>" data-action="search"
                           value="">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                </div>
                <ul class="list-unstyled" style="max-height: 200px;overflow: auto">
                    <?php foreach ($posts as $key => $item) { ?>
                        <?php
                        $new_arr = array();
                        foreach ($item as $key2 => $item2) {
                            $new_arr[$key2] = $item2['post_title'];
                        }
                        ?>
                        <li>
                            <label class="mycheckbox">

                                <input type="checkbox" data-m_elem_id="<?= $key ?>"
                                       data-m_type="post"
                                       data-m_type_label="<?= CDictionary::GetKey('post') ?>"
                                       data-titles='<?= json_encode($new_arr) ?>'>
                                <span class="checkbox_span"></span>

                                <span data-action="item_labels_by_langs"
                                      data-value='<?= json_encode($new_arr) ?>'><?= $item[$current_lang_user]['post_title'] ?></span>

                                <span data-action="translate_status" style="display:none"><a
                                        href="<?= ADMIN_URL ?>index.php?menu=post&submenu=add&edit_id=<?= $key ?>"
                                        target="_blank"><?= CDictionary::GetKey('not_translated') ?></a></span>

                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <button class="btn btn-sm btn-success"
                        data-action="add_to_menu"><?= CDictionary::GetKey('add') ?>
                </button>
            </div>

            <!--            cat product_module-->
            <?php if (CModule::HasModule('product_category')) { ?>
                <?php $product_category_obj = CModule::LoadModule('product_category');
                $product_category = $product_category_obj->GetList_Title(); ?>
                <div data-action="menu-container" class="mw_menu_child">
                    <div class="mw_menu_child_title"><?= CDictionary::GetKey('cat_product') ?></div>
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" name="search"
                               placeholder="<?= CDictionary::GetKey('search') ?>" data-action="search"
                               value="">
                    <span class="input-group-addon" id="search_but">
                            <span class="fa fa-search"></span>
                    </span>
                    </div>
                    <ul class="list-unstyled" style="max-height: 200px;overflow: auto">
                        <?php foreach ($product_category as $key => $item) { ?>
                            <?php //var_dump($item); ?>
                            <li>
                                <label class="mycheckbox">
                                    <input type="checkbox" data-m_elem_id="<?= $key ?>"
                                           data-m_type="product_category"
                                           data-m_type_label="<?= CDictionary::GetKey('cat_product') ?>"
                                           data-titles='<?= json_encode($item) ?>'><?= $post_category_obj->GetTree($item['level']) ?>
                                    <span class="checkbox_span"></span>

                                <span data-action="item_labels_by_langs"
                                      data-value='<?= json_encode($item) ?>'><?= $item[$current_lang_user] ?></span>
                                    <span data-action="translate_status" style="display:none"><a
                                            href="<?= ADMIN_URL ?>index.php?menu=product_category&submenu=add&edit_id=<?= $key ?>"
                                            target="_blank"><?= CDictionary::GetKey('not_translated') ?></a></span>


                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                    <button class="btn btn-sm btn-success"
                            data-action="add_to_menu"><?= CDictionary::GetKey('add') ?>
                    </button>
                </div>
            <?php } ?>


            <button class="btn btn-sm btn-success"
                    data-action="add_custom_menu_item"><?= CDictionary::GetKey('add') ?> Custom link
            </button>
        </div>
        <div class="col-md-9">

            <section id="menu_items_container">
                <div class="men u_all_desc">
                    <h4> <?= CDictionary::GetKey('menu_desc_big') ?></h4>
                    <p> <?= CDictionary::GetKey('menu_desc_small') ?></p>


                </div>
                <ol class="sortable" id="sortable">

                </ol>
            </section>
        </div>
    </div>
</div>

<div class="modal fade" id="img_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <input type="hidden" data-id>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= CDictionary::GetKey('media') ?></h4>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div style="display: none;">
    <div class="menu_item_img_template" id="menu_item_img_template">
        <img class="menu_item_img" src="" alt="">
        <i class="fa fa-times" data-action="delete_menu_img"></i>
    </div>
    <li id="menu_item_template" data-action="menu_item">
        <div class="menu_item">
            <div class="menu_item_head">
                <span data-action="menu-type">Home page</span>
                <span> : </span>
                <span data-action="menu-title">Home page</span>
                <i class="fa fa-fw fa-caret-down"></i>
                <span class="menu_item_delete_button">
                    <i class="fa fa-times"></i>
                </span>
            </div>
            <div style="display: none" class="menu_item_detailed">
                <div>
                    <span><?= CDictionary::GetKey('title') ?></span>
                    <?php $bool_check = true; ?>
                    <?php foreach (CLanguage::getInstance()->get_lang_keys_user() as $key => $item) { ?>
                        <input type="text" data-lang="<?= $item ?>" data-action="menu_item_title" class=" input-sm"
                               placeholder="Title" <?php if ($bool_check) {
                            $bool_check = false;
                        } else {
                            echo 'style="display: none"';
                        } ?>>
                    <?php } ?> 
                    <span class="img_menu_item_block">
                        <button type="button" class="btn btn-primary btn-sm" data-action="img_menu_item_button"><?= CDictionary::GetKey('img') ?>
                        </button>
                                <input type="hidden" data-action="menu_item_attach_id"/>

                        <span class="menu_img_container">

                        </span>

                    </span>
                </div>
                <div class="url_tab" style="display: none">
                    <span><?= CDictionary::GetKey('url') ?></span> 
                    <?php $bool_check = true; ?>
                    <?php foreach (CLanguage::getInstance()->get_lang_keys_user() as $key => $item) { ?>
                        <input type="text" data-lang="<?= $item ?>" data-action="menu_item_url" class=" input-sm"
                               placeholder="Title" <?php if ($bool_check) {
                            $bool_check = false;
                        } else {
                            echo 'style="display: none"';
                        } ?>>
                    <?php } ?>
                </div>
                <div>
                    <span>Class</span>
                    <input type="text" data-action="menu_item_class" class=" input-sm" placeholder="Class">
                    <span><?= CDictionary::GetKey('new_tab') ?></span> 
                    <input type="checkbox" data-action="menu_item_blank_tab">
                </div>
                <div>
                    <span>Attributes</span>
                    <?php $bool_check = true; ?>
                    <?php foreach (CLanguage::getInstance()->get_lang_keys_user() as $key => $item) { ?>
                        <input type="text" data-lang="<?= $item ?>" data-action="menu_item_bandle" class=" input-sm"
                               placeholder="Title" <?php if ($bool_check) {
                            $bool_check = false;
                        } else {
                            echo 'style="display: none"';
                        } ?>>
                    <?php } ?>
                </div>

            </div>
        </div>

    </li>
    <div/>