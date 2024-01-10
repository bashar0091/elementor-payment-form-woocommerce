<?php
/**
 * Plugin Name: Elementor Payment Form Woocommerce
 * Plugin URI: 
 * Description: 
 * Version: 1.0.0
 * Author: Dev Bucks
 * Author URI: 
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: epfwoo
 */


// Prevent direct access to the plugin file
defined( 'ABSPATH' ) || exit;
print_r($_SESSION['form_data']);
/**
 * 
 * Require All Css Files Here
 * 
 */
function epfwoo_enqueue_style() {
    
}
add_action( 'wp_enqueue_scripts', 'epfwoo_enqueue_style' );


/**
 * 
 * Require All Js Files Here
 * 
 */
function epfwoo_enqueue_scripts() {

    wp_enqueue_script( 'custom-form-script', plugin_dir_url( __FILE__ ) . 'assets/script.js', array( 'jquery' ), '1.0.0', true );

    // Ajax Request URL
    wp_localize_script('custom-form-script', 'formAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'epfwoo_enqueue_scripts' );



/**
 * 
 * Require All Includes Files Here
 * 
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/submission-store-session.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/submission-store-db.php';


/**
 * 
 * One Product Cart Item
 * 
 */
add_filter('woocommerce_add_cart_item_data', 'restrict_single_product_to_cart', 10, 3);
function restrict_single_product_to_cart($cart_item_data, $product_id, $variation_id) {
    WC()->cart->empty_cart();

    return $cart_item_data;
}

// Function to form_payment_field_register customizer settings
function form_payment_field_register($wp_customize) {
    // Add a section
    $wp_customize->add_section('form_payment_field', array(
        'title' => __('Form Payment Field', 'epfwoo'),
        'priority' => 30,
    ));

    // Add a setting
    $wp_customize->add_setting('form_payment_id', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add a control
    $wp_customize->add_control('form_id_control', array(
        'label' => __('Payment Id', 'epfwoo'),
        'section' => 'form_payment_field',
        'settings' => 'form_payment_id',
        'type' => 'number',
    ));

//================================= Form Name Id 1

    // Add a setting
    $wp_customize->add_setting('form_name_1', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    // Add a control
    $wp_customize->add_control('form_name_control_1', array(
        'label' => __('Form Name 1', 'epfwoo'),
        'section' => 'form_payment_field',
        'settings' => 'form_name_1',
        'type' => 'text',
    ));
    
//================================= Form Name Id 2

    // Add a setting
    $wp_customize->add_setting('form_name_2', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    // Add a control
    $wp_customize->add_control('form_name_control_2', array(
        'label' => __('Form Name 2', 'epfwoo'),
        'section' => 'form_payment_field',
        'settings' => 'form_name_2',
        'type' => 'text',
    ));
    
}
add_action('customize_register', 'form_payment_field_register');


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



// update woocommerce checkout 
function modify_checkout_fields($fields) {
	session_start();
    $user_first_name = isset($_SESSION['form_data']) ? $_SESSION['form_data']['applicant_name'] : '';
    $user_email_add = isset($_SESSION['form_data']) ? $_SESSION['form_data']['email'] : '';
    $user_telephone = isset($_SESSION['form_data']) ? $_SESSION['form_data']['phone'] : '';

    $fields['billing']['billing_first_name']['default'] = $user_first_name;
    $fields['billing']['billing_email']['default'] = $user_email_add;
    $fields['billing']['billing_phone']['default'] = $user_telephone;

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'modify_checkout_fields');