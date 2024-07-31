<?php
include '../permissions.php';
checkPermission('Manager'); // Only managers can access this

require_once '../controllers/general_controller.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = getProductsByIds([$product_id])[0];

    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No product ID provided']);
}
?>