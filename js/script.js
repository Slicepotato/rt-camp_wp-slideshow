jQuery(document).ready(function($){
    $('#upload_img').on('change', function() {
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#imgPreviewContainer').addClass('show');
                $('#imgPreview').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $('#clear_form').on('click', function(){
        $('#imgPreviewContainer').removeClass('show');
        $('#imgPreview').attr('src', '');
    });
    
    $('#image-list').sortable({
        placeholder: "ui-state-highlight",
        update: function (event, ui) {
            var data = $(this).sortable('serialize') + '&action=sort_list';

            $.post(app.ajaxurl, data);
        }
    });
});