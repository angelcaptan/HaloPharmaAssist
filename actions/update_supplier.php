<?php

include '../permissions.php';
checkPermission('Manager'); // Only managers can update supplier details
include_once (__DIR__ . "/../controllers/general_controller.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = isset($_POST['supplier_name']) ? $_POST['supplier_name'] : null;
    $supplier_email = isset($_POST['supplier_email']) ? $_POST['supplier_email'] : null;
    $supplier_phone = isset($_POST['supplier_phone']) ? $_POST['supplier_phone'] : null;

    // Log the posted data for debugging
    error_log('Posted data: ' . print_r($_POST, true));

    // Check for missing form inputs
    if (is_null($supplier_name) || is_null($supplier_email) || is_null($supplier_phone)) {
        echo "<script>alert('All fields are required. Please fill out the form completely.'); window.location.href = '../suppliers-details.php';</script>";
        exit;
    }

    $config_content = "<?php
return [
    'supplier_name' => '" . addslashes($supplier_name) . "',
    'supplier_email' => '" . addslashes($supplier_email) . "',
    'supplier_phone' => '" . addslashes($supplier_phone) . "'
];
";

    // Write the new configuration to the supplier_config.php file
    $file_path = '../config/supplier_config.php';
    $result = file_put_contents($file_path, $config_content);

    if ($result === false) {
        echo "<script>alert('Failed to update supplier details. Please check file permissions.'); window.location.href = '../suppliers-details.php';</script>";
        exit;
    }

    // Log the update action
    $general = new general_class();
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start session if not already started
    }
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    } else {
        echo "<script>alert('User ID not found in session. Please login again.'); window.location.href = '../login.php';</script>";
        exit;
    }
    $action = "Update";
    $table_name = "Supplier Details";
    $record_id = null; // Since there's no specific record ID, we can set it to null or an appropriate identifier

    $general->log_action($user_id, $action, $table_name, $record_id);

    // Redirect back to the supplier details page with a success message
    echo "<script>alert('Supplier details updated successfully.'); window.location.href = '../suppliers-details.php';</script>";
}
?>
