<?php
// Report errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../permissions.php';
checkPermission('Manager'); // Only managers can delete returns

include_once '../controllers/general_controller.php';

session_start();

if (isset($_GET['id'])) {
    $return_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    if (delete_return_ctr($return_id, $user_id)) {
        // Redirect to return list with success message
        header("Location: ../list-returns.php?message=Return deleted successfully");
    } else {
        // Redirect to return list with error message
        header("Location: ../list-returns.php?message=Error deleting return");
    }
} else {
    // Redirect to return list if no id is set
    header("Location: ../list-returns.php");
}
?>