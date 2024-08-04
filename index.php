<?php
require_once 'controllers/general_controller.php';
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require ("settings/core.php");
// Call function to check if user is logged in
checkLogin();

?>

<!doctype html>
<html lang="en">


<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="card card-transparent card-block card-stretch card-height border-none">
                    <div class="card-body p-0 mt-lg-2 mt-0">
                        <h3 class="mb-3">Welcome Back <?php echo $first_name; ?>!</h3>
                        <p class="mb-0 mr-4">This DashBoard Gives You A Summary of Abeer's Key Perfomance Insights .</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg col-md p-1">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-info-light">
                                        <img src="assets/images/product/1.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Total Sales $</p>
                                        <h4 id="totalSalesCount"></h4>
                                    </div>
                                </div>
                                <div class="iq-progress-bar mt-2">
                                    <span class="bg-info iq-progress progress-1" data-percent="85"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg col-md p-1">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center mb-4 card-return">
                                    <div class="icon iq-icon-box-2 bg-danger-light">
                                        <img src="assets/images/product/2.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Total Returns $</p>
                                        <h4 id="totalReturnsCount"></h4>
                                    </div>
                                </div>
                                <div class="iq-progress-bar mt-2">
                                    <span class="bg-danger iq-progress progress-1" data-percent="70"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg col-md p-1">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-success-light">
                                        <img src="assets/images/product/3.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Stock Count</p>
                                        <h4 id="inventoryCount"></h4>
                                    </div>
                                </div>
                                <div class="iq-progress-bar mt-2">
                                    <span class="bg-success iq-progress progress-1" data-percent="75"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg col-md p-1">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-warning-light">
                                        <img src="assets/images/product/1.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Sales Count</p>
                                        <h4 id="salesCount"></h4>
                                    </div>
                                </div>
                                <div class="iq-progress-bar mt-2">
                                    <span class="bg-warning iq-progress progress-1" data-percent="60"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg col-md p-1">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center mb-4 card-total-sale">
                                    <div class="icon iq-icon-box-2 bg-primary-light">
                                        <img src="assets/images/product/2.png" class="img-fluid" alt="image">
                                    </div>
                                    <div>
                                        <p class="mb-2">Customers</p>
                                        <h4 id="customersCount">0</h4>
                                    </div>
                                </div>
                                <div class="iq-progress-bar mt-2">
                                    <span class="bg-primary iq-progress progress-1" data-percent="50"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Products -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Top Products Sold</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center"></div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled row top-product mb-0" id="top-products-list">
                            <!-- Top products will be dynamically inserted here -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Top Returned Products -->
            <div class="col-lg-8">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Top Returned Products</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center"></div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled row top-product mb-0" id="top-returned-products-list">
                            <!-- Top returned products will be dynamically inserted here -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Top Profitable Products -->
            <div class="col-lg-4">
                <div class="card card-transparent card-block card-stretch mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between p-0">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Top Profitable Products</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                        </div>
                    </div>
                </div>
                <div id="top-profitable-products" class="card card-block card-stretch card-height-helf">
                    <!-- Top profitable products will be populated here by JavaScript -->
                </div>
            </div>
            <!-- Sales Over Time -->
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Sales Over Time</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center"></div>
                        </div>
                    </div>
                    <div id="sales-over-time-chart"></div>
                    </div>
                </div>
            </div>
            <!-- Sales By Billers -->
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Sales by Billers</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                            <div><a href="#" class="btn light-gray view-btn"> Billers</a></div>
                        </div>
                    </div>
                    <div id="treemap" style="min-height: 340px;">
                    </div>
                </div>
            </div>
           <!-- Category Distributions -->
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Category Distribution</h4>
                        </div>
                        <div class="card-header-toolbar d-flex align-items-center">
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="category-bubble-chart"></div>
                    </div>
                </div>
            </div>
            <!-- Scatter Plot for Product Distribution -->
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Product Distribution by Category</h4>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center"></div>
                </div>
                <div class="card-body">
                    <div id="category-scatter-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->
<footer class="iq-footer">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="">Terms of Use</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-right">
                        <span class="mr-1">
                            <script>document.write(new Date().getFullYear())</script>©
                        </span> <a href="#" class="">Halo Assist</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Backend Bundle JavaScript -->
<script src="assets/js/chart-custom.js" defer></script>
<script>
    let idleTime = 0;
    const idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    function timerIncrement() {
        idleTime++;
        if (idleTime > 9) { // 10 minutes
            window.location.href = 'lock_screen.php';
        }
    }

    function resetTimer() {
        idleTime = 0;
    }

    // Reset the idle timer on mouse movement, key press, etc.
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer;
    window.ontouchstart = resetTimer;
    window.onclick = resetTimer;
    window.onkeypress = resetTimer;
    window.addEventListener('scroll', resetTimer, true);
</script>

<script src="file.js" defer></script>

<script src="assets/js/customizer.js"></script>
<!-- Chart Custom JavaScript -->
<script async src="assets/js/chart-custom.js"></script>
<!-- App JavaScript -->
<script src="assets/js/app.js"></script>
<!-- ChatBot -->
<script type="text/javascript">
        (function (d, t) {
            var v = d.createElement(t), s = d.getElementsByTagName(t)[0];
            v.onload = function () {
                window.voiceflow.chat.load({
                    verify: { projectID: '66a47dd4d5cce6c701f7efbf' },
                    url: 'https://general-runtime.voiceflow.com',
                    versionID: 'production'
                });
            }
            v.src = "https://cdn.voiceflow.com/widget/bundle.mjs"; v.type = "text/javascript"; s.parentNode.insertBefore(v, s);
        })(document, 'script');
</script>

</html>
