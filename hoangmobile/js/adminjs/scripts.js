/* 
 * @Author: Ngo Van Thang
 * @Email: ngothangit@gmail.com
 */

var CustomJS = function(){
    return {
        uploadSingleImage: function($){
            var fields = new Array("favicon", "sitelogo", "ad_site_left", "ad_site_right", "ad_product_category");
            
            $.each(fields, function(index, field){
                var custom_uploader;
                $('#upload_' + field + '_button').click(function(e) {
                    e.preventDefault();

                    //If the uploader object has already been created, reopen the dialog
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }

                    //Extend the wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false
                    });

                    //When a file is selected, grab the URL and set it as the text field's value
                    custom_uploader.on('select', function() {
                        attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#' + field).val(attachment.url);
                    });

                    //Open the uploader dialog
                    custom_uploader.open();
                });
            });
        },
        uploadAds: function($){
            var custom_uploader;
            $('#upload_media_button').click(function(e) {
                e.preventDefault();
 
                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
 
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    frame: 'select', // 'post'
                    state: 'upload_media',
                    multiple: false,
                    //library: { type : 'image' },
                    button: { text: 'Close' }
                });
                custom_uploader.states.add([
                    new wp.media.controller.Library({
                        id: 'upload_media',
                        title:  'Upload Media',
                        priority:   20,
                        toolbar:    'select',
                        filterable: 'uploaded',
                        library:    wp.media.query( custom_uploader.options.library ),
                        multiple:   custom_uploader.options.multiple ? 'reset' : false,
                        editable:   true,
                        displayUserSettings: false,
                        displaySettings: true,
                        allowLocalEdits: true
                        //AttachmentView: ?
                    }),
                ]);
 
                //Open the uploader dialog
                custom_uploader.open();
            });
        },
        uploadSlider: function($){
            var custom_uploader;
            $('#upload_slide_img_button').click(function(e) {
                e.preventDefault();
 
                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
 
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
 
                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function() {
                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#slide_img').val(attachment.url);
                });
 
                //Open the uploader dialog
                custom_uploader.open();
            });
            
            $("#publish").click(function(event){
                var valid = true;
                if($("#slide_img").length > 0 && $("#slide_img").val().length == 0){
                    $("#slide_img").css('border', '1px solid red');
                    valid = false;
                }
                if($("#slide_order").length > 0 && !$.isNumeric($("#slide_order").val())){
                    $("#slide_order").css('border', '1px solid red');
                    valid = false;
                }
                if(valid == false){
                    event.stopImmediatePropagation();
                    return false;
                }
            });
        }
    }
}();

// Run
jQuery(document).ready(function($){
    CustomJS.uploadSingleImage($);
    CustomJS.uploadAds($);
    CustomJS.uploadSlider($);
});