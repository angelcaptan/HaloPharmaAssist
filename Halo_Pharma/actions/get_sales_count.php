<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$sql = "SELECT COUNT(*) as sales_count FROM sales";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(array("sales_count" => $row["sales_count"]));
} else {
    echo json_encode(array("sales_count" => 0));
}

$conn->close();
?>