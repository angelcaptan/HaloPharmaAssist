<?php
session_start();
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $email = $_POST['email'];
   $password = $_POST['password'];

   $general = new general_class();
   $user = $general->signinUser($email, $password);

   if ($user) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['first_name'] = $user['first_name'];
      $_SESSION['last_name'] = $user['last_name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['phone'] = $user['phone'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['gender'] = $user['gender'];
      header('Location: index.php');
   } else {
      $error = 'Invalid email or password';
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
                                 <h2 class="mb-2">Sign Into Abeer's Pharmacy Assist</h2>
                                 <p>Login to stay connected.</p>
                                 <form method="POST" action="actions/signin_proc.php">
                                    <?php if (isset($error)): ?>
                                           <div class="error"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="email" name="email" placeholder=" ">
                                             <label>Email</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="password" name="password" placeholder=" ">
                                             <label>Password</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="custom-control custom-checkbox mb-3">
                                             <input type="checkbox" class="custom-control-input" id="customCheck1">
                                             <label class="custom-control-label control-label-1" for="customCheck1">Remember Me</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <a href="auth-recoverpw.php" class="text-primary float-right">Forgot Password?</a>
                                       </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                    <p class="mt-3">
                                       Create an Account <a href="auth-sign-up.php" class="text-primary">Sign Up</a>
                                    </p>
                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-5 content-right">
                              <img src="assets/images/login/02.webp" class="img-fluid image-right" alt="">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
    </div>
    
    <script src="assets/js/backend-bundle.min.js"></script>
    
    <script src="assets/js/table-treeview.js"></script>
    
    <script src="assets/js/customizer.js"></script>
    
    <script async src="assets/js/chart-custom.js"></script>
    
    <script src="assets/js/app.js"></script>
  </body>
</html>

