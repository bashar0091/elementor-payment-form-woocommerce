<?php

// Include the TCPDF library
require_once 'TCPDF-main/tcpdf.php';

// Add action hook for WooCommerce thank you page
add_action('woocommerce_thankyou', 'generate_pdf_and_send_email_on_order_completion', 10, 1);

function generate_pdf_and_send_email_on_order_completion($order_id) {
    // Check if this is a valid order
    if (!$order_id) {
        return;
    }

    // Get the order object
    $order = wc_get_order($order_id);

    if ($order && $order->is_paid()) {
        
        
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

        $form_data = $_SESSION['form_data'];

        // HTML content for your email
        $message = '
        <div class="main_wrapper">
                <table>
                    <tr>
                        <td style="vertical-align: top;">
                            <img src="https://buhs.ac.bd/erp/wp-content/uploads/2023/12/ezgif.com-webp-to-png-converted.png" style="width: 120px;"/>
                        </td>

                        <td style="vertical-align: top;">
                            <h1 style="text-align: center; font-size: 25px;">Bangladesh University of Health Sciences</h1>
                            <h2 style="text-align: center; font-size: 20px;">Online Admission Form Submission</h2>
                            <h3 style="text-align: center; font-size: 18px;">' . $_SESSION['referer_title'] . '</h3>
                        </td>

                        <td style="vertical-align: top;">
                            <img src="'.$_SESSION['form_data']['Photo'].'" style="width: 120px;height: 130px;object-fit: cover;object-position: top;border: 1px solid #d9d9d9;"/>
                            <img src="'.$_SESSION['form_data']['signature'].'" style="width: 120px;height: 55px;object-fit: contain;"/>
                        </td>
                    </tr>
                </table>
                
                <table width="100%" cellspacing="0">
                    <tbody>';
                        $counter = 0;
                        foreach ($form_data as $key => $value) {
                            if (!empty($value)) {
                                if ($counter % 3 === 0) {
                                    $message .= '<tr>';
                                }
                                $message .= '
                                    <td style="width: 33.33%; margin: 10px;">
                                        <div style="margin-bottom: 10px; font-weight: bold;">' . ucwords(str_replace('_', ' ', $key)) . '</div>  
                                        <div style="border: 1px solid #e8e8e8; padding: 10px;">';
                                if (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0) {
                                    $message .= '<a href="' . $value . '">' . $value . '</a>';
                                } else {
                                    $message .= $value;
                                }
                                $message .= '</div></td>';
                                $counter++;
                                if ($counter % 3 === 0) {
                                    $message .= '</tr>';
                                }
                            }
                        }
                        // If the loop ends with an incomplete row, close the row
                        if ($counter % 3 !== 0) {
                            $remainingColumns = 3 - ($counter % 3);
                            for ($i = 0; $i < $remainingColumns; $i++) {
                                $message .= '<td style="width: 33.33%;"></td>';
                            }
                            $message .= '</tr>';
                        }
                    $message .= '
                    </tbody>
                </table>
                <h1 style="text-align: center; font-size: 25px; border-top: 1px solid #ddd;margin-top:50px;padding-top:10px">Payment Info</h1>
                <table width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 10px; font-weight: bold;">Name :</div>  
                                <div style="border: 1px solid #e8e8e8; padding: 10px;">'.$_SESSION['form_data']['applicant_name'].'</div>
                            </td>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 10px; font-weight: bold;">Email :</div>  
                                <div style="border: 1px solid #e8e8e8; padding: 10px;">'.$_SESSION['form_data']['email'].'</div>
                            </td>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 10px; font-weight: bold;">Phone :</div>  
                                <div style="border: 1px solid #e8e8e8; padding: 10px;">'.$_SESSION['form_data']['phone'].'</div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 10px; font-weight: bold;">Amount :</div>  
                                <div style="border: 1px solid #e8e8e8; padding: 10px;">500</div>
                            </td>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 10px; font-weight: bold;">Status :</div> 
                                <div style="border: 1px solid #e8e8e8; padding: 10px; font-weight: bold;">Paid</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>';

        // Create TCPDF object
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Set font
        $pdf->SetFont('times', '', 12); // Change 'times' to the desired font family and 12 to the desired font size
        
        // Add a page
        $pdf->AddPage();
        
        // Write HTML content
        $pdf->writeHTML($message, true, false, true, false, '');


        // Close and output PDF document
        $pdf_content = $pdf->Output('', 'S');

        // Set email parameters
        $Mailsubject = 'New Application Submission';
        $Mailto = "onlineapplicationbuhs@gmail.com,prp@buhs.ac.bd,dprp@buhs.ac.bd," . $_SESSION['form_data']['email'];

        // Set the sender's email and name
        $senderEmail = 'prp@buhs.ac.bd';
        $senderName = 'Buhs';
        $subject = $Mailsubject;

        // Set the email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";
        $headers .= 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        $headers .= 'Reply-To: ' . $senderEmail . "\r\n";

        // Generate the email body (HTML)
        $email_body = "--boundary\r\n";
        $email_body .= "Content-type:text/html;charset=UTF-8\r\n\r\n";
        $email_body .= $message . "\r\n";

        // Attach the PDF to the email
        $pdf_filename = 'application.pdf';
        $email_body .= "--boundary\r\n";
        $email_body .= "Content-Type: application/pdf; name=\"" . $pdf_filename . "\"\r\n";
        $email_body .= "Content-Disposition: attachment; filename=\"" . $pdf_filename . "\"\r\n";
        $email_body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $email_body .= chunk_split(base64_encode($pdf_content)) . "\r\n";
        $email_body .= "--boundary--";

        // Suppress errors for the mail function call
        $success = error_reporting(0); // Suppress errors
        $mail_result = mail($Mailto, $subject, $email_body, $headers);
        error_reporting($success); // Restore error reporting

        // Redirect user after processing
        wp_redirect(home_url() . '/thank-you');
        exit;
    }
}