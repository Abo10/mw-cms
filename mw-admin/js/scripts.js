function replace_url_param(param, value) {
    href = location.href;
    href = updateQueryStringParameter(href, param, value)
    location.replace(href)
    //location.reload()
}
function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }

    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}
function handle_editor_attaches() {
    var instance_id = $('.tab-content').find('.active').find('textarea').attr('id');
    console.log(page_prop.submited_items)

    $.each(page_prop.submited_items, function (index, value) {
        var type = value.attach_type;
        if (type == 'image') {
            var img_source = value.attach_img_src;
            tinyMCE.execCommand('mceInsertContent', false, '<img alt="Smiley face" src="' + img_source+ '"/>');

        }
        if (type == 'document') {
            var link = $('<a>');
            $(link).html(value.attach_title);
            $(link).attr('href', value.attach_doc_src);
            var element = CKEDITOR.dom.element.createFromHtml('<p>' + $(link).prop('outerHTML') + '</p>');
            CKEDITOR.instances[instance_id].insertElement(element);
        }
    })
    $('#img_modal').modal('hide')
    page_prop.submited_items = [];
}
function handle_editor_attaches_ck() {
    var instance_id = $('.tab-content').find('.active').find('textarea').attr('id');
    console.log(page_prop.submited_items)

    $.each(page_prop.submited_items, function (index, value) {
        var type = value.attach_type;
        if (type == 'image') {
            var img_source = value.attach_img_src;
            var img = $('<img>');
            $(img).attr('src', img_source);
            img_source = img_source.replace('thumbs', 'original')
            //console.log(img_source)
            $("<img/>") // Make in memory copy of image to avoid css issues
                .attr("src", img_source)
                .load(function () {
                    pic_real_width = this.width;   // Note: $(value).width() will not
                    pic_real_height = this.height; // work for in memory images.
                    $(img).attr('width', pic_real_width);
                    $(img).attr('height', pic_real_height);
                    var img2 = $(img).prop('outerHTML');
                    img2 = img2.replace('thumbs', 'medium')
                    //console.log(img2)
                    var element = CKEDITOR.dom.element.createFromHtml('<p>' + img2 + '</p>');
                    CKEDITOR.instances[instance_id].insertElement(element);
                });
        }
        if (type == 'document') {
            var link = $('<a>');
            $(link).html(value.attach_title);
            $(link).attr('href', value.attach_doc_src);
            var element = CKEDITOR.dom.element.createFromHtml('<p>' + $(link).prop('outerHTML') + '</p>');
            CKEDITOR.instances[instance_id].insertElement(element);
        }
    })
    $('#img_modal').modal('hide')
    page_prop.submited_items = [];
}

function handle_galery_multiple_images() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-gal-name]').data('gal-name');
        var tmp = $('#gallery_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;
        //$(tmp).attr('data-gal-id')
        console.log(image_src);
        $(tmp).find('img').attr('src', image_src);
        page_prop.counter++;
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[' + page_prop.counter + '][id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[' + page_prop.counter + '][title]'
        });
        $(active_tab).find('[data-action=gallery_content]').append(tmp);
    })
    page_prop.submited_items = []
    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_gallery_button]').show();
    }


}

function handle_galery_multiple_covers() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-cover-name]').data('cover-name');
        var tmp = $('#gallery_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;
        //$(tmp).attr('data-gal-id')
        console.log(image_src);
        $(tmp).find('img').attr('src', image_src);
        page_prop.counter++;
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[' + page_prop.counter + '][id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[' + page_prop.counter + '][title]'
        });
        $(active_tab).find('[data-action=cover_content]').append(tmp);
    })
    page_prop.submited_items = []


    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_cover_button]').show();
    }

}

function handle_galery_multiple_gallery_covers() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-gallery-name]').data('gallery-name');
        var tmp = $('#gallery_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;
        //$(tmp).attr('data-gal-id')
        console.log(image_src);
        $(tmp).find('img').attr('src', image_src);
        page_prop.counter++;
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[' + page_prop.counter + '][id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[' + page_prop.counter + '][title]'
        });
        $(active_tab).find('[data-action=cover_gallery_content]').append(tmp);
    })
    page_prop.submited_items = []


    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_cover_gallery_button]').show();
    }

}

function handle_galery_multiple_attaches() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-gal-name]').data('gal-name');
        var tmp = $('#gallery_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;
        //$(tmp).attr('data-gal-id')
        console.log(image_src);
        // if type = document
        if (value.attach_type == 'document') {
            image_src = value.attach_doc_icon_src;
            $(tmp).find('input[type=text]').val(value.attach_title)
        }
        $(tmp).find('img').attr('src', image_src);
        page_prop.counter++;
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[' + page_prop.counter + '][id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[' + page_prop.counter + '][title]'
        });
        $(active_tab).find('[data-action=gallery_content]').append(tmp);
    })
    page_prop.submited_items = []

    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_gallery_button]').show();
    }


}

