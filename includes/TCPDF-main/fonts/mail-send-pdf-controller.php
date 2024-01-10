<?php
// Include the dompdf autoloader
require_once 'TCPDF-main/tcpdf.php';
class CustomPDF extends TCPDF {
    public function Header() {
        $this->SetY(30); 
    }
}
function bimly_email_send_and_pdf_generate($work_type = '', $Date_of_preparation_of_the_protocol = '', $Place_of_investment = '', $Subject_of_accepted_works = '', $Investor_name = '', $Nazwa_wykonawcy = '' ,$sub_investor = [],$sub_propitor = [], $Date_of_preparation_of_the_protocol2 = '' , $text_ar = [], $new_image_url = [], $uploaded_image_urls = [], $uploaded_image_urls2= [], $email_data = '',$statement_select='', $statement_select2='', $no_issue_select = 0,$insert_id='' ) {
    $pdf_sending_email = $email_data;
  
    $pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $unic_id = bimly_generateUniqueID($insert_id);
    // Add a page
    $pdf->AddPage();
    // $pdf->SetFont('dejavusans', '', 14);
    $pdf->setJPEGQuality(100);
    $today_date = date("Y-m-d H:i:s");
    // Form data
    $Mailsubject = 'New Enquiry';
    $Mailto = $pdf_sending_email;

    // Set the sender's email and name
    $senderEmail = 'bimly@gmail.com';
    $senderName = 'Bimly';
    $subject = $Mailsubject;
    // HTML template (same as in your original code)
    // ...
    $message2 = '<div style="width: 595px; margin: 0 auto;">
                <table style="width: 100%;">
                    <tr>
                        <td style="color: #6149CC; font-family: Noto Sans; font-size: 25px; font-weight: 800;">bimly</td>
                        <td style="color: #0F082E;font-family: Noto Sans;font-size: 10px;font-weight: 400;text-align: right;">'.$today_date.'r, Białystok</td>
                    </tr>
                    <tr><td></td></tr>
                </table>
                <table style="padding-top:10px">
                    <tr>
                        <td style="color: #0F082E; font-family: Noto Sans; font-size: 17px; font-weight: 700;">Protokół odbioru robót</td>
                    </tr>
                    <tr>
                        <td style="color: #656179; font-family: Noto Sans !important; font-size: 12px; font-weight: 500;">Nr. wewnętrzny #'.$unic_id.'</td>
                    </tr>
                    <tr><td></td></tr>
                </table>
                    <table style="width: 100%;" cellspacing="3" cellpadding="4" >
                        <tr>
                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;" >Rodzaj robót</td>
                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;">'.$work_type.'</td>
                        </tr>
                        <tr>
                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;" bgcolor="#F5F5F7">Adres inwestycji</td>
                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600;  padding: 10px;" bgcolor="#F5F5F7">'.$Place_of_investment.'</td>
                        </tr>
                        <tr>
                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;">Data odbioru</td>
                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;">'.$Date_of_preparation_of_the_protocol2.'</td>
                        </tr>
                        <tr>
                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;" bgcolor="#F5F5F7">Inwestor</td>
                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;" bgcolor="#F5F5F7">'.$Investor_name.'</td>
                        </tr>';
                        $s = 2;
                        if (is_array($sub_investor) && !empty($sub_investor)) {
                            foreach ($sub_investor as $sub_invest) {
                                if ($s % 2 == 0) {
                                    $message2 .= '<tr>
                                        <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;" >Przedstawiciel Inwestora</td>
                                        <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;">'.$sub_invest.'</td>
                                    </tr>';
                                } else {
                                    $message2 .= '<tr>
                                    <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;"  bgcolor="#F5F5F7">Przedstawiciel Inwestora</td>
                                    <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;"  bgcolor="#F5F5F7">'.$sub_invest.'</td>
                                </tr>';
                                }
                                $s++;
                            }
                        }
                        $message2 .= '<tr>
                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; background: #F5F5F7; padding: 10px;" bgcolor="#F5F5F7">Wykonawca</td>
                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; background: #F5F5F7; padding: 10px;" bgcolor="#F5F5F7">'.$Nazwa_wykonawcy.'</td>
                        </tr>';
                        $k = 2;
                        if (is_array($sub_propitor) && !empty($sub_propitor)) {
                            foreach ($sub_propitor as $sub_propit) {
                                if ($k % 2 == 0) {
                                    $message2 .= '<tr>
                                            <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;">Przedstawiciel Wykonawcy</td>
                                            <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;">'.$sub_propit.'</td>
                                        </tr>';
                                } else {
                                    $message2 .= '<tr>
                                        <td style="color: #656179; font-family: Noto Sans; font-size: 11px; font-weight: 400; padding: 10px;" bgcolor="#F5F5F7">Przedstawiciel Wykonawcy</td>
                                        <td style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 600; padding: 10px;" bgcolor="#F5F5F7">'.$sub_propit.'</td>
                                    </tr>';
                                }
                                $k++;
                            }
                        }
                        $message2 .= ' </table>';
                        if ($no_issue_select) {
                            $message2 .= '<div><div style="color:#0f082e;font-family:Noto Sans;font-size: 14px;font-weight:700;margin: 15px 0;">&nbsp; &nbsp;&nbsp;Prace odebrano bez uwag.</div></div>';
                        } else {
                            $message2 .= '<div>
                                    <div style="color:#0f082e;font-family:Noto Sans;font-size: 14px;font-weight:800;margin: 15px 0;">W toku odbioru stwierdzono następujące usterki:</div>
                                    <div style="border-radius: 4px; padding: 10px;" bgcolor="#F5F5F7">';
                                $d = 0;
                                $count_val = count($text_ar)-1;
                                if( is_array($text_ar) && !empty($text_ar)) {
                                    foreach($text_ar as $keys=>$text_d ) {
                                        $url_info = array_key_exists( $d, $new_image_url ) ? $new_image_url[$d]: [];
                                        $message2 .= '<div style="color: #0F082E; font-family: Noto Sans; font-size: 11px; font-weight: 400;">';
                                        $text_data = bimly_text_divided_version($text_d);
                                        foreach($text_data as $key =>$tx ) {
                                            if ( $key == 0 ) {
                                                $message2 .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $tx;
                                            } else {
                                                $message2 .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;" . $tx;
                                            }
                                        };
                                        $message2 .='</div><div style="color:#0f082e;font-family:Noto Sans;font-size: 14px;font-weight:700;margin: 15px 0;">&nbsp; &nbsp;Usterka istotna</div><br><table><tr>';
                                        if(is_array($url_info) && !empty($url_info)) {
                                            foreach($url_info as $url ) {
                                                $message2 .= '<td>&nbsp; &nbsp;<img src="'.$url.'" alt="img" width="100" height="80" /></td>';
                                            }
                                        } else {
                                            $message2 .= '
                                            <td style="font-size:13px">&nbsp; &nbsp;brak wyboru obrazu</td>'; 
                                        }
                                        $d++;
                                        if ($count_val == $keys ) {
                                            $message2 .= '</tr></table><br>';
                                        } else {
                                            $message2 .= '</tr></table>';
                                        }
                                    }
                                }
                                $message2 .= '</div></div><div style="font-family:Noto Sans; font-size:11px;font-weight:700">'.bimly_convart_text_func_email($statement_select, $statement_select2).' '.$Date_of_preparation_of_the_protocol2.'</div>';
                        }
                    
                    $message2 .= '<div style="display: flex;">
                    <table style="width:100%">
                    <tr>';
                if (is_array($uploaded_image_urls) && !empty($uploaded_image_urls)) {
                    $t = 0;
                    foreach($uploaded_image_urls as $sg ) {
                        if ( $t == 0 ) {
                            $message2 .= '<td><label style="font-size:12px">Inwestor</label><br><img src="'.$sg.'" alt="signature" width="150" /></td>';
                        } else {
                            $message2 .= '<td><label style="font-size:12px">Przedstawiciel Inwestor</label><br><img src="'.$sg.'" alt="signature" width="150" /></td>';
                        }
                        $t++;
                    }
                }
                $f = 0;
                if (is_array($uploaded_image_urls2) && !empty($uploaded_image_urls2)) {
                    foreach($uploaded_image_urls2 as $sg1 ) {
                        if ( $f == 0) {
                        $message2 .= '<td><label style="font-size:12px">Wykonawca</label><br><img src="'.$sg1.'" alt="signature" width="150" /></td>';
                        } else {
                            $message2 .= '<td><label style="font-size:12px">Przedstawiciel Wykonawca</label><br><img src="'.$sg1.'" alt="signature" width="150" /></td>';
                        }
                        $f++;
                    }
                }
                $message2 .= '</tr></table></div>
            </div>';

/**
 * Email template
 */
    $message = '
        <html>
           
            <head>
                <style>
                    body {
                        font-family: "Noto Sans !important"; 
                    }
                    #bimly_hello_world {
                        display: none;
                    }
            
                    #bimly_hello_world:target {
                        display: block;
                    }
                </style>
            </head>
        <body>
            <div style="width: 595px; margin: 0 auto; padding: 30px;">
            

                <table style="width: 100%;margin-bottom: 20px;">
                    <tr>
                        <td style="color: #6149CC; font-family: Noto Sans; font-size: 25px; font-weight: 700;">bimly</td>
                        <td style="color: #0F082E;font-family: Noto Sans;font-size: 15px;font-weight: 400;text-align: right;">'.$today_date.'r, Białystok</td>
                    </tr>
                </table>

                <div>
                    <div style="margin-bottom: 20px;">
                        <div style="color: #0F082E; font-family: Noto Sans; font-size: 22px; font-weight: 700;">Protokół odbioru robót</div>
                        <div style="color: #656179; font-family: Noto Sans !important; font-size: 16px; font-weight: 500;">Nr. wewnętrzny #'.$unic_id.'</div>
                    </div>
                    <div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; padding-left: 5px;">Rodzaj robót</td>
                                <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; padding-left: 5px;">'.$work_type.'</td>
                            </tr>
                            <tr>
                                <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; background: #F5F5F7; padding-left: 5px;">Adres inwestycji</td>
                                <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; background: #F5F5F7; padding-left: 5px;">'.$Place_of_investment.'</td>
                            </tr>
                            <tr>
                                <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; padding-left: 5px;">Data odbioru</td>
                                <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; padding-left: 5px;">'.$Date_of_preparation_of_the_protocol2.'</td>
                            </tr>
                            <tr>
                                <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; background: #F5F5F7; padding-left: 5px;">Inwestor</td>
                                <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; background: #F5F5F7; padding-left: 5px;">'.$Investor_name.'</td>
                            </tr>';
                            if (is_array($sub_investor) && !empty($sub_investor)) {
                                foreach ($sub_investor as $sub_invest) {
                                    $message .= '<tr>
                                        <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; padding-left: 5px;">Przedstawiciel Inwestora</td>
                                        <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; padding-left: 5px;">'.$sub_invest.'</td>
                                    </tr>';
                                }
                            }
                            $message .= '<tr>
                                <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; background: #F5F5F7; padding-left: 5px;">Wykonawca</td>
                                <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; background: #F5F5F7; padding-left: 5px;">'.$Nazwa_wykonawcy.'</td>
                            </tr>';
                            if (is_array($sub_propitor) && !empty($sub_propitor)) {
                                foreach ($sub_propitor as $sub_propit) {
                                $message .= '<tr>
                                        <td style="color: #656179; font-family: Noto Sans; font-size: 15px; font-weight: 400; padding-left: 5px;">Przedstawiciel Wykonawcy</td>
                                        <td style="color: #0F082E; font-family: Noto Sans; font-size: 15px; font-weight: 600; padding-left: 5px;">'.$sub_propit.'</td>
                                    </tr>';
                                }
                            }
                            $message .= ' </table>
                    </div>
                </div>';
                if ($no_issue_select) {
                    $message .= '<div><div style="color:#0f082e;font-family:Noto Sans;font-size: 16px;font-weight:700;margin: 15px 0;">Prace odebrano bez uwag.</div></div>';
                } else {
                    $message .= '<div>
                                <div style="color:#0f082e;font-family:Noto Sans;font-size: 18px;font-weight:700;margin: 15px 0;">W toku odbioru stwierdzono następujące usterki:</div>
                                <div style="border-radius: 4px; background: var(--greys-dark-5, #F5F5F7); padding: 10px;">';
                            $d = 0;
                            if( is_array($text_ar) && !empty($text_ar)) {
                                foreach($text_ar as $text_d ) {
                                    $url_info = array_key_exists( $d, $new_image_url ) ? $new_image_url[$d]: [];
                                    $message .= '<div style="color: #0F082E; font-family: Noto Sans; font-size: 16px; font-weight: 400;margin-top:10px">'.$text_d.'</div>
                                    <div style="color:#0f082e;font-family:Noto Sans;font-size: 16px;font-weight:600;margin: 15px 0;">Usterka istotna</div>';
                                    if(is_array($url_info) && !empty($url_info)) {
                                        foreach($url_info as $url ) {
                                            $message .= '
                                                <span style="margin-top:7px;margin-right:7px"><img src="'.$url.'" alt="img" style="height: 120px; width: 150px;" /></span>';
                                        }
                                    } else {
                                        $message .='<span style="margin-top:7px;margin-right:7px">brak wyboru obrazu</span>';
                                    }
                                    $d++;
                                }
                            }
                        $message .= '</div></div><div style="margin-top:15px;font-size:12px; font-weight:600">
                           '.bimly_convart_text_func_email($statement_select, $statement_select2).' '.$Date_of_preparation_of_the_protocol2.'
                        </div>';
                    }
                    $message .='<div style="
                    display: flex;
                    justify-content: space-between;
                    padding: 30px 0;
                ">
                <table style="width:78%">
                ';
                if (is_array($uploaded_image_urls) && !empty($uploaded_image_urls)) {
                    $t = 0;
                    foreach($uploaded_image_urls as $sg ) {
                        if ( $t == 0 ) {
                            $message .= '<tr><td><label>Inwestor</label></td></tr>
                            <tr><td><img src="'.$sg.'" alt="signature" style="width: 200px;" /></td></tr> ';
                        } else {
                            $message .= '<tr><td><label>Przedstawiciel Inwestor</label></td></tr>
                            <tr><td><img src="'.$sg.'" alt="signature" style="width: 200px;" /></td></tr> ';
                        }
                        $t++;
                    }
                }
                $message .='</table><table>';
                $f = 0;
                if (is_array($uploaded_image_urls2) && !empty($uploaded_image_urls2)) {
                    foreach($uploaded_image_urls2 as $sg1 ) {
                        if ( $f == 0) {
                            $message .= '<tr>
                            <td>
                                <label>Wykonawca</label></td></tr>
                            <tr><td><img src="'.$sg1.'" alt="signature" style="width: 200px;" />
                            </td>
                        </tr>';
                        } else {
                            $message .= '<tr>
                            <td>
                                <label>Przedstawiciel Wykonawca</label></td></tr>
                            <tr><td><img src="'.$sg1.'" alt="signature" style="width: 200px;" />
                            </td>
                        </tr>';
                        }
                        $f++;
                    }
                }
                $message .= '</table></div>
                <div style="background:#6149cc;display: flex;padding:10px;color:#fff;justify-content: space-between;">
                    <div style="
                    width: 40%;
                ">bimly</div>
                    <div style="
                    text-align: center;
                "><a href="#" style="
                color: #fff;
            ">www.bilmy.pl</a></div>
                    <div></div>
                </div>
            </div>
        </body>
    </html>
    ';

    $pdf->writeHTML($message2, true, false, true, false, '');
    // Get PDF content
    $template_name = 'Bimly Protokół - Nr. wewnętrzny #'.$unic_id.'.pdf';
    $pdf_content = $pdf->Output($template_name, 'S');
    
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
    $pdf_filename = $template_name;
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

    // Check if mail was sent successfully
    if ($mail_result) {
        header('Location: https://bimly.pilardev.fi/thank-you/');
        exit;
    }
}


