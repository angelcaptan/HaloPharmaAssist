<?php
// report errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../permissions.php';
checkPermission('Manager'); // Only managers can delete products

// Include the general controller
include_once '../controllers/general_controller.php';

session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    if (archive_product_ctr($product_id, $user_id)) {
        // Redirect to product list with success message
        header("Location: ../list-product.php?message=Product archived successfully");
    } else {
        // Redirect to product list with error message
        header("Location: ../list-product.php?message=Error archiving product");
    }
} else {
    // Redirect to product list if no id is set
    header("Location: ../list-product.php");
}
?>


