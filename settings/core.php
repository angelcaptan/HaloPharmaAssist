<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//start session
session_start(); 

if(isset($_SESSION['user_id']) ) {
    // Session variables are set, assign them to local variables
    $user = $_SESSION['user_id'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $phone = $_SESSION['phone'];
}



// Include necessary files
include(__DIR__."/../functions/all_functions.php");



//for header redirection
ob_start();

//funtion to check for login
function checkLogin() {
    if (empty($_SESSION['user_id'])) {
        header("Location: auth-sign-in.php");
    }
}


// //function to get user ID
// function get_user_id() {
//     return $_SESSION['user_id'];
// }


// //function to check for role (admin, customer, etc)
// function get_user_role() {
//     return $_SESSION['role'];
// }


?>
