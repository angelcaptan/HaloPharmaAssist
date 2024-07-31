<?php
// Include the controller
include_once '../controllers/general_controller.php';

include '../permissions.php';
checkPermission('Manager'); // Only managers can edit categories

session_start(); // Ensure session is started

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    // Call the update_category function from the controller
    $result = update_category($category_id, $category_name, $user_id);

    if ($result) {
        // Redirect back to the category list with a success message
        header("Location: ../list-category.php?message=Category updated successfully");
    } else {
        // Redirect back to the category list with an error message
        header("Location: ../list-category.php?message=Error updating category");
    }
} else {
    // If the form wasn't submitted, redirect back to the category list
    header("Location: ../list-category.php");
}
?>