/**
 * Created by abo on 12/8/2015.
 */
CKEDITOR.plugins.add('mwplugin',
    {
        init: function (editor) {
            var pluginName = 'mwplugin';
            editor.ui.addButton('Newplugin',
                {
                    label: 'My New Plugin',
                    command: 'OpenWindow',
                    icon: CKEDITOR.plugins.getPath('mwplugin') + 'mybuttonicon.png'
                });
            var cmd = editor.addCommand('OpenWindow', { exec: showGallery });
        }
    });

function showGallery(e) {

    $.ajax({
        url:'index.php?menu=gallery&submenu=action',
        type:'POST',
        data:{
            action:'show_gallery'
        },
        success:function(msg){
            $('#img_modal .modal-body').html(msg);
            $('#img_modal').modal();
            console.log(msg)
        }
    })
}