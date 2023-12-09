<?php

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
    $checkout_url = wc_get_checkout_url();

    echo json_encode(
        array(
            'checkout_url' => $checkout_url,
        ),
    );

    wp_die();
}


//button click , all data get in this response
function send_custom_webhook( $record, $handler) {

    session_start();

    $form_name = $record->get_form_settings( 'form_name' );

    $form_name_id = !empty(get_theme_mod('form_name_1')) ? get_theme_mod('form_name_1') : '';
    if ( $form_name_id !== $form_name ) {
        return;
    }

    $raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }

    $_SESSION['form_data'] = $fields;

    
}
add_action( 'elementor_pro/forms/new_record', 'send_custom_webhook', 10, 2 );

//=========================

function send_custom_webhook2( $record, $handler) {

    session_start();

    $form_name = $record->get_form_settings( 'form_name' );

    $form_name_id = !empty(get_theme_mod('form_name_2')) ? get_theme_mod('form_name_2') : '';
    if ( $form_name_id !== $form_name ) {
        return;
    }

    $raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }

    $_SESSION['form_data'] = $fields;

    
}
add_action( 'elementor_pro/forms/new_record', 'send_custom_webhook2', 10, 2 );