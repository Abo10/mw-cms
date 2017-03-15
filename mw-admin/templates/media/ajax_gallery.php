<!--<div id="page-wrapper">-->
<!---->
<!--    <div class="container-fluid">-->
<!---->
<!--        <div>-->
<!--            <div class="row">-->
<!--                <div class="col-lg-5 ">-->
<!--                   <span class="btn btn-default btn-file">-->
<!--                        Browse <input type="file" id="gallery_file" multiple>-->
<!--                        <span id="ajax_load" style="display: none">-->
<!--                            <img src="--><?//= URL_BASE ?><!--web/res/ajax-loader.gif" alt="">-->
<!--                        </span>-->
<!---->
<!--                    </span>-->
<!--                    <button class="btn btn-default" id="add_gallery_items">Add Gallery</button>-->
<!--                    Selected items <span id="selected_attachment">0</span>-->
<!--                </div>-->
<!---->
<!--                <div class="col-lg-3 col-lg-offset-4">-->
<!--                    <div class="input-group">-->
<!--                        <input type="text" class="form-control" placeholder="Search" id="gallery_search">-->
<!--                    <span class="input-group-addon">-->
<!--                            <span class="fa fa-search" id="search_but"></span>-->
<!--                    </span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <!-- Tab panes -->-->
<!--            <div class="tab-content">-->
<!---->
<!--                <div role="tabpanel" class="tab-pane active" id="tabnavall">-->
<!--                    --><?php
//                    $gallery = new CAttachGallery();
//                    $items = $gallery->GetGallery('images');
//                    $icon_obj = CIcons::getInstance();
//                    foreach ($items as $value) {
//                        ?>
<!--                        <div class="col-md-2 attachment_item" data-attach-id="--><?//= $value['id'] ?><!--"-->
<!--                             data-attach-title="--><?//= $value['title'] ?><!--" data-attach-type="--><?//= $value['type'] ?><!--">-->
<!--                            <div class="gal_toolbar">-->
<!--                                <input type="checkbox" data-action="gallery-checkbox" style="display: none;">-->
<!--                            </div>-->
<!--                            --><?php //if ($value['type'] == 'document') { ?>
<!--                                <img src="--><?//= $icon_obj->GetIcon($value['ext']) ?><!--" class="img-responsive" alt="">-->
<!--                                <a href="--><?//= $value['url'] ?><!--"><span>--><?//= $value['title'] ?><!--</span></a>-->
<!--                            --><?php //} ?>
<!--                            --><?php //if ($value['type'] == 'image') { ?>
<!--                                <img src="--><?//= $value['url_thumb'] ?><!--" class="img-responsive" alt="">-->
<!--                                <span>--><?//= $value['title'] ?><!--</span>-->
<!---->
<!--                            --><?php //} ?>
<!--                        </div>-->
<!--                        --><?php
//                    }
//                    ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <!-- /.container-fluid -->-->
<!--</div>-->
<!---->
<!--<script>-->
<!--    $('#gallery_search').on('keyup', function () {-->
<!--        var search_query = $(this).val();-->
<!--        if (search_query) {-->
<!--            $('[data-attach-title]').hide()-->
<!--            $('[data-attach-title*="' + search_query + '"]').show()-->
<!--        } else {-->
<!--            $('[data-attach-title]').show()-->
<!--        }-->
<!--    })-->
<!--    $('[role=tablist] a').on('click', function () {-->
<!--        if ($(this).attr('href') == '#tabnavall') {-->
<!--            $('[data-attach-type]').show()-->
<!--        }-->
<!--        if ($(this).attr('href') == '#tabnavimage') {-->
<!--            $('[data-attach-type]').hide()-->
<!--            $('[data-attach-type=image]').show()-->
<!--        }-->
<!--        if ($(this).attr('href') == '#tabnavdocs') {-->
<!--            $('[data-attach-type]').hide()-->
<!--            $('[data-attach-type=document]').show()-->
<!--        }-->
<!--    })-->
<!--    $('#gallery_file').on('change', function () {-->
<!--        var formdata = new FormData();-->
<!--        var files = $('#gallery_file')[0].files;-->
<!--        $(files).each(function (index, value) {-->
<!--            formdata.append('file' + index, value);-->
<!--        })-->
<!--        formdata.append('action', 'file_upload');-->
<!--        $.ajax({-->
<!--            url: 'index.php?menu=media&submenu=action',-->
<!--            data: formdata,-->
<!--            processData: false,-->
<!--            contentType: false,-->
<!--            type: 'POST',-->
<!--            beforeSend: function () {-->
<!--                $('#ajax_load').show();-->
<!--            },-->
<!--            success: function (data) {-->
<!--                $('[data-action=gallery_button]').trigger('click')-->
<!--//                    alert(data);-->
<!--//                    console.log(data)-->
<!--                //location.reload();-->
<!--            }-->
<!--        });-->
<!--    })-->
<!--</script>-->
<!---->
