<?php
include '../permissions.php';
checkPermission('Manager'); // Only managers can edit products

include_once '../controllers/general_controller.php';

session_start(); 

if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $expiry_date = $_POST['expiry_date'];
    $total_amount = $_POST['total_amount'];
    $user_id = $_SESSION['user_id'];

    // Handle image upload if a new image is uploaded
    if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] == 0) {
        $image = $_FILES['image_name']['name'];
        $image_tmp_name = $_FILES['image_name']['tmp_name'];
        $image_folder = '../assets/images/stock/' . $image;
        move_uploaded_file($image_tmp_name, $image_folder);
    } else {
        // Get the existing image from the database if no new image is uploaded
        $product = getProductsByIds([$product_id])[0];
        $image = $product['image'];
    }

    $result = update_product($product_id, $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id);

    if ($result === true) {
        header("Location: ../list-product.php?message=Product updated successfully");
    } else {
        header("Location: ../list-product.php?message=Error updating product");
    }
} else {
    header("Location: ../list-product.php?message=No product ID provided");
}
?>