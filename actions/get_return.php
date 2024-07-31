<?php
include_once '../controllers/general_controller.php';

if (isset($_GET['id'])) {
    $return_id = $_GET['id'];
    $general = new general_class();
    $return = $general->getReturnById($return_id);

    if ($return) {
        echo json_encode(['success' => true, 'return' => $return]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Return not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No return ID provided.']);
}
?>