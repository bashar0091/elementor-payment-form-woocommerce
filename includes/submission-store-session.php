<?php

session_start();

add_action('wp_ajax_submit_custom_form', 'submit_custom_form');
add_action('wp_ajax_nopriv_submit_custom_form', 'submit_custom_form');
function submit_custom_form() {

    session_start();

    $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : '';

    parse_str($form_data, $formFields);
    $_SESSION['postID'] = isset($formFields['post_id']) ? $formFields['post_id'] : '';
    $_SESSION['formID'] = isset($formFields['form_id']) ? $formFields['form_id'] : '';
    $_SESSION['referer_title'] = isset($formFields['referer_title']) ? $formFields['referer_title'] : '';

    $payment_id = !empty(get_theme_mod('form_payment_id')) ? get_theme_mod('form_payment_id') : '';

    WC()->cart->add_to_cart($payment_id, 1, 0, array(), $cart_item_data);

    wp_die();
}