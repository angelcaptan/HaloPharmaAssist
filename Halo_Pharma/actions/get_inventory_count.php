<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

// Update the query to count non-archived products
$sql = "SELECT COUNT(*) as inventory_count FROM Products WHERE archived = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(array("inventory_count" => $row["inventory_count"]));
} else {
    echo json_encode(array("inventory_count" => 0));
}

$conn->close();
?>