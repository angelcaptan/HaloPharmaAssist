<?php
require_once '../controllers/general_controller.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $sale_id = $_GET['id'];
    $sale = get_sale_by_id($sale_id);
    if ($sale) {
        echo json_encode($sale);
    } else {
        echo json_encode(['error' => 'Sale not found']);
    }
} else {
    echo json_encode(['error' => 'No sale ID provided']);
}
?>