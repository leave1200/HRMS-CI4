<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('sendEmail')) {
    function sendEmail($mailConfig) {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = env('EMAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('EMAIL_USERNAME');
            $mail->Password = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = env('EMAIL_ENCRYPTION');
            $mail->Port = env('EMAIL_PORT');

            // Recipient settings
            $mail->setFrom($mailConfig['mail_from_email'], $mailConfig['mail_from_name']);
            $mail->addAddress($mailConfig['mail_recipient_email'], $mailConfig['mail_recipient_name']);

            // Content settings
            $mail->isHTML(true);
            $mail->Subject = $mailConfig['mail_subject'];
            $mail->Body = $mailConfig['mail_body'];

            // Send email
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error or handle it as needed
            error_log('Mail error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
