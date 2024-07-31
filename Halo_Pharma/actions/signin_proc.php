<?php
// report errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

// include the controller class
require_once (__DIR__ . "/../controllers/general_controller.php");

// collect form data
$email = $_POST['email'];
$password = $_POST['password'];

// call the function to sign in user
$signin = signin_user_ctr($email, $password);

if (is_array($signin)) {
    // Place the user info in session variables
    session_start();
    $_SESSION['user_id'] = $signin['user_id'];
    $_SESSION['first_name'] = $signin['first_name'];
    $_SESSION['last_name'] = $signin['last_name'];
    $_SESSION['email'] = $signin['email'];
    $_SESSION['phone'] = $signin['phone'];
    $_SESSION['role'] = $signin['role'];

    // Redirect to the dashboard
    header("Location: ../index.php");
    exit();
} else {
    // Redirect to the login page with an error message
    header("Location: ../auth-sign-in.php?error=Invalid credentials");
    exit();
}



if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = get_product_by_id($product_id);
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found"]);
    }
} else {
    echo json_encode(["error" => "No product ID provided"]);
}
?>



?>