<?php

include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$query = "
    SELECT 
        p.product_name, 
        p.image, 
        COUNT(r.product_id) AS total_returns
    FROM 
        returns r
    JOIN 
        Products p ON r.product_id = p.product_id
    GROUP BY 
        r.product_id
    ORDER BY 
        total_returns DESC
    LIMIT 6
";

$result = $conn->query($query);

$top_returned_products = [];
while ($row = $result->fetch_assoc()) {
    $top_returned_products[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($top_returned_products);
?>