<?php

include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$query = "
    SELECT 
        p.product_name, 
        p.image, 
        COUNT(s.product_id) AS total_sales
    FROM 
        Sales s
    JOIN 
        Products p ON s.product_id = p.product_id
    GROUP BY 
        s.product_id
    ORDER BY 
        total_sales DESC
    LIMIT 4
";

$result = $conn->query($query);

$top_products = [];
while ($row = $result->fetch_assoc()) {
    $top_products[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($top_products);
?>