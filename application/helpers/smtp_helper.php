<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require PROJECT_DOCUMENT_ROOT . '/application/libraries/PHPMailer/src/Exception.php';
require PROJECT_DOCUMENT_ROOT . '/application/libraries/PHPMailer/src/PHPMailer.php';
require PROJECT_DOCUMENT_ROOT . '/application/libraries/PHPMailer/src/SMTP.php';

if (!function_exists('smtp_setup')) {

    /* @author: ZAHIR
     * make send smtp mail
     */

    function send_mailz($mail_data=false) {
        
        $sender_email = 'care@ahealz.com';
        $sender_name = 'AhealZ Care';
        $receiver_email = 'zahir.alam@ahealz.com';
        $receiver_name = 'Care';
        $subject = 'Greetings From AhealZ';
        $message = 'How is your health?';
        
        if( !empty($mail_data) ){
            
            $sender_email = !(empty($mail_data['from'])) ? $mail_data['from'] : 'care@ahealz.com';
            $sender_name = !(empty($mail_data['name'])) ? $mail_data['name'] . '  (' . $sender_email . ')': 'AhealZ Care';
            $receiver_email = !(empty($mail_data['to'])) ? $mail_data['to'] : 'zahir.alam@ahealz.com';
            $receiver_name = !(empty($mail_data['receiver_name'])) ? $mail_data['receiver_name'] : 'Care';
            $subject = !(empty($mail_data['subject'])) ? $mail_data['subject'] : 'Greetings From AhealZ';
            $message = !(empty($mail_data['message'])) ? $mail_data['message'] : 'How is your health?';
            
        }
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = "smtp.zoho.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
        $mail->Port = 587; // TLS only
        $mail->SMTPSecure = 'tls'; // ssl is deprecated
        $mail->SMTPAuth = true;
        $mail->Username = 'care@ahealz.com'; // email
        $mail->Password = 'careTK2*'; // password
        $mail->setFrom('care@ahealz.com', $sender_name); // From email and name
        $mail->addAddress($receiver_email, $receiver_name); // to email and name
        $mail->Subject = $subject;
        $mail->msgHTML($message); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
        $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
        
        $mail->send();
        
//        if (!$mail->send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
//        } else {
//            echo "Message sent!";
//        }
    }

}