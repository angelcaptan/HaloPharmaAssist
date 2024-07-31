<?php
require_once '../controllers/general_controller.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = get_user_by_id($user_id);

    if ($user) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No user ID provided.']);
}
?>