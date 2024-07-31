<?php
session_start();
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $gender = $_POST['gender']; 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $general = new general_class();
    $result = $general->registerUser($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password);

    if ($result === true) {
        header('Location: auth-sign-in.php');
    } else {
        $error = $result;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Halo Pharma Assist</title>
      
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="assets/css/backende209.css?v=1.0.0">
    <link rel="stylesheet" href="assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/vendor/remixicon/fonts/remixicon.css">
</head>
<body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
    <div class="wrapper">
        <section class="login-content">
            <div class="container">
                <div class="row align-items-center justify-content-center height-self-center">
                    <div class="col-lg-8">
                        <div class="card auth-card">
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center auth-content">
                                    <div class="col-lg-7 align-self-center">
                                        <div class="p-3">
                                            <h2 class="mb-2">Sign Up🏥💊</h2>
                                            <p>Create Your Abeer Pharmacy Account.</p>
                                            <form method="POST" action="actions/signup_proc.php" onsubmit="return validateForm()">
                                                <?php if (isset($error)): ?>
                                                    <div class="error"><?php echo $error; ?></div>
                                                <?php endif; ?>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="text"
                                                                name="first_name" placeholder=" " required>
                                                            <label>First Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="text"
                                                                name="last_name" placeholder=" " required>
                                                            <label>Last Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="email"
                                                                name="email" placeholder=" " required>
                                                            <label>Email</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="text"
                                                                name="phone" placeholder=" " required>
                                                            <label>Phone No.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <select class="floating-input form-control" name="role"
                                                                required>
                                                                <option value="Pharmacist">Pharmacist</option>
                                                                <option value="Manager">Manager</option>
                                                            </select>
                                                            <label>Role</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <select class="floating-input form-control" name="gender"
                                                                required>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                            <label>Gender</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password"
                                                                name="password" placeholder=" " required>
                                                            <label>Password</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password"
                                                                name="confirm_password" placeholder=" " required>
                                                            <label>Confirm Password</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck1" required>
                                                            <label class="custom-control-label" for="customCheck1">I
                                                                agree with the terms of use</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                                <p class="mt-3">
                                                    Already have an Account <a href="auth-sign-in.php"
                                                        class="text-primary">Sign In</a>
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 content-right">
                                        <img src="assets/images/login/03.webp" class="img-fluid image-right" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/backend-bundle.min.js"></script>

    <script src="assets/js/table-treeview.js"></script>

    <script src="assets/js/customizer.js"></script>

    <script async src="assets/js/chart-custom.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
        function validateForm() {
            const password = document.querySelector('[name="password"]').value;
            const confirmPassword = document.querySelector('[name="confirm_password"]').value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>