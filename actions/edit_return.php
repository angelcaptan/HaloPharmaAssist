<?php
include_once '../controllers/general_controller.php';

include '../permissions.php';
checkPermission('Manager'); // Only managers can edit returns

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_id = $_POST['return_id'];
    $customer_name = $_POST['customer_name'];
    $quantity = $_POST['quantity'];
    $total_amount = $_POST['total_amount'];
    $return_status = $_POST['return_status'];
    $return_note = $_POST['return_note'];
    $user_id = $_SESSION['user_id']; 

    $result = update_return($return_id, $customer_name, $quantity, $total_amount, $return_status, $return_note, $user_id);

    if ($result) {
        header('Location: ../list-returns.php?message=Return updated successfully');
    } else {
        header('Location: ../list-returns.php?message=Error updating return');
    }
} else {
    header('Location: ../list-returns.php');
}
?>