<?php
include '../permissions.php';
checkPermission('Manager'); // Only managers can delete categories

include_once '../controllers/general_controller.php';

session_start(); // Ensure session is started

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    // Call the archive_category function from the controller
    $result = archive_category($category_id, $user_id);

    if ($result) {
        // Redirect back to the category list with a success message
        header("Location: ../list-category.php?message=Category deleted successfully");
    } else {
        // Redirect back to the category list with an error message
        header("Location: ../list-category.php?message=Error deleting category");
    }
} else {
    // If no 'id' parameter is set, redirect back to the category list
    header("Location: ../list-category.php");
}
?>