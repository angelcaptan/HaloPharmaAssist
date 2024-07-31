<?php
// Report errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the controller class and functions
require_once (__DIR__ . "/../controllers/general_controller.php");
include_once (__DIR__ . "/../functions/all_functions.php");

// Collect form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'];
$gender = $_POST['gender']; 
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate email
if (!validateEmail($email)) {
    echo "Invalid email address.";
    exit();
}

// Call the function to add user
$register = register_user_ctr($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password);

if ($register === "User registered successfully.") {
    $_SESSION['message'] = "User registered successfully";
    header("Location: ../auth-sign-in.php");
} else {
    echo "Error signing up: " . $register;
}