<?php
require_once '../controllers/general_controller.php';

session_start(); // Ensure session is started

$response = [
    'status' => 'error',
    'message' => 'Invalid request method.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_date = $_POST['return_date'];
    $reference_no = $_POST['reference_no'];
    $receiver_name = $_POST['receiver_name'];
    $customer_name = $_POST['customer_name'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_amounts = $_POST['total_amount'];
    $overall_total_amount = $_POST['overall_total_amount'];
    $return_status = $_POST['return_status'];
    $return_note = $_POST['return_note'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    $success = true;
    foreach ($product_ids as $index => $product_id) {
        $quantity = $quantities[$index];
        $total_amount = $total_amounts[$index];

        if (!add_return($return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note, $user_id)) {
            $success = false;
            break;
        }
        // Update product quantity for return
        if ($return_status === 'Refunded') {
            update_product_quantity_for_return($product_id, $quantity);
        }
    }

    if ($success) {
        $response = [
            'status' => 'success',
            'message' => 'Return added successfully.'
        ];
    } else {
        $response['message'] = 'Failed to add return.';
    }
}

echo json_encode($response);
?>