<?php
// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require './mailBuilder.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body) {
    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                      // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;             // Enable SMTP authentication
        $mail->Username   = ''; // SMTP username (your Gmail address)
        $mail->Password   = '';  // SMTP password (your Gmail password or app password)
        $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;              // TCP port to connect to

        // Recipients
        $mail->setFrom('Shoppie@gmail.com', 'Shoppie');
        $mail->addAddress
        ($to);               // Add a recipient
        $mail->addReplyTo('aristotepascaldjounda@gmail.com', 'Folley');

        // Content
        $mail->isHTML(true);                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);   // For non-HTML mail clients

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}


function sendCommandEmail($user, $articles, $date, $address, $number){
    return sendEmail($user['email'], "Your Command Is On Its Way To You.", commandMail($user, $articles, $date, $address, $number));
}

?>
