<?php

/**
 * store data in database
 */
add_action('woocommerce_thankyou', 'store_data_after_payment', 10, 1 );

function store_data_after_payment($order_id) {
    session_start();

    // Check if this is a valid order
    if (!$order_id) {
        return;
    }

    // Get the order object
    $order = wc_get_order($order_id);

    if ( $order && $order->is_paid() ) {

        global $wpdb;
        $table_name_e_submissions = $wpdb->prefix . 'e_submissions';
        $meta_data = array(
            'edit_post_id' => $_SESSION['postID'],
        );
        $encoded_meta = json_encode($meta_data);
        $data_e_submissions = array(
            'type' => 'submission',
            'hash_id' => rand(),
            'main_meta_id' => '',
            'post_id' => $_SESSION['postID'],
            'referer' => '',
            'referer_title' => $_SESSION['referer_title'],
            'element_id' => $_SESSION['formID'],
            'form_name' => '',
            'campaign_id' => 0,
            'user_id' => '',
            'user_ip' => '',
            'user_agent' => '',
            'actions_count' => 0,
            'actions_succeeded_count' => 0,
            'status' => 'new',
            'is_read' => 0,
            'meta' => $encoded_meta,
            'created_at_gmt' => current_time('mysql'),
            'updated_at_gmt' => '',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
        $wpdb->insert($table_name_e_submissions, $data_e_submissions);

        // Get the ID of the inserted row
        $submission_id = $wpdb->insert_id;

        $table_name_e_submissions_values = $wpdb->prefix . 'e_submissions_values';
        foreach ($_SESSION['form_data'] as $key => $value) {
            $data_e_submissions_values = array(
                'submission_id' => $submission_id,
                'key' => $key,
                'value' => $value,
            );

            $wpdb->insert($table_name_e_submissions_values, $data_e_submissions_values);
        }

        wp_redirect( home_url() . '/thank-you' );
        exit;
    }
}