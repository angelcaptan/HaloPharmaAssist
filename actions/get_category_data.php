<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$query = "
    SELECT 
        c.category_name, 
        COUNT(s.product_id) AS total_sales_count,
        SUM(s.total_amount) AS total_sales_amount
    FROM 
        Categories c
    JOIN 
        Products p ON c.category_id = p.category_id
    JOIN 
        Sales s ON p.product_id = s.product_id
    GROUP BY 
        c.category_name
";

$result = $conn->query($query);

$category_data = [];
while ($row = $result->fetch_assoc()) {
    $category_data[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($category_data);
?>