<?php
require_once '../controllers/general_controller.php';

session_start(); // Ensure session is started

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sale_date = $_POST['sale_date'];
    $reference_no = $_POST['reference_no'];
    $biller_id = $_POST['biller_id'];
    $customer_name = $_POST['customer_name'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_amounts = $_POST['total_amount'];
    $overall_total_amount = $_POST['overall_total_amount'];
    $sale_status = $_POST['sale_status'];
    $payment_status = $_POST['payment_status'];
    $sale_note = $_POST['sale_note'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    $success = true;

    foreach ($product_ids as $index => $product_id) {
        $quantity = $quantities[$index];
        $total_amount = $total_amounts[$index];

        $result = add_sale($sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note, $user_id);
        if ($result === true) {
            // Update product quantity after successful sale insertion
            updateProductQuantity($product_id, -$quantity);
        } else {
            $success = false;
            break;
        }
    }

    if ($success) {
        header('Location: ../list-sales.php?success=1');
    } else {
        header('Location: ../add-sale.php?error=' . urlencode($result));
    }
}
?>