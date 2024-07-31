<?php
// Report errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/general_controller.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $user_id = validatePasswordResetToken($token);

    if ($user_id) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        if (resetPassword($user_id, $password_hashed)) {
            echo "Password has been reset successfully.";
        } else {
            echo "Error resetting password.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>