

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../controllers/general_controller.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $managerEmail = $_POST['manager'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.mailgun.org'; // Mailgun SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'postmaster@sandbox466cab17ec1142d398d8c19b5a5e5e8f.mailgun.org'; // Mailgun SMTP username
        $mail->Password = '88eca10b1401673d1380e0d23f1efb07-0f1db83d-bdf86c5b'; // Mailgun SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Enable verbose debug output
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        // Recipients
        $mail->setFrom('noreply@yourdomain.com', 'Abeer Pharmacy Limited- Kakata Branch');
        $mail->addAddress($managerEmail); 

        // Content
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body = nl2br($message); // HTML body
        $mail->AltBody = strip_tags($message); // Plain text body

        $mail->send();


        // Log the action
        session_start(); 
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            log_action($user_id, 'send_emergency_inquiry', 'EmergencyInquiries', null);
        }


        // Redirect back to emergency-contact.php with a success message
        header('Location: ../emergency-contact.php?message=Message+has+been+sent');
        exit(); 
    } catch (Exception $e) {
        // Redirect back to emergency-contact.php with an error message
        header('Location: ../emergency-contact.php?error=' . urlencode('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo));
        exit(); 
    }
}
?>