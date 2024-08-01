<?php
include_once (__DIR__ . "/../controllers/general_controller.php");

$conn = (new db_connection())->db_conn();

$sql = "SELECT email, CONCAT(first_name, ' ', last_name) AS name FROM Users WHERE role = 'Manager'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['email'] . "'>" . $row['name'] . "</option>";
    }
} else {
    echo "<option value=''>No managers found</option>";
}

$conn->close();
?>
