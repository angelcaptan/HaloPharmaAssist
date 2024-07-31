<?php

include '../permissions.php';
checkPermission('Manager'); // Only managers can edit sales

require_once '../controllers/general_controller.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sale_id']) && isset($data['sale_date']) && isset($data['customer_name']) && isset($data['total_amount']) && isset($data['sale_status']) && isset($data['payment_status']) && isset($data['sale_note'])) {
    $sale_id = $data['sale_id'];
    $sale_date = $data['sale_date'];
    $customer_name = $data['customer_name'];
    $total_amount = $data['total_amount'];
    $sale_status = $data['sale_status'];
    $payment_status = $data['payment_status'];
    $sale_note = $data['sale_note'];
    $admin_id = $_SESSION['user_id']; 

    $result = update_sale($sale_id, $sale_date, $customer_name, $total_amount, $sale_status, $payment_status, $sale_note, $admin_id);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Failed to update sale with ID $sale_id");
        echo json_encode(['success' => false, 'message' => 'Failed to update sale']);
    }
} else {
    error_log('Invalid input: ' . json_encode($data));
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>