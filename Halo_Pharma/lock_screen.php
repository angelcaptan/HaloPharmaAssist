<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Lock Screen | Halo Pharma Assist</title>

   <!-- Favicon -->
   <link rel="shortcut icon" href="assets/images/favicon.ico" />
   <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
   <link rel="stylesheet" href="assets/css/backende209.css?v=1.0.0">
   <link rel="stylesheet" href="assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css">
   <link rel="stylesheet" href="assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
   <link rel="stylesheet" href="assets/vendor/remixicon/fonts/remixicon.css">
   <style>
      .iq-error {
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         text-align: center;
      }

      .height-self-center {
         display: flex;
         align-items: center;
         justify-content: center;
         height: 100vh;
      }
   </style>
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
                              <div class="p-3 text-center">
                                 <img src="assets/images/user/panda.webp" class="rounded avatar-80 mb-3"
                                    alt="User Avatar">
                                 <h2 class="mb-2">Hi!
                                    <?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'User'; ?>
                                 </h2>
                                 <p>You were inactive! Enter your password to unlock.</p>
                                 <form method="POST" action="unlock.php">
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="password" name="password"
                                                placeholder=" " required>
                                             <label>Password</label>
                                          </div>
                                       </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Unlock</button>
                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-5 content-right">
                              <img src="assets/images/login/01.png" class="img-fluid image-right" alt="Lock Screen">
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