jQuery(document).ready(function($) {
    $('.form_submission .elementor-form').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: formAjax.ajaxurl,
            data: {
                action: 'submit_custom_form',
                form_data: formData,
            },
            success: function(response) {
                var data = JSON.parse(response);
                window.location.href = data.checkout_url;
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});