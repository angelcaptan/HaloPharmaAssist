<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$query = "
    SELECT 
        c.category_name, 
        COUNT(p.product_id) AS product_count
    FROM 
        Products p
    JOIN 
        Categories c ON p.category_id = c.category_id
    GROUP BY 
        c.category_name
";

$result = $conn->query($query);

$product_distribution = [];
while ($row = $result->fetch_assoc()) {
    $product_distribution[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($product_distribution);
?>