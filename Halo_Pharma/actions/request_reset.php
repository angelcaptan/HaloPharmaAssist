<?php
// Report errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/general_controller.php';
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user = getUserByEmail($email);

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        storePasswordResetToken($user['user_id'], $token, $expiry);

        $resetLink = "http://localhost/Halo_Pharma/auth-reset-password.php?token=" . $token; // Change this to your domain

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.mailgun.org';
            $mail->SMTPAuth = true;
            $mail->Username = 'postmaster@sandbox466cab17ec1142d398d8c19b5a5e5e8f.mailgun.org';
            $mail->Password = '88eca10b1401673d1380e0d23f1efb07-0f1db83d-bdf86c5b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@yourdomain.com', 'Halo Pharma Assist');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body = "Please click on the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo "Password reset email has been sent.";
        } catch (Exception $e) {
            echo "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No user found with that email address.";
    }
}
?>