function handle_galery_single_image() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-gal-single-name]').data('gal-single-name');
        var tmp = $('#gallery_single_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;

        $(tmp).find('img').attr('src', image_src);
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[title]'
        });
        $(active_tab).find('[data-action=gallery_single_content]').html(tmp);
        console.log(tmp);

    })
    page_prop.submited_items = []

    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_main_image_button]').show();
    }

}
function handle_share_single_share_image() {
    console.log(page_prop)
    var active_tab = $('.tab-content').find('.active');

    $.each(page_prop.submited_items, function (index, value) {
        var name = $(active_tab).find('[data-gal-single-share-name]').data('gal-single-share-name');
        var tmp = $('#gallery_single_item_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;

        $(tmp).find('img').attr('src', image_src);
        $(tmp).find('input[type=hidden]').attr({
            name: name + '[id]',
            value: image_id
        });
        $(tmp).find('input[type=text]').attr({
            name: name + '[title]'
        });
        $(active_tab).find('[data-action=gallery_single_share_content]').html(tmp);
        console.log(tmp);

    })
    page_prop.submited_items = []

    if ($(active_tab).first().is(':first-child')) {
        $('[data-action=copy_main_image_button]').show();
    }

}

function handle_map_single_image() {


    $.each(page_prop.submited_items, function (index, value) {
        var name = "lang[map][img_id]";
        var tmp = $('#map_single_image_template').clone().removeAttr('id');
        var image_src = value.attach_img_src;
        var image_id = value.attach_id;

        $(tmp).find('img').attr('src', image_src);
        $(tmp).find('input[type=hidden]').attr({
            name: name,
            value: image_id
        });
        $(tmp).find('input[type=text]').remove()
        $('#map_modal').find('[data-action="map_image_container"]').html(tmp);
    })
    page_prop.submited_items = []


}


$(function () {
    $(document).on('click', '[data-action=gal-delete]', function () {
        $(this).closest('.gallery_item').remove()
        var active_tab = $('.tab-content').find('.active').first();
        if ($(active_tab).find('[data-action=cover_content]').children().length == 0) {
            $('[data-action="copy_cover_button"]').hide();
        }
        if ($(active_tab).find('[data-action=cover_gallery_content]').children().length == 0) {
            $('[data-action="copy_cover_gallery_button"]').hide();
        }
        if ($(active_tab).find('[data-action="gallery_content"]').children().length == 0) {
            $('[data-action="copy_gallery_button"]').hide();
        }
        if ($(active_tab).find('[data-action="gallery_single_content"]').children().length == 0) {
            $('[data-action="copy_main_image_button"]').hide();
        }

    })
    $(document).on('click', '[data-action=map-icon-delete]', function () {
        $(this).closest('.map_icon').remove()
    })
    $(document).on('click', '[data-action=delete-map-item]', function () {
        var u_id = $(this).closest('[data-u-id]').data('u-id');
        $(this).closest('[data-u-id]').remove();
        $.each(markers, function (i, v) {
            if (v.u_attr == u_id) {
                v.setMap(null)
            }
        })
    })
    $(document).on('click', '[data-action=map-nav-panel] a', function () {
        var lang = $(this).data('href');
        $('.map-tab-content').find("[data-action=map-lang-item]").hide()
        $('.map-tab-content').find("[data-action=map-lang-item][data-value=" + lang + "]").show()

    })
    $('.mw-tree input[type=checkbox]').on('click', function () {
        var val = $(this).val();
        if ($(this).prop('checked')) {
            $('.mw-tree input[value=' + val + ']').closest('li').find('>ul').show()
        } else {
            $('.mw-tree input[value=' + val + ']').closest('li').find('input[type=checkbox]').prop('checked', false)
            $('.mw-tree input[value=' + val + ']').closest('li').find('ul').hide()
        }
    })
    $('[data-action="edit_slug"]').on('click', function () {
        $(this).closest('[data-action="edit_slug_container"]').find('[data-action="slug-value"]').prop('readonly', false);
        $(this).closest('[data-action="edit_slug_container"]').find('[data-action="save_edited_slug"]').show();
        $(this).hide();
    })
    $('[data-action="save_edited_slug"]').on('click', function () {
        var url = $(this).closest('[data-action="edit_slug_container"]').data('url');
        var id = $(this).closest('[data-action="edit_slug_container"]').data('id');
        var lang = $(this).closest('[data-action="edit_slug_container"]').data('lang');
        var slug = $(this).closest('[data-action="edit_slug_container"]').find('[data-action="slug-value"]').val();
        var button = $(this)
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                action: 'edit_slug',
                slug: slug,
                lang: lang,
                id: id
            },
            success: function (msg) {
                console.log(msg)
                try{
                    var decoded = JSON.parse(msg);
                    console.log(decoded)
                    if(decoded.status == 1){
                        console.log(1)
                        $(button).closest('[data-action="edit_slug_container"]').find('[data-action="slug-value"]').prop('readonly', true).val(decoded.message);
                        $(button).closest('[data-action="edit_slug_container"]').find('[data-action="edit_slug"]').show();
                        $(button).hide();
                    }else{
                        $(button).closest('[data-action="edit_slug_container"]').find('[data-action="slug-value"]').prop('readonly', true);
                        $(button).closest('[data-action="edit_slug_container"]').find('[data-action="edit_slug"]').show();
                        $(button).hide();
                        alert(decoded.message)
                    }
                }catch(err){

                }

            }
        })

    })
})
