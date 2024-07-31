<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$sql = "SELECT SUM(total_amount) as total_sales FROM Sales";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(array("total_sales" => $row["total_sales"]));
} else {
    echo json_encode(array("total_sales" => 0));
}

$conn->close();

?>