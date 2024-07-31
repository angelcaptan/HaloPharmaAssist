<?php
include '../permissions.php';
checkPermission('Manager'); // Only managers can add categories

include_once '../controllers/general_controller.php';

session_start(); // Ensure session is started

if (isset($_POST['category_name']) && !empty($_POST['category_name'])) {
    $category_name = $_POST['category_name'];
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    $result = add_category($category_name, $user_id);

    if ($result === true) {
        header("Location: ../list-category.php?message=Category added successfully");
    } else {
        header("Location: ../list-category.php?message=Error adding category");
    }
} else {
    header("Location: ../list-category.php?message=No category name provided");
}
?>