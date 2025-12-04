<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gojannathaniel@gmail.com'; 
    $mail->Password = 'syoykadatugzoqlh'; // your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gojannathaniel@gmail.com', 'Test Mail');
    $mail->addAddress('YOUR_EMAIL_HERE@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Testing PHPMailer';
    $mail->Body = 'If you see this, your SMTP works.';

    $mail->send();
    echo "✅ Email sent successfully!";
} catch (Exception $e) {
    echo "❌ Error sending mail: {$mail->ErrorInfo}";
}
