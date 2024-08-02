<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$sql = "
SELECT
    biller_name,
    sale_date,
    category_name,
    total_sales
FROM (
    SELECT 
        u.last_name as biller_name, 
        DATE(s.sale_date) as sale_date, 
        c.category_name,
        SUM(s.total_amount) as total_sales,
        ROW_NUMBER() OVER (PARTITION BY s.biller_id ORDER BY SUM(s.total_amount) DESC) as rank
    FROM 
        Sales s
    JOIN 
        Products p ON s.product_id = p.product_id
    JOIN 
        Categories c ON p.category_id = c.category_id
    JOIN 
        Users u ON s.biller_id = u.user_id
    GROUP BY 
        s.biller_id, p.category_id, DATE(s.sale_date)
) as RankedCategories
WHERE
    rank <= 5
ORDER BY
    biller_name, total_sales DESC;
";

$result = $conn->query($sql);

$sales_data = [];
while ($row = $result->fetch_assoc()) {
    $sales_data[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($sales_data);
?>