function bimly_convart_text_func_email($statement1, $statement2) {
    $text = '';
    if ($statement1 == '1' && $statement2 == '1' ) {
        $text = 'Prace odebrano z uwagami opisanymi w niniejszym protokole. Strony uzgodniły, że usunięcie usterek nastąpi do dnia';
    } else if ($statement1 == '0' && $statement2 == '1') {
        $text = 'Przedmiot robót nie nadaje się do odbioru, ponieważ usterki są istotne i uniemożliwiają prawidłowe użytkowanie przedmiotu umowy, zgodnie z uwagami opisanymi w niniejszym protokole. Strony uzgodniły, że usunięcie usterek nastąpi do dnia';
    } else if ($statement1 == '0' && $statement2 == '0') {
        $text = 'Prace odebrano z uwagami opisanymi w niniejszym protokole. Usterki są nieistotne i nie da się ich usunąć';
    } else {
        $text = 'Przedmiot robót nie nadaje się do odbioru, ponieważ usterki są istotne i uniemożliwiają prawidłowe użytkowanie przedmiotu umowy, zgodnie z uwagami opisanymi w niniejszym protokole. Usterek nie da się usunąć';
    }
   return $text;
}

function bimly_generateUniqueID($insertID) {
    // Combine the insert ID with a fixed prefix (if needed)
    $prefix = '45d65g234c'; // You can customize the prefix
    $uniqueID = $prefix . $insertID;

    return $uniqueID;
}


function bimly_text_divided_version($longText) {
        $maxLineLength = 600;
        // Initialize an array to store the divided text
        $dividedTextArray = array();
        // Split the text into words
        $words = explode(' ', $longText);

        // Initialize variables for the current line and its length
        $currentLine = '';
        $currentLineLength = 0;

        // Loop through each word
        foreach ($words as $word) {
            // Calculate the length of the current line with the added word and space
            $lineWithWordLength = $currentLineLength + strlen($currentLine) + strlen($word) + 1;

            // Check if adding the word would exceed the maximum line length
            if ($lineWithWordLength <= $maxLineLength) {
                // Add the word and space to the current line
                if (!empty($currentLine)) {
                    $currentLine .= ' ';
                }
                $currentLine .= $word;
                $currentLineLength = $lineWithWordLength;
            } else {
                // Push the current line to the array and reset variables
                $dividedTextArray[] = $currentLine;
                $currentLine = $word;
                $currentLineLength = strlen($word);
            }
        }

        // Push any remaining text to the array
        if (!empty($currentLine)) {
            $dividedTextArray[] = $currentLine;
        }
    return $dividedTextArray;
}
?>
