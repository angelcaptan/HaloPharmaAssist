<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reset Password - Halo Pharma Assist</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="assets/css/backende209.css?v=1.0.0">
    <link rel="stylesheet" href="assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/vendor/remixicon/fonts/remixicon.css">
</head>

<body class=" ">
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
                                            <h2 class="mb-2">Reset Password</h2>
                                            <form method="POST" action="actions/recover_password.php">
                                                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                                                <div class="floating-label form-group">
                                                    <input class="floating-input form-control" type="password"
                                                        name="password" placeholder=" ">
                                                    <label>New Password</label>
                                                </div>
                                                <div class="floating-label form-group">
                                                    <input class="floating-input form-control" type="password"
                                                        name="confirm_password" placeholder=" ">
                                                    <label>Confirm Password</label>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Reset Password</button>
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
</body>

</html>