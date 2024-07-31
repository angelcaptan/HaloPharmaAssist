<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../permissions.php';
checkPermission('Manager'); // Only managers can delete users

require_once '../controllers/general_controller.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['user_id'])) {
    $user_id = $input['user_id'];
    $admin_id = $_SESSION['user_id']; // Assuming the admin's user ID is stored in the session

    $result = delete_user($user_id, $admin_id);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No user ID provided.']);
}