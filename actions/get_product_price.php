<?php
require_once '../controllers/general_controller.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $general = new general_class();
    $product = $general->getProductById($product_id);

    if ($product) {
        echo json_encode(['success' => true, 'price' => $product['price']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided.']);
}
?>