<?php
include '../permissions.php';
checkPermission('Manager'); // Only managers can delete sales

require_once '../controllers/general_controller.php';

session_start();

if (isset($_GET['id'])) {
    $sale_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    if (delete_sale($sale_id, $user_id)) {
        $_SESSION['message'] = 'Sale deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete sale.';
        $_SESSION['message_type'] = 'danger';
    }
} else {
    $_SESSION['message'] = 'No sale ID provided.';
    $_SESSION['message_type'] = 'danger';
}

header('Location: ../list-sales.php');
exit;
?>