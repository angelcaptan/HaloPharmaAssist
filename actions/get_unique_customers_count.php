<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$sql = "SELECT COUNT(DISTINCT customer_name) as unique_customers_count FROM Sales";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(array("unique_customers_count" => $row["unique_customers_count"]));
} else {
    echo json_encode(array("unique_customers_count" => 0));
}

$conn->close();

?>