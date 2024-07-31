<?php

include '../permissions.php';
checkPermission('Manager'); // Only managers can update supplier details
include_once (__DIR__ . "/../controllers/general_controller.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = $_POST['supplier_name'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_phone = $_POST['supplier_phone'];

    $config_content = "<?php
return [
    'supplier_name' => '" . addslashes($supplier_name) . "',
    'supplier_email' => '" . addslashes($supplier_email) . "',
    'supplier_phone' => '" . addslashes($supplier_phone) . "'
];
";

    // Write the new configuration to the supplier_config.php file
    file_put_contents('../config/supplier_config.php', $config_content);

    // Log the update action
    $general = new general_class();
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    $action = "Update";
    $table_name = "Supplier Details";
    $record_id = null; // Since there's no specific record ID, we can set it to null or an appropriate identifier

    $general->log_action($user_id, $action, $table_name, $record_id);

    // Redirect back to the supplier details page with a success message
    echo "<script>alert('Supplier details updated successfully.'); window.location.href = '../suppliers-details.php';</script>";
}
?>