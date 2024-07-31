<?php
// Report errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the controller class
require_once (__DIR__ . "/../settings/core.php");
include_once (__DIR__ . "/../controllers/general_controller.php");

session_start(); // Ensure session is started

// Collect form data
$product_name = $_POST['product_name'];
$category_id = $_POST['category_id'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$expiry_date = $_POST['expiry_date'];
$total_amount = $_POST['total_amount'];
$imageName = basename($_FILES["image"]["name"]);
$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

// Get product image
$target_dir = "../assets/images/stock/";

// Check if the directory exists, if not, create it
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$target_file = $target_dir . $imageName;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Move the image to the folder
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    if (add_product($product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $imageName, $user_id)) {
        echo "<script>alert('Product added');</script>";
        header("Location: ../add-product.php");
    } else {
        echo "<script>alert('Product could not be added');</script>";
        header("Location: add-product.php");
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>