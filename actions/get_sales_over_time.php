<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$query = "
    SELECT 
        DATE(s.sale_date) as sale_date, 
        SUM(s.total_amount) as total_sales 
    FROM 
        Sales s
    GROUP BY 
        DATE(s.sale_date)
    ORDER BY 
        DATE(s.sale_date)
";

$result = $conn->query($query);

$sales_data = [];
while ($row = $result->fetch_assoc()) {
    $sales_data[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($sales_data);
?>
