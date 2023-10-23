
jQuery(document).ready(function($){

    var img_url = $("#custom_image_url").val();

    if (img_url) {
        $("#image_preview").html(`<img src='${img_url}' />`)
    }

    var frame;
    $('#upload_image_button').on("click", function(){

        if (frame){
            frame.open();
            return false;
        }
        frame = wp.media({
            title: custom_script_vars.upload_image_text,
            button:{
                text: custom_script_vars.select_image_text
            },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $("#custom_image_id").val(attachment.id);
            $("#custom_image_url").val(attachment.sizes.medium.url);
            $("#image_preview").html(`<img src='${attachment.sizes.medium.url}' />`)
            console.log(attachment);
        });
        
        frame.open();
    });

});
